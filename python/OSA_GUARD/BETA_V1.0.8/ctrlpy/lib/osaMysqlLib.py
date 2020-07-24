#!/usr/bin/env python
#encoding=utf-8
'''
	Author: 	OSA开源团队
	Description:	(监控项目 mysql status 数据采集与报警)
	Date: 		2011-08-31
'''
import re ,MySQLdb,simplejson
import subprocess,time,signal,sys
from datetime import datetime
from ctrlpy.lib import cmdtosql
from ctrlpy.lib.osaLogLib import *
from ctrlpy.etc.config import MONITOR


def mysql_get_lastdata(itemid):
	'''
	@获取上一次获取的数据中的visitNum字段值
	'''
	try:
		sql = "select oMonResult from osa_monitor_record where oItemid ="+str(itemid)+" order by id desc limit 1"
		con = cmdtosql._get_pcon()
		cur = con.cursor()
		cur.execute(sql)
		itemdata=cur.fetchone()
		cmdtosql._exit(con, cur)
		if itemdata == None or itemdata[0]=='' or itemdata[0] == 'null':
			return None
		else:
			return eval(itemdata[0])
	except Exception as e:
		log_error("mysql_get_lastdata:"+str(e))


def mysql_get_status(status):
	'''
	@获取 mysql 中需要的status
	'''
	try:
		status_list = {}
		for state in status:
			if state[0] == 'Com_change_db':
				status_list['Com_change_db'] = int(state[1]) 
			if state[0] == 'Com_delete':
				status_list['Com_delete'] = int(state[1])
			if state[0] == 'Com_insert':
				status_list['Com_insert'] = int(state[1])
			if state[0] == 'Com_select':
				status_list['Com_select'] = int(state[1])
			if state[0] == 'Com_update':
				status_list['Com_update'] = int(state[1])
			if state[0] == 'Threads_cached':
				status_list['Threads_cached'] = int(state[1])
			if state[0] == 'Threads_connected':
				status_list['Threads_connected'] = int(state[1])
			if state[0] == 'Threads_created':
				status_list['Threads_created'] = int(state[1])
			if state[0] == 'Threads_running':
				status_list['Threads_running'] = int(state[1])
			if state[0] == 'Aborted_clients':
				status_list['Aborted_clients'] = int(state[1])
			if state[0] == 'Aborted_connects':
				status_list['Aborted_connects'] = int(state[1])
			if state[0] == 'Qcache_free_blocks':
				status_list['Qcache_free_blocks'] = int(state[1])
			if state[0] == 'Qcache_free_memory':
				status_list['Qcache_free_memory'] = int(state[1])
			if state[0] == 'Qcache_hits':
				status_list['Qcache_hits'] = int(state[1])
			if state[0] == 'Qcache_inserts':
				status_list['Qcache_inserts'] = int(state[1])
			if state[0] == 'Qcache_queries_in_cache':
				status_list['Qcache_query'] = int(state[1])
			if state[0] == 'Qcache_total_blocks':
				status_list['Qcache_total_blocks'] = int(state[1])
			if state[0] == 'Connections':
				status_list['Connections'] = int(state[1])
			if state[0] == 'Table_locks_immediate':
				status_list['Table_locks_immediate'] = int(state[1])
			if state[0] == 'Table_locks_waited':
				status_list['Table_locks_waited'] = int(state[1])
			if state[0] == 'Questions':
				status_list['Questions'] = int(state[1])
			if state[0] == 'Bytes_sent':
				status_list['Bytes_sent'] = int(state[1])
			if state[0] == 'Bytes_received':
				status_list['Bytes_received'] = int(state[1])
		return status_list	
	except Exception as e:
		log_error("mysql_get_status():"+str(e))


def mysql_get_variables(variables):
	'''
	@mysql 获取需要的variables
	'''
	try:
		variables_list = {}
		for varis in variables:
			if varis[0] == 'max_connections':
				variables_list['max_connections'] = int(varis[1])
			if varis[0] == 'query_cache_size':
				variables_list['query_cache_size'] = int(varis[1])
		return variables_list
	except Exception as e:
		log_error("mysql_get_variables():"+str(e))

	
def mysql_get_querycache(status_list,variables_list):
	'''
	@mysql 计算查询缓存利用率
	'''
	try:
		query_cache_size = variables_list['query_cache_size']
		qcache_free_memory = status_list['Qcache_free_memory']
		query_cache_rate = float(query_cache_size - qcache_free_memory)/query_cache_size
		return query_cache_rate
	except Exception as e:
		log_error("mysql_get_querycache():"+str(e))


