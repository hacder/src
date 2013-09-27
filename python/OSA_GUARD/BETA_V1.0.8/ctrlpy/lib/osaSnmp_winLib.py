#!/usr/bin/env python
#encoding=utf-8

'''
	Author:	osa开源团队 larry
	Description:	snmp获取windows状态
	Create Date：	2012/12/13
'''
from pysnmp.proto.rfc1902 import ObjectName
from pysnmp.entity.rfc3413.oneliner import cmdgen
from pysnmp.proto.rfc1902 import ObjectName
from pysnmp.entity.rfc3413.oneliner import cmdgen
from ctrlpy.lib.osaLogLib import *
import re,sys

reload(sys) 
sys.setdefaultencoding('utf8') 

def ger_snmp_get(agent,ip,key,port,oid):
	if port is None:
		port = 161
	port = int(port)
	cg=cmdgen.CommandGenerator()
	errorIndication, errorStatus, errorIndex, varBindTable = cg.getCmd(
		cmdgen.CommunityData(agent, key,1),
		cmdgen.UdpTransportTarget((ip, port),retries=3),
		#cmdgen.UdpTransportTarget((ip, port),timeout=15,retries=3)
		oid
	)
	if varBindTable:
		return varBindTable
	else:
		log_error("ger_snmp_get():"+ str(errorIndication))
		#sys.exit(0)
		return False
		

def ger_snmp_next_onece(agent,ip,key,port,oid):
	if port is None:
		port = 161
	port = int(port)
	cg=cmdgen.CommandGenerator()
	errorIndication, errorStatus, errorIndex, varBindTable = cg.nextCmd(
		cmdgen.CommunityData(agent, key,1),
		cmdgen.UdpTransportTarget((ip, port),retries=3),
		#cmdgen.UdpTransportTarget((ip, port),timeout=15,retries=3)
		oid
	)
	if varBindTable:
		return varBindTable
	else:
		log_error("ger_snmp_next():"+ str(errorIndication))
		#sys.exit(0)	
		return False
		
def ger_snmp_next(agent,ip,key,port,oid):
	snmpinfo = ger_snmp_next_onece(agent,ip,key,port,oid)
	i=0
	while True:		
		if snmpinfo == False and i<3:
			i=i+1			
			snmpinfo = ger_snmp_next_onece(agent,ip,key,port,oid)
		else:
			return snmpinfo
			
def cpu_usage(agent,ip,key,port):
	'''
	windows 总的cpu使用率 
	总共100%，used是所有cpu核数用的百分比数字相加除以cpu核心数量
	'''
	
	oid = ObjectName('1.3.6.1.2.1.25.3.3.1.2')
	cpu_data=ger_snmp_next(agent,ip,key,port,oid)
	
	j=k=0
	for i in cpu_data:
		j=float(i[0][1])+j
		k=k+1
	
	if k>0:
		avg_load=float(j/k)
		if avg_load > 0:
			load=float(avg_load/100)
		else:
			load=0
		return {'cpu':{"win":str(load),'win_cpu_total':str(1)}}
	else:
		return {'cpu':{"win":str(0),'win_cpu_total':str(1)}}

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
    return system_bit,system_type


def get_system_process(agent, ip, key, port):
	'''
	获取系统当前运行进程数量
	'''	
	system_process_oid = ObjectName('.1.3.6.1.2.1.25.1.6.0')
	system_process_num = ger_snmp_get(agent, ip, key, port, system_process_oid)
	return {'process_num':str(system_process_num[0][1])}
	
def get_system_uptime(agent, ip, key, port):
	'''
	获取系统运行时间
	'''
	system_uptime_oid = ObjectName('.1.3.6.1.2.1.1.3.0')
	system_uptime = ger_snmp_get(agent, ip, key, port, system_uptime_oid)
	return {'systme_uptime':str(system_uptime[0][1])}
	
def get_login_user(agent, ip, key, port):
	'''
	获取登录用户数
	'''
	login_user_oid = ObjectName('.1.3.6.1.2.1.25.1.5.0')
	login_user = ger_snmp_get(agent, ip, key, port, login_user_oid)
	return {'user_login':str(login_user[0][1])}	
	
