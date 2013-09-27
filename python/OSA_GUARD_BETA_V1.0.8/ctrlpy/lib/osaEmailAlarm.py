#!/usr/bin/env python
#encoding=utf-8

'''
	Author:		osa开源团队
	Description:	alram email(按照监控类型重构email content)
	Create Date:	2011-11-01
'''

'''
	报警信息分为四级。故障信息（1），提醒信息（3），系统信息（2），恢复消息（4）
	故障信息，ping 失败，网页访问失败，数据库连接失败
	提醒信息，各种指标报警信息
	系统信息，服务器snmp获取信息失败
'''

import os ,sys ,time
from ctrlpy.lib.osaLogLib import *

def website_alarm_content(itemname,itemobject,reason,level):
	'''
	@website 恢复正常
	'''
	monitorTime = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime())
	if int(level) == 1:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 页面访问失败。\n "
		bottomTemp = "故障原因:"+str(reason)
		subject = "[故障信息]"+str(topTemp)
	elif int(level) == 3:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 指标异常。\n "
		bottomTemp = "故障原因:"+str(reason)
		subject = "[提醒信息]"+str(topTemp)
	elif int(level) == 4 and reason != '' and reason != '项目恢复正常':
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 恢复正常。\n "
		bottomTemp = "上一次故障原因:"+str(reason)
		subject = "[恢复信息]"+str(topTemp)
	elif reason == '' or reason == '项目恢复正常':
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 不稳定。\n "
		bottomTemp = "上一次故障原因:不稳定。"
		subject = "[恢复信息]"+str(topTemp)

	middleTemp = "监控类型:网页存活(http/https) \n\
			所在域/服务器:"+str(itemobject)+" \n\
			检查时间:"+str(monitorTime)+" \n "
	template = topTemp + middleTemp + bottomTemp
	return template,subject


def ping_alarm_content(itemname,itemobject,level):
	'''
	@ping 报警信息
	'''
	monitorTime = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime())
	if int(level) == 1:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 连接失败。\n "
		bottomTemp = "故障原因:数据包全部丢弃 "
		subject = "[故障信息]"+str(topTemp)
	elif int(level) == 4:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 恢复正常。\n "
		bottomTemp = "上一次故障原因:数据包全部丢弃  "
		subject = "[恢复信息]"+str(topTemp)
		
	middleTemp = "监控类型:ping \n\
			所在域/服务器:"+str(itemobject)+" \n\
			检查时间:"+str(monitorTime)+" \n "
	template = topTemp + middleTemp + bottomTemp
	return template,subject

	
	
def tcp_alarm_content(itemname,itemobject,level):
	'''
	@故障信息，页面访问失败
	'''
	monitorTime = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime())
	if int(level) == 1:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 连接失败。\n "
		bottomTemp = "故障原因:TCP连接超时 "
		subject = "[故障信息]"+str(topTemp)
	elif int(level) == 4:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 恢复正常。\n "
		bottomTemp = "上一次故障原因:TCP连接超时 "
		subject = "[恢复信息]"+str(topTemp)
		
	middleTemp = "监控类型:TCP \n\
			所在域/服务器:"+str(itemobject)+" \n\
			检查时间:"+str(monitorTime)+" \n "
	template = topTemp + middleTemp + bottomTemp
	return template,subject
	

def udp_alarm_content(itemname,itemobject,level):
	'''
	@udp 报警信息，int(level) 取值为1或4
	'''
	monitorTime = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime())
	if int(level) == 1:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 连接失败。\n "
		bottomTemp = "故障原因:udp连接超时 "
		subject = "[故障信息]"+str(topTemp)
	elif int(level) == 4:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 恢复正常。\n "
		bottomTemp = "上一次故障原因:udp连接超时 "
		subject = "[恢复信息]"+str(topTemp)
	middleTemp = "监控类型:UDP \n\
			所在域/服务器:"+str(itemobject)+" \n\
			检查时间:"+str(monitorTime)+" \n "
	template = topTemp + middleTemp + bottomTemp
	return template,subject
	