def mysql_get_visitcache(status_list):
	'''
	@mysql 计算缓存访问率
	'''
	try:
		threads_created = status_list['Threads_created']
		connections = status_list['Connections']
		visit_cache_rate =float(threads_created)/connections
		return visit_cache_rate
	except Exception as e:
		log_error("mysql_get_visitcache():"+str(e))



def mysql_get_scrapcache(status_list):
	'''
	@mysql 计算查询缓存碎片率
	'''
	try:
		qcache_free_blocks = status_list['Qcache_free_blocks']
		qcache_total_blocks = status_list['Qcache_total_blocks']
		scrap_cache_rate = float(qcache_free_blocks)/qcache_total_blocks
		return scrap_cache_rate
	except Exception as e:
		log_error("mysql_get_scrapcache():"+str(e))

	
def mysql_get_hitcache(status_list):
	'''
	@mysql 计算查询缓存命中率
	'''
	try:
		qcache_hits = status_list['Qcache_hits']
		qcache_inserts = status_list['Com_select']
		hits_cache_rate = float(qcache_hits)/(qcache_hits + qcache_inserts)
		return hits_cache_rate
	except Exception as e:
		log_error("mysql_get_hitcache():"+str(e))

def mysql_get_subordinate_status(subordinatelist):
	'''
	@mysql 获取主从状态,判断是否报警
	'''
	try:
		if not subordinatelist:
			return True
		elif subordinatelist[0][10] == 'No' or subordinatelist[0][11] == 'No':
			return True
		return False			
	except Exception as e:
		
		log_error("mysql_get_subordinate_status():"+str(e))
		return True

def mysql_alarm_tconnected(itemconfig,status_list):
	'''
	@mysql 报警指标Threads_connected
	'''
	try:
		switch = itemconfig['Threads_connected']['condition']
		value = itemconfig['Threads_connected']['value']		
		if switch == '大于':
			if int(status_list['Threads_connected'])> int(value):
				return True #说明符合报警条件
		elif switch == '小于':
			if int(status_list['Threads_connected'])<int(value):
				return True #说明符合报警条件
		return False #说明不需要报警
	except Exception as e:
		log_error("mysql_alarm_tconnected():"+str(e)+'||'+str(itemconfig)+str(status_list))
	


def mysql_alarm_trunning(itemconfig,status_list):
	'''
	@mysql 报警指标Threads_running
	'''
	try:
		switch = itemconfig['Threads_running']['condition']
		value = itemconfig['Threads_running']['value']
		if switch == '大于':
			if int(status_list['Threads_running'])>int(value):
				return True #说明符合报警条件
		elif switch == '小于':
			if int(status_list['Threads_running'])<int(value):
				return True #说明符合报警条件
		return False #说明不需要报警
	except Exception as e:
		log_error("mysql_alarm_trunning():"+str(e))
	

def mysql_alarm_aclients(itemconfig,status_list,last_aclients):
	'''
	@mysql 报警指标Abored_clients
	@说明 Abored_clients_now = status_list['Abored_clients'] - Abored_clients_last
	'''
	try:
		if last_aclients == None:#统一认为是这次为第一次监控
			return False
		switch = itemconfig['Abored_clients']['condition']
		value = itemconfig['Abored_clients']['value']
		cNum = status_list['Abored_clients'] - last_aclients
		if switch == '大于':
			if int(cNum)>int(value):
				return True #说明符合报警条件
		elif switch == '小于':
			if int(cNum)<int(value):
				return True #说明符合报警条件
		return False #说明不需要报警
	except Exception as e:
		log_error("mysql_alarm_aclients():"+str(e))


def mysql_alarm_aconnects(itemconfig,status_list,last_aconnects):
	'''
	@mysql 报警指标Abored_clients
	@说明 Abored_connects_now = status_list['Abored_connects'] - Abored_connects_last
	'''
	try:
		if last_aconnects == None:#统一认为是这次为第一次监控
			return False
		switch = itemconfig['Abored_connects']['condition']
		value = itemconfig['Abored_connects']['value']
		cNum = status_list['Abored_connects'] - last_aconnects
		if switch == '大于':
			if int(cNum)>int(value):
				return True #说明符合报警条件
		elif switch == '小于':
			if int(cNum)<int(value):
				return True #说明符合报警条件
		return False #说明不需要报警
	except Exception as e:
		log_error("mysql_alarm_aconnects():"+str(e))

	
def mysql_alarm_querycache(itemconfig,query_cache_rate):
	'''
	@mysql 报警指标：缓存利用率
	'''
	try:
		switch = itemconfig['query_cache_rate']['condition']
		value = itemconfig['query_cache_rate']['value']
		if switch == '大于':
			if int(query_cache_rate)>int(value):
				return True #说明符合报警条件
		elif switch == '小于':
			if int(query_cache_rate)<int(value):
				return True #说明符合报警条件
		return False #说明不需要报警
	except Exception as e:
		log_error("mysql_alarm_querycache():"+str(e))


