#!/usr/bin/env python
# encoding=utf-8

'''
	Author:		osa开源团队
	Description:	略
	Create Date:	2011-07-20
'''
import socket
import sys
import time
import os

from unctrlpy.lib.osaUtil import save_log
from unctrlpy.lib.encode import decode, encode
from unctrlpy.lib.osaFileRecv import get_fsocket_port , file_recv_main
from unctrlpy.lib.osaFileSend import osaSendFile
from unctrlpy.lib.hostSocket import  FproSocket
from unctrlpy.etc.config import SOCKET,FSOCKET,CFILENAME


def isMySelfCmd(fromPhpCmd):
	'''
	@cmd 转发给unctrlpy端的指令
		判断该指令是否需要加工处理
		return 需要加工的函数 or False
	'''
	try:
		mylist = ['getconfigfile','saveconfigfile']
		num = len(fromPhpCmd.split('!'))
		cmstr  = (((fromPhpCmd.split('!')[num-1]).split(':')[0]).replace('"','')).replace('{','')
		for i in mylist:
			if str(cmstr) == str(i):
				
				return str(i)
	except Exception as e:
		save_log('ERROR',str(e))
		return False
	return False


def GetConfigFile(ip,cname,port):
	'''
	@ip agent端的ip地址
	@cname 配置文件名
	@port 远程机器文件传端口
	return 给PHP的文件名和文件大小 or False
	'''

	cfile = CFILENAME[cname]
	
	try:
		S = osaSendFile(filename=cfile,remoteip=ip,port=int(port))
	except Exception as e:
		save_log('ERROR',e)
		time.sleep(1)
		S = osaSendFile(filename=cfile,remoteip=ip,port=int(port))
	
	if S:
		toAgentCmd = 'SYSTEM_RUN_COMMAND!{"getconfigfile":"sentfileok"}'
	return toAgentCmd


def OsaChooseDef(defname='',ip='',fromPhpCmd=''):
	'''
	@defname 函数名称
	@ unctrlpy IP地址
	'''         
	
	num = len(fromPhpCmd.split('!'))
	
	if defname == '':
		return None
	
	if defname == 'getconfigfile':
		cname = ((((fromPhpCmd.split('!')[num-1]).split(':')[1]).replace('"','')).replace('}','').split('|'))[0]
		port = ((((fromPhpCmd.split('!')[num-1]).split(':')[1]).replace('"','')).replace('}','').split('|'))[1]
		return defname,GetConfigFile(ip,cname,port)
	
	if defname == 'saveconfigfile':
		port = ((((fromPhpCmd.split('!')[num-1]).split(':')[1]).replace('"','')).replace('}','').split('|'))[1]
		cname = ((((fromPhpCmd.split('!')[num-1]).split(':')[1]).replace('"','')).replace('}','').split('|'))[0]
		return defname,SaveConfigFile(ip,cname,port)


def SaveConfigFile(ip,cname,port):
	'''
	@ip 远程IP地址
	@cname 配置文件名称
	'''
	
	cfile = CFILENAME[cname]
	fsize = None
	
	try:
		fsize = file_recv_main(host='0.0.0.0',port=int(port),filename = cfile)
	except Exception as e:
		save_log('ERROR',str(e))
		return False

	if fsize:
		os.system('/usr/bin/dos2unix -k '+ cfile)
		return 'SYSTEM_RUN_COMMAND!{"saveconfigfile":"saveok"}'
	
	return None
