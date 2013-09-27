#!/usr/bin/env python
#encoding=utf-8
import os,sys,time,md5
from unctrlpy.lib import osaBatchLib
from unctrlpy.lib.osaFileRecv import file_recv_main
from unctrlpy.lib import hostSocket
from unctrlpy.lib import osaSysFunc
from unctrlpy.etc.config import SOCKET,FSOCKET,DIRS
from unctrlpy.lib.osaUtil import save_log
'''
	Author:		osa开源团队
	Description: 服务操作模块
	Create Date:	2012-05-22
	
'''	

def index(rev):
	'''
	@批量服务操作主函数
	return 结果返回给agent端,写入数据库
	'''
	if not rev:
		return False
	return batchServiceRestart(rev)
	

	
def batchServiceRestart(rev):
	'''
	@服务操作函数
	'''
	
	
	
	#判断是否选择了脚本操作
	citem = osaBatchLib.getConfigItem(rev)	
	
	if 'service_scriptfile' in citem:
		script = citem['service_scriptfile']
	else:		
		script = 'service '+str(citem['service_name'])+' '+citem['service_type']
		
	sr = osaBatchLib.scriptOrCmd(script)
	if sr:
		try:
			result = osaBatchLib.runCmdOrScript(script)
			return "{'status':'OK','result':'OK!'}"
		except Exception as e:
			save_log('ERROR','Cmd or Script is run Faild!'+str(e))
			result = 'Cmd or Script is run Faild!'+str(e)
			return "{'status':'ERROR','result':'services error!'}"	
	else:
		return "{'status':'ERROR','result':'Unknow error!'}"


	
