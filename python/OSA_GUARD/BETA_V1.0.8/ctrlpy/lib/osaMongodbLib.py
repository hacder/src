#!/usr/bin/env python
#encoding=utf-8
'''
	Author: 	OSA开源团队
	Description:	(监控项目 mongodb status 数据采集与报警)
	Date: 		2011-08-30
'''
import re ,simplejson
import urllib2 ,socket
import subprocess,time,signal,sys
from datetime import datetime
from ctrlpy.etc.config import MONITOR
from ctrlpy.lib.osaLogLib import *
from ctrlpy.lib import cmdtosql


def mongodb_get_lastdata(itemid):
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
		if itemdata is None or itemdata[0]=='' or itemdata[0] =='null':
			return None
		else:
			return eval(itemdata[0])
	except Exception as e:
		log_error("mongodb_get_lastdata:"+str(e))


	
def mongodb_get_baseinfo(info,lastdata,sec):
	'''
	@mongodb 获取内存等基本信息
	'''
	try:
		baseinfo = {}
		try:
			baseinfo['lock_ratio'] = info['serverStatus']['globalLock']['ratio']
		except Exception as e:
			baseinfo['lock_ratio'] = float(info['serverStatus']['globalLock']['lockTime'])/float(info['serverStatus']['globalLock']['totalTime'])
		baseinfo['lock_total'] = info['serverStatus']['globalLock']['currentQueue']['total']
		baseinfo['lock_readers'] = info['serverStatus']['globalLock']['currentQueue']['readers']
		baseinfo['lock_writers'] = info['serverStatus']['globalLock']['currentQueue']['writers']
		baseinfo['used_mem'] = info['serverStatus']['mem']['resident']
		baseinfo['used_space'] = info['serverStatus']['mem']['mapped']
		baseinfo['page_faults'] = info['serverStatus']['extra_info']['page_faults']
		baseinfo['curr_connects'] = info['serverStatus']['connections']['current']
		baseinfo['able_connects'] = info['serverStatus']['connections']['available']
		try:
			baseinfo['indexrate'] = 1-info['serverStatus']['indexCounters']['btree']['missRatio']
			baseinfo['accesses'] = info['serverStatus']['indexCounters']['btree']['accesses']
		except Exception as e:
			baseinfo['indexrate'] = 1-info['serverStatus']['indexCounters']['missRatio']
			baseinfo['accesses'] = info['serverStatus']['indexCounters']['accesses']
		baseinfo['query'] = info['serverStatus']['opcounters']['query']
		baseinfo['insert'] = info['serverStatus']['opcounters']['insert']
		baseinfo['update'] = info['serverStatus']['opcounters']['update']
		baseinfo['delete'] = info['serverStatus']['opcounters']['delete']
		baseinfo['getmore'] = info['serverStatus']['opcounters']['getmore']
		if lastdata == '' or lastdata == None:
			baseinfo['query_rate'] = baseinfo['insert_rate'] = baseinfo['update_rate'] = 0
			baseinfo['delete_rate'] = baseinfo['getmore_rate'] = baseinfo['access_rate'] = 0
		else:
			baseinfo['query_rate'] = (int(baseinfo['query'])-int(lastdata['query']))/float(sec)
			baseinfo['insert_rate'] = (int(baseinfo['insert'])-int(lastdata['insert']))/float(sec)
			baseinfo['update_rate'] = (int(baseinfo['update'])-int(lastdata['update']))/float(sec)
			baseinfo['delete_rate'] = (int(baseinfo['delete'])-int(lastdata['delete']))/float(sec)
			baseinfo['getmore_rate'] = (int(baseinfo['getmore'])-int(lastdata['getmore']))/float(sec)
			baseinfo['access_rate'] = (int(baseinfo['accesses'])-int(lastdata['accesses']))/float(sec)
		return baseinfo
	except Exception as e:
		log_error("mongodb_get_baseinfo():"+str(e))


