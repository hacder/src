#!/usr/bin/env python
#encoding=utf-8

'''
	Author: 	OSA开源团队
	Description:	(采集监控数据
			  目前仅支持脚本采集)
	Date: 		2011-08-14
'''

from multiprocessing import Process,Pool
from ctrlpy.lib import cmdtosql
from ctrlpy.etc.config import PNUM,DIRS
from ctrlpy.lib.osaSnmpLib import server_snmp_analyze
from ctrlpy.lib.osaPing import Ping
from ctrlpy.lib.osaEmailLib import get_mailto_users,sendMail,is_notice_server,get_email_byname
from ctrlpy.lib.osaEmailAlarm import *
from ctrlpy.lib.osaLogLib import *
import os,time,sys,signal,Queue

reload(sys) 
sys.setdefaultencoding('utf8')  

'''
#多进程并发采集监控数据
'''

def server_get_lastreason(ipid):
	'''
	#获取服务器上次发生故障时间
	'''
	try:
		sql = "select oAlarmText from osa_collect_alarm where oIpid="+str(ipid)+" order by id desc limit 1"
		result = cmdtosql.select(sql)
                if result:
                        return result[0][0]
                else:
                        return ''
        except Exception as e:
                log_error("server_get_lastreason():"+str(e))


def get_iplist():
	'''
	@获取默认采集数据的服务器列表，排除暂停的采集的服务器
	@oIsStop = 0 :表示服务器不暂停
	'''
	try:
		sql = "select oIp from osa_ipinfo where oIsStop = 0"
		iplist=[]
		con = cmdtosql._get_pcon()
		cur = con.cursor()
		cur.execute(sql)
		list = cur.fetchall()
	except Exception as e:
		log_error("collect.get_iplist():"+str(e))
	for ip in list:
		iplist.append(ip[0])
	
	cmdtosql._exit(con, cur)
	return iplist
	

def get_ipQueue():
	'''
	@将取得的ip放入队列
	'''
	iplist=get_iplist()
	q=Queue.Queue()
	for ip in iplist:
		if ip == None or not ip:
			break
		q.put(ip)
	return q
	

def get_ipinfo(ip):
	'''
	@通过ip从osa_ipinfo中获取ipinfo信息
	'''
	try:
		sql = "select * from osa_ipinfo where oIp='"+str(ip)+"'"
		con = cmdtosql._get_pcon()
		cur = con.cursor()
		cur.execute(sql)
		ipinfo=cur.fetchone()
	except Exception as e:
		log_error("collect.get_ipinfo(ip):"+str(e))
	cmdtosql._exit(con, cur)
	return ipinfo

def get_snmpinfo():
	'''
	@从数据库osa_snmp表获取snmp配置信息。
	'''
	try:
		sql = "select oSnmpPort,oSnmpKey from osa_snmp where id='1'"
		con = cmdtosql._get_pcon()
		cur = con.cursor()
		cur.execute(sql)
		snmpinfo=cur.fetchone()
	except Exception as e:
		log_error("collect.get_snmpinfo(ip):"+str(e))
	cmdtosql._exit(con, cur)
	return snmpinfo
	
def mail_status_alarm(ipinfo,userlist,content,maxnum,level,subject):
	'''
	@邮件发送报警状态相关
	@返回isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime
	'''
	isNoticeNow = isNoticeNext =0
	noticeNextUsers =''
	noticeNextTime ='0000-00-00 00:00:00'
	for user in userlist:
		isEmail = is_notice_server(ipinfo,user,maxnum,level)
		if isEmail == 'not-send':#不发送
			pass
		elif isEmail == 'now-send':#立即发送
			isNoticeNow += 1 #表示已通知
			mailto = get_email_byname(user)
			#content = choose_template(template)
			sendMail(subject,content,mailto)
		else:#转下次发送
			isNoticeNext += 1
			noticeNextUsers += user+',' 
			noticeNextTime = isEmail
	return isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime
	
	
