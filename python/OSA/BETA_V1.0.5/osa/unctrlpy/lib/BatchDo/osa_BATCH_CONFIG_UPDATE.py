#!/usr/bin/env python
#encoding=utf-8
import os,sys,time,md5
from unctrlpy.lib import osaBatchLib
from unctrlpy.lib.osaFileRecv import file_recv_main
from unctrlpy.lib import hostSocket
from unctrlpy.etc.config import SOCKET,FSOCKET,DIRS
from unctrlpy.lib.osaUtil import save_log
'''
	Author:		osa开源团队
	Description:配置文件更新模块
	Create Date:	2012-05-16
	
'''	

def index(rev):
	'''
	@接收文件主函数
	return 结果写入数据库
	'''
	if not rev:
		return False
	return batchConfigFileRecv(rev)
	

	
def batchConfigFileRecv(rev):
	'''
	@文件接收函数
	'''
	
	#文件保存目标位置
	citem = osaBatchLib.getConfigItem(rev)
	dfile = citem['config_update_targetpath']
	#文件名
	dfilename = os.path.basename(dfile)
	#文件目录
	fdir = os.path.dirname(dfile)
	#目标目录不存在则创建
	if not os.path.exists(fdir):
		try:
			makedirs(fdir)
		except Exception as e:
			save_log('ERROR',str(e))
			return "{'status':'ERROR','result':'x0041'}"
		
	#端口号
	fileport = osaBatchLib.getConfigPort(rev)
	
	#临时文件
	tempfile = osaBatchLib.tempFilePath(rev)
	#开始接收文件
	try:
		fsize = file_recv_main(host='0.0.0.0',port=int(fileport),filename = tempfile)
	except Exception as e:
		save_log('ERROR',str(e))
		return False
	if fsize:
		#对文件进行处理
		advance = citem['config_update_advance'].split('|')
		backfile = dfile+'.bak.'+str(time.strftime('%Y-%m-%d_%H:%M:%S', time.localtime()))
		if advance[0] == 'backup':
			if os.path.exists(dfile):
				try:
					os.rename(dfile,backfile)
					os.rename(tempfile,dfile)
				except Exception as e:		
					save_log('ERROR',str(e))
					return "{'status':'ERROR','result':'x0042'}"
					
			else:
				os.rename(tempfile,dfile)		
		else:
			if os.path.exists(dfile):
				try:
					os.rename(dfile,backfile)
					os.rename(tempfile,dfile)
					os.remove(backfile)
				except Exception as e:	
					save_log('ERROR',str(e))
					return "{'status':'ERROR','result':'x0043'}"
					
			else:
				os.rename(tempfile,dfile)
		if advance[1] == 'document_integrity':
			dict = osaBatchLib.revToDict(rev)
			if dict['md5'] != osaBatchLib.md5sum(dfile):
				return "{'status':'ERROR','result':'x0044'}"
		#执行脚本或者指令
		scmd = citem['config_update_scriptfile']
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
	return "{'status':'ERROR','result':'x0045'}"
