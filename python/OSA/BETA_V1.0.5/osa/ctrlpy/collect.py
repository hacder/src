#!/usr/bin/env python
#encoding=utf-8

'''
	Author: 	ows开源团队
	Description:	(采集监控数据
			  目前仅支持脚本采集)
	Date: 		2011-07-20
'''
from multiprocessing import Process,Queue,Pool
from ctrlpy.lib import cmdtosql
from ctrlpy.etc.config import PNUM,DIRS
from ctrlpy.lib.osaUtil import save_log
import os,time,sys,signal


'''
	多进程并发采集监控数据
'''
def _get_monitime():
        '''
           获取采集数据入库时间
        '''
	sql = 'SELECT oMonTime FROM osa_monitor order by id desc limit 1'
	r = cmdtosql.select(sql)
	if not r:
	        lasttime =  '1979-01-01 00:00:00'
	else:
		lasttime = str((r[0])[0])
		
	ltime = time.mktime(time.strptime(lasttime, "%Y-%m-%d %H:%M:%S"))
	ntime = time.time()
	if (ntime - ltime) > 600:
		montime = cmdtosql._get_time(1)
	#如果当前时间小于数据库的时间
	elif (ntime - ltime) <= 0:
		sys.exit()
	else:
		Ltime = time.localtime( ltime + 300 )
		montime = time.strftime('%Y-%m-%d %H:%M:%S', Ltime)
	return montime


def mut_process(cmd,q,monitime):
	if cmd.isspace() or not cmd:
		cmd = 'SYSTEM_RUN_COMMAND!{"mon_all_stat":""}' 
	for x in xrange(PNUM['num']):
		ip = q.get()
		try:
			signal.signal(signal.SIGCHLD,signal.SIG_IGN)
			i = Process(target=cmdtosql.insertMonitor, args = [ip,cmd,monitime])
			i.start()
						
		except Exception as e:			
			save_log('ERROR','Process Exception:' + str(e))
		if q.qsize() == 0:
			break

def allDatatoDb(cmd,q,monitime):
	while True:
		mut_process(cmd,q,monitime)
		if q.qsize() == 0:
			return 'over'

def collectDo():

	#定义采集指令

	cmd = ''
	starttime = time.time()
	#获取监控时间
	monitime = _get_monitime()
	save_log('DEBUG',monitime + ' collect!')	
	#获取IP地址列表 
	iplist = cmdtosql.getIpList()
	save_log('INFO','total server num is:'+str(len(iplist)))
	#准备队列
	q = Queue()
	#IP地址入队
	for ip in iplist:
        	q.put(ip)
	
	#采集所有数据
	try:		
		res=allDatatoDb(cmd,q,monitime)
	except Exception as collerror:
		save_log('ERROR','collected error :'+str(collerror))
	ltime = time.mktime(time.strptime(_get_monitime(), "%Y-%m-%d %H:%M:%S"))
	mtime = ltime - time.time()
	ptime = time.time() - starttime
	save_log('DEBUG','collected time used :'+str(ptime))
	save_log('INFO','---------------------------------------------')
	#等待所有子进程执行完毕
	time.sleep(100)
	sys.exit()

if __name__ == '__main__' and sys.argv[1] == 'collect':
	lock =  DIRS['CFG_ROOT']+'py.table.lock'
	os.system('rm -f ' + lock)
	collectDo()
