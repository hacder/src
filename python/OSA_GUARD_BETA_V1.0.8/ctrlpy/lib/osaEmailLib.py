#!/usr/bin/env python
#encoding=utf-8

'''
	Author:		osa开源团队
	Description:	email 通知验证
	Create Date:	2011-08-15
'''
from datetime import *
import time ,os ,sys,random
import smtplib
from email.header import Header
from email.mime.text import MIMEText
from email.MIMEMultipart import MIMEMultipart 
from ctrlpy.lib import cmdtosql
from ctrlpy.lib.osaLogLib import *



def get_email_byname(username):
	'''
	@通过Username来获取用户对应的邮箱
	'''
	sql = "select oEmail from osa_users where oUserName='"+username+"'"
	result = cmdtosql.select(sql)
	if not result or result == None:
		return ''
	return result[0][0]

def sendMail(subject,content,mailto):
	'''
	@邮件发送函数
	@逻辑：从osa_email_config获取邮件服务信息，进行邮件发送
	'''
	sql = "select * from osa_email_config limit 1"
	result = cmdtosql.select(sql)
	if not result or result == None:
		log_error('SMTP不能用,原因：数据表osa_email_config为空')
		return
	smtp_server = result[0][2]
	smtp_from = result[0][5]
	smtp_user = result[0][1]
	smtp_pass = result[0][4]
	smtp_port = result[0][3]
	
	msg = MIMEMultipart()
	msg['Subject'] = Header(subject,'utf-8')
	msg['From'] = smtp_from
	msg['To'] = mailto

	part =  MIMEText(content,'plain','utf-8')
	msg.attach(part)

	try:
		smtpServer = smtplib.SMTP(smtp_server,str(smtp_port))		
	except Exception as e:
		log_error('SMTP连接邮件服务器失败,可能原因:'+str(e))
		return False
	try:
		smtpServer.login(smtp_user,smtp_pass)
		smtpServer.sendmail(smtp_from,mailto,msg.as_string())
		log_info('send mail to:'+mailto)
	except smtplib.SMTPException as e:
		log_error('SMTP发送邮件失败,可能原因:'+str(e))
	finally:
		smtpServer.quit()
		return True
		
def get_mailto_users(user_list='ALL'):
	'''
	@根据user_list的值来获取需要通知的用户列表
	'''
	userlist = []
	if user_list=='ALL':
		users = cmdtosql.select("select oUserName from osa_users where oStatus='0'")
		for user in users:
			userlist.append(user[0])
		return userlist
	else:
		for user in user_list.split(','):
			userlist.append(user)
		return userlist
		

def get_list(str):
	'''
	@将字符转分割放入列表中
	'''
	list = []
	for element in str.split(','):
		list.append(element)
	return list

def switch_time(noticetype):
	'''
	@根据时间和通知类型来判断是否发送邮件
	@noticetype:分为三种类型，defaults：默认任何时候接收，next:转到下次接收，refuse：拒绝接收
	@return 三种类型，'not-send':不发送，'now-send':现在发送，时间字符串：表示转为下次发送时间
	'''
	now = date.today()
	weeknum = now.strftime('%w')
	lstime = time.strftime("%Y-%m-%d",time.localtime())+" 18:00:00"
	letime = time.strftime("%Y-%m-%d",time.localtime())+" 23:59:59"
	rstime = time.strftime("%Y-%m-%d",time.localtime())+" 00:00:00"
	retime = time.strftime("%Y-%m-%d",time.localtime())+" 08:00:00"
	ctime = time.strftime("%Y-%m-%d %H:%M:%S",time.localtime())
	
	if noticetype == 'defaults':
		return 'now-send'
	if noticetype == 'refuse':
		if weeknum == 0 or weeknum == 6:#周末
			return 'not-send'
		elif ctime > lstime and ctime < letime:#当天18点~24点
			return 'not-send'
		elif ctime > rstime and ctime < retime:#当天00点~08点
			return 'not-send'
		else:
			return 'now-send'
	if noticetype == 'next':
		if weeknum == 6 or weeknum == 0:#周末
			#计算下次发送时间
			return get_noticetime_weekly(weeknum)
		elif ctime > lstime and ctime < letime:#当天18点~24点
			#计算下次发送时间
			return get_noticetime_night(1)
		elif ctime > rstime and ctime < retime:#当天00点~08点
			#计算下次发送时间
			return get_noticetime_night(2)
		else:
			return 'now-send'
	
def get_noticetime_weekly(weeknum):
	'''
	@获取周六周天的告警信息下次通知的时间
	'''
	today = date.today()
	deltaOne = timedelta(days=1)
	deltaTwo = timedelta(days=2)
	if weeknum == 6:#周六
		nextday = (today + deltaTwo).strftime("%Y-%m-%d")
	if weeknum == 0:#周天
		nextday = (today + deltaOne).strftime("%Y-%m-%d")
	nextTime = nextday + " 08:00:00"
	return nextTime