def mysql_alarm_others(key ,itemconfig,status):
	'''
	@mysql 自定义指标报警
	@还需改善，还没考虑完整
	'''
	switch = itemconfig[key]['switch']
	value = itemconfig[key]['value']
	other_value = 0
	for state in status:
		if state[0] == key:
			other_value = int(state[1])
	if switch == '>':
		if other_value>value:
			return True #说明符合报警条件
	elif switch == '<':
		if other_value<value:
			return True #说明符合报警条件
	return False #说明不需要报警


	
def mysql_alarm_analyze(itemconfig,status_list ,last_aclients ,last_aconnects ,query_cache_rate ,status,subordinatelist):
	'''
	@mysql 对报警指标进行分析
	'''		
	#for key in itemconfig.keys():
	if 'Threads_connected' in itemconfig.keys() and mysql_alarm_tconnected(itemconfig,status_list) == True:
		reason = "Mysql当前连接数"+itemconfig['Threads_connected']['condition']+" "+itemconfig['Threads_connected']['value']
		
		return True ,reason  #表示需要报警
	elif 'Threads_running' in itemconfig.keys() and mysql_alarm_trunning(itemconfig,status_list) == True:
		reason = "Mysql激活的线程数"+itemconfig['Threads_running']['condition']+" "+itemconfig['Threads_running']['value']
		return True,reason
	elif 'Abored_clients' in itemconfig.keys() and mysql_alarm_aclients(itemconfig,status_list,last_aclients) == True:
		reason = "Mysql中断连接数"+itemconfig['Abored_clients']['condition']+" "+itemconfig['Abored_clients']['value']
		return True ,reason
	elif 'Abored_connects' in itemconfig.keys() and mysql_alarm_aconnects(itemconfig,status_list,last_aconnects) == True:
		reason = "Mysql失败连接数"+itemconfig['Abored_connects']['condition']+" "+itemconfig['Abored_connects']['value']
		return True ,reason
	elif 'query_cache_rate' in itemconfig.keys() and mysql_alarm_querycache(itemconfig,query_cache_rate) == True:
		reason = "Mysql查询缓存利用率"+itemconfig['query_cache_rate']['condition']+" "+itemconfig['query_cache_rate']['value']
		return True ,reason
	elif 'subordinate_status' in itemconfig.keys() and mysql_get_subordinate_status(subordinatelist) == True:
		reason = "Mysql subordinate状态异常,可能原因是主从没有开启或者subordinate线程己停止"
		return True ,reason
	#else :
	#	if mysql_alarm_others(key ,itemconfig,status) == True:
	#		return True
	return False,'' #表示不需要报警	

	
