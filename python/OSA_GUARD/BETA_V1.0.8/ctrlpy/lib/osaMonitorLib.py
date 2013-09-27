#!/usr/bin/python
#encoding=utf-8
'''
	 Autor: osa开源团队
	 Description:监控项目 辅助模块
	 create date：2012-08-30
'''

import sys,os,signal,simplejson,Queue,time
from threading import Thread
from multiprocessing import Process
from ctrlpy.lib.osaEmailLib import *
from ctrlpy.lib.osaEmailAlarm import *
from ctrlpy.lib.osaEmailTemplate import choose_template
from ctrlpy.etc.config import MONITOR,DIRS
from ctrlpy.lib import cmdtosql
from ctrlpy.lib.osaWebsiteLib import *
from ctrlpy.lib.osaCustomLib import *
from ctrlpy.lib.osaApacheLib import *
from ctrlpy.lib.osaPing import *
from ctrlpy.lib.osaMongodbLib import *
from ctrlpy.lib.osaRedisLib import *
from ctrlpy.lib.osaTcpLib import *
from ctrlpy.lib.osaFtpLib import *
from ctrlpy.lib.osaUdpLib import *
from ctrlpy.lib.osaDnsLib import *
from ctrlpy.lib.osaNginxLib import *
from ctrlpy.lib.osaLighttpdLib import *
from ctrlpy.lib.osaMysqlLib import *
from ctrlpy.lib.osaMemcacheLib import *
from ctrlpy.lib.osaLogLib import *


def monitor_get_lastReason(itemid):
	'''
	@获取项目恢复提醒上一次的原因
	'''
	try:
		sql = "select oAlarmText from osa_monitor_alarm where oItemid="+str(itemid)+" order by id desc limit 1"
		result = cmdtosql.select(sql)
		if result:
			return result[0][0]
		else:
			return ''
	except Exception as e:
		log_error("monitor_get_lastReason():"+str(e))
	

def monitor_get_iteminfos():
	'''
	@osaMonitor 获取当前需要监控的项目信息 
	@满足时间条件和 oIsStop = 0
	'''
	try :
		time_now = cmdtosql._get_time(1)
		sql = "select * from osa_monitors where (oNextCheckTime is null or oNextCheckTime <='"+time_now+"') and oIsStop = 0 "
		log_debug("monitor_get_iteminfos()执行sql语句:"+str(sql))
		return cmdtosql.select(sql)
	except Exception as e:
		log_error("monitor_get_iteminfos():"+str(e))

	
def monitor_item_inQueue(iteminfo):
	'''
	@osaMonitor 监控项目信息入队列
	'''
	qitem = Queue.Queue()
	for info in iteminfo:
		qitem.put(info)
	return qitem

	
def monitor_item_outQueue(qitem):
	'''
	@osaMonitor 监控项目信息出队列
	'''
	try:	
		if qitem.qsize() != 0:
			itemlist = qitem.get(block = False)
		else:
			itemlist = None
		return itemlist
	except Exception as e:
		return None
	
	
def monitor_item_nextTime(itemlist):
	'''
	@osaMonitor 计算项目下次监控时间
	'''
	try:
		begintime = itemlist[13]
	except Exception as e:
		log_error('monitor_item_nextTime(itemlist):'+str(e))
		#begintime =  itemlist[10] 	
	if begintime == None:
		begintime =  itemlist[10] 
	now_sec = time.mktime(time.strptime(str(begintime), "%Y-%m-%d %H:%M:%S"))	
	midtime = int(time.mktime(time.localtime()) - now_sec) % int(itemlist[4])	
	next_sec = time.mktime(time.localtime()) + itemlist[4] - midtime
	nextTime = time.localtime(next_sec)	
	return time.strftime('%Y-%m-%d %H:%M:%S', nextTime)
	
	
def monitor_item_updateTime(itemlist):
	'''
	@osaMonitor 更新项目下次监控时间
	'''
	itemlist = list(itemlist)
	nextTime = monitor_item_nextTime(itemlist)
	sql = "update osa_monitors set oNextCheckTime='"+nextTime+"' where id="+str(itemlist[0])
	try:
		time.sleep(1)
		log_debug("monitor_item_updateTime()执行sql语句:"+str(sql))
		cmdtosql.update(sql)
	except Exception as e:
		log_error("monitor_item_updateTime():"+str(e))


def mail_status_itemalarm(iteminfo,userlist,content,maxnum,level,subject):
	'''
	@osaMonitor 监控项目报警邮件状态
	@返回isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime
	'''
	isNoticeNow = isNoticeNext =0
	noticeNextUsers = ''
	noticeNextTime = '0000-00-00 00:00:00'	

	for user in userlist:
		isEmail = is_notice_item(iteminfo,user,maxnum,level)
		if isEmail == 'not-send':#不发送
			pass
		elif isEmail == 'now-send':#立即发送
			isNoticeNow += 1 #表示已通知
			mailto = get_email_byname(user)
			#content = choose_template(template)
			try:
				sendMail(subject,content,mailto)
			except Exception as e:
				log_error('SMTP连接邮件服务器失败,可能原因:'+str(e))			
		else:#转下次发送
			isNoticeNext += 1
			noticeNextUsers += user+',' 
			noticeNextTime = isEmail
	return isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime
		
