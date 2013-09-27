#!/usr/bin/env python
#encoding=utf-8

'''
	Author:		osa开源团队
	Description:	略
	Create Date:	2011-07-20
'''

import time
import os
import commands
import re

from unctrlpy.etc.config import DIRS, COMMANDS
import osajson 


def save_log(type, data):
	'''
	@type: 日志记录类型
	@data: 日志内容
	'''

	logdir = DIRS['PY_OSA_LOG'] + time.strftime('%Y_%m', time.localtime()) + '/'
	
	if not os.path.exists(logdir):
		os.system('mkdir -p ' + logdir)
	log_file = logdir + time.strftime('%Y_%m_%d', time.localtime()) + '_sysinfo.log'
	
	f = open(log_file, 'a')
	f.write(type + ' ' + time.strftime('%Y-%m-%d %H:%M:%S', time.localtime()) + ' ' + str(data) + '\n')
	f.close()


def _ayArgs(args):
	'''
	分析参数
	@args: 脚本或命令参数
	@return: 返回参数
	'''

	value = ''
	
	if _ayWatch(args):
		for arg in args.split('|'):
			value += arg + ' ' 
	return args


def ayCmdToExec(fromPhpCmd):
	'''
	解析来自agent端的指令
	@fromPhpCmd: agent端指令
	@return：返回指令执行后的返回值
	'''
	if _ayWatch(fromPhpCmd):
		cmds = fromPhpCmd.split('!')
		cmdType = cmds[0].split('_')[1]
		
		if cmdType.upper() == "RUN":
			cmd = osajson.read(cmds[1])
			cmd_prefix = cmd.keys()[0]
			
			if len(str(cmd.values()[0])) > 2:
				cmd_suffix = _ayArgs(cmd.values()[0])
				return cmd_prefix + ' ' + cmd_suffix, cmdType
			
			return (cmd_prefix + ' ' + str(cmd.values()[0])).strip(), cmdType

		elif cmdType.upper() == "ADD":
			pass

		elif cmdType.upper() == "UPDATE":
			pass
		
		elif cmdType.upper() == "DEL":
			pass
		
		else:
			pass
	
	else:
		status, info = commands.getstatusoutput(fromPhpCmd)
		return fromPhpCmd, info, status

def reWithToPhp(cmdType, cmdName, option="", value=""):
	
	if cmdType.upper() in COMMANDS['CMDS']:
		if option:
			return 'RETURN_SYSTEM_%s_COMMAND!"%s"=>"{"%s":"%s"}"' % \
					(cmdType.upper(), cmdName, option, value)
		return 'RETURN_SYSTEM_%s_COMMAND!%s' % (cmdType.upper(), value)	
	else:
		pass
		

def _ayWatch(ayName):
	'''
	用于判断指令符号
	'''

	return re.search('[!|]', ayName)

