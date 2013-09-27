#!/usr/bin/env python
#encoding=utf-8
'''
	Author:		osa开源团队
	Description:	批量操作任务
	Create Date:	2012-05-15
'''
import time
import os,sys
import threading
from ctrlpy.lib.osaUtil import save_log
from ctrlpy.lib.osaBatchLib import batchIpList,putBatchIp,getBatchIp,getBatchId
from ctrlpy.lib.cmdtosql import update

#导入批量操作模块
from ctrlpy.etc.config import BATCHLIST,BATCH,DIRS

def isBatchCmd(fromPhpCmd):
	'''
	@fromPhpCmd 来自php端的批量操作指令
	@例如：BATCH_DOCUMENT_DISTRIBUTION
	'''
	batchlist = BATCHLIST.keys()
	
	if fromPhpCmd in batchlist:
		return True
	return False


def chooseBatchDef(fromPhpCmd,rev,type='batch'):
	'''	
	Description: 接收来自PHP端的批量操作指令，执行指定函数
				 前缀：osa_ 加上 PHP端指令名称作函数名称.
				 批量操作的参数统一为：接收到的指令详情
	@fromPhpCmd 来自php端的批量操作指令
	@rev 接收到的指令内容
	return 返回执行结果
	'''	
	try:		
			
		batchlist = BATCHLIST.keys()
		for b in batchlist:	
			path = DIRS['PY_OSA_LIB']+'BatchDo/osa_'+ b +'.py'
				
			if os.path.exists(path) and b == fromPhpCmd:	
				try:
					f = __import__('ctrlpy.lib.BatchDo.osa_'+b, globals(), locals(), ['index'], -1)
				except Exception as e:
					save_log('ERROR','Import Error:'+str(e))
				break
		if not f:
			save_log('ERROR','Import file Error!')
			return False
		NewIpSubThreed(rev,f,type)
		#ipSubThreed(rev,f,type)
	except Exception as e:	
		save_log('ERROR','BATCH func error:'+str(e))		
		return False
	return True

def ipSubThreed(rev,func,type='batch'):
	'''
	@每IP每线程，执行任务,最大同时执行maxthreed个任务
	'''
	if not rev or not func:		
		return False		
	iplist = batchIpList(rev)
	try:
		q = putBatchIp(iplist)
	except Exception as e:
		save_log('ERROR','BATCH QUEUE is error:'+str(e))
	while True:
		if q.qsize() == 0:
			#此次批量操作执行完成，更新状态
			id = str(getBatchId(rev))
			if type == 'batch':
				usql = "UPDATE `osa_tasknow` SET oStatus = '执行完成' WHERE id = "+id+""			
				try:
					uid = update(usql)
				except Exception as uerror:
					save_log('ERROR','update error,sql is:'+usql+',error info:'+str(uerror))
			break
			
		
		#线程控制，防止同时执行任务过大导致服务器负载突发
		for x in xrange(BATCH['maxthreed']):
		
			ip = getBatchIp(q)
			if ip == None or not ip:
				break	
			try:
				tt = threading.Thread(target=func.index,args=[rev,type,ip,x])
				tt.setDaemon(0)
				tt.start()				
			except Exception as e:
				save_log('ERROR','BATCH Threed is error:'+str(e))
	time.sleep(300)
	
	
def NewIpSubThreed(rev,func,type='batch'):
	'''
	@每IP每线程，执行任务,最大同时执行maxthreed个任务
	'''
	thread_arr = []
	
	if not rev or not func:	
		save_log('ERROR','rev or func Error!')
		return False		
	iplist = batchIpList(rev)
	try:
		q = putBatchIp(iplist)
	except Exception as e:
		save_log('ERROR','BATCH QUEUE is error:'+str(e))
		return False
		
	#while True:
	
	
		
	#线程控制，防止同时执行任务过大导致服务器负载突发
	#for x in xrange(BATCH['maxthreed']):
		
	for x in xrange(q.qsize()):
					
		ip = getBatchIp(q)
		
		try:
			tt = threading.Thread(target=func.index,args=[rev,type,ip,x])
			thread_arr.append(tt)			
		except Exception as e:
			save_log('ERROR','BATCH Threed is error:'+str(e))
			
	for i in xrange(len(thread_arr)):
		try:
			thread_arr[i].setDaemon(0)
			thread_arr[i].start()
		except Exception as e:
			save_log('ERROR','BATCH Threed start is error:'+str(e))
		
	for i in xrange(len(thread_arr)):
		thread_arr[i].join()	
	
	if q.qsize() == 0:
		#此次批量操作执行完成，更新状态
		id = str(getBatchId(rev))
		if type == 'batch':
			usql = "UPDATE `osa_tasknow` SET oStatus = '执行完成' WHERE id = "+id+""			
			try:
				uid = update(usql)
			except Exception as uerror:
				save_log('ERROR','update error,sql is:'+usql+',error info:'+str(uerror))
				
		save_log('INFO','BATCH Threed is ok!')
		return True
	
	return False
	#time.sleep(300)



