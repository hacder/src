#!/usr/bin/env python
#encoding=utf-8
'''
	Author:		osa开源团队
	Description:	批量操作任务
	Create Date:	2012-05-15
'''
import time
import os,sys,re
from unctrlpy.lib.osaUtil import save_log
from unctrlpy.lib import osaBatchLib

#导入批量操作模块
from unctrlpy.etc.config import BATCHLIST,DIRS

def ayCmd(fromAngentCmd):
	'''
	@fromPhpCmd: 来自agent端的指令	
	'''
	try:
		phpdict = {}
		batchlist = BATCHLIST.keys()
		if re.search('BATCH_',fromAngentCmd):
			phpdict = eval(fromAngentCmd)
		if not phpdict or not phpdict['command']:
			return False
		if phpdict['command'] in batchlist:
			return phpdict['command']
		return False		
	except Exception as allargs:				
		errlog = 'ayCmdToConsole Error : %s ' % allargs
		save_log('WARNING', errlog)	
		return False



def isBatchCmd(fromPhpCmd):
	'''
	@fromPhpCmd 来自php端的批量操作指令
	@例如：BATCH_DOCUMENT_DISTRIBUTION
	'''
	batchlist = BATCHLIST.keys()
	
	if fromPhpCmd in batchlist:
		return True
	return False


def chooseBatchDef(fromPhpCmd,rev):
	'''	
	Description: 接收来自PHP端的批量操作指令，执行指定函数
				 前缀：osa_ 加上 PHP端指令名称作函数名称.
				 批量操作的参数统一为：接到到的指令详情
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
                                        f = __import__('unctrlpy.lib.BatchDo.osa_'+b, globals(), locals(), ['index'], -1)
                                except Exception as e:
                                        save_log('ERROR','Import Error:'+str(e))
				break
		if not f:
			return "{'status': 'ERROR', 'result': 'Import batch file Error!'}"
		return f.index(rev)
	except Exception as e:
	
		save_log('ERROR','chooseBatchDef error:'+str(e))
		return "{'status': 'ERROR', 'result': 'chooseBatchDef error:'"+str(e)+"}"


