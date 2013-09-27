#!/usr/bin/env python
#encoding=utf-8
import os,sys,time,md5
from unctrlpy.lib import osaBatchLib
from unctrlpy.lib import osaDiskLib


'''
	Author:		osa开源团队
	Description: 负载检查模块
	Create Date:	2012-05-22
	
'''	

def index(rev):
	'''
	@负载检查主函数
	return 结果返回给agent端,写入数据库
	'''
	if not rev:
		return False
	return batchTopCheck(rev)
	

	
def batchTopCheck(rev):
	'''
	@负载检查
	'''
	
		
	#判断是否选择了脚本操作
	citem = osaBatchLib.getConfigItem(rev)
	if citem['topstate_scriptfile'] == 'default':
		citem['topstate_scriptfile'] = 'uptime'
	
	#执行脚本或者指令
	scmd = citem['topstate_scriptfile']
	sr = osaBatchLib.scriptOrCmd(scmd)
	if sr:
		try:
			result = osaBatchLib.runCmdOrScript(scmd)
			return "{'status':'OK','result':'"+result+"'}"
		except Exception as e:
			save_log('ERROR',str(e))
			result = 'Cmd or Script is Faild!'
			return "{'status':'ERROR','result':'"+result+"'}"	
		return "{'status':'OK','result':'OK'}"
	else:
		return "{'status':'ERROR','result':'Unknow error!'}"
		
#rev="{'command':'BATCH_DISKSPACE_CHECK','iparr':'192.168.2.1|192.168.2.2','config_items':{'diskspace_threshold':'90','unit':'MB'},'id':'id'}"
	
