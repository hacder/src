#!/usr/bin/env python
#encoding=utf-8

import sys,re,time
import simplejson as json
import thread,threading
from datetime import datetime
from ctrlpy.lib.osaPing import Ping
#from pysnmp.entity.rfc3413.oneliner import cmdgen
from pysnmp.proto.rfc1902 import ObjectName
from ctrlpy.lib.cmdtosql import _get_time
from ctrlpy.lib.osaLogLib import *
from pysnmp.proto.rfc1902 import ObjectName
from ctrlpy.lib.osaSnmp_winLib import Win_handle_func,ger_snmp_get,ger_snmp_next
from ctrlpy.lib import cmdtosql

reload(sys) 
sys.setdefaultencoding('utf8') 

def getOStype(agent, ip, key, port):
	'''
	获取操作系统类型：
	bit --> getOStype[0]
	type --> getOStype[1]
	'''
	system_oid = ObjectName('1.3.6.1.2.1.1.1')
	system_get = ger_snmp_next(agent,ip,key,port,system_oid)
	system_name = str(system_get[0][0][1])
	if 'Windows' in system_name:
		system_type = 'Windows'
		if 'x86' in system_name:
			system_bit = "32bit"
		else:
			system_bit = "64bit"
	elif 'Linux' in system_name:
		system_type = 'Linux'
		if 'x86_64' in system_name:
			system_bit = "64bit"
		else:
			system_bit = "32bit"
	else:
		system_type = 'Null'

	Usql = "update osa_ipinfo set oOsType='"+system_type+"' where oIp = '"+ip+"'"

	cmdtosql.update(Usql)

	return system_bit,system_type


def change_dict(snmp_get):
	'''
	把snmp获取来的数据转为字典
	'''
	snmp_dict={}
	for key in snmp_get:		#print f
		snmp_index=key[0][0][-1]
		snmp_values=str(key[0][1])
		snmp_change={snmp_index:snmp_values}
		snmp_dict.update(snmp_change)
	return snmp_dict

def get_snmp_func():
	'''
	@获取snmp 采集数据的函数
	'''
	func = [memory_handle,user_login,loadstat,process_num,constat,network_stat,cpu_usage,disk_io,disk_stat]
	return func
	
def memory_handle(agent,ip,key,port):
	'''
	#memory 内存使用信息，单位单位：GBytes
	#user : 已使用内存
	#total :总内存
	#return {"memory":{"used":"used","total":"total"}}
	'''
	moid = ObjectName('.1.3.6.1.4.1.2021.4')
	mdata=ger_snmp_next(agent,ip,key,port,moid)

	swap_mem_total=mdata[2][0][1]
	swap_mem_avail=mdata[3][0][1]
	real_mem_total=mdata[4][0][1]
	real_mem_avail=mdata[5][0][1]
	Buffer = mdata[9][0][1]
	Cached = mdata[10][0][1]
	swap_used = swap_mem_total - swap_mem_avail
	real_used = real_mem_total - real_mem_avail
	return {'memory':{'real_used':str(round(float(real_used)/1048576,2)),'real_total':str(round(float(real_mem_total)/1048576,2)),'swap_used':str(round(float(swap_used)/1048576,2)),'swap_total':str(round(float(swap_mem_total)/1048576,2)),'Buffer':str(round(float(Buffer)/1048576,2)),'Cached':str(round(float(Cached)/1048576,2))}}
		

	
def user_login(agent,ip,key,port):
	'''
	#login 登录用户数量
	#return {"user_login":"login"} 
	'''
	login_oid=ObjectName('.1.3.6.1.2.1.25.1.5.0')
	user_login =ger_snmp_get(agent,ip,key,port,login_oid)
	return {'user_login':str(user_login[0][1])}
	#return user_login
	
	
def loadstat(agent,ip,key,port):
	'''
	#loadstat 负载
	#fifteen : 15分钟的负载平均值
	#five : 5分钟的负载平均值
	#one : 1分钟的负载平均值
	#return {"loadstat":{"fifteen":"fifteen","five":"five","one":"one"}}
	'''
	load_one_oid=ObjectName('.1.3.6.1.4.1.2021.10.1.3.1')
	load_five_oid=ObjectName('.1.3.6.1.4.1.2021.10.1.3.2')
	load_fifteen_oid=ObjectName('.1.3.6.1.4.1.2021.10.1.3.3')
	load_one =ger_snmp_get(agent,ip,key,port,load_one_oid)
	load_five =ger_snmp_get(agent,ip,key,port,load_five_oid)
	load_fifteen =ger_snmp_get(agent,ip,key,port,load_fifteen_oid)
	return {'loadstat':{'fifteen':str(load_fifteen[0][1]),'five':str(load_five[0][1]),'one':str(load_one[0][1])}}
	