def ftp_alarm_content(itemname,itemobject,level):
	'''
	@ftp 报警信息，int(level) 取值为1或4
	'''
	monitorTime = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime())
	if int(level) == 1:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 连接失败。\n "
		bottomTemp = "故障原因:ftp连接超时 "
		subject = "[故障信息]"+str(topTemp)
	elif int(level) == 4:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 恢复正常。\n "
		bottomTemp = "上一次故障原因:ftp连接超时 "
		subject = "[恢复信息]"+str(topTemp)
		
	middleTemp = "监控类型:FTP \n\
			所在域/服务器:"+str(itemobject)+" \n\
			检查时间:"+str(monitorTime)+" \n "
	template = topTemp + middleTemp + bottomTemp
	return template,subject
		
		
def dns_alarm_content(itemname,itemobject,reason,level):
	'''
	@dns 报警信息，int(level) 取值为1或4
	'''
	monitorTime = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime())
	if int(level) == 1:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 连接失败。\n "
		bottomTemp = "故障原因:"+str(reason)+" "
		subject = "[故障信息]"+str(topTemp)
	elif int(level) == 3:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 解析结果异常。\n "
		bottomTemp = "故障原因:"+str(reason)+" "
		subject = "[提醒信息]"+str(topTemp)
	elif int(level) == 4 and reason != '' and reason != '项目恢复正常':
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 恢复正常。\n "
		bottomTemp = "上一次故障原因:"+str(reason)+" "
		subject = "[恢复信息]"+str(topTemp)
	elif reason == '' or reason == '项目恢复正常' :
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 不稳定。\n "
		bottomTemp = "上一次故障原因:不稳定。"
		subject = "[恢复信息]"+str(topTemp)
		
	middleTemp = "监控类型:DNS \n\
			所在域/服务器:"+str(itemobject)+" \n\
			检查时间:"+str(monitorTime)+" \n "
	template = topTemp + middleTemp + bottomTemp
	return template,subject
		
		
def server_alarm_content(devname,ipstr,reason,level):
	'''
	@server 报警信息
	'''
	monitorTime = time.strftime('%Y-%m-%d %H:%M:%S',time.localtime())
	if int(level) == 1:
		topTemp = "OSA监控到您的服务器:"+str(devname)+" 连接失败。\n "
		bottomTemp = "故障原因:数据包全部丢失 "
		subject = "[故障信息]"+str(topTemp)
	elif int(level) == 2:
		topTemp = "OSA监控到您的服务器:"+str(devname)+" 数据获取异常。\n "
		bottomTemp = "故障原因:SNMP获取数据失败 "
		subject = "[系统信息]"+str(topTemp)
	elif int(level) == 4 and reason != '' and reason != '项目恢复正常':
		topTemp = "OSA监控到您的服务器:"+str(devname)+" 恢复正常。\n "
		bottomTemp = "上一次故障原因:"+str(reason)+" "
		subject = "[恢复信息]"+str(topTemp)
	elif reason == '' or reason == '项目恢复正常' :
		topTemp = "OSA监控到您的监控项目:"+str(devname)+" 不稳定。\n "
		bottomTemp = "上一次故障原因:不稳定。"
		subject = "[恢复信息]"+str(topTemp)
	middleTemp = "监控类型:服务器(ip) \n\
			服务器:"+str(ipstr)+" \n\
			检查时间:"+str(monitorTime)+" \n "
	template = topTemp + middleTemp + bottomTemp
	return template,subject


def apache_alarm_content(itemname,itemobject,reason,level):
	'''
	@apache 报警信息
	'''
	monitorTime = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime())
	if int(level) == 1:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 状态页访问失败。\n "
		bottomTemp = "故障原因:"+str(reason)+" "
		subject = "[故障信息]"+str(topTemp)
	elif int(level) == 3:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 指标异常。\n "
		bottomTemp = "故障原因:"+str(reason)+" "
		subject = "[提醒信息]"+str(topTemp)
	elif int(level) == 4 and reason != '' and reason != '项目恢复正常':
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 恢复正常。\n "
		bottomTemp = "上一次故障原因:"+str(reason)+" "
		subject = "[恢复信息]"+str(topTemp)
	elif reason == '' or reason == '项目恢复正常' :
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 不稳定。\n "
		bottomTemp = "上一次故障原因:不稳定。"
		subject = "[恢复信息]"+str(topTemp)
		
	middleTemp = "监控类型:Apache \n\
			所在域/服务器:"+str(itemobject)+" \n\
			检查时间:"+str(monitorTime)+" \n "
	template = topTemp + middleTemp + bottomTemp
	return template,subject


