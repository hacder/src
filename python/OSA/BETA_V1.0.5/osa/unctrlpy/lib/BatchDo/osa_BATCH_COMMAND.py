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
	Description: 命令或者脚本执行模块
	Create Date:	2012-05-22
	
'''	

def index(rev):
	'''
	@命令或者脚本执行主函数
	return 结果返回给agent端,写入数据库
	'''
	if not rev:
		return False
	return batchCmdOrShell(rev)
	

	
def batchCmdOrShell(rev):
	'''
	@命令或者脚本执行函数
	'''
	
	
	
	#判断是否选择了脚本操作
	citem = osaBatchLib.getConfigItem(rev)
	
	if 'command_scriptfile' in citem:
		scmd = citem['command_scriptfile']
	else:		
		return "{'status':'ERROR','result':'Unknow error!'}"
	sr = osaBatchLib.scriptOrCmd(scmd)
	
	if sr:
		try:
			result = osaBatchLib.runCmdOrScript(scmd)
			return "{'status':'OK','result':'"+result+"'}"
		except Exception as e:
			save_log('ERROR',str(e))
			result = 'Cmd or Script is run Faild!'
			return "{'status':'ERROR','result':'"+result+"'}"	
	else:
		return "{'status':'ERROR','result':'Unknow error!'}"

#rev="{'command':'BATCH_SERVICE_RESTART','iparr':'192.168.2.1|192.168.2.2','config_items':{'service_scriptfile':'/root/shell/a.sh'},'id':'id'}"
	
