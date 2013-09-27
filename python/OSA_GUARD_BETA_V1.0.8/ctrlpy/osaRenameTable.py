#!/usr/bin/env python
#encoding=utf-8
'''
	Author:         osa开源团队
	Description:    (对服务器与项目监控报警次数清零)
	Date:           2011-12-18
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


def osa_rename_table():
	'''
	@对需要分表的表进行重命名
	'''
	suffix = compute_table_suffix()
	if suffix == False:
		return False
	else:
		m_alarm = "osa_monitor_alarm_"+str(suffix)
		m_record = "osa_monitor_record_"+str(suffix)
		s_alarm = "osa_collect_alarm_"+str(suffix)
		s_record = "osa_collect_data_"+str(suffix)
		sql = "rename table osa_monitor_record to "+str(m_record)+",osa_monitor_alarm to "+str(m_alarm)+",osa_collect_alarm to "+str(s_alarm)+",osa_collect_data to "+str(s_record)
		cmdtosql.execsql(sql)
		osa_manage_table(m_alarm,m_record,s_alarm,s_record)
		osa_create_table()


def osa_create_table():
	'''
	@ create table
	'''
	create_table_salarm()
	create_table_srecord()
	create_table_malarm()
	create_table_mrecord()


def osa_manage_table(m_alarm,m_record,s_alarm,s_record):
	'''
	#insert into osa_manage_table
	'''
	now = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime());
	sql = "insert into osa_table_manage values(null,'"+m_alarm+"','"+now+"','malarm'),(null,'"+m_record+"','"+now+"','mdata'),(null,'"+s_alarm+"','"+now+"','salarm'),(null,'"+s_record+"','"+now+"','sdata')"
	#print(sql)
	cmdtosql.execsql(sql)


def compute_table_suffix():
	'''
	@根据时间来计算新表的后缀
	'''
	today = str(date.today())
	year = today[0:4]
	today = today[5:]
	if str(today) == '04-01':
		return year+'04' 
	elif str(today) == '07-01':
		return year+'07'
	elif str(today) == '10-01':
		return year+'10'
	elif str(today) == '01-01':
		return year+'01'
	else:
		return False



def create_table_salarm():
	'''
	#创建新的osa_collect_alarm 表
	'''
	sql = "CREATE TABLE `osa_collect_alarm`(`id` int(20) NOT NULL AUTO_INCREMENT,`oIpid` int(20) NOT NULL,`oAlarmText` text,`oAlarmTime` timestamp NULL DEFAULT NULL,`oAlarmLevel` tinyint(4) DEFAULT NULL,`oAlarmType` varchar(50) DEFAULT NULL,`oSnapShot` varchar(200) DEFAULT NULL,`oIsNotice` tinyint(4) DEFAULT '0',`oIsRead` tinyint(4) DEFAULT '0',`oFaultTime` int(20) DEFAULT NULL,`oIsNoticeNext` tinyint(4) DEFAULT '0',`oNoticeNextTime` timestamp NULL DEFAULT NULL,`oNoticeNextUsers` varchar(500) DEFAULT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8"

	cmdtosql.execsql(sql)



def create_table_srecord():
	'''
	#创建新的osa_collect_data表
	'''
	sql = "CREATE TABLE `osa_collect_data` (`id` int(20) NOT NULL AUTO_INCREMENT,`oIpid` int(20) NOT NULL,`oCollectTime` timestamp NULL DEFAULT NULL,`oCollectData` varchar(5000) DEFAULT NULL,`oReplayTime` double DEFAULT NULL,`oStatus` varchar(20) DEFAULT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8"
	
	cmdtosql.execsql(sql)


def create_table_malarm():
	'''
	#创建新的osa_monitor_alarm表
	'''
	sql = "CREATE TABLE `osa_monitor_alarm` (`id` int(20) NOT NULL AUTO_INCREMENT,`oItemid` int(20) NOT NULL,`oMonName` varchar(100) DEFAULT NULL,`oAlarmText` text,`oAlarmTime` timestamp NULL DEFAULT NULL,`oAlarmLevel` tinyint(4) NOT NULL,`oAlarmType` varchar(20) DEFAULT NULL,`oSnapShot` varchar(200) DEFAULT NULL,`oIsNotice` tinyint(4) DEFAULT '0',`oIsRead` tinyint(4) DEFAULT '0',`oIsNoticeNext` tinyint(4) DEFAULT '0',`oNoticeNextTime` timestamp NULL DEFAULT NULL,`oNoticeNextUsers` varchar(500) DEFAULT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8"
	
	cmdtosql.execsql(sql)



def create_table_mrecord():
	'''
	#创建新的osa_monitor_record表
	'''
	sql = "CREATE TABLE `osa_monitor_record` (`id` int(20) NOT NULL AUTO_INCREMENT,`oItemid` int(20) NOT NULL,`oMonTime` timestamp NULL DEFAULT NULL,`oMonResult` text,`oReplayTime` double DEFAULT NULL,`oStatus` varchar(20) DEFAULT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8"

	cmdtosql.execsql(sql)





def renameTable():
	'''
	#分表逻辑入口
	'''
	while True:
		osa_rename_table()
		#获取当前时间到下一次00:00:00中间的时间差值（整数秒）
                timediff = get_diff_time()
                #休眠直到下一次的00:00:00
                time.sleep(timediff)


class renameDaemon(osaDamoClass.Daemon):
	
	def _run(self):
		renameTable()	



#################################测试分表逻辑####################################


if __name__ == '__main__':
	'''
	@  
	'''
	daemon = renameDaemon(DIRS['ROOT']+'osaRenameTable.pid')
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