################################################## osaMonitor 公用函数 完成 ##########################################
	
		
def update_iteminfo_remind(isNoticeNow,isNoticeNext,iteminfo):
	'''
	@osaMonitor 恢复通知时根据isNoticeNow,isNoticeNext更新osa_monitors表
	@oNotifiedNum:已发送告警数量，oNotiNum:连续告警次数
	@思路，当立即发送通知和转下次通知时，标识该item今天已发送的通知+1
	'''
	try:
		oNotifiedNum = ""
		if isNoticeNow>0 or isNoticeNext >0:
			oNum=int(iteminfo[19])+1
			sql = "update osa_monitors set oStatus='1' ,oFaultTime='0000-00-00 00:00:00' ,oNotiNum=0 ,oNotifiedNum="+str(oNum)+",oRepeatedNum=0,oIsEmail='0' where id="+str(iteminfo[0])	
		else:
			sql = "update osa_monitors set oStatus='1' ,oFaultTime='0000-00-00 00:00:00' ,oNotiNum=0 ,oRepeatedNum=0,oIsEmail='0' where id="+str(iteminfo[0])
		log_debug("update_iteminfo_remind()执行sql语句:"+str(sql))
		cmdtosql.update(sql)	
	except Exception as e:
		log_error("update_iteminfo_remind()"+str(e))
	

	
def update_iteminfo_except(isNoticeNow,isNoticeNext,iteminfo):
	'''
	@osaMonitor 服务异常报警更新osa_monitors 记录
	'''
	try:
		oNotifiedNum = ""
		if str(iteminfo[17]) == '1':#表示上次正常，错误时间从现在开始
			faultTime = cmdtosql._get_time(1)
		else:#表示上次异常，错误时间从过去开始
			faultTime = iteminfo[18]
		if isNoticeNow>0 or isNoticeNext >0:
			oNum = int(iteminfo[19])+1
			notiNum = int(iteminfo[20])+1
			sql = "update osa_monitors set oStatus='0' ,oFaultTime='"+str(faultTime)+"',oNotifiedNum="+str(oNum)+",oNotiNum="+str(notiNum)+",oIsEmail='1'  where id="+str(iteminfo[0])
		else:
			sql = "update osa_monitors set oStatus='0' ,oFaultTime='"+str(faultTime)+"',oIsEmail='1' where id="+str(iteminfo[0])
		log_debug("update_iteminfo_except()执行sql语句:"+str(sql))
		cmdtosql.update(sql)	
	except Exception as e:
		log_error("update_iteminfo_except():"+str(e))
		
		
def insert_itemalarm_remind(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,type):
	'''
	@osaMonitor 恢复通知根据通知方式来更新osa_monitor_alarm表
	'''
	try:
		isNotice = 0
		if isNoticeNow == 0 and isNoticeNext == 0 and iteminfo[22] == str(0):
			return False
		else:
			alarmTime = cmdtosql._get_time(1)
			itemid = iteminfo[0]
			reason = monitor_get_lastReason(itemid)
			
			ntext = '项目恢复正常'
			if reason == '' or reason == '项目恢复正常':
				ntext = '不稳定'
			
			##再添加一个服务器故障时间
			startTime = str(iteminfo[18])
			#计算服务器故障时间，并写入表osa_monitor_alarm
			if startTime == 'None' or startTime == '':
				startTime = cmdtosql._get_time(1)
			faultTime = time.time()-time.mktime(time.strptime(startTime,"%Y-%m-%d %H:%M:%S"))
			if isNoticeNow > 0:
				isNotice =1
			
				
			if isNoticeNext > 0:
				notice_sql = "insert into osa_monitor_alarm(oItemid,oMonName,oAlarmText,oAlarmTime,oAlarmLevel,oAlarmType,oIsNotice,oIsRead,oFaultTime,oIsNoticeNext,oNoticeNextTime,oNoticeNextUsers) \
								values("+str(iteminfo[0])+",'"+str(iteminfo[1])+"','"+str(ntext)+"','"+str(alarmTime)+"','4','"+str(type)+"',"+str(isNotice)+",0,"+str(faultTime)+",1,"+str(noticeNextTime)+",'"+str(noticeNextUsers.strip(','))+"')"
			else:
				notice_sql = "insert into osa_monitor_alarm(oItemid,oMonName,oAlarmText,oAlarmTime,oAlarmLevel,oAlarmType,oIsNotice,oIsRead,oFaultTime) \
								values("+str(iteminfo[0])+",'"+str(iteminfo[1])+"','"+str(ntext)+"','"+str(alarmTime)+"','4','"+str(type)+"',"+str(isNotice)+",0,"+str(faultTime)+")"
			log_debug("insert_itemalarm_remind()执行sql语句:"+str(notice_sql))
			cmdtosql.execsql(notice_sql)		
	except Exception as e:
		log_error("insert_itemalarm_remind():"+str(e))


