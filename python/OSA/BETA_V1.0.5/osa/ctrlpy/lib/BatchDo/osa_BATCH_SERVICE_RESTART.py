#!/usr/bin/env python
#encoding=utf-8
import os,sys,time

from ctrlpy.lib import osaBatchLib

'''
	Author:		osa开源团队
	Description: 服务重启模块
	Create Date:	2012-05-22	
'''	

def index(rev,type,ip,x=''):
	'''
	@ 服务重启主函数
	return 空
	'''
	if not rev or not ip:
		return False
	if not type:
		type = 'batch'
	
	try:
		r = osaBatchLib.batchSendCmd(rev,ip,type)
	except Exception as e:
		save_log('ERROR','BATCH_SERVICE_RESTART:'+str(e))
	#子线程退出
	sys.exit()
	

	
	
	
	
