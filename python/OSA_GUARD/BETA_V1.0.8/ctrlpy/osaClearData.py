#!/usr/bin/env python
#encoding=utf-8
'''
	Author: 	ows开源团队
	Description:	(对服务器与项目监控报警次数清零)
	Date: 		2011-08-20
'''
from datetime import *
import subprocess,time,signal,sys,datetime
from ctrlpy.etc.config import DIRS
from ctrlpy.lib import cmdtosql
from ctrlpy.lib import osaDamoClass
from ctrlpy.lib.osaLogLib import *
reload(sys) 
sys.setdefaultencoding('utf8')  

def get_diff_time():
	'''
	@计算当前时间与00:00:00之间的时间差
	@休眠时间差，下次正好是在00:00:00整点检测转下次通知的时间
	'''
	try:
		ntime = time.time()
		today = date.today()
		tomorrow = (today + timedelta(days=1)).strftime("%Y-%m-%d")
		timestr = tomorrow + " 00:00:00"
		rtime = time.mktime(time.strptime(timestr,"%Y-%m-%d %H:%M:%S"))
		timediff = rtime - ntime
		return int(timediff)
	except Exception as e:
		log_error("get_diff_time():"+str(e))

	
def clear_ipinfo_server():
	'''
	@osa_ipinfo中关于当天对服务器的已发邮件计数清零
	'''
	try:
		sql = "update osa_ipinfo set oNotifiedNum = 0"
		log_debug("clear_ipinfo_server()执行sql语句:"+sql)
		cmdtosql.update(sql)
	except Exception as e:
		log_error("clear_ipinfo_server():"+str(e))

def clear_monitor_item():
	'''
	@osa_monitors中关于当天项目的已发邮件计数清零
	'''
	try:
		sql = "update osa_monitors set oNotifiedNum = 0 "
		log_debug("clear_monitor_server()执行sql语句:"+sql)
		cmdtosql.update(sql)
	except Exception as e:
		log_error("clear_monitor_server():"+str(e))
		
def clearData():
	#ppath = DIRS['ROOT'] + 'ctrlpy/collect.py collect'
	
	pypath = DIRS['PYTHONPATH']
	while True:
		#清空osa_ipinfo中的告警次数
		clear_ipinfo_server()
		#清空osa_monitors中的告警次数
		clear_monitor_item()
		#获取当前时间到下一次00:00:00中间的时间差值（整数秒）
		timediff = get_diff_time()
		#休眠直到下一次的00:00:00
		time.sleep(timediff)

class collectDaemon(osaDamoClass.Daemon):
	def _run(self):
		clearData()

if __name__ == '__main__':	
	
	daemon = collectDaemon(DIRS['ROOT']+'osaClearData.pid')
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

