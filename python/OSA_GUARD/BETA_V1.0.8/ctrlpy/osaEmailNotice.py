#!/usr/bin/env python
#encoding=utf-8
'''
	Author: 	ows开源团队
	Description:	(监控转下次发的邮件。)
	Date: 		2011-08-20
'''
from datetime import *
import subprocess,time,signal,sys,datetime
from ctrlpy.etc.config import DIRS
from ctrlpy.lib import cmdtosql
from ctrlpy.lib.osaEmailLib import sendMail,get_mailto_users,get_email_byname
from ctrlpy.lib.osaEmailAlarm import *
from ctrlpy.lib import osaDamoClass
import sys
reload(sys) 
sys.setdefaultencoding('utf8')  

def get_diff_time():
	'''
	@计算当前时间与08:00:00之间的时间差
	@休眠时间差，下次正好是在08:00:00整点检测转下次通知的时间
	'''
	ntime = time.time()
	today = date.today()
	tomorrow = (today + timedelta(days=1)).strftime("%Y-%m-%d")
	timestr = tomorrow + " 08:00:00"
	rtime = time.mktime(time.strptime(timestr,"%Y-%m-%d %H:%M:%S"))
	timediff = rtime - ntime
	return int(timediff)

def get_notice_server():
	'''
	@获取osa_collect_alarm中的记录
	@条件：oIsNoticeNext == 1
	'''
	alarmtime = (date.today()).strftime("%Y-%m-%d") + " 08:00:00"
	sql = "select * from osa_collect_alarm where oIsNoticeNext = 1 and oNoticeNextTime = '"+str(alarmtime)+"'"
	result = cmdtosql.select(sql)
	if not result or result == None:
		return False
	return result


def server_get_ipinfo(ipid):
	'''
	@根据ipid获取服务器信息
	'''
	sql = "select * from osa_device where oIpid = "+str(ipid)
	result = cmdtosql.select(sql)
	if not result or result == None:
		return False
	return result	


def mailto_users_server(users,level,ipid):
	'''
	@根据报警类型和用户列表发送邮件
	'''
	rs = server_get_ipinfo(ipid)
	if rs == False:
		return False
	devname = rs[1]
	ipstr = rs[2]
	reason = ''
	content,subject = server_alarm_content(devname,ipstr,reason,level)
	userlist = get_mailto_users(users)
	for user in userlist:
		mailto = get_email_byname(user)
		sendMail(subject,content,mailto)
	

def send_email_server():
	'''
	@服务器报警中根据记录中的值发送邮件
	'''
	result = get_notice_server()
	if not result or result == False:
		return False
	for record in result:
		users = record[12]
		level = record[5]
		ipid = record[1]
		mailto_users_server(users,level,ipid)
		#更新osa_collect_alarm
		sql = "update osa_collect_alarm set oIsNotice=1 , oIsNoticeNext=0 where id ="+record[0]
		cmdtosql.update(sql)


def get_notice_item():
	'''
	@获取osa_monitor_alarm中的记录
	@条件：oIsNoticeNext == 1
	'''
	alarmtime = (date.today()).strftime("%Y-%m-%d") + " 08:00:00"
	sql = "select * from osa_monitor_alarm where oIsNoticeNext = 1 and oNoticeNextTime = '"+str(alarmtime)+"'"
	result = cmdtosql.select(sql)
	if not result or result == None:
		return False
	return result


def monitor_get_iteminfo(id):
	'''
	@获取监控项目的信息	
	'''
	sql = "select * from osa_monitors where id = "+str(id)
	result = cmdtosql.select(sql)
	if not result or result == None:
		return False
	return result	


def monitor_alarm_content(type,itemname,itemobject,level):
	'''
	@根据监控类型获取报警内容
	'''
	reason = ''
	if type == 'http':
		content,subject = website_alarm_content(itemname,itemobject,reason,level)
	elif type == 'ping':
		content,subject = ping_alarm_content(itemname,itemobject,reason,level)
	elif type == 'tcp':
		content,subject = tcp_alarm_content(itemname,itemobject,reason,level)
	elif type == 'udp':
		content,subject = udp_alarm_content(itemname,itemobject,reason,level)
	elif type == 'ftp':
		content,subject = ftp_alarm_content(itemname,itemobject,reason,level)
	elif type == 'dns':
		content,subject = dns_alarm_content(itemname,itemobject,reason,level)
	elif type == 'apache':
		content,subject = apache_alarm_content(itemname,itemobject,reason,level)
	elif type == 'nginx':
		content,subject = nginx_alarm_content(itemname,itemobject,reason,level)
	elif type == 'lighttpd':
		content,subject = lighttpd_alarm_content(itemname,itemobject,reason,level)
	elif type == 'memcache':
		content,subject = memcache_alarm_content(itemname,itemobject,reason,level)
	elif type == 'mongodb':
		content,subject = mongodb_alarm_content(itemname,itemobject,reason,level)
	elif type == 'redis':
		content,subject = redis_alarm_content(itemname,itemobject,reason,level)
	elif type == 'mysql':
		content,subject = mysql_alarm_content(itemname,itemobject,reason,level)
	elif type == 'custom':
		content,subject = custom_alarm_content(itemname,itemobject,reason,level)
	return content,subject
	

	
	
def mailto_users_item(users,type,level,itemid):
	'''
	@根据报警类型和用户列表发送邮件
	@项目报警类型跟内容暂时还没确定，暂时不能使用
	'''
	#content = choose_template(type)
	
	rs = monitor_get_iteminfo(itemid)
	if rs == False:
		return False
	itemname = rs[1]
	itemobject = rs[2]
	content,subject = monitor_alarm_content(type,itemname,itemobject,level)	
	userlist = get_mailto_users(users)
	for user in userlist:
		mailto = get_email_byname(user)
		sendMail(subject,content,mailto)


def send_email_item():
	'''
	@项目报警中根据记录中的值发送邮件
	'''
	result = get_notice_server()
	if not result or result == False:
		return False

	for record in result:
		level = record[5]
		alarmType = record[6]
		users = record[12]
		itemid = record[1]
		mailto_users_item(users,alarmType,level,itemid)
		#更新osa_collect_alarm
		sql = "update osa_monitor_alarm set oIsNotice=1 , oIsNoticeNext=0 where id ="+record[0]
		cmdtosql.update(sql)	

		
def noticeEmail():
	#ppath = DIRS['ROOT'] + 'ctrlpy/collect.py collect'
	
	pypath = DIRS['PYTHONPATH']
	while True:
		#服务器监控报警
		send_email_server()
		#监控项目监控报警
		send_email_item()
		#获取当前时间到下一次08:00:00中间的时间差值（整数秒）
		timediff = get_diff_time()
		#休眠直到下一次的08:00:00
		time.sleep(timediff)


class collectDaemon(osaDamoClass.Daemon):
	def _run(self):
		noticeEmail()


if __name__ == '__main__':	
	
	daemon = collectDaemon(DIRS['ROOT']+'osaEmailNotice.pid')
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

