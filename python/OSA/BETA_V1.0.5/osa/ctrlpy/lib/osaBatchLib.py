#!/usr/bin/env python
#encoding=utf-8

'''
	Author:		osa开源团队
	Description:	批量操作部分功能函数
	Create Date:	2012-05-15
'''


import os,sys
from multiprocessing import Queue
from ctrlpy.lib import hostSocket
from ctrlpy.lib import osaResult
from ctrlpy.etc.config import SOCKET,FSOCKET,DIRS
from ctrlpy.lib.osaUtil import save_log	


def revToDict(rev):
	'''
	@把接收来的字符串处理为字典格式
	'''
	return eval(rev)

def batchIpList(rev):
	'''
	@提取进行批量操作的IP地址列表
	'''
	rdict = revToDict(rev)
	return rdict['iparr'].split('|')

def putBatchIp(iplist):
	'''
	@IP地址入队
	'''
	# 准备队列
	q = Queue()

	# IP地址入队
	for ip in iplist:
		q.put(ip)
	return q

def getBatchIp(q):
	'''
	@从队列里面取IP
	'''
	ip = None
	if q.qsize() != 0:
		ip = q.get()	
	return ip
	
def getConfigItem(rev):
	'''
	@提取配置项
	'''
	rdict = revToDict(rev)
	return rdict['config_items']

def getBatchId(rev):
	'''
	@提取此次批量操作的数据库ID
	'''
	rdict = revToDict(rev)
	return rdict['id']
	
def batchSendCmd(rev,ip,type):
	'''
	@组装发送指令
	'''
	
	#重新组装发送指令
	
	cmddict = revToDict(rev)
	
	cmddict['type'] = type
	
	cmddict['clientip'] = ip
	
	
	#判断远程受控端是否可以连接
	try:
		isalive = hostSocket.PortIsAlive(ip,port=SOCKET['REMOTE_PORT'])
	except Exception as e:
		save_log('ERROR',ip+' port is not alive!'+str(e))
			
	if isalive == False:
		result = "{'batchinfo':"+str(cmddict)+",'command':'batchresult','batchresult':{'status':'ERROR','result':'can not connected client ip!'}}"
		
		osaResult.batchresult(result)
		sys.exit()
	
	#通知远程服务器
	fromUnctrlpyData = hostSocket.FproSocket(ip, SOCKET['REMOTE_PORT'], str(cmddict))
	
	return fromUnctrlpyData