def process_num(agent,ip,key,port):
	'''
	#process_num : 进程数量
	#return {"process_num":"process_num"}
	'''
	process_oid=ObjectName('.1.3.6.1.2.1.25.1.6.0')
	process_num =ger_snmp_get(agent,ip,key,port,process_oid)
	return {'process_num':str(process_num[0][1])}
	

def constat(agent,ip,key,port):
	'''
	#constat:连接数
	#udp:udp连接数，tcp:tcp连接数 ，all:tcp连接数+udp连接数
	return {"constat":{"udp":"udp","tcp":"tcp","all":"all"}}
	'''
	tcp_oid=ObjectName('1.3.6.1.2.1.6.13.1.3')
	udp_oid=ObjectName('1.3.6.1.2.1.7.5.1.2')
	constat_tcp =ger_snmp_next(agent,ip,key,port,tcp_oid)
	constat_udp =ger_snmp_next(agent,ip,key,port,udp_oid)
	count_tcp=0
	count_udp=0
	for row in constat_tcp:
		count_tcp+=1
	for row in constat_udp:
		count_udp+=1
	return {'constat':{'udp':str(count_udp),'tcp':str(count_tcp),'all':str(int(count_tcp)+int(count_udp))}}
	


def disk_Handle_sau(agent,ip,key,port):
	'''
	获取每个逻辑分区的计算单位
	'''
	sau_oid=ObjectName('.1.3.6.1.2.1.25.2.3.1.4')
	sau_get=ger_snmp_next(agent,ip,key,port,sau_oid)
	sau=change_dict(sau_get)
	return sau
	

def disk_Handle_description(agent,ip,key,port):
	'''
	获取正在使用的逻辑分区
	'''
	description={}
	description_oid=ObjectName('.1.3.6.1.2.1.25.2.3.1.3')
	description_get=ger_snmp_next(agent,ip,key,port,description_oid)
	for key in description_get:
		description_key=key[0][0][-1]
		description_value=str(key[0][1])
		if re.match('/',description_value):
			#print re.match('/',description_value).group()
			#print   description_value	   
			y={description_key:description_value}
			description.update(y)
	return description
	
		   
def disk_stat(agent,ip,key,port):
	total_total={}
	description= disk_Handle_description(agent,ip,key,port)
	sau= disk_Handle_sau(agent,ip,key,port)
	use_oid=ObjectName('.1.3.6.1.2.1.25.2.3.1.6')
	total_oid=ObjectName('.1.3.6.1.2.1.25.2.3.1.5')
	use_get=ger_snmp_next(agent,ip,key,port,use_oid)
	use=change_dict(use_get)
	total_get=ger_snmp_next(agent,ip,key,port,total_oid)
	total=change_dict(total_get)
	for id in description.keys():
		z={str(description[id]):{'total':str(round(float(int(total[id])*int(sau[id]))/1073741824,2)),'use':str(round(float(int(use[id])*int(sau[id]))/1073741824,2))}}		
		total_total.update(z)
	return {'disk':total_total}

	
def disk_io_device(agent,ip,key,port):
	'''
	@获取磁盘I/O 中设备
	'''
	device={}
	sda_oid=ObjectName('.1.3.6.1.4.1.2021.13.15.1.1.2')
	device_get=ger_snmp_next(agent,ip,key,port,sda_oid)
	for key in device_get:
		device_key=key[0][0][-1]
		device_value=key[0][1]
		json_str={device_key:device_value}
		device.update(json_str)
	return device


def disk_io(agent,ip,key,port):
	'''
	@获取每个I/O设备的写入字节量和读取字节量,单位：Kbytes
	'''
	try:
		io_total={}
		io_device=disk_io_device(agent,ip,key,port)
		write_oid=ObjectName('.1.3.6.1.4.1.2021.13.15.1.1.4')
		read_oid=ObjectName('.1.3.6.1.4.1.2021.13.15.1.1.3')
		read_get=ger_snmp_next(agent,ip,key,port,read_oid)
		write_get=ger_snmp_next(agent,ip,key,port,write_oid)
		read=change_dict(read_get)
		write=change_dict(write_get)
		for id in io_device.keys():
			if(int(read[id])!=0):
				io_str={str(io_device[id]):{'read':str(round(float(read[id])/1024,2)),'write':str(round(float(write[id])/1024,2))}}
				io_total.update(io_str)
		return {'io':io_total}
	except Exception as e:
		return {"io": {"sda2": {"read": "0.0", "write": "0.0"}, "sda": {"read": "0.0", "write": "0.0"}}}
	
	
