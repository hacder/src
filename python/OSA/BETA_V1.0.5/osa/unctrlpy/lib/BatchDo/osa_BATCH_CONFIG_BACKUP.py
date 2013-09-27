#!/usr/bin/env python
#encoding=utf-8
import os,sys,shutil,time,md5
from unctrlpy.lib import osaBatchLib
from unctrlpy.lib.osaFileRecv import file_recv_main
from unctrlpy.lib import hostSocket
from unctrlpy.etc.config import SOCKET,FSOCKET,DIRS
from unctrlpy.lib.osaUtil import save_log
'''
	Author:		osa开源团队
	Description:配置文件备份模块
	Create Date:	2012-05-16
	
'''	

def index(rev):
	'''
	@配置文件备份主函数
	return 结果写入数据库
	'''
	if not rev:
		return False
	return batchConfigBackup(rev)
	

	
def batchConfigBackup(rev):
	'''
	@配置文件备份
	'''
	
	#源文件
	citem = osaBatchLib.getConfigItem(rev)
	sfilelist = citem['config_backup_sourcefile'].split('|')
	
	for sfile in sfilelist:
	
		sname = os.path.basename(sfile)
	
		#文件保存目标位置
		dfile = citem['config_backup_dir']
		
		if citem['config_backup_rule'] == '1' or citem['config_backup_rule'] == 1 :
			if dfile.endswith(os.sep):
				dpath = dfile + sname + '.bak'
			else:
				dpath = dfile + os.sep + sname + '.bak'  
		else:
			if dfile.endswith(os.sep):
				dpath = dfile + sname + '.bak.' + str(time.strftime('%Y-%m-%d_%H:%M:%S', time.localtime()))
			else:
				dpath = dfile + os.sep + sname + '.bak.' + str(time.strftime('%Y-%m-%d_%H:%M:%S', time.localtime()))
			
		if not os.path.exists(dfile):
			try:
				os.makedirs(dfile)
			except Exception as e:
				save_log('ERROR','mkdir error:'+str(e))
				return "{'status':'ERROR','result':'x0052,mkdir error.'}"
	
		try:
			shutil.copyfile(sfile,dpath)
		except Exception as e:
			save_log('ERROR','copy file error:'+str(e))
			return "{'status':'ERROR','result':'x0051,copy file error.'}"	
	
	return "{'status':'OK','result':'config backup succeed!'}"
	
#rev="{'command':'BATCH_CONFIG_BACKUP','iparr':'192.168.2.1|192.168.2.2','config_items':{'config_backup_sourcefile':'/data/conf/nginx/nginx.conf|/root/shell/a.txt','config_backup_dir':'/backup/','config_backup_rule':'1'},'id':'id'}"
		
			
		
		
		
		
		
		
		
		
		
		
		
