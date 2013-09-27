#!/usr/bin/env python
# encoding=utf-8

'''
	Author:		osa开源团队
	Description:	socket数据处理模块
	Create Date:	2011-07-20
'''

import socket
import sys
import time
import random
from encode import encode, decode
from osaUtil import save_log
from unctrlpy.etc.config import SOCKET


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
		
		if type == 'result' and host == '127.0.0.1':	
			sock.send(cmd)
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
		#sock.close()
		return False
	
	return data


def oldproSocket(host, port, cmd, type=None):
	'''
	@host: 主机IP
	@port: 主机端口
	@cmd:  指令
	'''

	# 指令执行后返回的结果
	data = ''

	# 开始通信
	try:
		sock = socket.socket()
		sock.settimeout(SOCKET['PROTIMEOUT'])
		sock.connect((host, port))
			
		if type:
			sock.send(encode(cmd + type))
		else:
			sock.send(encode(cmd))
		
		data = decode(sock.recv(SOCKET['BUFSIZE']))
		sock.close()
	
	except socket.timeout:
		sock.send(encode('Time out!'))
		save_log('WARNING','host:'+host+' Time out!')
		sock.close()
		return data
		sys.exit()
	
	except socket.error, args:
		(error_no, msg) = args
		error_log = 'Connect server faild:%s, error_no=%d ,error_host=%s' % (msg, error_no,host)
		save_log('ERROR',error_log)
		sock.close()
		return data
		sys.exit()
	
	return data

def FproSocket(host, port, cmd, type=None):
	'''
	@host: 主机IP
	@port: 主机端口
	@cmd:  指令
	'''

	# 开始通信
	try:
		sock = socket.socket()
		sock.settimeout(SOCKET['PROTIMEOUT'])
		sock.connect((host, port))
		if type:
			sock.send(encode(cmd + type))
		else:
			if host == '127.0.0.1':
				sock.send(cmd)
			else:
				sock.send(encode(cmd))
		
		sock.close()

	except Exception as e:
		save_log('ERROR',str(e))
		return False
	
	return 1

def RproSocket(host, port, cmd, type=None):
	'''
	@host: 主机IP
	@port: 主机端口
	@cmd:  指令
	'''
	cmd = str(cmd)
	# 开始通信
	try:
		i = 0
		while True:
			time.sleep(round(float(random.randrange(0, 10000, 1))/10000,4))
			data = socketSend(host,port,cmd,type='result')

			if data == False:
				continue

			if decode(data) == 'result_send_ok' or i > 10:
				break
			i = i + 1		
			
	except Exception as e:
		save_log('ERROR','RproSocket error:'+ str(e))
		return False
	
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