def insert_itemalarm_except(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,alarminfo):
	'''
	@osaMonitor 发生异常时更新osa_monitor_alarm表
	'''
	try:
		isNotice = 0
		alarmTime = cmdtosql._get_time(1)
		if isNoticeNow == 0 and isNoticeNext == 0:
			return False
		else:
			if isNoticeNow > 0:
				isNotice =1
			if isNoticeNext > 0:
				notice_sql = "insert into osa_monitor_alarm(oItemid,oMonName,oAlarmText,oAlarmTime,oAlarmLevel,oAlarmType,oIsNotice,oIsRead,oIsNoticeNext,oNoticeNextTime,oNoticeNextUsers) \
								values("+str(iteminfo[0])+",'"+str(iteminfo[1])+"','"+str(alarminfo['text'])+"','"+str(alarmTime)+"','"+str(alarminfo['level'])+"','"+str(alarminfo['type'])+"',"+str(isNotice)+",0,1,"+str(noticeNextTime)+",'"+str(noticeNextUsers.strip(','))+"')"
			else:
				notice_sql = "insert into osa_monitor_alarm(oItemid,oMonName,oAlarmText,oAlarmTime,oAlarmLevel,oAlarmType,oIsNotice,oIsRead) \
								values("+str(iteminfo[0])+",'"+str(iteminfo[1])+"','"+str(alarminfo['text'])+"','"+str(alarmTime)+"','"+str(alarminfo['level'])+"','"+str(alarminfo['type'])+"',"+str(isNotice)+",0)"
			log_debug("insert_itemalarm_except()执行sql语句:"+str(notice_sql))
			cmdtosql.execsql(notice_sql)
	except Exception as e:
		log_error("insert_itemalarm_except():"+str(e))



def monitor_insert_itemdata(itemid,result='',replayTime=0,status='正常'):
	'''
	@osaMonitor 插入osa_monitor_record记录
	@默认数据：恢复正常数据
	'''
	try:
		montime=cmdtosql._get_time(1)
		sql = "insert into osa_monitor_record(oItemid,oMonTime,oMonResult,oReplayTime,oStatus) values("+str(itemid)+",'"+str(montime)+"','"+str(result)+"','"+str(replayTime)+"','"+str(status)+"')"
		log_debug("monitor_insert_itemdata()执行sql语句:"+str(sql))
		cmdtosql.execsql(sql)
	except Exception as e:
		log_error("monitor_insert_itemdata():"+str(e))

#####################################分割线 -----------项目监控过程中 数据库操作----------- 分割线############################

		
def monitor_website_index(iteminfo):
	'''
	@osaMonitor 网页存活入口函数
	'''
	try:
		itemconfig = eval(iteminfo[8])
		url = iteminfo[2]
		isAlarm,result,reason,level = website_alive_check(url,itemconfig)
	
		if isAlarm == '0':#表示不报警
			if str(iteminfo[17]) == '0':#表示上次报警了，是否恢复报警
				#根据通知对象获取通知用户
				userlist = get_mailto_users(iteminfo[9])
				#获取上次报警原因
				lastReason = monitor_get_lastReason(iteminfo[0])
				#获取发送邮件内容
				mailContent ,subject= website_alarm_content(iteminfo[1],iteminfo[2],lastReason,4)
				#或立即发送,转下次发送信息
				isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,4,subject)
				#恢复通知 状态变化,更新osa_monitors记录
				update_iteminfo_remind(isNoticeNow,isNoticeNext,iteminfo)
				#恢复通知 状态变化,更新osa_monitor_alarm记录
				insert_itemalarm_remind(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,type="http")
			#插入osa_monitor_data记录
			monitor_insert_itemdata(iteminfo[0],result)
		else:#报警：1.访问出错，2.高级项不符合
			#alarmText = simplejson.dumps(result)
			alarminfo = {'text':reason,'level':level,'type':'http'}
			#根据通知对象获取通知用户
			userlist = get_mailto_users(iteminfo[9])
			#获取发送邮件内容
			mailContent ,subject= website_alarm_content(iteminfo[1],iteminfo[2],reason,level)
			#或立即发送,转下次发送信息
			isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,1,subject)
			#异常通知 ,更新osa_monitors记录
			update_iteminfo_except(isNoticeNow,isNoticeNext,iteminfo)
			#异常通知 ,插入osa_monitor_alarm记录
			insert_itemalarm_except(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,alarminfo)
			#插入osa_monitor_record记录
			monitor_insert_itemdata(iteminfo[0],result,0,'异常')
	except Exception as e:
		log_error("monitor_website_index():"+str(e))	 


