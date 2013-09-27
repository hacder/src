#!/usr/bin/env python
#encoding=utf-8
'''
	Author:		OSA开源团队
	Description:	Log Module	
	Create Date:	2012-10-31
'''

import time,os,sys
from ctrlpy.etc.config import DIRS,LOG


def log_error(errorinfo):
	'''
	@记录函数执行过程中错误信息
	@errorinfo:错误信息
	'''
	if(LOG['ERROR'] == 0):
		return

	logdir = DIRS['PY_OSA_LOG'] + time.strftime('%Y/%m/%d', time.localtime()) + '/'
	
	if not os.path.exists(logdir):
		os.system('mkdir -p ' + logdir)

	logfile = logdir+"osainfo.log"
	f = open(logfile, 'a')
	f.write('ERROR ' + time.strftime('%Y-%m-%d %H:%M:%S', time.localtime()) + ' ' + str(errorinfo) + '\n')
	f.close()


def log_debug(debuginfo):
	'''
	@记录函数过程中调试信息
	@debuginfo:调试信息
	'''
	if(LOG['DEBUG'] == 0):
		return

	logdir = DIRS['PY_OSA_LOG'] + time.strftime('%Y/%m/%d', time.localtime()) + '/'

        if not os.path.exists(logdir):
                os.system('mkdir -p ' + logdir)

        logfile = logdir+"osadebug.log"
        f = open(logfile, 'a')
        f.write('DEBUG ' + time.strftime('%Y-%m-%d %H:%M:%S', time.localtime()) + ' ' + str(debuginfo) + '\n')
        f.close() 


def log_info(info):
	'''
        @记录函数过程中调试信息
        @info:记录信息
        '''
        if(LOG['INFO'] == 0):
                return

        logdir = DIRS['PY_OSA_LOG'] + time.strftime('%Y/%m/%d', time.localtime()) + '/'

        if not os.path.exists(logdir):
                os.system('mkdir -p ' + logdir)

        logfile = logdir+"osanotes.log"
        f = open(logfile, 'a')
        f.write('INFO ' + time.strftime('%Y-%m-%d %H:%M:%S', time.localtime()) + ' ' + str(info) + '\n')
        f.close()


