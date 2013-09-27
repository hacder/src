#!/usr/bin/env python
#encoding=utf-8
'''
	Author: 	OSA开源团队
	Description:	(监控项目 nginx status 数据采集与报警)
	Date: 		2011-08-31
'''
import re,simplejson
import urllib2 ,socket
import subprocess,time,signal,sys
from datetime import datetime
from ctrlpy.lib.osaLogLib import *
from ctrlpy.etc.config import MONITOR
from ctrlpy.lib import cmdtosql



def nginx_get_lastvisit(itemid):
	'''
	@获取上一次获取的数据中的visitNum字段值
	'''
	try:
		sql = " select oMonResult from osa_monitor_record where oItemid ="+str(itemid)+" order by id desc limit 1"
		con = cmdtosql._get_pcon()
		cur = con.cursor()
		cur.execute(sql)
		itemdata=cur.fetchone()    
		cmdtosql._exit(con, cur)
		if itemdata== None or itemdata[0]=='' or itemdata[0]=='null':
			return None
		else:
			datajson = eval(itemdata[0])
			return int(datajson['visitNum'])
	except Exception as e:
		log_error('nginx_get_lastvisit:'+str(e))


def nginx_get_baseinfo(content):
	'''
	@nginx status 页面获取nginx性能数据
	'''
	try:
		readNum = writeNum = waitNum = visitNum = totalNum = acceptNum = 0
		content = content.split("\n")
		totalNum = int(content[0].split(":")[1].strip(" "))
		visitNum = int(content[2].strip(" ").split(" ")[2])
		acceptNum = int(content[2].strip(" ").split(" ")[0])
		tempstr = content[3].replace("Reading: ","").replace("Writing: ","").replace("Waiting: ","")
		tempstr = tempstr.strip(" ").split(" ")
		readNum = int(tempstr[0])
		writeNum = int(tempstr[1])
		waitNum = int(tempstr[2])
		return readNum , writeNum , waitNum , visitNum , totalNum ,acceptNum
	except Exception as e:
		log_error("nginx_get_baseinfo():"+str(e))

	
def nginx_alarm_tconnected(itemconfig ,totalNum):
	'''
	@nginx 报警指标 并发连接数
	'''
	try:
		switch = itemconfig['curr_connects']['condition']
		value = itemconfig['curr_connects']['value']
		if switch == '大于':
			if totalNum>value:
				return True #说明符合报警条件
		elif switch == '小于':
			if totalNum<value:
				return True #说明符合报警条件
		return False #说明不需要报警
	except Exception as e:
		log_error("nginx_alarm_tconnected():"+str(e))

def nginx_alarm_reqrate(itemconfig ,rateNum):
	'''
	@nginx 报警指标 吞吐率
	'''
	try:
		switch = itemconfig['request_rate']['condition']
		value = itemconfig['request_rate']['value']
		if switch == '大于':
			if rateNum>value:
				return True #说明符合报警条件
		elif switch == '小于':
			if rateNum<value:
				return True #说明符合报警条件
		return False #说明不需要报警	
	except Exception as e:
		log_error("nginx_alarm_reqrate():"+str(e))

	
	
def nginx_get_requestrate(visitNum ,last_visitNum ,time):
	'''
	@nginx 计算nginx的吞吐率
	'''
	try:
		if last_visitNum == None:
			return 0
		request_num = float(visitNum) - float(last_visitNum)
		request_rate = request_num/int(time)
		return request_rate
	except Exception as e:
		log_error("nginx_get_requestrate():"+str(e))
	
	
def nginx_alarm_analyze(itemconfig,rateNum,totalNum):
	'''
	@nginx 报警条件分析
	'''
	for key in itemconfig.keys():
		if key == 'curr_connects' and nginx_alarm_tconnected(itemconfig ,totalNum) == True:
			reason = "Nginx活动连接数"+itemconfig['curr_connects']['condition']+" "+itemconfig['curr_connects']['value']
			return True ,reason
		elif key == 'request_rate' and nginx_alarm_reqrate(itemconfig,rateNum) == True:
			reason = "Nginx吞吐率"+itemconfig['request_rate']['condition']+" "+itemconfig['request_rate']['value']
			return True ,reason
	return False ,''
	
	
def nginx_status_analyze(url,itemconfig,itemid,time):
	'''
	@nginx status 页面分析
	@json 格式还待处理
	'''
	try:
		start = datetime.now()
		socket.setdefaulttimeout(MONITOR['timeout'])
		result = urllib2.urlopen(url)
		end = datetime.now()
	except Exception as e:
		log_info("nginx-status页面访问失败:"+str(e))
		reason = url+"访问失败，连接超时"
		return str(0),reason,'',str(1)
	responsetime = (end-start).microseconds/1000
	content = result.read()
	readNum , writeNum , waitNum , visitNum , totalNum ,acceptNum = nginx_get_baseinfo(content)
	last_visitNum = nginx_get_lastvisit(itemid)
	rateNum = nginx_get_requestrate(visitNum,last_visitNum,time)
	nginx_json = {"totalNum":totalNum,"readNum":readNum,"writeNum":writeNum,"waitNum":waitNum,"visitNum":visitNum,"acceptNum":acceptNum,"rateNum":rateNum,"responsetime":responsetime}
	isAlarm,reason = nginx_alarm_analyze(itemconfig,rateNum,totalNum)
	if isAlarm == True:#报警
		return str(0),reason,simplejson.dumps(nginx_json),str(3)
	else:#不报警
		return str(1),'',simplejson.dumps(nginx_json),str(4)

	
##########################分割线-----------nginx server-status 数据处理结束 -------------分割线######################

if __name__ == '__main__':
	'''
	@test 报警部分没实现
	'''
	pass
	
