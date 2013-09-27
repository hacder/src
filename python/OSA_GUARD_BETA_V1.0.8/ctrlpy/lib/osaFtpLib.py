#!/usr/bin/env python
#encoding=utf-8
'''
	Author: 	OSA开源团队
	Description:	(监控项目 ftp(port) 报警)
	Date: 		2011-08-31
'''
import re,simplejson
import socket,ftplib
import subprocess,time,signal,sys
from datetime import datetime
from ctrlpy.lib.osaLogLib import *


def ftp_get_port(itemconfig):
	'''
	@获取ftp port
	'''
	try:
		if itemconfig['port'] != '':
			port = itemconfig['port']
		else:
			port = 21
		return port
	except Exception as e:
		log_error("ftp_get_port():"+str(e))


def ftp_get_logins(itemconfig):
	'''
	@获取ftp 登录用户名和密码
	'''
	if itemconfig['defaults'] == '1':#匿名登录
		return '',''
	else:#验证登录
		return itemconfig['ftpuser'],itemconfig['ftppass']


def ftp_connect_check(host,itemconfig):
	'''
	@ftp 连接检测
	'''
	ftp = ftplib.FTP()
	port = ftp_get_port(itemconfig)
	ftpuser ,ftppass = ftp_get_logins(itemconfig)
	try:
		start = datetime.now()
		ftp.connect(str(host),int(port))
		if ftpuser == '':
			ftp.login()
		else:
			ftp.login(ftpuser,ftppass)
		end = datetime.now()
	except Exception as e:
		log_info("ftp连接失败:"+str(e))
		return False,simplejson.dumps({"responsetime":0})
	responsetime = (end - start).microseconds / 1000
	return True,simplejson.dumps({"responsetime":responsetime})
	
	
##########################分割线-----------Ftp 存活验证结束 -------------分割线######################

