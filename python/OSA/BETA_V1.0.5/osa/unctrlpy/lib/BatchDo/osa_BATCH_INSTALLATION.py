#!/usr/bin/env python
#encoding=utf-8

from unctrlpy.lib import osaBatchLib
from unctrlpy.lib.osaUtil import save_log

'''
	Author:		osa开源团队
	Description: 命令或者脚本执行模块
	Create Date:	2012-05-23
'''	
def index(rev):
	'''
	批量安装主函数
	'''
	if not rev:
		return False
	else:
		return batchAppInstalltion(rev)

def batchAppInstalltion(rev):
	'''
	安装执行函数，实为调用一个shell脚本
	'''
	value = osaBatchLib.getConfigItem(rev)
	script=value['install_scriptfile']
	if script:
		try:
			result = osaBatchLib.runCmdOrScript(script)
			return "{'status':'OK','result':'"+result+"'}"
		except Exception ,e:
			save_log('ERROR',str(e))
			result = 'Install Script run failed!'
			return "{'status':'ERROR','result':'"+result+"'}"
	else:
		return "{'status':'ERROR','result':'Unknow error!'}"
		

