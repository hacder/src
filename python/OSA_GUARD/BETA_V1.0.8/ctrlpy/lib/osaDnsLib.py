#!/usr/bin/env python
#encoding=utf-8
'''
	Author: 	OSA开源团队
	Description:	(监控项目 DNS 报警)
	Date: 		2011-08-31
'''
import DNS,simplejson
import subprocess,time,signal,sys
from datetime import datetime
from ctrlpy.lib.osaLogLib import *


def dns_get_qtype(itemconfig):
	'''
	@获取dns qtype
	'''
	try:
		qtype = ''
		if 'qtype' in itemconfig and itemconfig['qtype'] != '':
			qtype = itemconfig['qtype']
		return str(qtype)
	except Exception as e:
		log_error("dns_get_qtype():"+str(e))


def dns_get_iplist(itemconfig):
	'''
	@获取dns ip列表
	'''
	iplist = []
	if 'iplist' in itemconfig.keys() and itemconfig['iplist'] != '':
		for ip in itemconfig['iplist'].split(','):
			iplist.append(ip)
	return iplist
	

def dns_get_server(itemconfig):
	'''
	@获取指定的dns服务器
	'''
	try:
		if 'server'in itemconfig.keys() and itemconfig['server'] != '':
			return itemconfig['server']
		else:
			return False
	except Exception as e:
		log_error("dns_get_server():"+str(e))


def dns_ip_check(result,ip):
	'''
	@当dns qtype == 'A'时，指定ip匹配
	'''
	for i in result:
		if i['data'] == ip:
			return True
		else:
			continue
	return False


def dns_iplist_check(result,iplist,answers):
	'''
	@当dns qtype == 'A'时，指定iplist匹配
	'''
	if not len(result):
		reason = "DNS解析结果为空"
		return str(0),reason,answers,str(3)
	if not len(iplist):#说明不需要匹配
		return str(1),'',answers,str(4)
	for ip in iplist:
		if dns_ip_check(result,ip) == False:
			reason ="DNS解析结果与"+str(ip)+"不匹配"
			return str(0),reason,answers,str(3)
		else:
			continue
	return str(1),'',answers,str(4)


def dns_deal_answers(result,responsetime,dnstype):
	'''
	@处理DNS返回结果
	'''
	answers = {}
	answers['responsetime']=responsetime
	if len(result):
		list = ''
		for i in result:
			list = list+str(i['data'])+','
		answers[dnstype]=str(list)
	return simplejson.dumps(answers)	
	

def dns_server_check(host,itemconfig):
	'''
	@dns 检测
	@依赖pyDNS模块
	'''
	server = dns_get_server(itemconfig)
	dnstype = dns_get_qtype(itemconfig)
	iplist = dns_get_iplist(itemconfig)
	if server != False and server !=None:
		DNS.defaults['server'] = server
	DNS.DiscoverNameServers()
	try:
		start = datetime.now()
		request = DNS.Request()
		result = request.req(name = host,qtype=dnstype).answers
		end = datetime.now()
	except Exception as e:
		log_info("DNS request failed:"+str(e))
		reason = "DNS解析请求失败"
		return str(0),reason,'',str(1)
	responsetime = (end - start).microseconds / 1000
	answers = dns_deal_answers(result,responsetime,dnstype)
	if dnstype == 'A':
		return dns_iplist_check(result,iplist,answers)
	else :
		if not len(result):
			reason = "DNS解析结果为空"
			return str(0),reason,answers,str(3)
		else:
			return str(1),'',answers,str(4)
			

##########################分割线-----------DNS 存活验证结束 -------------分割线######################

if __name__ == '__main__':
	'''
	@test
	'''
	pass
	

