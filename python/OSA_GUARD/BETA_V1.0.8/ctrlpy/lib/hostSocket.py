#!/usr/bin/env python
# encoding=utf-8

'''
	Author:		osa开源团队
	Description:	Server端Socket数据接收模块
	Create Date:	2011-07-20
'''

import socket
import sys
import time

from encode import encode, decode
from ctrlpy.lib.osaLogLib import *
from ctrlpy.etc.config import SOCKET ,COMMANDS


def proSocket(host, port, cmd, type=None):
	'''
	@host: 主机IP
	@port: 主机端口
	@cmd:  指令
	'''

	data = ''
	d = ''
	try:
		sock = socket.socket()
		sock.settimeout(SOCKET['PROTIMEOUT'])
		sock.connect((host, port))
		print encode(cmd)	
		if type:
			sock.send(encode(cmd + type))
		else:
			sock.send(encode(cmd))

		while True:
			d = sock.recv(SOCKET['BUFSIZE'])
			data = data + d
			time.sleep(SOCKET['DELAY'])
			if not d:
				break
		try:
			data = decode(data)
		except  Exception as e:
			
			log_error('decode error:'+str(e) + ' Try increasing the delay.')
			sock.send(encode(cmd))
			
			data=''
			d=''
			
			while True:
				d = sock.recv(SOCKET['BUFSIZE'])
				data = data + d
				time.sleep(SOCKET['DELAY']+1)
				if not d:
					break
			try:
				data = decode(data)
			except  Exception as e:
				
				log_error('recv decode error:'+str(e))
				return False
	except Exception as e:
		
		log_error('ip: '+host+' , port: '+str(port)+' , proSocket other error: '+str(e))
		sock.close()
		return False
	
	return data


def FproSocket(host, port, cmd, type=None):
	'''
	@host: 主机IP
	@port: 主机端口
	@cmd:  指令
	'''

	 

	try:
		sock = socket.socket()
		sock.settimeout(SOCKET['PROTIMEOUT'])
		sock.connect((host, port))
                
		if type:
			sock.send(encode(cmd + type))
		else:
			sock.send(encode(cmd))
		sock.close()
	except Exception as e:
		log_error('FproSocket error:'+str(e))
		return 0
	return 1


def PortIsAlive(host, port,count=SOCKET['COUNT']):
	'''
	@host: 主机IP
	@port: 主机端口
	'''
	
	for i in xrange(count):
	
		sock = socket.socket()
	
		sock.settimeout(SOCKET['FPROTIMEOUT'])
		try:		
		
			sock.connect((host, int(port)))
		
			sock.send('PORT_IS_ALIVE')
		
		except Exception as e:
			time.sleep(SOCKET['INTERVAL'])
			continue
		return True
	return False
	
def FproSocket_modKey(host ,port ,cmd ,type=None):
	try:
		sock = socket.socket()
		#sock.settimeout(SOCKET['PROTIMEOUT'])
		sock.connect((host, port))
                
		if type:
			sock.send(encode(cmd + type ,COMMANDS['_MOD_KEY']))
		else:
			sock.send(encode(cmd,COMMANDS['_MOD_KEY']))
		sock.close()
	except Exception as e:
		log_error('FproSocket error:'+str(e))
		return 0
	return 1
