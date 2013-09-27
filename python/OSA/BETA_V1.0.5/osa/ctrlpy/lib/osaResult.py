#!/usr/bin/env python
#encoding=utf-8
'''
	Author:		osa开源团队
	Description:	处理来自unctrlpy端返回的结果信息
	Create Date:	2012-05-18
'''
import sys,time,os
from ctrlpy.etc.config import RLIST
from ctrlpy.lib.cmdtosql import _get_time,_get_pcon,_exit,_get_con
from ctrlpy.lib.osaUtil import save_log

def isConseResult(r):
	'''
	@判断是否为客户端返回的结果
	'''	
	try:	
		rdict = eval(r)
	except Exception:
		return False	
	if rdict['command'] in RLIST:
		return rdict['command']
	return False


def chooseResultDef(r):
	'''
	@选择处理结果的函数
	'''
	rcmd = isConseResult(r)
	if not rcmd or rcmd == False:
		return False	
	func = rcmd + '(r)'	
	return eval(func)

def batchresult(r):
	'''
	@处理批量操作结果
	'''
	rdict = eval(r)
	oCmdType =  rdict['batchinfo']['command']
	oBatchid = rdict['batchinfo']['id']
	oClientip = rdict['batchinfo']['clientip']
	oRunTime = _get_time(flag=1)
	oResult = str(rdict['batchresult'])
	if rdict['batchinfo']['type'] == 'batch':		
		isql = "INSERT INTO `osa_tasknow_result` (`oCmdType`,  `oRunTime`, `oTaskNowid`,`oClientip`, `oResult`) VALUES ('"+oCmdType+"', '"+str(oRunTime)+"', "+str(oBatchid)+", '"+str(oClientip)+"', \""+oResult+"\")"
	else:
		isql = "INSERT INTO `osa_taskplan_result` (`oCmdType`, `oRunTime`, `oTaskPlanid`, `oClientip`, `oResult`) VALUES ('"+oCmdType+"', '"+str(oRunTime)+"', "+str(oBatchid)+", '"+str(oClientip)+"', \""+oResult+"\")"
	

	try:
		con = _get_pcon()
		cur = con.cursor()
		cur.execute(isql)
	except Exception as inserror:
		_exit(con, cur)
		save_log('ERROR','insert into db error 1,sql is:'+isql+",error info:"+str(inserror))	
		time.sleep(random.randint(0,10))
		try:
			con = _get_con()
			cur = con.cursor()
			cur.execute(isql)
		except Exception as inserror:
			
			save_log('ERROR','insert into db error 2,sql is:'+isql+",error info:"+str(inserror))
		finally:				
			_exit(con, cur)
			sys.exit()
	finally:		
		_exit(con, cur)
		sys.exit()