def cpu_usage(agent,ip,key,port):
	'''
	@获取cpu的使用率
	@user用户模式cpu使用率
	@kernel内核模式cpu使用率
	@low-priority低优先级模式cpu使用率
	'''
	cpu_stats = ObjectName('.1.3.6.1.4.1.2021.11')
	
	cpuinfo =  ger_snmp_next(agent,ip,key,port,cpu_stats)
	time.sleep(5)
	cpuinfo_2 = ger_snmp_next(agent,ip,key,port,cpu_stats)

	user = int(cpuinfo_2[11][0][1]) - int(cpuinfo[11][0][1])
	Nice = int(cpuinfo_2[12][0][1]) - int(cpuinfo[12][0][1])
	System = int(cpuinfo_2[13][0][1]) - int(cpuinfo[13][0][1])
	Idle = int(cpuinfo_2[14][0][1]) - int(cpuinfo[14][0][1])
	Wait = int(cpuinfo_2[15][0][1]) -  int(cpuinfo[15][0][1])
	Kernel = int(cpuinfo_2[16][0][1]) - int(cpuinfo[16][0][1])
	Interrupt = int(cpuinfo_2[17][0][1]) - int(cpuinfo[17][0][1])
	SoftIRQ = int(cpuinfo_2[22][0][1]) - int(cpuinfo[22][0][1])

	total = user+Nice+System+Idle+Wait+Kernel+Interrupt+SoftIRQ
	user_usage = float(user)/int(total)
	kernel_usage = float(Kernel)/int(total)
	low_usage = float(Nice)/int(total)
	iowait_usage = float(Wait)/int(total)

	idle_usage = float(Idle)/int(total)
	System_usage = float(System)/int(total)
	Interrupt_usage = float(Interrupt)/int(total)
	SoftIRQ_usage = float(SoftIRQ)/int(total)

	return {'cpu':{'user':user_usage,'kernel':kernel_usage,'nice':low_usage,'wait':iowait_usage,'idle':idle_usage,'system':System_usage,'irq':Interrupt_usage,'softirq':SoftIRQ_usage}}


def cpu_usage_test(agent,ip,key,port):
        '''
        @获取cpu的使用率
        @user用户模式cpu使用率
        @kernel内核模式cpu使用率
        @low-priority低优先级模式cpu使用率
        '''

        user_oid=ObjectName('.1.3.6.1.4.1.2021.11.9.0')
        kernel_oid=ObjectName('.1.3.6.1.4.1.2021.11.10.0')
        low_oid=ObjectName('.1.3.6.1.4.1.2021.11.11.0')
        user_get=ger_snmp_get(agent,ip,key,port,user_oid)
        kernel_get=ger_snmp_get(agent,ip,key,port,kernel_oid)
        low_get=ger_snmp_get(agent,ip,key,port,low_oid)
        #total = user_get[0][1]+kernel_get[0][1]+low_get[0][1]
        user_usage=float(user_get[0][1])
        kernel_usage=float(kernel_get[0][1])
        low_usage=float(low_get[0][1])
        return {'cpu':{'user':user_usage,'kernel':kernel_usage,'low':low_usage}}
	
def system_info(agent,ip,key,port):
	'''
	获取系统的位数，看是64位还是32位
	'''
	system_oid=ObjectName('1.3.6.1.2.1.1.1')
	system_get=ger_snmp_next(agent,ip,key,port,system_oid)
	system_name = system_get[0][0][1]
	#print system_name
	system_bit= str(system_name).split()[11]
	return system_bit
	#print  system_next

	   
def network_eth(agent,ip,key,port):
	'''
	获取正在使用的网卡
	'''
	active_eth={}
	statu_oid=ObjectName('.1.3.6.1.2.1.2.2.1.8')
	statu_get=ger_snmp_next(agent,ip,key,port,statu_oid)
	status_vals=change_dict(statu_get)
	Description_oid=ObjectName('.1.3.6.1.2.1.2.2.1.2')
	Description_get=ger_snmp_next(agent,ip,key,port,Description_oid)
	Description_vals=change_dict(Description_get)
	for key in status_vals:
		if status_vals[key]!='2' and not re.match('lo',Description_vals[key]):
			result_eth={key:Description_vals[key]}
			active_eth.update(result_eth)
	return active_eth

			
def diffrent_value(agent,ip,key,port,in_flow_oid,out_flow_oid):
	'''
	@计算一分钟内网卡的流量,机房带宽，单位：MBps
	'''
	network_stat={}
	in_flow_one=ger_snmp_next(agent,ip,key,port,in_flow_oid)
	out_flow_one=ger_snmp_next(agent,ip,key,port,out_flow_oid)
	time.sleep(16)
	in_flow_two=ger_snmp_next(agent,ip,key,port,in_flow_oid)
	out_flow_two=ger_snmp_next(agent,ip,key,port,out_flow_oid)
	in_flow_one_dict=change_dict(in_flow_one)
	in_flow_two_dict=change_dict(in_flow_two)		
	out_flow_one_dict=change_dict(out_flow_one)
	out_flow_two_dict=change_dict(out_flow_two)
	active_eth=network_eth(agent,ip,key,port)
	for k in active_eth:
		in_flow_value=int(in_flow_two_dict[k])-int(in_flow_one_dict[k])
		out_flow_value=int(out_flow_two_dict[k])-int(out_flow_one_dict[k])
		flow_total={active_eth[k]:{'inbond':str(round(float(in_flow_value)/2/1048576,3)),'outbond':str(round(float(out_flow_value)/2/1048576,3))}}
		network_stat.update(flow_total)
	return network_stat		 

	 