def monitor_ping_index(iteminfo):
	'''
	@osaMonitor ping入口函数
	'''
	try:
		url = iteminfo[2]
		isAlarm,result = ping_alive_check(url)
		if isAlarm == '0':#表示不报警
			if str(iteminfo[17]) == '0':#表示上次报警了，是否恢复报警
				#根据通知对象获取通知用户
				userlist = get_mailto_users(iteminfo[9])
				#获取邮件内容
				mailContent,subject = ping_alarm_content(iteminfo[1],iteminfo[2],4)
				#或立即发送,转下次发送信息
				isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,4,subject)
				#恢复通知 状态变化,更新osa_monitors记录
				update_iteminfo_remind(isNoticeNow,isNoticeNext,iteminfo)
				#恢复通知 状态变化,更新osa_monitor_alarm记录
				insert_itemalarm_remind(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,type="ping")
			#插入osa_monitor_data记录
			monitor_insert_itemdata(iteminfo[0],result)
		else:#报警：1.ping 不通
			
			alarminfo = {'text':'数据包全部丢失','level':1,'type':'ping'}
			#根据通知对象获取通知用户
			userlist = get_mailto_users(iteminfo[9])
			#获取邮件内容
			mailContent,subject = ping_alarm_content(iteminfo[1],iteminfo[2],1)
			#或立即发送,转下次发送信息
			isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,1,subject)
			#异常通知 ,更新osa_monitors记录
			update_iteminfo_except(isNoticeNow,isNoticeNext,iteminfo)
			#异常通知 ,插入osa_monitor_alarm记录
			insert_itemalarm_except(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,alarminfo)
			#插入osa_monitor_record记录
			monitor_insert_itemdata(iteminfo[0],result,0,'异常')
	except Exception as e:
		log_error("monitor_ping_index():"+str(e))	

def monitor_tcp_index(iteminfo):
	'''
	@osaMonitor tcp 入口函数
	'''
	try:
		ip = iteminfo[2]
		itemconfig = eval(iteminfo[8])
		port = itemconfig['port']
		isAlarm,result = tcp_connect_check(ip,port)
		if isAlarm == True:#表示不报警
			if str(iteminfo[17]) == '0':#表示上次报警了，是否恢复报警
				#根据通知对象获取通知用户
				userlist = get_mailto_users(iteminfo[9])
				#获取邮件内容
				mailContent ,subject= tcp_alarm_content(iteminfo[1],iteminfo[2],4)
				#或立即发送,转下次发送信息
				isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,4,subject)
				#恢复通知 状态变化,更新osa_monitors记录
				update_iteminfo_remind(isNoticeNow,isNoticeNext,iteminfo)
				#恢复通知 状态变化,更新osa_monitor_alarm记录
				insert_itemalarm_remind(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,type="tcp")
			#插入osa_monitor_data记录
			monitor_insert_itemdata(iteminfo[0],result)
		else:#报警：1.ping 不通
			alarminfo = {'text':'TCP连接超时','level':1,'type':'tcp'}
			#根据通知对象获取通知用户
			userlist = get_mailto_users(iteminfo[9])
			#获取邮件内容
			mailContent,subject = tcp_alarm_content(iteminfo[1],iteminfo[2],1)
			#或立即发送,转下次发送信息
			isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,1,subject)
			#异常通知 ,更新osa_monitors记录
			update_iteminfo_except(isNoticeNow,isNoticeNext,iteminfo)
			#异常通知 ,插入osa_monitor_alarm记录
			insert_itemalarm_except(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,alarminfo)
			#插入osa_monitor_record记录
			monitor_insert_itemdata(iteminfo[0],result,0,'异常')
	except Exception as e:
		log_error("monitor_tcp_index():"+str(e))
	
def monitor_udp_index(iteminfo):
	'''
	@osaMonitor udp 入口函数
	'''
	try:
		ip = iteminfo[2]
		itemconfig = eval(iteminfo[8])
		port = itemconfig['port']
		isAlarm,result = udp_connect_check(ip,port)
		if isAlarm == True:#表示不报警
			if str(iteminfo[17]) == '0':#表示上次报警了，是否恢复报警
				#根据通知对象获取通知用户
				userlist = get_mailto_users(iteminfo[9])
				#获取邮件内容
				mailContent,subject = udp_alarm_content(iteminfo[1],iteminfo[2],4)
				#或立即发送,转下次发送信息
				isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,4,subject)
				#恢复通知 状态变化,更新osa_monitors记录
				update_iteminfo_remind(isNoticeNow,isNoticeNext,iteminfo)
				#恢复通知 状态变化,更新osa_monitor_alarm记录
				insert_itemalarm_remind(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,type="udp")
			#插入osa_monitor_data记录
			monitor_insert_itemdata(iteminfo[0],result)
		else:#报警：1.ping 不通
			alarminfo = {'text':'UDP连接超时','level':1,'type':'udp'}
			#根据通知对象获取通知用户
			userlist = get_mailto_users(iteminfo[9])
			#获取mail内容
			mailContent,subject = udp_alarm_content(iteminfo[1],iteminfo[2],1)
			#或立即发送,转下次发送信息
			isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,1,subject)
			#异常通知 ,更新osa_monitors记录
			update_iteminfo_except(isNoticeNow,isNoticeNext,iteminfo)
			#异常通知 ,插入osa_monitor_alarm记录
			insert_itemalarm_except(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,alarminfo)
			#插入osa_monitor_record记录
			monitor_insert_itemdata(iteminfo[0],result,0,'异常')
	except Exception as e:
		log_error("monitor_udp_index():"+str(e))

	