def update_ipinfo_remind(isNoticeNow,isNoticeNext,ipinfo):
	'''
	@服务器恢复通知时根据isNoticeNow,isNoticeNext更新osa_ipinfo表
	@思路，当立即发送通知和转下次通知时，标识该ip今天已发送的通知+1
	'''
	try:
		if isNoticeNow>0 or isNoticeNext >0:
			oNum=int(ipinfo[6])+1
			sql = "update osa_ipinfo set oIsAlive='1' ,oStatus='正常' ,oFaultTime='OOOO-OO-OO OO:00:00' ,oNotiNum=0 ,oNotifiedNum="+str(oNum)+",oIsEmail='0'  where id="+str(ipinfo[0])
		else:
			sql = "update osa_ipinfo set oIsAlive='1' ,oStatus='正常' ,oFaultTime='0000-00-00 00:00:00' ,oNotiNum=0,oIsEmail='0'  where id="+str(ipinfo[0])
		log_debug("update_ipinfo_remind()执行sql语句:"+str(sql))
		cmdtosql.update(sql)
	except Exception as e:
		log_error("update_ipinfo_remind():"+str(e))

	
def insert_collectalarm_remind(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,ipinfo):
	'''
	@服务器恢复通知根据通知方式来更新osa_collect_alarm表
	'''
	try:
		isNotice = 0
		if isNoticeNow == 0 and isNoticeNext == 0 and ipinfo[8] == 0:
			return False
		else:
			alarmTime = cmdtosql._get_time(1)
			##再添加一个服务器故障时间
			startTime = str(ipinfo[5])
			#计算服务器故障时间，并写入表osa_collect_alarm
			faultTime = time.time()-time.mktime(time.strptime(startTime,"%Y-%m-%d %H:%M:%S"))
			if isNoticeNow > 0:
				isNotice =1
			if isNoticeNext > 0:
				notice_sql = "insert into osa_collect_alarm(oIpid,oAlarmText,oAlarmTime,oAlarmLevel,oAlarmType,oIsNotice,oIsRead,oFaultTime,oIsNoticeNext,oNoticeNextTime,oNoticeNextUsers) \
								values("+str(ipinfo[0])+",'服务器恢复正常','"+str(alarmTime)+"','4','server',"+str(isNotice)+",0,"+str(faultTime)+",1,"+str(noticeNextTime)+",'"+str(noticeNextUsers.strip(','))+"')"
			else:
				notice_sql = "insert into osa_collect_alarm(oIpid,oAlarmText,oAlarmTime,oAlarmLevel,oAlarmType,oIsNotice,oIsRead,oFaultTime) \
								values("+str(ipinfo[0])+",'服务器恢复正常','"+str(alarmTime)+"','4','server',"+str(isNotice)+",0,"+str(faultTime)+")"
			log_debug("insert_collectalarm_remind()执行sql语句:"+str(notice_sql))
			cmdtosql.execsql(notice_sql)
	except Exception as e:
		log_error("insert_collectalarm_remind():"+str(e))

	
def insert_collectdata_remind(ipid,result,replayTime=0):
	'''
	@服务器恢复通知,插入osa_collect_data记录
	'''
	try:
		collectTime=cmdtosql._get_time(1)
		snmp_sql = "insert into osa_collect_data(oIpid,oCollectTime,oCollectData,oReplayTime,oStatus) values("+str(ipid)+",'"+str(collectTime)+"','"+str(result)+"','"+str(replayTime)+"','正常')"
		log_debug("insert_collectdata_remind()执行sql语句:"+str(snmp_sql))
		cmdtosql.execsql(snmp_sql)
	except Exception as e:
		log_debug("insert_collectdata_remind()执行sql语句:"+str(snmp_sql))
		log_error("insert_collectdata_remind():"+str(e))
	

