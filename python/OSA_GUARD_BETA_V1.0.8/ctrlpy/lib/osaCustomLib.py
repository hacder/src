#!/usr/bin/env python
#encoding=utf-8
'''
	Author: 	OSA开源团队
	Description:	(监控项目及项目报警辅助函数)
	Date: 		2012-09-03
	@ 依赖pyDNS 需要安装pyDNS模块
'''
import re ,simplejson
import subprocess,time,signal,sys
from datetime import datetime
from ctrlpy.lib.osaLogLib import *
from ctrlpy.lib import osaSnmpLib
from ctrlpy.lib.osaPing import Ping
from pysnmp.entity.rfc3413.oneliner import cmdgen
from pysnmp.proto.rfc1902 import ObjectName
from ctrlpy.lib import cmdtosql

def _get_snmpinfo():
	'''
	@从数据库osa_snmp表获取snmp配置信息。
	'''
	try:
		sql = "select oSnmpPort,oSnmpKey from osa_snmp where id='1'"
		con = cmdtosql._get_pcon()
		cur = con.cursor()
		cur.execute(sql)
		snmpinfo=cur.fetchone()
	except Exception as e:
		log_error("collect.get_snmpinfo(ip):"+str(e))
	cmdtosql._exit(con, cur)
	return snmpinfo

def custom_get_baseinfo(ip,itemconfig):
	'''
	@custom 根据类型获取信息
	'''

	name = itemconfig['name']
	agent = 'myagent'
	snmpinfo = _get_snmpinfo()
	key = snmpinfo[1]
	port = snmpinfo[0]
	#snmp 配置验证
	verify_res = osaSnmpLib.snmp_config_verify(agent,ip,key,port)
	if verify_res == False:
		return False
	bit,ostype = osaSnmpLib.getOStype(agent, ip, key, port)
	if ostype == 'Windows':
		from ctrlpy.lib import osaSnmp_winLib
		if name == 'memory':
			return osaSnmp_winLib.get_system_memory(agent,ip,key,port)
		elif name == 'network':			
			return osaSnmp_winLib.network_stat(agent,ip,key,port)
		elif name == 'diskstat':
			return osaSnmp_winLib.get_system_disk(agent,ip,key,port)
		elif name == 'logins':
			return osaSnmp_winLib.get_login_user(agent,ip,key,port)	
		elif name == 'cpu':
			return osaSnmp_winLib.cpu_usage(agent,ip,key,port)
	else:
		if name == 'loadstat':
			return osaSnmpLib.loadstat(agent,ip,key,port)
		elif name == 'memory':
			return osaSnmpLib.memory_handle(agent,ip,key,port)
		elif name == 'network':			
			return osaSnmpLib.network_stat(agent,ip,key,port)
		elif name == 'diskstat':
			return osaSnmpLib.disk_stat(agent,ip,key,port)
		elif name == 'cpu':
			return osaSnmpLib.cpu_usage(agent,ip,key,port)
		elif name == 'logins':
			return osaSnmpLib.user_login(agent,ip,key,port)
		elif name == 'diskio':
			return osaSnmpLib.disk_io(agent,ip,key,port)
	
	return False


def custom_loadstat_analyze(itemconfig,custom_json):
	'''
	@custom 负载指标判断
	'''
	try:
		indicators = itemconfig['indicators']
		if 'one' in indicators.keys():
			value = indicators['one']['value']
			switch = indicators['one']['condition']
			if float(custom_json['loadstat']['one']) > float(value):
				return True,'最近1分钟平均负载大于'+str(value)
			else:
				return False,''
		elif 'five' in indicators.keys():
			value = indicators['five']['value']
			switch = indicators['five']['condition']
			if float(custom_json['loadstat']['five']) > float(value):
				return True,'最近5分钟平均负载大于'+str(value) 
			else:
				return False,''
		elif 'fifteen' in indicators.keys():
			value = indicators['fifteen']['value']
			switch = indicators['fifteen']['condition']
			if float(custom_json['loadstat']['fifteen']) > float(value):
				return True,'最近15分钟平均负载大于'+str(value) 
			else:
				return False,''
	except Exception as e:
		log_error("custom_loadstat_analyze():"+str(e))		
	

def custom_memory_analyze(itemconfig,custom_json):
	'''
	@custom memory指标判断
	'''
	try:
		indicators = itemconfig['indicators']
		if 'real' in indicators.keys():
			switch = indicators['real']['condition']
			value = indicators['real']['value']
			mem_rate = float(custom_json['memory']['real_used'])/float(custom_json['memory']['real_total'])
			if (mem_rate*100) > float(value):
				return True,'内存使用率大于'+str(value)
			else:
				return False,''
		elif 'swap' in indicators.keys():
			switch = indicators['swap']['condition']
			value = indicators['swap']['value']
			mem_rate = float(custom_json['memory']['swap_used'])/float(custom_json['memory']['swap_total'])
			if (mem_rate*100) > float(value):
				return True,'swap内存使用率大于'+str(value)
			else:
				return False,''
	except Exception as e:
		log_error("custom_memory_analyze():"+str(e))

	
def custom_logins_analyze(itemconfig,custom_json):
	'''
	@custom logins指标判断
	'''
	try:
		indicators = itemconfig['indicators']
		switch = indicators['logins']['condition']
		value = indicators['logins']['value']
		if float(custom_json['user_login']) > float(value):
			return True,'当前登录用户数大于'+str(value)
		else:
			return False,''
	except Exception as e:
		log_error("custom_logins_analyze():"+str(e))

	
