#!/usr/bin/env python
#encoding=utf-8
import os,sys,time,md5,shutil
from unctrlpy.lib import osaBatchLib
from unctrlpy.lib.osaFileRecv import file_recv_main
from unctrlpy.lib import hostSocket
from unctrlpy.etc.config import SOCKET,FSOCKET,DIRS
from unctrlpy.lib.osaUtil import save_log
'''
	Author:		osa开源团队
	Description:文件分发模块
	Create Date:	2012-05-16
	
'''	

def index(rev):
	'''
	@批量分发文件主函数
	return 结果写入数据库
	'''
	if not rev:
		return False
	return batchFileRecv(rev)
	

	
def batchFileRecv(rev):
	'''
	@文件接收函数
	'''
	
	#文件保存目标位置
	citem = osaBatchLib.getConfigItem(rev)
	dfile = citem['targetpath']
	#文件名
	dfilename = os.path.basename(dfile)
	#文件目录
	fdir = os.path.dirname(dfile)
	#目标目录不存在则创建
	if not os.path.exists(fdir):
		try:
			os.makedirs(fdir)
		except Exception as e:
			save_log('ERROR','mkdir is error:'+str(e))
			return "{'status':'ERROR','result':'x0027'}"
			
			
		
	#端口号
	fileport = osaBatchLib.getConfigPort(rev)
	
	#临时文件
	tempfile = osaBatchLib.tempFilePath(rev)
	#开始接收文件
	try:
		fsize = file_recv_main(host='0.0.0.0',port=int(fileport),filename = tempfile)
	except Exception as e:
		save_log('ERROR','file recv error:'+str(e))
		return "{'status':'ERROR','result':'x0026'}"	
		
	if fsize:
		#对文件进行处理
		advance = citem['advance'].split('|')
		backfile = dfile+'.bak.'+str(time.strftime('%Y-%m-%d_%H:%M:%S', time.localtime()))
		if advance[0] == 'cut':
			if os.path.exists(dfile):
				try:
					os.rename(dfile,backfile)
					os.rename(tempfile,dfile)
				except Exception as e:			
					save_log('ERROR',str(e))
					return "{'status':'ERROR','result':'x0022'}"
					
			else:
				try:
					os.rename(tempfile,dfile)
				except:
					shutil.copy(tempfile,dfile)
		elif advance[0] == 'copy':
			if os.path.exists(dfile):
				try:
					os.rename(dfile,backfile)
					os.rename(tempfile,dfile)
					os.remove(backfile)
				except Exception as e:
					save_log('ERROR',str(e))
					return "{'status':'ERROR','result':'x0023'}"
					
			else:
				try:
					os.rename(tempfile,dfile)
				except:
					shutil.copy(tempfile,dfile)
		else:
				return "{'status':'ERROR','result':'x0021'}"
		if advance[1] == 'document_integrity':
			dict = osaBatchLib.revToDict(rev)
			if dict['md5'] != osaBatchLib.md5sum(dfile):
				return "{'status':'ERROR','result':'x0024'}"
		#执行脚本或者指令
		scmd = citem['distribution_script']
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
	return "{'status':'ERROR','result':'x0025'}"