def monitor_ftp_index(iteminfo):
	'''
	@osaMonitor ftp入口函数
	'''
	try:
		host = iteminfo[2]
		itemconfig = eval(iteminfo[8])
		isAlarm,result = ftp_connect_check(host,itemconfig)
		if isAlarm == True:#表示不报警
			if str(iteminfo[17]) == '0':#表示上次报警了，是否恢复报警
				#根据通知对象获取通知用户
				userlist = get_mailto_users(iteminfo[9])
				#获取邮件内容
				mailContent,subject = ftp_alarm_content(iteminfo[1],iteminfo[2],4)
				#或立即发送,转下次发送信息
				isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,4,subject)
				#恢复通知 状态变化,更新osa_monitors记录
				update_iteminfo_remind(isNoticeNow,isNoticeNext,iteminfo)
				#恢复通知 状态变化,更新osa_monitor_alarm记录
				insert_itemalarm_remind(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,type="ftp")
			#插入osa_monitor_data记录
			monitor_insert_itemdata(iteminfo[0],result)
		else:#报警：1.ping 不通
			alarminfo = {'text':'FTP连接超时','level':1,'type':'ftp'}
			#根据通知对象获取通知用户
			userlist = get_mailto_users(iteminfo[9])
			#获取邮件内容
			mailContent,subject = ftp_alarm_content(iteminfo[1],iteminfo[2],1)
			#或立即发送,转下次发送信息
			isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,1,subject)
			#异常通知 ,更新osa_monitors记录
			update_iteminfo_except(isNoticeNow,isNoticeNext,iteminfo)
			#异常通知 ,插入osa_monitor_alarm记录
			insert_itemalarm_except(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,alarminfo)
			#插入osa_monitor_record记录
			monitor_insert_itemdata(iteminfo[0],result,0,'异常')
	except Exception as e:
		log_error("monitor_ftp_index():"+str(e))	
		

def monitor_dns_index(iteminfo):
	'''
	@osaMonitor dns 入口函数
	'''
	try:
		host = iteminfo[2]
		itemconfig = eval(iteminfo[8])
		isAlarm ,reason,result,level = dns_server_check(host,itemconfig)
		if isAlarm == "1":#表示不报警
			if str(iteminfo[17]) == '0':#表示上次报警了，是否恢复报警
				#根据通知对象获取通知用户
				userlist = get_mailto_users(iteminfo[9])
				#获取上次报警原因
				lastReason = monitor_get_lastReason(iteminfo[0])
				#获取邮件内容
				mailContent,subject = dns_alarm_content(iteminfo[1],iteminfo[2],lastReason,level)
				#或立即发送,转下次发送信息
				isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,level,subject)
				#恢复通知 状态变化,更新osa_monitors记录
				update_iteminfo_remind(isNoticeNow,isNoticeNext,iteminfo)
				#恢复通知 状态变化,更新osa_monitor_alarm记录
				insert_itemalarm_remind(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,type="dns")
			#插入osa_monitor_data记录
			monitor_insert_itemdata(iteminfo[0],result)
		else:#报警：1.ping 不通
			alarminfo = {'text':reason,'level':level,'type':'dns'}
			#根据通知对象获取通知用户
			userlist = get_mailto_users(iteminfo[9])
			mailContent,subject = dns_alarm_content(iteminfo[1],iteminfo[2],reason,level)
			#或立即发送,转下次发送信息
			isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,level,subject)
			#异常通知 ,更新osa_monitors记录
			update_iteminfo_except(isNoticeNow,isNoticeNext,iteminfo)
			#异常通知 ,插入osa_monitor_alarm记录
			insert_itemalarm_except(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,alarminfo)
			#插入osa_monitor_record记录
			monitor_insert_itemdata(iteminfo[0],result,0,'异常')
	except Exception as e:
		log_error("monitor_dns_index():"+str(e))	
	
def monitor_apache_index(iteminfo):
	'''
	@osaMonitor apache 入口函数
	'''
	try:
		url = iteminfo[2]
		itemconfig = eval(iteminfo[8])
		isAlarm ,reason,result,level = apache_status_analyze(url,itemconfig)
		if isAlarm == "1":#表示不报警
			if str(iteminfo[17]) == '0':#表示上次报警了，是否恢复报警
				#根据通知对象获取通知用户
				userlist = get_mailto_users(iteminfo[9])
				#获取lastReason
				lastReason = monitor_get_lastReason(iteminfo[0])
				#获取邮件内容
				mailContent,subject = apache_alarm_content(iteminfo[1],iteminfo[2],lastReason,level)
				#或立即发送,转下次发送信息
				isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,level,subject)
				#恢复通知 状态变化,更新osa_monitors记录
				update_iteminfo_remind(isNoticeNow,isNoticeNext,iteminfo)
				#恢复通知 状态变化,更新osa_monitor_alarm记录
				insert_itemalarm_remind(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,type="apache")
			#插入osa_monitor_data记录
			monitor_insert_itemdata(iteminfo[0],result)
		else:#报警：1.ping 不通
			alarminfo = {'text':reason,'level':level,'type':'apache'}
			#根据通知对象获取通知用户
			userlist = get_mailto_users(iteminfo[9])
			mailContent,subject = apache_alarm_content(iteminfo[1],iteminfo[2],reason,level)
			#或立即发送,转下次发送信息
			isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,level,subject)
			#异常通知 ,更新osa_monitors记录
			update_iteminfo_except(isNoticeNow,isNoticeNext,iteminfo)
			#异常通知 ,插入osa_monitor_alarm记录
			insert_itemalarm_except(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,alarminfo)
			#插入osa_monitor_record记录
			monitor_insert_itemdata(iteminfo[0],result,0,'异常')
	except Exception as e:
		log_error("monitor_apache_index():"+str(e))
		
	
	