def custom_network_analyze(itemconfig,custom_json):
	'''
	@custom network指标判断
	'''
	try:
		indicators = itemconfig['indicators']
		if 'inbond' in indicators.keys():
			switch = indicators['inbond']['condition']
			value = indicators['inbond']['value']
			if 'eth0' in custom_json['network'].keys():
				if float(custom_json['network']['eth0']['inbond'])*1024 > float(value):
					return True,'网卡流入速率大于'+str(value)
			return False,''
		elif 'outbond' in indicators.keys():
			switch = indicators['outbond']['condition']
			value = indicators['outbond']['value']
			if 'eth0' in custom_json['network'].keys():
				if float(custom_json['network']['eth0']['outbond'])*1024 > float(value):
					return True,'网卡流出速率大于'+str(value)
			return False,''
	except Exception as e:
		log_error("custom_network_analyze():"+str(e))	

	
	
def custom_cpu_analyze(itemconfig,custom_json):
	'''
	@custom cpu指标判断
	@用户模式cpu使用率，内核模式cpu使用率，低优先级模式cpu使用率
	@name : 'user','kernel','low-priority'
	'''
	try:
		indicators = itemconfig['indicators']
		switch = indicators['use']['condition']
		value = indicators['use']['value']
		if switch == '大于':
			
			try:			
				if float(custom_json['cpu']['win']) > float(value)/100:
					return True,'当前CPU使用率大于'+str(value)
				else:
					return False,''
			except Exception as e:
				if float(custom_json['cpu']['user']) > float(value)/100:
					return True,'当前CPU使用率大于'+str(value)			
				else:
					return False,''
		elif switch == '小于':
			if 'win' in custom_json['cpu']:			
				if float(custom_json['cpu']['win']) < float(value)/100:
					return True,'当前CPU使用率小于'+str(value)
				else:
					return False
			elif float(custom_json['cpu']['user']) < float(value)/100:
				return True,'当前CPU使用率小于'+str(value)			
			else:
				return False,''	
	except Exception as e:
		log_error("custom_cpu_analyze():"+str(e))


def custom_diskio_analyze(itemconfig,custom_json):
	'''
	@custom diskio指标判断
	'''
	
	try:
		indicators = itemconfig['indicators']
		switch = indicators['switch']
		value = indicators['value']
		if switch == '>':
			for key in custom_json['io']:
				if key[0][name] > value:
					return True,key.keys()
			return False,''
		if switch == '<':
			for key in custom_json['io']:
				if key[0][name] < value:
					return True,key.keys()
			return False,''
	except Exception as e:
		log_error("custom_diskio_analyze():"+str(e))	



def custom_diskstat_analyze(itemconfig,custom_json):
	'''
	@custom diskstat指标判断
	'''
	try:
		indicators = itemconfig['indicators']
		switch = indicators['used']['condition']
		value = indicators['used']['value']
		cdisk = custom_json['disk']
		for k,v in cdisk.items():
			use=float(v['use'])
			total=float(v['total'])
			if total > 0:
				cvalue = float(use/total)
			else:
				cvalue = 0
			v = float(float(value)/100)
			if cvalue > v :
				return True,'磁盘空间使用率大于'+str(value) + '%'

		return False,''
	except Exception as e:
		log_error("custom_diskstat_analyze():"+str(e))
		return False,''
		

def custom_alarm_analyze(itemconfig,custom_json):
	'''
	@custom 报警分析
	'''
	try:
		name = itemconfig['name']
		if name == 'loadstat':
			return custom_loadstat_analyze(itemconfig,custom_json)
		elif name == 'memory':
			return custom_memory_analyze(itemconfig,custom_json)
		elif name == 'network':
			return custom_network_analyze(itemconfig,custom_json)
		elif name == 'diskstat':
			return custom_diskstat_analyze(itemconfig,custom_json)
		elif name == 'cpu':
			return custom_cpu_analyze(itemconfig,custom_json)
		elif name == 'logins':
			return custom_logins_analyze(itemconfig,custom_json)
		elif name == 'diskio':
			return custom_diskio_analyze(itemconfig,custom_json)
	except Exception as e:
		log_error("custom_alarm_analyze():"+str(e))		


def custom_status_analyze(ip,itemconfig):
	'''
	@custom 自定义指标
	'''
	#首先 ping(ip)
	start = datetime.now()
	isTrue = Ping(ip)
	end = datetime.now()
	responsetime = (end-start).microseconds/1000
	if isTrue == False:
		reason = "ping连接失败,数据包全部丢失"
		return str(0),reason,'',str(1)
	#获取数据(根据名字)	
	
	custom_json = custom_get_baseinfo(ip,itemconfig)
	if(bool(custom_json) == False):
		
		reason = "目标服务器Snmp配置有误,或windows服务器不支持的指标监控类型"+str(itemconfig['name'])+"。"
		return str(0),reason,simplejson.dumps({'responsetime':responsetime}),str(2)
	else:
		
		custom_json['responsetime'] = responsetime
		#指标判定,是否要报警
		isAlarm ,reason= custom_alarm_analyze(itemconfig,custom_json)
		if isAlarm == True:#报警
			return str(0),reason,simplejson.dumps(custom_json),str(3)
		else:#不报警
			return str(1),reason,simplejson.dumps(custom_json),str(4)
	
	
###################################分割线 ------------ 服务 自定义指标 相关函数 ------------ 分隔符######################


if __name__ == '__main__':
	'''
	@custom test
	'''
	pass
	
	