def mysql_deal_baseinfo(status_list,variables_list,lastdata,sec):
	'''
	@mysql 对status_list和variables_list的数据进行处理，返回需要采集的数据
	'''
	try:
		mysql_data = {}
		mysql_data['query_cache_rate'] = mysql_get_querycache(status_list,variables_list)
		mysql_data['visit_cahce_rate'] = mysql_get_visitcache(status_list)
		mysql_data['scrap_cache_rate'] = mysql_get_scrapcache(status_list)
		mysql_data['hits_cache_rate'] = mysql_get_hitcache(status_list)
		mysql_data['max_connections'] = variables_list['max_connections']
		mysql_data['Threads_connected'] = status_list['Threads_connected']
		mysql_data['Threads_running'] = status_list['Threads_running']
		mysql_data['Threads_created'] = status_list['Threads_created']
		mysql_data['Threads_cached'] = status_list['Threads_cached']
		mysql_data['Qcache_query'] = status_list['Qcache_query']
		mysql_data['Aborted_connects'] = status_list['Aborted_connects']
		mysql_data['Aborted_clients'] = status_list['Aborted_clients']
		mysql_data['Com_change_db'] = status_list['Com_change_db']
		mysql_data['Com_delete'] = status_list['Com_delete']
		mysql_data['Com_insert'] = status_list['Com_insert']
		mysql_data['Com_select'] = status_list['Com_select']
		mysql_data['Com_update'] = status_list['Com_update']
		mysql_data['Table_locks_immediate'] = status_list['Table_locks_immediate']
		mysql_data['Table_locks_waited'] = status_list['Table_locks_waited']
		mysql_data['Bytes_sent'] = status_list['Bytes_sent']
		mysql_data['Bytes_received'] = status_list['Bytes_received']
		mysql_data['Questions'] = status_list['Questions']
		mysql_data['Connections'] = status_list['Connections']
		if lastdata == '' or lastdata == None:
			mysql_data['change_db_rate'] = mysql_data['delete_rate'] = mysql_data['select_rate'] =mysql_data['update_rate'] = mysql_data['insert_rate'] =0
			mysql_data['locks_immediate_rate'] = mysql_data['locks_waited_rate'] = mysql_data['questions_rate']=0
			mysql_data['bytes_sent_rate'] = mysql_data['bytes_received_rate']=0
			mysql_data['Aborted_clients_rate'] =  mysql_data['Aborted_connects_rate'] = 0
		else:
			mysql_data['change_db_rate'] = (int(status_list['Com_change_db'])-int(lastdata['Com_change_db']))/float(sec)
			mysql_data['delete_rate'] = (int(status_list['Com_delete'])-int(lastdata['Com_delete']))/float(sec)
			mysql_data['select_rate'] = (int(status_list['Com_select'])-int(lastdata['Com_select']))/float(sec)
			mysql_data['insert_rate'] = (int(status_list['Com_insert'])-int(lastdata['Com_insert']))/float(sec)
			mysql_data['update_rate'] = (int(status_list['Com_update'])-int(lastdata['Com_update']))/float(sec)
			mysql_data['locks_immediate_rate'] = (int(status_list['Table_locks_immediate'])-int(lastdata['Table_locks_immediate']))/float(sec)
			mysql_data['locks_waited_rate'] = (int(status_list['Table_locks_waited'])-int(lastdata['Table_locks_waited']))/float(sec)
			mysql_data['questions_rate'] = (int(status_list['Questions'])-int(lastdata['Questions']))/float(sec)
			mysql_data['bytes_sent_rate'] = (int(status_list['Bytes_sent'])-int(lastdata['Bytes_sent']))/float(sec)
			mysql_data['bytes_received_rate'] = (int(status_list['Bytes_received'])-int(lastdata['Bytes_received']))/float(sec)
			mysql_data['Aborted_clients_rate'] = (int(status_list['Aborted_clients'])-int(lastdata['Aborted_clients']))/float(sec)
			mysql_data['Aborted_connects_rate'] = (int(status_list['Aborted_connects'])-int(lastdata['Aborted_connects']))/float(sec)
		return mysql_data
	except Exception as e:
		log_error('mysql_deal_baseinfo:'+str(e))
	
	
def mysql_status_analyze(object,itemconfig,itemid,sec):
	'''
	@mysql 服务状态与性能分析
	@整体已完成，但还需要调优
	'''
	try:
		start = datetime.now()
		con = MySQLdb.connect(host=object,port=int(itemconfig['port']),user=itemconfig['user'],passwd=itemconfig['passwd'])
		end = datetime.now()
	except Exception as e:
		log_info("mysql数据库连接失败:"+str(e))
		reason = "Mysql数据库连接失败"
		return str(0),reason,'',str(1)
		
	cursor=con.cursor()
	
	cursor.execute('show global status;')
	status = cursor.fetchall()
	
	cursor.execute('show global variables')
	variables = cursor.fetchall()
	
	cursor.execute('show subordinate status')
	subordinatelist=cursor.fetchall()
	
	responsetime = (end-start).microseconds/1000
	status_list = mysql_get_status(status)
	variables_list = mysql_get_variables(variables)
		
	lastdata = mysql_get_lastdata(itemid)
	mysql_json = mysql_deal_baseinfo(status_list,variables_list,lastdata,sec)
	mysql_json['responsetime']=responsetime
	if lastdata == '' or lastdata == None:
		last_aclients = last_aconnects = None;
	else:
		last_aclients = lastdata['Aborted_clients']
		last_aconnects = lastdata['Aborted_connects']
	isAlarm ,reason = mysql_alarm_analyze(itemconfig,status_list ,last_aclients ,last_aconnects ,mysql_json['query_cache_rate'] ,status,subordinatelist)
	if isAlarm == True :
		return str(0) ,reason ,simplejson.dumps(mysql_json),str(3)
	else:
		return str(1) ,'',simplejson.dumps(mysql_json),str(4)
	

##########################分割线-----------mysql status 数据处理结束 -------------分割线######################


def mysql_get_itemdata(itemid):
	'''
	@osaMonitor 获取mysql 上一次采集的数据
	'''
	#取距离现在最近的一条记录
	sql = "select * from osa_monitor_record where oItemid ="+str(itemid)+" order by id desc limit 1"
	result = cmdtosql.select(sql)
	if not result or result == None:#不存在结果，情况：项目第一次开始监控
		return None,None
	if result[0][3] == '' or result[0][3]==None:
		return None,None
	list = eval(result[0][3])
	if not list or list == None:#上次结果为空，情况：上次出现exception
		return None,None
	return list['Aborted_clients'],list['Aborted_connects']

if __name__ == '__main__':
	'''
	@test
	'''
	pass
	
	