def monitor_lighttpd_index(iteminfo):
	'''
	@osaMonitor lighttpd 入口函数
	'''
	try:
		url = iteminfo[2]
		itemconfig = eval(iteminfo[8])
		isAlarm ,reason,result,level = lighttpd_status_analyze(url,itemconfig)
		if isAlarm == "1":#表示不报警
			if str(iteminfo[17]) == '0':#表示上次报警了，是否恢复报警
				#根据通知对象获取通知用户
				userlist = get_mailto_users(iteminfo[9])
				#获取lastreason
				lastReason = monitor_get_lastReason(iteminfo[0])
				#获取邮件内容
				mailContent,subject = lighttpd_alarm_content(iteminfo[1],iteminfo[2],lastReason,level)
				#或立即发送,转下次发送信息
				isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,level,subject)
				#恢复通知 状态变化,更新osa_monitors记录
				update_iteminfo_remind(isNoticeNow,isNoticeNext,iteminfo)
				#恢复通知 状态变化,更新osa_monitor_alarm记录
				insert_itemalarm_remind(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,type="lighttpd")
			#插入osa_monitor_data记录
			monitor_insert_itemdata(iteminfo[0],result)
		else:#报警：1.ping 不通
			alarminfo = {'text':reason,'level':level,'type':'lighttpd'}
			#根据通知对象获取通知用户
			userlist = get_mailto_users(iteminfo[9])
			#获取邮件内容
			mailContent,subject = lighttpd_alarm_content(iteminfo[1],iteminfo[2],reason,level)
			#或立即发送,转下次发送信息
			isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,level,subject)
			#异常通知 ,更新osa_monitors记录
			update_iteminfo_except(isNoticeNow,isNoticeNext,iteminfo)
			#异常通知 ,插入osa_monitor_alarm记录
			insert_itemalarm_except(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,alarminfo)
			#插入osa_monitor_record记录
			monitor_insert_itemdata(iteminfo[0],result,0,'异常')
	except Exception as e:
		log_error("monitor_lighttpd_index():"+str(e))	
	

def monitor_nginx_index(iteminfo):
	'''
	@osaMonitor nginx 入口函数
	'''
	try:
		url = iteminfo[2]
		itemconfig = eval(iteminfo[8])
		isAlarm ,reason,result,level = nginx_status_analyze(url,itemconfig,iteminfo[0],iteminfo[4])
		if isAlarm == "1":#表示不报警
			if str(iteminfo[17]) == '0':#表示上次报警了，是否恢复报警
				#根据通知对象获取通知用户
				userlist = get_mailto_users(iteminfo[9])
				#lastreason
				lastReason = monitor_get_lastReason(iteminfo[0])
				#mail content
				mailContent,subject = nginx_alarm_content(iteminfo[1],iteminfo[2],lastReason,level)
				#或立即发送,转下次发送信息
				isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,level,subject)
				#恢复通知 状态变化,更新osa_monitors记录
				update_iteminfo_remind(isNoticeNow,isNoticeNext,iteminfo)
				#恢复通知 状态变化,更新osa_monitor_alarm记录
				insert_itemalarm_remind(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,type="nginx")
			#插入osa_monitor_data记录
			monitor_insert_itemdata(iteminfo[0],result)
		else:#报警：1.ping 不通
			alarminfo = {'text':reason,'level':level,'type':'nginx'}
			#根据通知对象获取通知用户
			userlist = get_mailto_users(iteminfo[9])
			#mail content
			mailContent,subject = nginx_alarm_content(iteminfo[1],iteminfo[2],reason,level)
			#或立即发送,转下次发送信息
			isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,level,subject)
			#异常通知 ,更新osa_monitors记录
			update_iteminfo_except(isNoticeNow,isNoticeNext,iteminfo)
			#异常通知 ,插入osa_monitor_alarm记录
			insert_itemalarm_except(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,alarminfo)
			#插入osa_monitor_record记录
			monitor_insert_itemdata(iteminfo[0],result,0,'异常')
	except Exception as e:
		log_error("monitor_nginx_index():"+str(e))		
	