def get_noticetime_night(district):
	'''
	@获取下班时间告警信息下次通知的时间
	@distrcit:表示区间，district==1时表示18:00:00~24:00:00,district==2时表示00:00:00~08:00:00
	'''
	today = date.today()
	deltaTime = timedelta(days=1)
	if district == 1:
		nextday = (today + deltaTime).strftime("%Y-%m-%d")
	if district == 2:
		nextday = today.strftime("%Y-%m-%d")
	nextTime = nextday + " 08:00:00"
	return nextTime
	
def notice_email_personality(username,itemtype,alarmlevel,itemobject):
	'''
	@个性化设置判断该用户是否需要通知
	@username:用户名，itemtype:监控项目类型，alarmlevel:报警级别，itemobject：应用对象
	@return 三种类型，'not-send':不发送，'now-send':现在发送，时间字符串：表示转为下次发送时间
	'''	
	#根据用户名来获取该用户的个性化设置
	sql = "select * from osa_global_config where oUserName='"+str(username)+"' limit 1"
	res = cmdtosql.select(sql)
	#默认一个列表，列表默认需要服务器判断的类型
	typelist=['server','tcp','udp']
	
	if not res or res == None:
		#说明该用户没有个性化设置，直接返回true
		return 'now-send'
		
	if res[0][5]:#总开关
		closelist = get_list(res[0][5])
		email = 'email'
		if email in closelist:
			return 'not-send'
		
	if res[0][3]:
		#说明个性化设置里面含有只接收指定服务器的报警
		iplist = get_list(res[0][3])
		if itemtype in typelist:
			if itemobject not in iplist:
				return 'not-send'
		
	if res[0][2]:
		#故障级别判断
		levellist =  get_list(res[0][2])
		if str(alarmlevel) not in levellist:
			return 'not-send'
	if res[0][1]:
		return switch_time(res[0][1])
		

def is_notice_server(ipinfo,username,maxnum,alarmlevel):
	'''
	@针对服务器告警信息，整体判断是否要发送邮件
	@需要的信息必须有：已发送告警次数notifiednum,连续告警次数notinum,恢复的时候清零
	@return 三种类型，'not-send':不发送，'now-send':现在发送，时间字符串：表示转为下次发送时间
	'''
	notifiednum = ipinfo[6]
	notinum = ipinfo[7]
	alarmlevel = str(alarmlevel)
	#服务器监控默认是连续告警两次 
	if alarmlevel == '4':
		if ipinfo[8] == '0':
			return 'not-send'
	else:
		if notinum >= 2:
			return 'not-send'

	#已告警次数要小于配置中的每个服务器每天最多的次数
	if notifiednum >= maxnum:
		return 'not-send'
	
	return notice_email_personality(username,'server',alarmlevel,ipinfo[1])
	
def is_notice_item(iteminfo,username,maxnum,alarmlevel):
	'''
	@针对监控项目告警信息，整体判断是否要发送邮件
	@需要的信息必须有：已发送告警次数notifiednum,连续告警次数notinum,恢复的时候清零
	@return 三种类型，'not-send':不发送，'now-send':现在发送，时间字符串：表示转为下次发送时间
	'''
	notifiednum = iteminfo[19]
	notinum = iteminfo[20]
	repeatednum = iteminfo[21]
	alarmlevel = str(alarmlevel)
	#根据是否为恢复通知判断报警通知次数
	if alarmlevel == '4':
		if iteminfo[7] == '0' or iteminfo[22] == '0':#iteminfo[7] == '0'表示恢复时不通知
			return 'not-send'
	else:
		if notinum >= iteminfo[5]:
			return 'not-send'
		#针对已重复次数是否已达到设置的值
		if repeatednum < iteminfo[6]:
			num = int(repeatednum) + 1
			sql = "update osa_monitors set oRepeatedNum = "+str(num)+" where id="+str(iteminfo[0])
                        cmdtosql.execsql(sql)
			return 'not-send'
		else:#重置已重复次数
			sql = "update osa_monitors set oRepeatedNum = 0 where id="+str(iteminfo[0])
			cmdtosql.execsql(sql)
			
	#判断项目发送的告警次数是否大于针对每个项目的报警次数限制
	if notifiednum >= maxnum:
		return 'not-send'
	return notice_email_personality(username,iteminfo[3],alarmlevel,iteminfo[2])
	
	

if __name__ == "__main__":
	pass
	#result = notice_email_personality('jiangfeng','ftp',1,'127.0.0.1')
	#print(result)	