def network_stat(agent,ip,key,port):
	'''
	@网络流量
	'''
	system_bit=system_info(agent,ip,key,port)
	#print system_bit
	if system_bit=='x86_64':
		in_flow_oid=ObjectName('.1.3.6.1.2.1.31.1.1.1.6')
		out_flow_oid=ObjectName('.1.3.6.1.2.1.31.1.1.1.10')
		network_stat=diffrent_value(agent,ip,key,port,in_flow_oid,out_flow_oid)	   
		return {'network':network_stat}		
	else:
		in_flow_oid=ObjectName('.1.3.6.1.2.1.2.2.1.10')
		out_flow_oid=ObjectName('.1.3.6.1.2.1.2.2.1.16')
		network_stat=diffrent_value(agent,ip,key,port,in_flow_oid,out_flow_oid)	   
		return {'network':network_stat}



#def handle_func(agent,ip,key,port):
#	snmp_handle_func = get_snmp_func()
#	info ={}
#	for x in snmp_handle_func:
#		func=x+'(agent,ip,key,port)'
#		y=eval(func)
#		info.update(y)
#	return info

	  
def handle_func(agent,ip,key,port):
	'''
	@snmp 获取服务器数据
	'''
	info ={}
	y=memory_handle(agent,ip,key,port)
	info.update(y)
	y=user_login(agent,ip,key,port)
	info.update(y)
	y=loadstat(agent,ip,key,port)
	info.update(y)
	y=process_num(agent,ip,key,port)
	info.update(y)
	y=constat(agent,ip,key,port)
	info.update(y)
	y=network_stat(agent,ip,key,port)
	info.update(y)
	y=cpu_usage(agent,ip,key,port)
	info.update(y)
	y=disk_io(agent,ip,key,port)
	info.update(y)
	y=disk_stat(agent,ip,key,port)
	info.update(y)
	return info
  
		   
def DicttoJson(agent,ip,key,port):
	dct=handle_func(agent,ip,key,port)
	#print dct
	encodedjson = json.dumps(dct)	   
	return encodedjson
	

def snmp_config_verify(agent,ip,key,port):
	'''
	@snmp 目标服务器配置验证
	'''
	system_oid = ObjectName('1.3.6.1.2.1.1.1')
	try:
		system_get = ger_snmp_next(agent,ip,key,port,system_oid)
		system_name = str(system_get[0][0][1])
	except Exception as e:
		log_error(str(ip)+" snmp config error! snmp_config_verify()"+str(e))
		return False	
	if bool(system_name) == False:
		return False
	else:
		return True


def server_snmp_analyze(agent,ip,key,port):
	'''
	@服务器snmp采集数据入口
	@ping判断是否能够联通
	'''
	start = datetime.now()
	#isTrue = Ping(ip)
	end = datetime.now()
	responsetime = float((end-start).microseconds/1000)
	#if isTrue == False:
	#	reason = "ping连接失败,数据包全部丢失"
	#	return str(0),reason,'',str(1)
	verify_res = snmp_config_verify(agent,ip,key,port)
	if verify_res == False:
		reason = "目标服务器Snmp配置有误,Snmp获取数据失败"
		print reason
		return str(0),reason,'',str(2)
	else:
		try:
			bit,ostype = getOStype(agent, ip, key, port)
			if ostype == 'Linux':
				snmp_json = handle_func(agent,ip,key,port)
			elif ostype == 'Windows':
				snmp_json = Win_handle_func(agent,ip,key,port)
			else:
				log_error("handle_func: os get error!")
				reason = "snmp获取操作系统类型失败"
				print reason
				return str(0),reason,'',str(2)
		except Exception as e:
			log_error("handle_func:"+str(e))
			reason = "snmp获取数据失败,可能原因是:网络环境不稳定，没有收到SNMP返回数据."
			print reason
			return str(0),reason,'',str(2)
		snmp_json['responsetime']=responsetime
		return str(1),'',json.dumps(snmp_json),str(4)

		
################################################## 分割线   ------------------------   分割线 #############################################################

if __name__ == '__main__':
	#pass
	agent='myagent'
	ip='127.0.0.1'
	key='public'
	port=161
	server_snmp_analyze(agent,ip,key,port)
	


