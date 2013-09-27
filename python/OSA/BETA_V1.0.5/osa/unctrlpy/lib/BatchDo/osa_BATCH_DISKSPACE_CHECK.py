#!/usr/bin/env python
#encoding=utf-8
import os,sys,time,md5
from unctrlpy.lib import osaBatchLib
from unctrlpy.lib import osaDiskLib


'''
	Author:		osa开源团队
	Description: 磁盘空间检查模块
	Create Date:	2012-05-22
	
'''	

def index(rev):
	'''
	@磁盘空间检查主函数
	return 结果返回给agent端,写入数据库
	'''
	if not rev:
		return False
	return batchDiskCheck(rev)
	

	
def batchDiskCheck(rev):
	'''
	@磁盘空间检查
	'''
	
		
	#判断是否选择了脚本操作
	citem = osaBatchLib.getConfigItem(rev)
	devlist = osaDiskLib.disk_partitions()
	rlist = []
	if citem['unit'] == 'MB':		
		for devpath in devlist:
			info=osaDiskLib.disk_usage(devpath[1])
			if int(info[1]/1024/1024) >=  int(citem['diskspace_threshold']) :
				rlist.append(devpath[1])
	elif citem['unit'] == '%' :
		for devpath in devlist:
			info=osaDiskLib.disk_usage(devpath[1])
			if int(info[3]) >=  int(citem['diskspace_threshold']) :
				rlist.append(devpath[1])
	
	if rlist:
		return "{'status':'ERROR','result':'disk stat more than diskspace_threshold!detail list:"+str(rlist)+"'}"
	else:
		return "{'status':'OK','result':'disk stat is normal!'}"
		

	