def update_ipinfo_exception(isNoticeNow,isNoticeNext,ipinfo,level):
	'''
	@服务异常报警更新osa_ipinfo 记录
	'''
	try:
		if ipinfo[2] == '1':#表示上次正常，错误时间从现在开始
			faultTime = cmdtosql._get_time(1)
		else:#表示上次异常，错误时间从过去开始
			faultTime = ipinfo[5]
		if str(level) == '1':
			status = '失去响应'
		elif str(level) == '2':
			status = '其他异常'

		if isNoticeNow>0 or isNoticeNext >0:
			oNum = int(ipinfo[6])+1
			notiNum = int(ipinfo[7])+1
			sql = "update osa_ipinfo set oIsAlive='0' ,oStatus='"+str(status)+"' ,oFaultTime='"+str(faultTime)+"',oNotifiedNum="+str(oNum)+",oNotiNum="+str(notiNum)+",oIsEmail='1'  where id="+str(ipinfo[0])
		else:
			sql = "update osa_ipinfo set oIsAlive='0' ,oStatus='"+str(status)+"' ,oFaultTime='"+str(faultTime)+"' where id="+str(ipinfo[0])
		log_debug("update_ipinfo_exception()执行sql语句:"+str(sql))
		cmdtosql.update(sql)
	except Exception as e:
		log_error("update_ipinfo_exception():"+str(e))
	

def insert_collectalarm_exception(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,ipinfo,alarminfo):
	'''
	@服务器异常通知根据通知方式来更新osa_collect_alarm表
	'''
	try:
		isNotice = 0
		alarmTime = cmdtosql._get_time(1)
		##再添加一个服务器故障时间
		startTime = str(ipinfo[5])
		#计算服务器故障时间，并写入表osa_collect_alarm
		#faultTime = time.time()-time.mktime(time.strptime(startTime,"%Y-%m-%d %H:%M:%S"))
		if isNoticeNow == 0 and isNoticeNext == 0:
			return False
		else:
			if isNoticeNow > 0:
				isNotice =1
			if isNoticeNext > 0:
				notice_sql = "insert into osa_collect_alarm(oIpid,oAlarmText,oAlarmTime,oAlarmLevel,oAlarmType,oIsNotice,oIsRead,oIsNoticeNext,oNoticeNextTime,oNoticeNextUsers) \
								values("+str(ipinfo[0])+",'"+str(alarminfo['text'])+"','"+str(alarmTime)+"','"+str(alarminfo['level'])+"','server',"+str(isNotice)+",0,1,"+str(noticeNextTime)+",'"+str(noticeNextUsers.strip(','))+"')"
			else:
				notice_sql = "insert into osa_collect_alarm(oIpid,oAlarmText,oAlarmTime,oAlarmLevel,oAlarmType,oIsNotice,oIsRead) \
								values("+str(ipinfo[0])+",'"+str(alarminfo['text'])+"','"+str(alarmTime)+"','"+str(alarminfo['level'])+"','server',"+str(isNotice)+",0)"
			log_debug("insert_collectalarm_exception()执行sql语句:"+str(notice_sql))
			cmdtosql.execsql(notice_sql)
	except Exception as e:
		log_error("insert_collectalarm_exception():"+str(e))

	
def insert_collectdata_exception(ipid,result,replayTime=0):
	'''
	@服务器异常通知，插入osa_collect_data记录
	'''
	try:
		result = result.replace("'","")
		collectTime=cmdtosql._get_time(1)
		snmp_sql = "insert into osa_collect_data(oIpid,oCollectTime,oCollectData,oReplayTime,oStatus) values("+str(ipid)+",'"+str(collectTime)+"','"+str(result)+"','"+str(replayTime)+"','异常')"
		log_debug("insert_collectdata_exception()执行sql语句:"+str(snmp_sql))
		cmdtosql.execsql(snmp_sql)
	except Exception as e:
		log_error("insert_collectdata_exception():"+str(e))
	
	
