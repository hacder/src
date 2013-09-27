#!/usr/bin/env python
#encoding=utf-8

'''
	Author:		osa开源团队
	Description:部分文件操作函数
	Create Date:	2012-05-22
	
'''	

import sys, os ,shutil ,re
from unctrlpy.lib.osaUtil import save_log

  
def rmgeneric(path, __func__):  
  
	try:  
		__func__(path)  
		save_log('INFO','Removed: '+str(path))  
	except OSError, (errno, strerror):  
		ERROR_STR= """Error removing %(path)s, %(error)s """  
		save_log('ERROR',ERROR_STR % {'path' : path, 'error': strerror })
		return False
			  
def removeall(path):  
	'''
	@整目录递归删除函数
	'''
  
	if not os.path.isdir(path):  
		return False	  

	files=os.listdir(path)  
	
	for x in files:  
		fullpath=os.path.join(path, x)  
		if os.path.isfile(fullpath):  
			f=os.remove  
			rmgeneric(fullpath, f)  
		elif os.path.isdir(fullpath):  
			removeall(fullpath)  
			f=os.rmdir  
			rmgeneric(fullpath, f) 
			

def mvfile(src, dst , filetype):
	
	for i in os.listdir(src):
		filepath = src + os.sep + i
		if src.endswith(os.sep):
			filepath = src + i		
		if os.path.isdir(filepath):
			mvfile(filepath, dst,filetype)
		elif i.endswith(filetype) or re.search(filetype,i):
			try:				
				shutil.move(filepath, dst)
				save_log('INFO','move: '+filepath +' to ' +dst)				
			except Exception as error:
				save_log('ERROR','move:'+filepath +' to' +dst+ ' is error!')
				return False	
	return True 
