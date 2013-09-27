#!/usr/bin/env python
#encoding=utf-8
import os,sys,time
#sys.path.append('/usr/local/osa')

from ctrlpy.lib import osaBatchLib

from ctrlpy.lib.osaFileSend import osaSendFile
from ctrlpy.lib import hostSocket
from ctrlpy.lib import osaResult
from ctrlpy.etc.config import SOCKET,FSOCKET,DIRS
from ctrlpy.lib.osaFilelib import GetRemotoPort
from ctrlpy.lib.osaUtil import save_log
'''
	Author:		osa开源团队
	Description: 配置更新模块
	Create Date:	2012-05-16
	
'''	

def index(rev,type,ip,x=''):
	'''
	@批量更新配置文件主函数
	return 空
	'''
	if not rev or not ip:
		return False
	if not type:
		type = 'batch'
	
	try:
		r = batchFileSend(rev,ip,type)
	except Exception as e:
		save_log('ERROR','BATCH_CONFIG_UPDATE:'+str(e))
	#子线程退出
	sys.exit()
		
		
	

	
def batchFileSend(rev,ip,type):
	'''
	@type 区分计划任务还是马上执行的批量操作
	@文件发送函数
	'''
	
	##握手之前先探测对方服务器可用端口
	remotePort = GetRemotoPort(ip,portlist = FSOCKET['portlist'])
	
	#获取配置项
	clist = osaBatchLib.getConfigItem(rev)
	
	#提取出需要发送的文件
	sfile = clist['config_update_sourcefile']
	
	#重组发送字符指令,添加远程端口等选项
	cmddict = osaBatchLib.revToDict(rev)
	
	cmddict['fport'] = remotePort
	
	cmddict['type'] = type
	
	cmddict['clientip'] = ip
	
	#判断远程受控端是否可以连接
	isalive = hostSocket.PortIsAlive(ip,port=SOCKET['REMOTE_PORT'])
	
	if isalive == False:
		result = "{'batchinfo':"+str(cmddict)+",'command':'batchresult','batchresult':{'status':'ERROR','result':'can not connected client ip!'}}"
		osaResult.batchresult(result)
		sys.exit()
	
	#发送指令给unctrlpy端，告诉对方要进行文件接收了
	fromUnctrlpyData = hostSocket.FproSocket(ip, SOCKET['REMOTE_PORT'], str(cmddict))
	
	#初始化返回结果
	result = None	
		
	#检查远程端口是否己经成功开启
	if fromUnctrlpyData:
		#如果端口己开启,开始传送文件,网络之间有延迟，对方开放端口也需要时间,可以尝试sleep一定间隔。
		try:		
			#time.sleep(SOCKET['DELAY'])
			result = osaSendFile(filename=sfile,remoteip=ip,port=int(remotePort))
		except Exception as e:
			save_log('DEBUG','BATCH file send error 1:'+str(e))
			time.sleep(SOCKET['DELAY'])
			try:
				result = osaSendFile(filename=sfile,remoteip=ip,port=int(remotePort))
			except Exception as e:
				save_log('ERROR','BATCH file send error,code:x0012,'+str(e)+',ip: '+ip)		
				result = 'x0012'
	else:
		#防止信息未及时收到重试一次
		try:	
			time.sleep(SOCKET['DELAY'])
			result = osaSendFile(filename=sfile,remoteip=ip,port=int(remotePort))
		except Exception as e:
			save_log('ERROR','BATCH file send error,code:x0011,'+str(e)+',ip: '+ip)		
			result = 'x0011'		
		
	return result
		

