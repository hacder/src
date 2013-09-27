#!/usr/bin/env python
#encoding=utf-8

'''
	Author:		osa开源团队
	Description:	任务计划执行
	Create Date:	2012-05-15
'''
import socket
import sys
import threading
import time,signal
from ctrlpy.lib.osaUtil import save_log,ayCmdToConsole
from ctrlpy.lib import hostSocket,osaTaskLib
from multiprocessing import Process,Queue
from ctrlpy.etc.config import TASK,DIRS
from ctrlpy.lib.osaBatch import isBatchCmd,chooseBatchDef
from ctrlpy.lib import osaDamoClass

def putTaskId(tinfolist):
	'''
	@计划任务ID入队
	'''
	# 准备队列
	q = Queue()

	# 计划任务信息入队
	for info in tinfolist:
		q.put(info)
	return q

def getTaskId(q):
	'''
	@从队列里面取IP
	'''
	info = None
	if q.qsize() != 0:
		info = q.get()	
	return info


def doTaskMain(tinfo):
	'''
	@tinfo 批量操作指令类型
	@执行计划任务
	'''
	cmdstr, ip = ayCmdToConsole(tinfo)
	try:
		type = 'task'
		chooseBatchDef(cmdstr,tinfo,type)
	except Exception as e:
		save_log('ERROR','TASK batch chooseBatchDef error:'+str(e))	

def getTaskList():
	'''
	@@目前未完成
	@获取任务列表
	'''	
	
	return osaTaskLib.chooseoRunCycle()
	
def mut_process():
	'''
	@生成多进程，执行计划任务
	'''
	#根据ID生成子进程
	tinfolist = getTaskList()
	
	if tinfolist != None:
		tq = putTaskId(tinfolist)		
		while True:
			if tq.qsize() == 0:
				break		
		
			for x in xrange(TASK['maxprocess']):
				tinfo = getTaskId(tq)
				if tinfo == None:
					break
				save_log('INFO','Task run :'+str(tinfo))
				try:
					#处理子进程退出信号
					signal.signal(signal.SIGCHLD,signal.SIG_IGN)
					i = Process(target=doTaskMain, args = [tinfo])
					i.start()
				except Exception as taskerror:
					save_log('ERROR','task process error:'+str(taskerror))
			
	
def runTask():
	'''
	@计划任务入口函数
	'''
	
	while True:	
	    #主进程一直监听数据库，获取任务列表
		mut_process()
		#间隔一定时间给其他应用程序操作计划任务表
		time.sleep(TASK['interval'])

class osaTaskDaemon(osaDamoClass.Daemon):
	def _run(self):
		runTask()	

if __name__ == '__main__':	
	
	daemon = osaTaskDaemon(DIRS['ROOT']+'osaTask.pid')
	if len(sys.argv) == 2:
		if 'START' == (sys.argv[1]).upper():
			daemon.start()
		elif 'STOP' == (sys.argv[1]).upper():
			daemon.stop()
		elif 'RESTART' == (sys.argv[1]).upper():
			daemon.restart()
		else:
			print "Unknow Command!"
			print "Usage: %s start|stop|restart" % sys.argv[0]
			sys.exit(2)
		sys.exit(0)
	else:
		print "Usage: %s start|stop|restart" % sys.argv[0]
		sys.exit(0)