def nginx_alarm_content(itemname,itemobject,reason,level):
	'''
	@nginx 报警信息
	'''
	monitorTime = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime())
	if int(level) == 1:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 状态页访问失败。\n "
		bottomTemp = "故障原因:"+str(reason)+" "
		subject = "[故障信息]"+str(topTemp)
	elif int(level) == 3:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 指标异常。\n "
		bottomTemp = "故障原因:"+str(reason)+" "
		subject = "[提醒信息]"+str(topTemp)
	elif int(level) == 4 and reason != '' and reason != '项目恢复正常':
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 恢复正常。\n "
		bottomTemp = "上一次故障原因:"+str(reason)+" "
		subject = "[恢复信息]"+str(topTemp)
	elif reason == '' or reason == '项目恢复正常' :
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 不稳定。\n "
		bottomTemp = "上一次故障原因:不稳定。"
		subject = "[恢复信息]"+str(topTemp)
		
	middleTemp = "监控类型:Nginx \n\
			所在域/服务器:"+str(itemobject)+" \n\
			检查时间:"+str(monitorTime)+" \n "
	template = topTemp + middleTemp + bottomTemp
	return template,subject


def lighttpd_alarm_content(itemname,itemobject,reason,level):
	'''
	@lighttpd 报警信息
	'''
	monitorTime = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime())
	if int(level) == 1:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 状态页访问失败。\n "
		bottomTemp = "故障原因:"+str(reason)+" "
		subject = "[故障信息]"+str(topTemp)
	elif int(level) == 3:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 指标异常。\n "
		bottomTemp = "故障原因:"+str(reason)+" "
		subject = "[提醒信息]"+str(topTemp)
	elif int(level) == 4 and reason != '' and reason != '项目恢复正常':
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 恢复正常。\n "
		bottomTemp = "上一次故障原因:"+str(reason)+" "
		subject = "[恢复信息]"+str(topTemp)
	elif reason == '' or reason == '项目恢复正常' :
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 不稳定。\n "
		bottomTemp = "上一次故障原因:不稳定。"
		subject = "[恢复信息]"+str(topTemp)
	middleTemp = "监控类型:Lighttpd \n\
			所在域/服务器:"+str(itemobject)+" \n\
			检查时间:"+str(monitorTime)+" \n "
	template = topTemp + middleTemp + bottomTemp
	return template,subject


def mongodb_alarm_content(itemname,itemobject,reason,level):
	'''
	@mongodb 报警信息
	'''
	monitorTime = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime())
	if int(level) == 1:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 状态页访问失败。\n "
		bottomTemp = "故障原因:"+str(reason)+" "
		subject = "[故障信息]"+str(topTemp)
	elif int(level) == 3:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 指标异常。\n "
		bottomTemp = "故障原因:"+str(reason)+" "
		subject = "[提醒信息]"+str(topTemp)
	elif int(level) == 4 and reason != '' and reason != '项目恢复正常':
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 恢复正常。\n "
		bottomTemp = "上一次故障原因:"+str(reason)+" "
		subject = "[恢复信息]"+str(topTemp)
	elif reason == '' or reason == '项目恢复正常' :
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 不稳定。\n "
		bottomTemp = "上一次故障原因:不稳定。"
		subject = "[恢复信息]"+str(topTemp)
	middleTemp = "监控类型:Mongodb \n\
			所在域/服务器:"+str(itemobject)+" \n\
			检查时间:"+str(monitorTime)+" \n "
	template = topTemp + middleTemp + bottomTemp
	return template,subject


def redis_alarm_content(itemname,itemobject,reason,level):
	'''
	@redis 报警信息
	'''
	monitorTime = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime())
	if int(level) == 1:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 状态页访问失败。\n "
		bottomTemp = "故障原因:"+str(reason)+" "
		subject = "[故障信息]"+str(topTemp)
	elif int(level) == 3:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 指标异常。\n "
		bottomTemp = "故障原因:"+str(reason)+" "
		subject = "[提醒信息]"+str(topTemp)
	elif int(level) == 4 and reason != '' and reason != '项目恢复正常':
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 恢复正常。\n "
		bottomTemp = "上一次故障原因:"+str(reason)+" "
		subject = "[恢复信息]"+str(topTemp)
	elif reason == '' or reason == '项目恢复正常' :
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 不稳定。\n "
		bottomTemp = "上一次故障原因:不稳定。"
		subject = "[恢复信息]"+str(topTemp)

	middleTemp = "监控类型:Redis \n\
			所在域/服务器:"+str(itemobject)+" \n\
			检查时间:"+str(monitorTime)+" \n "
	template = topTemp + middleTemp + bottomTemp
	return template,subject


