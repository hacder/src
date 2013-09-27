#!/usr/bin/python
#encoding=utf-8
'''
	 Autor: osa开源团队
	 Description:监控项目（包含报警与数据采集）、
	 create date：2012-08-30
'''

import sys,os,signal,simplejson,time,multiprocessing
from threading import Thread
from ctrlpy.etc.config import MONITOR,DIRS
from ctrlpy.lib import cmdtosql
from ctrlpy.lib.osaMonitorLib import *
from ctrlpy.lib import osaDamoClass
from ctrlpy.lib.osaLogLib import *
reload(sys) 
sys.setdefaultencoding('utf8')  

def monitor_function_choose(itemtype):
	'''
	@osaMonitor 根据监控项目类型来选择要执行的函数方法
	'''
	if itemtype == 'http':
		return monitor_website_index
	elif itemtype == 'ping':
		return monitor_ping_index
	elif itemtype == 'tcp':
		return monitor_tcp_index
	elif itemtype == 'udp':
		return monitor_udp_index
	elif itemtype == 'dns':
		return monitor_dns_index
	elif itemtype == 'ftp':
		return monitor_ftp_index
	elif itemtype == 'apache':
		return monitor_apache_index
	elif itemtype == 'nginx':
		return monitor_nginx_index
	elif itemtype == 'lighttpd':
		return monitor_lighttpd_index
	elif itemtype == 'mysql':
		return monitor_mysql_index
	elif itemtype == 'mongodb':
		return monitor_mongodb_index
	elif itemtype == 'redis':
		return monitor_redis_index
	elif itemtype == 'memcache':
		return monitor_memcache_index
	elif itemtype == 'custom':#自定义监控
		return monitor_custom_index
	else:
		return None
	
	
def monitor_index():
	'''
	@osaMonitor 入口执行函数
	'''
	while True:#条件为真，一直监控
		#获取需要监控的项目基本信息
		iteminfo = monitor_get_iteminfos()
		#多进程对监控项目进行监控
		qitem = monitor_item_inQueue(iteminfo)
		del iteminfo
		if multiprocessing.cpu_count() < MONITOR['maxprocess']:
			MONITOR['maxprocess'] = multiprocessing.cpu_count()
		p = multiprocessing.Pool(processes=MONITOR['maxprocess'])
		while True:
			if qitem.qsize() == 0:
				break			
			
			itemlist = monitor_item_outQueue(qitem)
			if itemlist == None:
				break
			#更新项目下次监控时间
			monitor_item_updateTime(itemlist)
			#根据类型选择执行的函数
			funcname = monitor_function_choose(itemlist[3])
			#日志记录 (这部分还在规划中)
			try:
								
				p.apply_async(funcname,(itemlist, ))
				
			except Exception as e:
				log_error('monitor_index() process failed:'+str(e))	
			
				
		p.close()
		p.join()
		#休眠一段时间,缓解数据库压力
		time.sleep(0.3)
		

class osaMonitor(osaDamoClass.Daemon):
	def _run(self):
		monitor_index()

		
if __name__=='__main__':
	#monitor_index()
	daemon=osaMonitor(DIRS['ROOT']+'osaMonitor.pid')
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