def get_system_memory(agent, ip, key, port):
	'''
	获取系统内存使用状况：单位：GBytes
	'''
	disk_partition_oid = ObjectName('.1.3.6.1.2.1.25.2.3.1.3')
	disk_allocationunits_oid = ObjectName('.1.3.6.1.2.1.25.2.3.1.4')
	disk_storagesize_oid = ObjectName('.1.3.6.1.2.1.25.2.3.1.5')
	disk_storageused_oid = ObjectName('.1.3.6.1.2.1.25.2.3.1.6')
	
	disk_partition = ger_snmp_next(agent, ip, key, port, disk_partition_oid)
	disk_allocationunits = ger_snmp_next(agent, ip, key, port, disk_allocationunits_oid)
	disk_storagesize = ger_snmp_next(agent, ip, key, port, disk_storagesize_oid)
	disk_storageused = ger_snmp_next(agent, ip, key, port, disk_storageused_oid)
	
	virtalmem = {}
	physicalmem = {}
	
	for num in range(len(disk_partition)):
		if disk_partition[num][0][1] == "Virtual Memory":
			virtalmem_total = int(disk_allocationunits[num][0][1]) * int(disk_storagesize[num][0][1]) 
			virtalmem_used = int(disk_allocationunits[num][0][1]) * int(disk_storageused[num][0][1]) 		
			
		elif disk_partition[num][0][1] == "Physical Memory":
			physicalmem_total = int(disk_allocationunits[num][0][1]) * int(disk_storagesize[num][0][1]) 
			physicalmem_used = int(disk_allocationunits[num][0][1]) * int(disk_storageused[num][0][1]) 
			
			
	return {'memory':{'swap_total':str(round(float(virtalmem_total)/1073741824,2)),'real_total':str(round(float(physicalmem_total)/1073741824,2)),'swap_used':str(round(float(virtalmem_used)/1073741824,2)),'real_used':str(round(float(physicalmem_used)/1073741824,2)),'Buffer':'0.0','Cached':'0.0'}}
	
def get_system_disk(agent, ip, key, port):
	'''
	获取系统磁盘状况 单位：GBytes
	'''
	disk_partition_oid = ObjectName('.1.3.6.1.2.1.25.2.3.1.3')
	disk_allocationunits_oid = ObjectName('.1.3.6.1.2.1.25.2.3.1.4')
	disk_storagesize_oid = ObjectName('.1.3.6.1.2.1.25.2.3.1.5')
	disk_storageused_oid = ObjectName('.1.3.6.1.2.1.25.2.3.1.6')
	
	disk_partition = ger_snmp_next(agent, ip, key, port, disk_partition_oid)
	disk_allocationunits = ger_snmp_next(agent, ip, key, port, disk_allocationunits_oid)
	disk_storagesize = ger_snmp_next(agent, ip, key, port, disk_storagesize_oid)
	disk_storageused = ger_snmp_next(agent, ip, key, port, disk_storageused_oid)
	
	partition = {}
	for num in range(len(disk_partition)):
		if disk_partition[num][0][1] != "Virtual Memory" and disk_partition[num][0][1] != "Physical Memory":
			partition_total = int(disk_allocationunits[num][0][1]) * int(disk_storagesize[num][0][1]) 
			partition_used = int(disk_allocationunits[num][0][1]) * int(disk_storageused[num][0][1]) 
			part_name = str(disk_partition[num][0][1])[0:2]
			partition[part_name] = {'use':str(round(float(partition_used)/1073741824,2)),'total':str(round(float(partition_total)/1073741824,2))}
		
	return {'disk':partition}


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
	time.sleep(60)
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
		flow_total={active_eth[k]:{'inbond':str(round(float(in_flow_value)/60/1048576,3)*8),'outbond':str(round(float(out_flow_value)/60/1048576,3)*8)}}
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


	
def Win_handle_func(agent,ip,key,port):
	'''
	@snmp 获取服务器数据
	'''
	info ={}
	y=get_system_memory(agent,ip,key,port)
	info.update(y)
	y=get_login_user(agent,ip,key,port)
	info.update(y)
	y={'loadstat':{'five': '0', 'fifteen': '0', 'one': '0'}}
	info.update(y)
	y=get_system_process(agent,ip,key,port)
	info.update(y)
	y={'constat': {'udp': '0', 'all': '0', 'tcp': '0'}}
	info.update(y)
	y=network_stat(agent,ip,key,port)
	info.update(y)
	y=cpu_usage(agent,ip,key,port)
	info.update(y)
	y={'io': {'not_suport': {'read': '0', 'write': '0'}}}
	info.update(y)
	y=get_system_disk(agent,ip,key,port)
	info.update(y)
	return info

