#!/usr/bin/env python
#encoding=utf-8

'''
	Author:		osa开源团队
	Description:	略
	Create Date:	2011-07-20
'''

import subprocess
import ConfigParser
import os

from unctrlpy.etc.config import DIRS


def execScript(sname,spath=None,avg=''):
	'''
	执行shell脚本
	@sname: 脚本文件名
	@spath: 脚本所在路径
	return 脚本名，脚本执行成功的返回值
	'''

	avgstr = ''
	for str in avg:
		avgstr = avgstr + " " + str
	if not spath:
		if os.path.exists(DIRS['PY_OSA_SHELL_ROOT'] + sname):
			p = subprocess.Popen(DIRS['PY_OSA_SHELL_ROOT'] + sname, stdout = subprocess.PIPE, shell = True)
			s = sname.split()
			
			pstr = p.stdout.read().rstrip()
			i = 0
			while pstr == 'null' and i < 3:
				p = subprocess.Popen(DIRS['PY_OSA_SHELL_ROOT'] + sname, stdout = subprocess.PIPE, shell = True)
                       		s = sname.split()
                        	pstr = p.stdout.read().rstrip()
				i = i + 1
		else:
			p = subprocess.Popen( sname + avgstr, stdout = subprocess.PIPE, shell = True)
			s = sname.split()
			pstr = ['']
			pstr[0]=p.stdout.read().replace("\n",'@@@@')
	else:
		p = subprocess.Popen(spath + sname + avgstr, stdout = subprocess.PIPE, shell = True)
		s = sname.split()
		pstr = p.stdout.read().rstrip()
		i = 0
		while pstr == 'null' and i < 3:
			p = subprocess.Popen(spath + sname + avgstr, stdout = subprocess.PIPE, shell = True)
                	s = sname.split()
                	pstr = p.stdout.read().rstrip()	
			i = i + 1
	return s,pstr
#print execScript('runall')

def execAll():
	'''
	执行所有监控脚本
	'''
	
	return execScript('runall')


def execShellScript(cmd):
	'''
	根据配置文件执行SHELL指令
	@cmd :脚本文件名
	return 脚本名,脚本执行成功的返回值
	'''
	cmdlist = str(cmd).split(" ")
	
	if len(cmdlist) > 1:
		cmdavg = cmdlist[1:]
		c = cmdlist[0]
	else:
		cmdavg = ''
		c = cmd
	
	config = ConfigParser.ConfigParser()
	config.readfp(open(DIRS['PY_OSA_ETC'] + 'shell_cmd_config.ini'))
	alist = config.get("alist","shelllist").split('|')
	path=None

	for a in alist:
		if a == c:
			path = config.get(c,"path")
			
			if path == 'default':
				path = DIRS['PY_OSA_SHELL_ROOT']
			else:
				path = DIRS['PY_OSA_SHELL_ROOT'] + path
			return execScript(sname=c,spath=path,avg=cmdavg)
		else:
			shelllist=config.get(a,"shelllist").split('|')
			for s in shelllist:
				if s == c :
					path = config.get(a,"path")
					break
	if not path:
		path = None      
	
	elif path == 'default':
		path = DIRS['PY_OSA_SHELL_ROOT']
	else:
		path = DIRS['PY_OSA_SHELL_ROOT'] + path
		
	return execScript(sname=c,spath=path,avg=cmdavg)


