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
	Description:文件清理模块
	Create Date:	2012-05-22
	
'''	

def index(rev):
	'''
	@批量清理文件主函数
	return 结果返回给agent端
	'''
	if not rev:
		return False
	return batchFileClean(rev)
	

	
def batchFileClean(rev):
	'''
	@文件清理函数
	'''
	
	#需要清理的目录目录
	citem = osaBatchLib.getConfigItem(rev)
	dfile = citem['cleaner_sourcefile']
	
	if not os.path.isdir(dfile):  
		return "{'status':'ERROR','result':'x0032'}"	
	
	#文件被移动的位置
	mpath = citem['cleaner_targetpath']
	
	if not os.path.isdir(mpath):  
		mpath = '/dev/null'
	
	#操作高级选项
	
	advance = citem['cleaner_advance']
	
	#执行清理
	if advance == 'rm_dir':
		re = osaSysFunc.removeall(dfile)
		if re == False :
			result = "{'status':'ERROR','result':'x0031'}"
		else:
			result = "{'status':'OK','result':'clear over!'}"
		return result
	
	hlist = advance.split(',')
	
	for ftype in hlist:
		re = osaSysFunc.mvfile(dfile,mpath,(ftype.replace('.','\\.')))
		if re == False:
			return "{'status':'ERROR','result':'x0033'}"
	
	return "{'status':'OK','result':'clear over!'}"
	
#rev = "{'command':'BATCH_FILE_CLEANER','iparr':'192.168.2.1|192.168.2.2','config_items':{'cleaner_sourcefile':'/root/shell/','cleaner_targetpath':'/dev/null','cleaner_advance':'.bak,.log,.bak.'},'id':'id'}"

