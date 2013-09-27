#!/usr/bin/env python
#encoding=utf-8
'''
	Author: 	OSA开源团队
	Description:	(监控项目 apache status 数据采集与报警)
	Date: 		2011-08-31
'''
import re,simplejson
import urllib2 ,socket
import subprocess,time,signal,sys
from datetime import datetime
from ctrlpy.lib.osaLogLib import *
from ctrlpy.etc.config import MONITOR


def apache_get_connections(content):
	'''
	@根据apache status 页面内容获取连接数等信息
	@readNum:读请求连接，writeNum：发响应请求连接，keepNum:持久连接，closeNum：关闭连接，waitNum：等待连接
	'''
	try:
		readNum = writeNum = keepNum = closeNum = waitNum = 0
		result = re.search("<pre>([\s\S]*?)</pre>",content)
		if result is not None:
			resstr = result.groups()[0]
			readNum = len(re.findall("R",resstr))
			writeNum = len(re.findall("W",resstr))
			keepNum = len(re.findall("K",resstr))
			closeNum = len(re.findall("C",resstr))
			waitNum = len(re.findall("_",resstr))
		return readNum ,writeNum ,keepNum ,closeNum ,waitNum
	except Exception as e:
		log_error("apache_get_connections():"+str(e))
	
	
def apache_get_basicinfo(content):
	'''
	@根据apache status 页面内容获取apache 吞吐率等字符信息
	'''
	try:
		visitNum = rateNum = capacityNum = 0
		result = re.findall("<dt>.*?</dt>",content)
		for res in result:
			if res.find("Total accesses")!=-1:
				newres = res.replace("<dt>","")
				newres = newres.replace("</dt>","")
				newres = newres.split("-")
				visitNum = int(newres[0].replace("Total accesses:","").strip(" "))
			if res.find("requests/sec")!=-1:
				newres = res.replace("<dt>","")
				newres = newres.replace("</dt>","")
				newres = newres.split("-")
				rateNum = float(newres[0].split(' ')[0])
				capacityNum = float(newres[2].strip(" ").split(' ')[0])
		return visitNum,rateNum,capacityNum	
	except Exception as e:
		log_error("apache_get_basicinfo():"+str(e))
	
	
def apache_alarm_tconnected(itemconfig ,totalNum):
	'''
	@apache 报警指标 并发连接数
	'''
	try:
		switch = itemconfig['curr_connects']['condition']
		value = itemcongig['curr_connects']['value']
		if switch == '大于':
			if totalNum>value:
				return True #说明符合报警条件
		elif switch == '小于':
			if totalNum<value:
				return True #说明符合报警条件
		return False #说明不需要报警
	except Exception as e:
		log_error("apache_alarm_tconnected():"+str(e))
	

def apache_alarm_reqrate(itemconfig ,rateNum):
	'''
	@apache 报警指标 吞吐率
	'''
	try:
		switch = itemconfig['request_rate']['condition']
		value = itemcongig['request_rate']['value']
		if switch == '大于':
			if rateNum>value:
				return True #说明符合报警条件
		elif switch == '小于':
			if rateNum<value:
				return True #说明符合报警条件
		return False #说明不需要报警
	except Exception as e:
		log_error("apache_alarm_reqrate():"+str(e))
	
	
def apache_alarm_analyze(itemconfig,rateNum,totalNum):
	'''
	@apache 报警条件分析
	'''
	try:
		for key in itemconfig.keys():
			if key == 'curr_connects' and apache_alarm_tconnected(itemconfig ,totalNum) == True:
				reason = "Apache并发连接数"+itemconfig['curr_connects']['condition']+" "+itemconfig['curr_connects']['value']
				return True ,reason
			elif key == 'request_rate' and apache_alarm_reqrate(itemconfig,rateNum) == True:
				reason = "Apache吞吐率"+itemconfig['request_rate']['condition']+" "+itemconfig['request_rate']['value']
				return True ,reason
		return False ,''
	except Exception as e:
		log_error("apache_alarm_analyze():"+str(e))
	
	
def apache_status_analyze(url,itemconfig):
	'''
	@apache status 页面分析
	'''
	try:
		start = datetime.now()
		socket.setdefaulttimeout(MONITOR['timeout'])
		result = urllib2.urlopen(url)
		end = datetime.now()
	except Exception as e:
		log_info("apache-status页面访问失败:"+str(e))
		reason = url+"访问失败,连接超时"
		return str(0),reason,'',str(1)
	content = result.read()
	responsetime = (end - start).microseconds / 1000
	readNum ,writeNum ,keepNum ,closeNum ,waitNum = apache_get_connections(content)
	totalNum = readNum + writeNum 
	visitNum,rateNum,capacityNum = apache_get_basicinfo(content)
	apache_json = {"totalNum":totalNum,"readNum":readNum,"writeNum":writeNum,"keepNum":keepNum,"closeNum":closeNum,"waitNum":waitNum,"visitNum":visitNum,"rateNum":rateNum,"capacityNum":capacityNum,"responsetime":responsetime}
	isAlarm ,reason= apache_alarm_analyze(itemconfig,rateNum,totalNum)
	if isAlarm == True:
		return str(0),reason,simplejson.dumps(apache_json),str(3)
	else:
		return str(1),'',simplejson.dumps(apache_json),str(4)
	
	
##########################分割线-----------apache server-status 数据处理结束 -------------分割线######################


if __name__ == '__main__':
	'''
	@test 报警部分没实现
	'''
	pass
	
