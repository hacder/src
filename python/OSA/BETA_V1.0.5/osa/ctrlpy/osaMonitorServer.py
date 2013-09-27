#!/usr/bin/python
#encoding=utf-8
'''
 Autor: osa开源团队
 Description:监控报警任务、
 create date：2012-05-29
'''

from  ctrlpy.lib.osaDamoClass import Daemon
from ctrlpy.lib.osaUtil import save_log
from ctrlpy.lib.osaMonitorLib import *
from ctrlpy.etc.config import DIRS

class osaMonitorDaemon(Daemon):
	def _run(self):
		try:
			ServerIsAlive()
		except Exception as e:
			save_log('ERROR',"osa damo server isalive error:"+str(e))
			

if __name__=='__main__':
	daemon=osaMonitorDaemon(DIRS['ROOT']+'osaMonitorServer.pid')
	if len(sys.argv)==2:
		if sys.argv[1].upper()=='START':
			daemon.start()
		elif sys.argv[1].upper()=='STOP':
			daemon.stop()
		elif sys.argv[1].upper()=='RESTART':
			daemon.stop()
			daemon.start()
		else:
			print "Unknow Command!"
			print "Usage: %s start|stop|restart" % sys.argv[0]
			sys.exit(2)
		sys.exit(0)
	else:
		print "Usage: %s start|stop|restart" % sys.argv[0]
		sys.exit(0)
