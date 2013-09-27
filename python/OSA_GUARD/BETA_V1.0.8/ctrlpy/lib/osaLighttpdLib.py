#!/usr/bin/env python
#encoding=utf-8
'''
	Author: 	OSA开源团队
	Description:	(监控项目 lighttpd status 数据采集与报警)
	Date: 		2011-08-31
'''
import re,simplejson
import urllib2 ,socket
import subprocess,time,signal,sys
from datetime import datetime
from ctrlpy.lib.osaLogLib import *
from ctrlpy.etc.config import MONITOR


def lighttpd_get_rate(content):
	'''
	@获取 获取lightttpd 的吞吐率
	'''
	try:
		rate = 0
		result = re.findall("<tr><td>.*?</td></tr>",content)
		str =  result[7].replace("<tr><td>Requests</td><td class=\"string\">","")
		rate = float(str.split(" ")[0])
		return rate
	except Exception as e:
		log_error("lighttpd_get_rate():"+str(e))
	
	
def lighttpd_get_baseinfo(content):
	'''
	@获取 lighttpd 的并发连接信息
	'''
	try:
		rate = lighttpd_get_rate(content)
		readNum = handleNum = writeNum = closeNum = connectNum = totalNum = rpostNum = 0
		totalstr = re.findall("<b>.*?connections</b>",content)
		totalNum = int(totalstr[0].split(' ')[0].replace("<b>",""))
		tmpstr = re.search("connections</b>([\S\s]*?)</pre>",content)
		if tmpstr is not None:
			resstr = tmpstr.groups()[0]
			readNum = len(re.findall("r",resstr))
			writeNum = len(re.findall("W",resstr))
			handleNum = len(re.findall("h",resstr))
			closeNum = len(re.findall("C",resstr))
			connectNum = len(re.findall(".",resstr))
			rpostNum = len(re.findall("R",resstr))
		lighttpd_json={"rate":rate,"readNum":readNum,"handleNum":handleNum,"writeNum":writeNum,"closeNum":closeNum,"connectNum":connectNum,"totalNum":totalNum,"rpostNum":rpostNum}
		return lighttpd_json
	except Exception as e:
		log_error("lighttpd_get_baseinfo():"+str(e))
	

def lighttpd_alarm_tconnected(itemconfig ,totalNum):
	'''
	@nginx 报警指标 并发连接数
	
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
		log_error("lighttpd_alarm_tconnected():"+str(e))


def lighttpd_alarm_reqrate(itemconfig ,rateNum):
	'''
	@nginx 报警指标 吞吐率
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
		log_error("lighttpd_alarm_reqrate():"+str(e))

	
def lighttpd_alarm_analyze(itemconfig ,totalNum ,rateNum):
	'''
	@lighttpd 报警分析
	'''
	for key in itemconfig.keys():
		if key == 'curr_connects' and lighttpd_alarm_tconnected(itemconfig,totalNum) == True:
			reason = "Lighttpd并发连接数"+itemconfig['curr_connects']['condition']+" "+itemconfig['curr_connects']['value']
			return True ,reason #表示需要报警
		elif key == 'request_rate' and lighttpd_alarm_reqrate(itemconfig,rateNum) == True:
			reason = "Lighttpd吞吐率"+itemconfig['request_rate']['condition']+" "+itemconfig['request_rate']['value']
			return True ,reason
	return False,'' #表示不需要报警

	
def lighttpd_status_analyze(url,itemconfig):
	'''
	@lighttpd status 页面分析
	@json 格式数据还需额外处理
	'''
	try:
		start = datetime.now()
		socket.setdefaulttimeout(MONITOR['timeout'])
		result = urllib2.urlopen(url)
		end = datetime.now()
	except Exception as e:
		log_info("lighttpd-status页面访问失败:"+str(e))
		reason = url+"访问失败,连接超时"
		return str(0),reason,'',str(1)
	content = result.read()
	responsetime = (end-start).microseconds/1000
	lighttpd_json = lighttpd_get_baseinfo(content)
	lighttpd_json['responsetime'] = responsetime
	isAlarm ,reason= lighttpd_alarm_analyze(itemconfig ,lighttpd_json['totalNum'] ,lighttpd_json['rate'])
	if isAlarm == True:
		return str(0),reason,simplejson.dumps(lighttpd_json),str(3)
	else:
		return str(1),'',simplejson.dumps(lighttpd_json),str(4)
	
	
##########################分割线-----------lighttpd server-status 数据处理结束 -------------分割线######################


if __name__ == '__main__':
	'''
	@test 报警没有实现
	'''
	#url = "http://192.168.4.76:81/server-status"
	#socket.setdefaulttimeout(MONITOR['timeout'])
	#result = urllib2.urlopen(url)
	#content = result.read()
	#tmpstr = re.search("connections</b>([\s\S]*?)</pre>",content)
	#if tmpstr:
	#	print(tmpstr.groups(0))
	#else:
	#	print('null')