def monitor_mysql_index(iteminfo):
	'''
	@osaMonitor mysql 入口函数
	'''
	try:
		object = iteminfo[2]
		itemconfig = eval(iteminfo[8])
		isAlarm ,reason,result,level = mysql_status_analyze(object,itemconfig,iteminfo[0] ,iteminfo[4])
		if isAlarm == "1":#表示不报警
			if str(iteminfo[17]) == '0':#表示上次报警了，是否恢复报警
				#根据通知对象获取通知用户
				userlist = get_mailto_users(iteminfo[9])
				#lastreason
				lastReason = monitor_get_lastReason(iteminfo[0])
				#mail content
				mailContent,subject = mysql_alarm_content(iteminfo[1],iteminfo[2],lastReason,level)
				#或立即发送,转下次发送信息
				isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,level,subject)
				#恢复通知 状态变化,更新osa_monitors记录
				update_iteminfo_remind(isNoticeNow,isNoticeNext,iteminfo)
				#恢复通知 状态变化,更新osa_monitor_alarm记录
				insert_itemalarm_remind(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,type="mysql")
			#插入osa_monitor_data记录
			monitor_insert_itemdata(iteminfo[0],result)
		else:#报警：1.ping 不通
			alarminfo = {'text':reason,'level':level,'type':'mysql'}
			#根据通知对象获取通知用户
			userlist = get_mailto_users(iteminfo[9])
			#mail content
			mailContent,subject = mysql_alarm_content(iteminfo[1],iteminfo[2],reason,level)
			#或立即发送,转下次发送信息
			isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,level,subject)
			#异常通知 ,更新osa_monitors记录
			update_iteminfo_except(isNoticeNow,isNoticeNext,iteminfo)
			#异常通知 ,插入osa_monitor_alarm记录
			insert_itemalarm_except(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,alarminfo)
			#插入osa_monitor_record记录
			monitor_insert_itemdata(iteminfo[0],result,0,'异常')
	except Exception as e:
		log_error("monitor_mysql_index():"+str(e))	

	
def monitor_mongodb_index(iteminfo):
	'''
	@osaMonitor mongodb 入口函数
	'''
	try:
		#last_pagefault = mongodb_get_itemdata(itemid)
		sec = iteminfo[4]/60
		object = iteminfo[2]
		itemconfig = eval(iteminfo[8])
		isAlarm ,reason,result,level = mongodb_status_analyze(object,itemconfig,iteminfo[0],sec)
		if isAlarm == "1":#表示不报警
			if str(iteminfo[17]) == '0':#表示上次报警了，是否恢复报警
				#根据通知对象获取通知用户
				userlist = get_mailto_users(iteminfo[9])
				#lastreason
				lastReason = monitor_get_lastReason(iteminfo[0])
				#mail content
				mailContent,subject = mongodb_alarm_content(iteminfo[1],iteminfo[2],lastReason,level)
				#或立即发送,转下次发送信息
				isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,level,subject)
				#恢复通知 状态变化,更新osa_monitors记录
				update_iteminfo_remind(isNoticeNow,isNoticeNext,iteminfo)
				#恢复通知 状态变化,更新osa_monitor_alarm记录
				insert_itemalarm_remind(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,type="mongodb")
			#插入osa_monitor_data记录
			monitor_insert_itemdata(iteminfo[0],result)
		else:#报警：1.ping 不通
			alarminfo = {'text':reason,'level':level,'type':'mongodb'}
			#根据通知对象获取通知用户
			userlist = get_mailto_users(iteminfo[9])
			#mail content
			mailContent,subject = mongodb_alarm_content(iteminfo[1],iteminfo[2],reason,level)
			#或立即发送,转下次发送信息
			isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,level,subject)
			#异常通知 ,更新osa_monitors记录
			update_iteminfo_except(isNoticeNow,isNoticeNext,iteminfo)
			#异常通知 ,插入osa_monitor_alarm记录
			insert_itemalarm_except(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,alarminfo)
			#插入osa_monitor_record记录
			monitor_insert_itemdata(iteminfo[0],result,0,'异常')
	except Exception as e:
		log_error("monitor_mongodb_index():"+str(e))

		
def monitor_redis_index(iteminfo):
	'''
	@osaMonitor redis 入口函数
	'''
	try:
		url = iteminfo[2]
		itemconfig = eval(iteminfo[8])
		minute = float(iteminfo[4])/60
		isAlarm ,reason,result,level = redis_status_analyze(url,itemconfig,iteminfo[0],minute)
		if isAlarm == "1":#表示不报警
			if str(iteminfo[17]) == '0':#表示上次报警了，是否恢复报警
				#根据通知对象获取通知用户
				userlist = get_mailto_users(iteminfo[9])
				#lastreason
				lastReason = monitor_get_lastReason(iteminfo[0])
				#mail content
				mailContent,subject = redis_alarm_content(iteminfo[1],iteminfo[2],lastReason,level)
				#或立即发送,转下次发送信息
				isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,level,subject)
				#恢复通知 状态变化,更新osa_monitors记录
				update_iteminfo_remind(isNoticeNow,isNoticeNext,iteminfo)
				#恢复通知 状态变化,更新osa_monitor_alarm记录
				insert_itemalarm_remind(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,type="redis")
			#插入osa_monitor_data记录
			monitor_insert_itemdata(iteminfo[0],result)
		else:#报警：1.ping 不通
			alarminfo = {'text':reason,'level':level,'type':'redis'}
			#根据通知对象获取通知用户
			userlist = get_mailto_users(iteminfo[9])
			#mail content
			mailContent,subject = redis_alarm_content(iteminfo[1],iteminfo[2],reason,level)
			#或立即发送,转下次发送信息
			isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,level,subject)
			#异常通知 ,更新osa_monitors记录
			update_iteminfo_except(isNoticeNow,isNoticeNext,iteminfo)
			#异常通知 ,插入osa_monitor_alarm记录
			insert_itemalarm_except(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,alarminfo)
			#插入osa_monitor_record记录
			monitor_insert_itemdata(iteminfo[0],result,0,'异常')
	except Exception as e:
		log_error("monitor_redis_index():"+str(e))

	