def data_collect(agent,ip,key,port):
	'''
	@数据采集入口
	@逻辑：判断ip服务器是否存活，存活:{修改osa_ipinfo中的状态,snmp采集数据入库osa_data_collect},不存活:{修改osa_ipinfo中的状态，同时写入osa_monitor_alarm表}
	'''
	try:
		ipinfo=get_ipinfo(ip)
		if not ipinfo or ipinfo==False or ipinfo == None:
			log_error('ipinfo error:'+str(ip))
			return
			
		isAlarm,reason,result,level = server_snmp_analyze(agent,ip,key,port)
		if isAlarm == "1":
			if ipinfo[2] == '0':#ipinfo[2] == '0' 表示上次服务器不可达
				#判断是否需要发送邮件
				userlist = get_mailto_users('ALL')
				#last reason
				lastreason = server_get_lastreason(ipinfo[0])
				#mail content
				log_error('ipid:'+str(ipinfo[0]))
				log_error("lastReason:"+str(lastreason))
				mailcontent,subject = server_alarm_content(ipinfo[1],ipinfo[1],lastreason,level)
				#或立即发送,转下次发送信息
				isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_alarm(ipinfo,userlist,mailcontent,10,level,subject)		
				#恢复通知 状态变化,更新osa_ipinfo记录
				update_ipinfo_remind(isNoticeNow,isNoticeNext,ipinfo)
				#恢复通知 状态变化,更新osa_collect_alarm记录
				insert_collectalarm_remind(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,ipinfo)
			#插入osa_collect_data记录
			insert_collectdata_remind(ipinfo[0],result)
		else:#(上次正常不正常都要判断发送邮件)
			alarminfo = {'text':reason,'level':level,'type':'server'}
			#判断是否需要发送邮件
			userlist = get_mailto_users('ALL')
			#mail content
			mailcontent ,subject= server_alarm_content(ipinfo[1],ipinfo[1],reason,level)
			#或立即发送,转下次发送信息
			isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime = mail_status_alarm(ipinfo,userlist,mailcontent,10,level,subject)	
			#不可达通知 ,更新osa_ipinfo记录
			update_ipinfo_exception(isNoticeNow,isNoticeNext,ipinfo,level)
			#不可达通知 ,插入osa_collect_alarm记录
			insert_collectalarm_exception(isNoticeNow,isNoticeNext,noticeNextUsers,noticeNextTime,ipinfo,alarminfo)
			#插入osa_collect_data记录
			insert_collectdata_exception(ipinfo[0],result)
	except Exception as e:
		log_error("data_collect():"+str(e))		


def mut_process(q,agent,key,port):
 
	for x in xrange(PNUM['num']):
		if q.qsize() == 0:
			break
		ip = q.get()
		try:
			signal.signal(signal.SIGCHLD,signal.SIG_IGN)
			i = Process(target=data_collect, args = [agent,ip,key,port])
			i.start()
						
		except Exception as e:			
			log_error('mut_process() process exception:' + str(e))

def allDatatoDb(q,agent,key,port):
	while True:
		try:
			if q.qsize() == 0:
				return 'over'
			mut_process(q,agent,key,port)
		except Exception as e:			
			log_error('allDatatoDb() mut_process exception:' + str(e))
def collectDo():
	'''
	@采集入口调用函数
	'''
	q=get_ipQueue()
	#采集所有数据
	try:		
		snmpinfo = get_snmpinfo()
		res=allDatatoDb(q,agent='my-agent',key=snmpinfo[1],port=snmpinfo[0])
	except Exception as collerror:
		log_error('collectDo():'+str(collerror))
	time.sleep(120)
	sys.exit()

if sys.argv[1].upper()=='COLLECT':
	lock =  DIRS['CFG_ROOT']+'py.table.lock'
	os.system('rm -f ' + lock)
	collectDo()
	#rs = server_get_lastreason(83)
	#print(rs)


