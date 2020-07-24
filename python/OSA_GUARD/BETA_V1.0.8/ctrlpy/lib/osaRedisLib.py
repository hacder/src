#!/usr/bin/env python
#encoding=utf-8
'''
	Author: 	OSA开源团队
	Description:	(监控项目 redis stauts 数据采集与报警)
	Date: 		2011-08-20
'''
import re,simplejson
import urllib2 ,socket
import subprocess,time,signal,sys
from datetime import datetime
from ctrlpy.lib.osaLogLib import *
from ctrlpy.etc.config import MONITOR
from ctrlpy.lib import cmdtosql

def redis_get_lastdata(itemid):
	'''
	@获取上次插入的结果值
	'''
	try:
		sql = " select oMonResult from osa_monitor_record where oItemid ="+str(itemid)+" order by id desc limit 1"
		con = cmdtosql._get_pcon()
		cur = con.cursor()
		cur.execute(sql)
		itemdata=cur.fetchone()
		cmdtosql._exit(con, cur)
		if itemdata == None or itemdata[0]=='' or itemdata[0]=='null':
			return None
		else:
			return eval(itemdata[0])
	except Exception as e:
		log_error("redis_get_lastdata:"+str(e))


def redis_get_baseinfo(info,lastdata,minute):
	'''
	@redis 获取内存使用等基本信息
	'''
	try:
		baseinfo = {}
		baseinfo['used_mem'] = info['used_memory']
		baseinfo['connected_subordinate'] = info['connected_subordinates']
		baseinfo['pubsub_channels'] = info['pubsub_channels']
		baseinfo['pubsub_patters'] = info['pubsub_patterns']
		baseinfo['blocked_clients'] = info['blocked_clients']
		baseinfo['connected_clients'] = info['connected_clients']
		total_hits = float(info['keyspace_misses']) + float(info['keyspace_hits'])
		if total_hits == 0:
			baseinfo['indexrate'] = 0
		else:
			baseinfo['indexrate'] = float(info['keyspace_hits'])/total_hits
		baseinfo['total_command'] = info['total_commands_processed']
		baseinfo['total_connects'] = info['total_connections_received']
		if lastdata == '' or lastdata ==None:
			baseinfo['connects_rate'] = baseinfo['command_rate'] = 0
		else:
			baseinfo['connects_rate'] = (int(info['total_connections_received'])-int(lastdata['total_connects']))/float(minute)
			baseinfo['command_rate'] = (int(info['total_commands_processed'])-int(lastdata['total_command']))/float(minute)
		return baseinfo
	except Exception as e:
		log_error("redis_get_baseinfo():"+str(e))


def redis_alarm_tconnected(itemconfig,baseinfo):
	'''
	@redis 报警指标connected_clients
	'''
	try:
		switch = itemconfig['connected_clients']['condition']
		value = itemcongig['connected_clients']['value']
		cNum = baseinfo['connected_clients']
		if switch == '大于':
			if cNum>value:
				return True #说明符合报警条件
		elif switch == '小于':
			if cNum<value:
				return True #说明符合报警条件
		return False #说明不需要报警
	except Exception as e:
		log_error("redis_alarm_tconnected():"+str(e))	


def redis_alarm_usedmem(itemconfig,baseinfo):
	'''
	@redis 报警指标used_memory 获取的单位bytes 
	@used_memory/1024 转化为KB
	'''
	try:
		switch = itemconfig['used_memory']['condition']
		value = itemcongig['used_memory']['value']
		cNum = baseinfo['used_mem']/1024
		if switch == '大于':
			if cNum>value:
				return True #说明符合报警条件
		elif switch == '小于':
			if cNum<value:
				return True #说明符合报警条件
		return False #说明不需要报警
	except Exception as e:
		log_error("redis_alarm_usedmem():"+str(e))	


def redis_alarm_indexrate(itemconfig,baseinfo):
	'''
	@redis 报警指标redis 命中率
	'''
	try:
		switch = itemconfig['index_rate']['condition']
		value = itemcongig['index_rate']['value']
		cNum = baseinfo['indexrate']
		if switch == '大于':
			if cNum>value:
				return True #说明符合报警条件
		elif switch == '小于':
			if cNum<value:
				return True #说明符合报警条件
		return False #说明不需要报警
	except Exception as e:
		log_error("redis_alarm_indexrate():"+str(e))

	
def redis_alarm_others(key,itemconfig,info):
	'''
	@redis 自定义其他指标报警分析
	'''
	if key not in info.keys():
		return False #不存在指标名字
	switch = itemconfig[key]['switch']
	value = itemconfig[key]['value']
	other_value = info[key]
	if switch == '>':
		if other_value>value:
			return True #说明符合报警条件
	elif switch == '<':
		if other_value<value:
			return True #说明符合报警条件
	return False #说明不需要报警
	
	
def redis_alarm_analyze(itemconfig,baseinfo,info):
	'''
	@redis 报警条件分析
	'''
	for key in itemconfig.keys():
		if key == 'connected_clients' and redis_alarm_tconnected(itemconfig,baseinfo) == True:
			reason = "Redis链接客户数"+itemconfig['connected_clients']['condition']+" "+itemconfig['connected_clients']['value']
			return True ,reason
		elif key == 'used_memory' and redis_alarm_usedmem(itemconfig,baseinfo) == True:
			reason = "Redis使用内存"+itemconfig['used_memory']['condition']+" "+itemconfig['used_memory']['value']
			return True ,reason
		elif key == 'index_rate' and redis_alarm_indexrate(itemconfig,baseinfo) == True:
			reason = "Redis命中率"+itemconfig['index_rate']['condition']+" "+itemconfig['index_rate']['value']
			return True ,reason
	return False ,''#表示不需要报警
	

def redis_status_analyze(url,itemconfig,itemid,minute):
	'''
	@redis 状态页分析
	'''
	try:
		start = datetime.now()
		socket.setdefaulttimeout(MONITOR['timeout'])
		result = urllib2.urlopen(url)
		end = datetime.now()
	except Exception as e:
		log_info("redis-status页面访问失败:"+str(e))
		reason = url+"访问失败,连接超时"
		return str(0),reason,'',str(1)
	content = result.read()
	responsetime = (end-start).microseconds/1000
	try:
		info = simplejson.loads(content)
	except Exception as e:
		reason = "redis-status页面配置错误"
		return str(0),reason,'',str(1)
	lastdata = redis_get_lastdata(itemid)
	baseinfo = redis_get_baseinfo(info,lastdata,minute)
	baseinfo['responsetime']=responsetime
	isAlarm ,reason = redis_alarm_analyze(itemconfig,baseinfo,info)
	if isAlarm == True:
		return str(0) ,reason,simplejson.dumps(baseinfo),str(3)
	else:
		return str(1) ,'',simplejson.dumps(baseinfo),str(4)

	
##########################分割线-----------redis status 数据处理结束 -------------分割线######################	
	
if __name__ == '__main__':
	'''
	@test
	'''
	pass 
