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
from osaUtil import save_log
from ctrlpy.etc.config import SOCKET


def socketSend(host,port,cmd,type=None):
	'''
	@host: 主机IP
	@port: 主机端口
	@cmd:  指令	
	'''

	if PortIsAlive(host, port,count=SOCKET['COUNT']) == False:
		return False
	
	data = d = ''
	
	try:
		sock = socket.socket()
		#settimeout后导致出现很多time out异常
		#参考文档：http://www.douban.com/note/174791641/
		#sock.settimeout(SOCKET['PROTIMEOUT'])
		
		sock.connect((host, port))
			
		if type:
			sock.send(encode(cmd + type))
		else:
			sock.send(encode(cmd))

		while True:
			d = sock.recv(SOCKET['BUFSIZE'])
			data = data + d
			time.sleep(0.003)
			if not d:
				sock.close()
				return data
			
				
	except Exception as e:
		
		save_log('ERROR','ip: '+host+' , port: '+str(port)+' , socketSend  error: '+str(e))
		sock.close()
		
		return False
	


def proSocket(host, port, cmd, type=None):
	'''
	@host: 主机IP
	@port: 主机端口
	@cmd:  指令
	'''

	data = ''
	try:
		i = 0
		while True:
			data = socketSend(host,port,cmd,type=None)
			if data or i > 6:
				break
			i = i + 1		
	
		try:
			data = decode(data)
			
		except  Exception as e:
		
			time.sleep(SOCKET['DELAY']+1)
			
			i = 0
                	while True:
                        	data = socketSend(host,port,cmd,type=None)
                        	if data or i > 3:
                                	break
                        	i = i + 1		
	
			try:
				data = decode(data)
			except  Exception as e:
				
				save_log('ERROR','recv decode error:'+str(e)+str(host))
				return False
	except Exception as e:
		
		save_log('ERROR','ip: '+host+' , port: '+str(port)+' , proSocket other error: '+str(e))
	
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
		#sock.settimeout(SOCKET['PROTIMEOUT'])
		sock.connect((host, port))
                
		if type:
			sock.send(encode(cmd + type))
		else:
			sock.send(encode(cmd))
		sock.close()
	except Exception as e:
		save_log('ERROR','FproSocket error:'+str(e))
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

def udpPortIsAlive(host,port,count=SOCKET['COUNT']):
	'''
	@host: 主机IP
	@port: 主机端口
	'''

	for i in xrange(count):

		sock = socket.socket(socket.AF_INET,socket.SOCK_DGRAM)

            
		try:

			add = (host, int(port))

			sock.sendto('PORT_IS_ALIVE',add)

		except Exception as e:

			time.sleep(SOCKET['INTERVAL'])
			continue
		return True
	return False
	