def monitor_memcache_index(iteminfo):
	'''
	@osaMonitor memcache 入口函数
	'''
	try:
		url = iteminfo[2]
		itemconfig = eval(iteminfo[8])
		isAlarm ,reason,result,level = memcache_status_analyze(url,itemconfig,iteminfo[0],iteminfo[4])
		if isAlarm == "1":#表示不报警
			if str(iteminfo[17]) == '0':#表示上次报警了，是否恢复报警
				#根据通知对象获取通知用户
				userlist = get_mailto_users(iteminfo[9])
				#lastreason
				lastReason = monitor_get_lastReason(iteminfo[0])
				#mail content
				mailContent,subject = memcache_alarm_content(iteminfo[1],iteminfo[2],lastReason,level)
				#或立即发送,转下次发送信息
				isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,level,subject)
				#恢复通知 状态变化,更新osa_monitors记录
				update_iteminfo_remind(isNoticeNow,isNoticeNext,iteminfo)
				#恢复通知 状态变化,更新osa_monitor_alarm记录
				insert_itemalarm_remind(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,type="memcache")
			#插入osa_monitor_data记录
			monitor_insert_itemdata(iteminfo[0],result)
		else:#报警：1.ping 不通
			alarminfo = {'text':reason,'level':level,'type':'memcache'}
			#根据通知对象获取通知用户
			userlist = get_mailto_users(iteminfo[9])
			#mail content
			mailContent,subject = memcache_alarm_content(iteminfo[1],iteminfo[2],reason,level)
			#或立即发送,转下次发送信息
			isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,level,subject)
			#异常通知 ,更新osa_monitors记录
			update_iteminfo_except(isNoticeNow,isNoticeNext,iteminfo)
			#异常通知 ,插入osa_monitor_alarm记录
			insert_itemalarm_except(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,alarminfo)
			#插入osa_monitor_record记录
			monitor_insert_itemdata(iteminfo[0],result,0,'异常')
	except Exception as e:
		log_error("monitor_memcache_index():"+str(e))	


def monitor_custom_index(iteminfo):
	'''
	@osaMonitor custom(自定义) 入口函数
	'''
	try:
		object = str(iteminfo[2])
		itemconfig = eval(iteminfo[8])
		isAlarm ,reason,result,level = custom_status_analyze(object,itemconfig)
		if isAlarm == "1":#表示不报警
			if str(iteminfo[17]) == '0':#表示上次报警了，是否恢复报警
				#根据通知对象获取通知用户
				userlist = get_mailto_users(iteminfo[9])
				#lastReason
				lastReason = monitor_get_lastReason(iteminfo[0])
				#mail content
				mailContent,subject = custom_alarm_content(iteminfo[1],iteminfo[2],lastReason,level)
				#或立即发送,转下次发送信息
				isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,level,subject)
				#恢复通知 状态变化,更新osa_monitors记录
				update_iteminfo_remind(isNoticeNow,isNoticeNext,iteminfo)
				#恢复通知 状态变化,更新osa_monitor_alarm记录
				insert_itemalarm_remind(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,type="custom")
			#插入osa_monitor_data记录
			monitor_insert_itemdata(iteminfo[0],result)
		else:#报警：1.ping 不通
			alarminfo = {'text':reason,'level':level,'type':'custom'}
			#根据通知对象获取通知用户
			userlist = get_mailto_users(iteminfo[9])
			#mail content
			mailContent,subject = custom_alarm_content(iteminfo[1],iteminfo[2],reason,level)
			#或立即发送,转下次发送信息
			isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_itemalarm(iteminfo,userlist,mailContent,10,level,subject)
			#异常通知 ,更新osa_monitors记录
			update_iteminfo_except(isNoticeNow,isNoticeNext,iteminfo)
			#异常通知 ,插入osa_monitor_alarm记录
			insert_itemalarm_except(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,iteminfo,alarminfo)
			#插入osa_monitor_record记录
			monitor_insert_itemdata(iteminfo[0],result,0,'异常')
	except Exception as e:
		log_error("monitor_custom_index():"+str(e))	


if __name__ == '__main__':
	'''
	@test
	'''
	pass
	