def mongodb_alarm_tconnected(itemconfig,baseinfo):
	'''
	@mongodb 报警指标curr_connects
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
		log_error("mongodb_alarm_tconnected():"+str(e))	

	
def mongodb_alarm_ratio(itemconfig,baseinfo):
	'''
	@mongodb 报警指标ratio(锁定指标比例)
	'''
	try:
		switch = itemconfig['lock_ratio']['condition']
		value = itemcongig['lock_ratio']['value']
		cNum = baseinfo['lock_ratio']
		if switch == '大于':
			if cNum>value:
				return True #说明符合报警条件
		elif switch == '小于':
			if cNum<value:
				return True #说明符合报警条件
		return False #说明不需要报警
	except Exception as e:
		log_error("mongodb_alarm_ratio():"+str(e))
	

def mongodb_alarm_pagenum(itemconfig,baseinfo ,last_pagefaults ,sec):
	'''
	@mongodb 报警指标page_num
	@分页次数 数值/分  ：计算方式 page_faults（差值）/时间（分钟）
	'''
	try:
		if last_pagefaults == '' or last_pagefaults == None:
			return false
		switch = itemconfig['page_num']['condition']
		value = itemcongig['page_num']['value']
		pagenum = baseinfo['page_faults'] -int(last_pagefaults)
		pagenum = pagenum/int(sec)
		if switch == '大于':
			if pagenum>value:
				return True #说明符合报警条件
		elif switch == '小于':
			if pagenum<value:
				return True #说明符合报警条件
		return False #说明不需要报警
	except Exception as e:
		log_error("mongodb_alarm_pagenum():"+str(e))
	

	
def mongodb_alarm_analyze(itemconfig,baseinfo,last_pagefault,sec):
	'''
	@mongodb 报警条件分析
	'''
	try:
		for key in itemconfig.keys():
			if key == 'curr_connects' and mongodb_alarm_tconnected(itemconfig,baseinfo) == True:
				reason = "Mongodb当前连接数"+itemconfig['curr_connects']['condition']+" "+itemconfig['curr_connects']['value']
				return True ,reason #表示需要报警
			elif key == 'lock_ratio' and mongodb_alarm_usedmem(itemconfig,baseinfo) == True:
				reason = "Mongodb锁定时间比例"+itemconfig['lock_ratio']['condition']+" "+itemconfig['lock_ratio']['value']
				return True ,reason
			elif key == 'page_num' and mongodb_alarm_pagenum(itemcongig,baseinfo,last_pagefaults,sec) == True:
				reason = "Mongodb分页次数"+itemconfig['page_num']['condition']+" "+itemconfig['page_num']['value']
				return True ,reason
		return False ,'' #表示不需要报警
	except Exception as e:
		log_error('mongodb_alarm_analyze:'+str(e))


def mongodb_deal_infos(baseinfo,lockinfo,rateinfo=''):
	'''
	@mongodb 处理获取的数据
	'''
	mongodb_info = {}
	mongodb_info['baseinfo'] = baseinfo
	mongodb_info['lockinfo'] = lockinfo
	#mongodb_info['rateinfo'] = rateinfo
	return simplejson.dumps(mongodb_info)

	
def mongodb_status_analyze(url,itemconfig,itemid,sec):
	'''
	@mongodb 状态页分析
	'''
	try:
		start = datetime.now()
		socket.setdefaulttimeout(MONITOR['timeout'])
		result = urllib2.urlopen(url)
		end = datetime.now()
	except Exception as e:
		log_info("mongodb-status页面访问失败:"+str(e))
		reason = url+"访问失败,连接超时"
		return str(0),reason,'',str(1)
	content = result.read()
	responsetime = (end-start).microseconds/1000
	try:
		info = simplejson.loads(content)
	except Exception as e:
		reason = "Mongodb-status页面配置错误,"
		return str(0),reason,'',str(3)
	lastdata = mongodb_get_lastdata(itemid)
	baseinfo = mongodb_get_baseinfo(info,lastdata,sec)
	baseinfo['responsetime']=responsetime
	if lastdata == None or lastdata == '':
		last_pagefaults = None
	else:
		last_pagefaults = lastdata['page_faults']
	isAlarm ,reason= mongodb_alarm_analyze(itemconfig,baseinfo,last_pagefaults,sec)
	if isAlarm == True:
		return str(0) ,reason,simplejson.dumps(baseinfo),str(3)
	else:
		return str(1) ,'',simplejson.dumps(baseinfo),str(4)
	
	
##########################分割线-----------mongodb status 数据处理结束 -------------分割线######################


def mongodb_get_itemdata(itemid):
	'''
	@osaMonitor monogdb 获取osa_monitor_record记录
	'''
	sql = "select * from osa_monitor_record where oItemid="+itemid+" order by id desc limit 1"
	result = cmdtosql.select(sql)
	if not result or result == None:
		return None
	list = simplejson.loads(result[0][3])
	if not list or list == None:
		return None
	return list['page_faults']


if __name__ == '__main__':
	'''
	@test
	'''
	pass
	
