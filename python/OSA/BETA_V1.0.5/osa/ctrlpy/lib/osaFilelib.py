#!/usr/bin/env python
#encoding=utf-8

'''
	Author:		osa开源团队
	Description:	略
	Create Date:	2011-07-20
'''

import socket
import sys
import re
import time
import os

from ctrlpy.lib.encode import encode, decode
from ctrlpy.lib.osaFileRecv import get_fsocket_port , file_recv_main
from ctrlpy.lib.osaFileSend import osaSendFile
from ctrlpy.lib import hostSocket
from ctrlpy.lib.osaUtil import save_log
from ctrlpy.etc.config import SOCKET,FSOCKET,DIRS


def isMySelfCmd(fromPhpCmd):
	'''
	@cmd 转发给unctrlpy端的指令
	判断该指令是否需要加工处理
	return 需要加工的函数 or False
	'''

	mylist = ['getconfigfile','saveconfigfile']
	num = len(fromPhpCmd.split('!'))
	cmstr  = (((fromPhpCmd.split('!')[num-1]).split(':')[0]).replace('"','')).replace('{','')
	
	for i in mylist:
		if str(cmstr) == str(i):
			return str(i)
		
	return False


def GetConfigFile(ip,cname):
	'''
	@ip unctrlpy端的ip地址
	@cname 配置文件名
	return 给PHP的文件名和文件大小 or False
	'''

	port = get_fsocket_port()
	toUnctrlpyCmd = 'SYSTEM_RUN_COMMAND!{"getconfigfile":"'+cname+'|'+str(port)+'"}'
	fromUnctrlpyData = hostSocket.FproSocket(ip, SOCKET['REMOTE_PORT'], toUnctrlpyCmd)
	
	if not os.path.exists(DIRS['CTEMP']):
		os.system('mkdir -p '+ DIRS['CTEMP'])
		os.system('chmod 777 '+ DIRS['CTEMP'])
	
	lstr = (DIRS['CTEMP'])[-1:]
	fname = DIRS['CTEMP'] + str(cname)  +  '.tmp.' + time.strftime("%Y-%d-%m_%H%M%S", time.localtime())
	
	if lstr != '/':
		fname = DIRS['CTEMP'] + str(cname)  +  '.tmp.' + time.strftime("%Y-%d-%m_%H%M%S", time.localtime())
        
	fileseze = None
	
	try:
		fileseze = file_recv_main(host='0.0.0.0',port=int(port),filename = fname)
	except Exception as e:
		save_log('ERROR',e)
	
	if fileseze:
		r = "RETURN_SYSTEM_RUN_COMMAND!['"+fname+'|'+str(fileseze)+"']"
		os.system('chmod 777 '+fname)
		return r
	
	return "File get faid!"


def OsaChooseDef(defname='',ip='',fromPhpCmd=''):
	'''
	@defname 函数名称
	@ip unctrlpy IP地址
	'''         
	
	if defname == '':
		return None

	if defname == 'getconfigfile':
		num = len(fromPhpCmd.split('!'))
		cname = (((fromPhpCmd.split('!')[num-1]).split(':')[1]).replace('"','')).replace('}','')		
		return GetConfigFile(ip,cname)

	if defname == 'saveconfigfile':
		num = len(fromPhpCmd.split('!'))
		ln = ((((fromPhpCmd.split('!')[num-1]).split(':'))[1]).split('/'))[-1:]
		cname = str(ln[0]).split('.')[0]
		filename = (((fromPhpCmd.split('!')[num-1]).split(':')[1]).replace('"','')).replace('}','')
		return SaveConfigFile(ip,cname,filename)


def GetRemotoPort(ip='',portlist = FSOCKET['portlist']):
	'''
	@检查远程可用端口
	'''

	portstart  =  int(portlist.split('-')[0])
	portend    =  int(portlist.split('-')[1])
	
	while ( portstart < portend ):
		sk = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
                
		try:
			sk.connect((ip,portstart))
		except Exception as e:
			sk.close()
			return int(portstart)
		else:
			portstart = portstart + 1
			continue
			sk.close()


def SaveConfigFile(ip,cname,filename):
	'''
	@ip 远程IP地址
	@cname 配置文件名称
	'''
	
	rport = GetRemotoPort(ip = ip,portlist = FSOCKET['portlist'])

	toUnctrlpyCmd = 'SYSTEM_RUN_COMMAND!{"saveconfigfile":"'+cname+'|'+str(rport)+'"}'
	fromUnctrlpyData = hostSocket.FproSocket(ip, SOCKET['REMOTE_PORT'], toUnctrlpyCmd)
	fname = filename
	result = None
	
	if hostSocket.PortIsAlive(ip,rport):
		
		try:
			result = osaSendFile(filename=fname,remoteip=ip,port=int(rport))
		except Exception as e:
			save_log('ERROR',e)
	else:
		time.sleep(0.1)
		result = osaSendFile(filename=fname,remoteip=ip,port=int(rport))
	
	if result == 1:
		r = "RETURN_SYSTEM_RUN_COMMAND!['"+cname+' save ok'+"']"
		return r
	else:
		time.sleep(0.1)
		result = osaSendFile(filename=fname,remoteip=ip,port=int(rport))
		if result == 1:
			r = "RETURN_SYSTEM_RUN_COMMAND!['"+cname+' save ok'+"']"
			return r
		return None
