#!/usr/bin/env python
#encoding=utf-8
'''
	Author:		osa开源团队
	Description:	Util Module
	Create Date:	2011-07-20

	OWS(Open Web SA) Util Module
	1、save_log: 日志生成方法
	2、putCmd: 添加指令到队列(return queue)
	3、global maxsize, queue, lock
'''

import time
import os
import commands
import re
import sys
from threading import Lock
from Queue import Queue

from ctrlpy.etc.config import DIRS, QUEUE, EMAIL,BATCHLIST,LOG
from osaException import queueEmptyException, queueFullException
from defaultQueue import deQueue
from ctrlpy.lib import osajson
import simplejson as sjson

def save_log(type, data):
	'''
	@type: 日志记录类型
	@data: 记录内容
	'''
	if LOG[type] == 0 :
		return 
	
	logdir = DIRS['PY_OSA_LOG'] + time.strftime('%Y_%m', time.localtime()) + '/'
	
	if not os.path.exists(logdir):
		os.system('mkdir -p ' + logdir)
	
	log_file = logdir + time.strftime('%Y_%m_%d', time.localtime()) + '_sysinfo.log'
	f = open(log_file, 'a')
	f.write(type + ' ' + time.strftime('%Y-%m-%d %H:%M:%S', time.localtime()) + ' ' + str(data) + '\n')
	f.close()

def save_Thread_log(type,subtype,data):
	'''
	@type: 日志记录类型
	@data: 记录内容
	'''
	if LOG[type] == 0 :
		return 
	
	logdir = DIRS['PY_OSA_LOG'] + time.strftime('%Y_%m', time.localtime()) + '/Thread/'
	
	if not os.path.exists(logdir):
		os.system('mkdir -p ' + logdir)
	
	log_file = logdir + time.strftime('%Y_%m_%d', time.localtime()) + '_' + type+ '_' + subtype + '_debug.log'
	f = open(log_file, 'a')
	f.write(type + ' ' + time.strftime('%Y-%m-%d %H:%M:%S', time.localtime()) + ' ' + str(data) + '\n')
	f.close()

def ayCmdToConsole(fromPhpCmd):
	'''
	@fromPhpCmd: 来自php端的指令
	解析来自Php端的指令传递给unctrlpy
	'''
	
	try:
		num = len(fromPhpCmd.split('!'))
		return re.search('.*!',fromPhpCmd).group()[0:-1], fromPhpCmd.split('!')[num-1]
	except AttributeError:
		try:
			batchlist = BATCHLIST.keys()
			if re.search('BATCH_',fromPhpCmd):
				phpdict = eval(fromPhpCmd)
			if phpdict['command'] in batchlist:
				return phpdict['command'],1
			return 0,0
		except Exception as allargs:				
			errlog = 'ayCmdToConsole Error : %s ' % allargs
			save_log('ERROR', errlog)		
			return 0,0

a = '{"command":"BATCH_DOCUMENT_DISTRIBUTION","iparr":"192.168.1.4|192.168.1.6","config_items":{"sourcefile":"upload/2012-05-23/index.php","targetpath":"/data/test","advance":"cut|document_integrity","distribution_script":"test/test1/testll.py"},"id":"13","md5":"dffbc92bf2dcf84f55d44ed4b447c4d9","filesize":2759}'

queue = Queue( maxsize = QUEUE['MAXSIZE'])
lock = Lock()


def putCmd(recv, size=50):
	'''
	@将指令加入到执行序列中
	'''
	
	global lock, queue
	
	queue.put(recv)
	
	sq = deQueue(recv, queue, lock)
	sq.setDaemon(1)
	sq.start()

	if queue.empty():
		raise queueEmptyException, 'Empty: queue is empty.'
	elif queue.full():
		raise queueFullException, 'Full: queue is full.'
	else:
		return queue