def memcache_alarm_content(itemname,itemobject,reason,level):
	'''
	@memcache 报警信息
	'''
	monitorTime = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime())
	if int(level) == 1:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 状态页访问失败。\n "
		bottomTemp = "故障原因:"+str(reason)+" "
		subject = "[故障信息]"+str(topTemp)
	elif int(level) == 3:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 指标异常。\n "
		bottomTemp = "故障原因:"+str(reason)+" "
		subject = "[提醒信息]"+str(topTemp)
	elif int(level) == 4 and reason != '' and reason != '项目恢复正常':
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 恢复正常。\n "
		bottomTemp = "上一次故障原因:"+str(reason)+" "
		subject = "[恢复信息]"+str(topTemp)
	elif reason == '' or reason == '项目恢复正常' :
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 不稳定。\n "
		bottomTemp = "上一次故障原因:不稳定。"
		subject = "[恢复信息]"+str(topTemp)

	middleTemp = "监控类型:Memcache \n\
			所在域/服务器:"+str(itemobject)+" \n\
			检查时间:"+str(monitorTime)+" \n "
	template = topTemp + middleTemp + bottomTemp
	return template,subject


def mysql_alarm_content(itemname,itemobject,reason,level):
	'''
	@mysql 报警信息
	'''
	monitorTime = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime())
	if int(level) == 1:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 数据库连接失败。\n "
		bottomTemp = "故障原因:"+str(reason)+" "
		subject = "[故障信息]"+str(topTemp)
	elif int(level) == 3:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 指标异常。\n "
		bottomTemp = "故障原因:"+str(reason)+" "
		subject = "[提醒信息]"+str(topTemp)
	elif int(level) == 4 and reason != '' and reason != '项目恢复正常':
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 恢复正常。\n "
		bottomTemp = "上一次故障原因:"+str(reason)+" "
		subject = "[恢复信息]"+str(topTemp)
	elif reason == '' or reason == '项目恢复正常' :
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 不稳定。\n "
		bottomTemp = "上一次故障原因:不稳定。"
		subject = "[恢复信息]"+str(topTemp)
	middleTemp = "监控类型:Mysql \n\
			所在域/服务器:"+str(itemobject)+" \n\
			检查时间:"+str(monitorTime)+" \n "
	template = topTemp + middleTemp + bottomTemp
	return template,subject


def custom_alarm_content(itemname,itemobject,reason,level):
	'''
	@custom 报警信息
	'''
	monitorTime = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime())
	if int(level) == 1:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 连接失败。\n "
		bottomTemp = "故障原因:"+str(reason)+" "
		subject = "[故障信息]"+str(topTemp)
	elif int(level) == 2:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" Snmp获取数据失败。\n"
		bottomTemp = "故障原因:"+str(reason)+" "
		subject = "[系统信息]"+str(topTemp)
	elif int(level) == 3:
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 指标异常。\n "
		bottomTemp = "故障原因:"+str(reason)+" "
		subject = "[提醒信息]"+str(topTemp)
	elif int(level) == 4 and reason != '' and reason != '项目恢复正常':
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 恢复正常。\n "
		bottomTemp = "上一次故障原因:"+str(reason)+" "
		subject = "[恢复信息]"+str(topTemp)
	elif reason == '' or reason == '项目恢复正常' :
		topTemp = "OSA监控到您的监控项目:"+str(itemname)+" 不稳定。\n "
		bottomTemp = "上一次故障原因:不稳定。"
		subject = "[恢复信息]"+str(topTemp)
	middleTemp = "监控类型:服务器指标 \n\
			所在域/服务器:"+str(itemobject)+" \n\
			检查时间:"+str(monitorTime)+" \n "
	template = topTemp + middleTemp + bottomTemp
	return template,subject


