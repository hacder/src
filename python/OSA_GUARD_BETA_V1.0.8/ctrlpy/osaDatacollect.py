#!/usr/bin/env python
#encoding=utf-8


import subprocess,time,signal,sys
from ctrlpy.etc.config import DIRS
from ctrlpy.lib import osaDamoClass

reload(sys) 
sys.setdefaultencoding('utf8')  

def datacollect():
	ppath = DIRS['ROOT'] + 'ctrlpy/collect.py collect'
	
	pypath = DIRS['PYTHONPATH']
	while True:
		#子进程自己处理退出信号
		signal.signal(signal.SIGCHLD,signal.SIG_IGN)
		#生成子进程执行采集
		p = subprocess.Popen(pypath+' '+ppath, stdout = subprocess.PIPE, shell = True)
		#控制采集间隔
		time.sleep(300)
		

class collectDaemon(osaDamoClass.Daemon):
	def _run(self):
		datacollect()
		
		
if __name__ == '__main__':	
	
	daemon = collectDaemon(DIRS['ROOT']+'Datacollect.pid')
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
	

