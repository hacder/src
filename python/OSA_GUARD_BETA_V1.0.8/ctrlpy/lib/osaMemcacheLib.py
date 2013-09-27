#!/usr/bin/env python
#encoding=utf-8
'''
	Author: 	OSA开源团队
	Description:	(监控项目 memcache status数据采集与报警)
	Date: 		2011-08-31
'''
import re,simplejson
import urllib2 ,socket,ftplib
import subprocess,time,signal,sys
from datetime import datetime
from ctrlpy.etc.config import DIRS,MONITOR
from ctrlpy.lib.osaLogLib import *
from ctrlpy.lib import cmdtosql


def memcache_get_lastdata(itemid):
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
		if itemdata == None or itemdata[0] == ''or itemdata[0]=='null':
			return None
		else:
			return eval(itemdata[0])
	except Exception as e:
		log_error("memcache_get_lastdata:"+str(e))


def memcache_get_baseinfo(info,lastdata,sec):
	'''
	@memcache 获取缓存命中率等基本信息
	'''
	try:
		baseinfo = {}
		indexrate = (float(info['get_hits'])/(float(info['get_hits'])+float(info['get_misses'])))
		baseinfo['indexrate'] = indexrate
		baseinfo['curr_connects'] = info['curr_connections']
		baseinfo['total_connects'] = info['total_connections']
		baseinfo['used_mem'] = float(info['bytes']) #单位bytes
		baseinfo['curr_item'] = info['curr_items']
		baseinfo['bytes_read'] = info['bytes_read']
		baseinfo['bytes_written'] = info['bytes_written']
		baseinfo['spacerate'] = (float(baseinfo['used_mem'])/float(info['limit_maxbytes']))
		if lastdata == '' or lastdata ==None:
			baseinfo['read_rate']=baseinfo['write_rate']=baseinfo['connects_rate']=0
		else:
			baseinfo['read_rate']=(int(info['bytes_read'])-int(lastdata['bytes_read']))/float(sec)
			baseinfo['write_rate']=(int(info['bytes_written'])-int(lastdata['bytes_written']))/float(sec)
			baseinfo['connects_rate']=(int(info['total_connections'])-int(lastdata['total_connects']))/float(sec)
		return baseinfo
	except Exception as e:
		log_error("memcache_get_baseinfo():"+str(e))


def memcache_alarm_tconnected(itemconfig,baseinfo):
	'''
	@memcache 报警指标curr_connections
	'''
	try:
		switch = itemconfig['curr_connects']['condition']
		value = itemcongig['curr_connects']['value']
		cNum = baseinfo['curr_connects']
		if switch == '大于':
			if cNum>value:
				return True #说明符合报警条件
		elif switch == '小于':
			if cNum<value:
				return True #说明符合报警条件
		return False #说明不需要报警
	except Exception as e:
		log_error("memcache_alarm_tconnected():"+str(e))


	
def memcache_alarm_usedmem(itemconfig,baseinfo):
	'''
	@memcache 报警指标used_memory
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
		log_error("memcache_alarm_usedmem():"+str(e))


def memcache_alarm_others(key,itemconfig,info):
	'''
	@memcache 自定义指标 others
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
	
	
def memcache_alarm_analyze(itemconfig,baseinfo,info):	
	'''
	@memcache 报警条件分析
	'''
	for key in itemconfig.keys():
		if key == 'curr_connects' and memcache_alarm_tconnected(itemconfig,baseinfo) == True:
			reason = "Memcache当前连接数"+itemconfig['curr_connects']['condition']+" "+itemconfig['curr_connects']['value']
			return True ,reason #表示需要报警
		elif key == 'used_memory' and memcache_alarm_usedmem(itemconfig,baseinfo) == True:
			reason = "Memcache使用内存"+itemconfig['used_memory']['condition']+" "+itemconfig['used_memory']['value']
			return True ,reason
	return False ,''  #表示不需要报警
	
def memcache_status_analyze(url,itemconfig, itemid, sec):
	'''
	@memcache 状态页分析
	'''
	try:
		start = datetime.now()
		socket.setdefaulttimeout(MONITOR['timeout'])
		result = urllib2.urlopen(url)
		end = datetime.now()
	except Exception as e:
		log_info("memcache-status页面访问失败:"+str(e))
		reason = url+"访问失败,连接超时"
		return str(0),reason,'',str(1)
	content = result.read()
	responsetime = (end-start).microseconds/1000
	try:
		info = simplejson.loads(content)
	except Exception as e:
		reason ="memcache-status页面配置错误"
		return str(0),reason,'',str(3)
	lastdata = memcache_get_lastdata(itemid)
	baseinfo = memcache_get_baseinfo(info,lastdata,sec)
	baseinfo['responsetime']=responsetime
	isAlarm,reason = memcache_alarm_analyze(itemconfig,baseinfo,info)
	if isAlarm == True:
		return str(0) ,reason,simplejson.dumps(baseinfo),str(3)
	else:
		return str(1) ,'',simplejson.dumps(baseinfo),str(4)
		
if __name__ == '__main__':
	'''
	@ test 
	'''
	pass
	
