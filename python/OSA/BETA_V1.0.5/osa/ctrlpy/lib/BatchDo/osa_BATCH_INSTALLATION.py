#!/usr/bin/env python
#encoding=utf-8
import sys
from ctrlpy.lib import osaBatchLib

'''
	Author:		osa开源团队
	Description:   agent端程序安装控制模块
	Create Date:	2012-05-23
'''	
def index(rev,type,ip,x=''):
	'''
	agent端程序安装主函数
	'''
	if not rev or not ip:
		return False
	if not type:
		type = 'batch'

	try:
		r = osaBatchLib.batchSendCmd(rev,ip,type)
	except Exception,e:
		save_log('ERROR','BATCH_INSTALLTION:'+str(e))
	sys.exit()
	




