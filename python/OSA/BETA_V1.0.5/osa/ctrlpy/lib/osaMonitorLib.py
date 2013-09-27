#!/usr/bin/env python
#encoding=utf-8
'''
 Autor: osa开源团队
 Description:监控报警任务、
 create date：2012-05-29
'''
import urllib2
import time
import sys,socket
import subprocess
import threading
import MySQLdb
import random,signal
from ctrlpy.lib.cmdtosql import select,update,_get_time,_get_con,_exit,_get_pcon,notifyByEmail
from ctrlpy.lib.osaUtil import save_log
from ctrlpy.lib.osaPing import Ping
from ctrlpy.lib.hostSocket import PortIsAlive
from ctrlpy.etc.config import SOCKET,MONITOR



def GetUserEmailAddress(user_list):
	'''
	根据用户列表获得邮件地址列表
	'''	
	emails=[]
	if user_list == 'ALL':
		email_list = select('SELECT oEmail FROM osa_users')
		#email_list是元组
		for email in email_list:
			emails.append(email[0])
		return emails
	else:
		for user in user_list.split(','):
			email = select("SELECT oEmail FROM  osa_users WHERE oUserName='"+user+"'")
			#这里是防止没有填写邮箱的情况
			if email:
				emails.append(email[0][0])
		return emails

	
def CreateMailBody(type,item,subitem):
	'''
	创建异常告警或者恢复通知的邮件内容
	'''
	if type == 'STATUS_EXCEPTION':
		if subitem == 'webisalive':
			return "网页"+item+"状态异常，无法访问，请检查web服务是否正常"
		if subitem == 'diskspacecheck':
			return "服务器"+item+"磁盘状态异常"
		if subitem == 'topstatcheck':
			return "服务器"+item+"负载过高"
		if subitem == 'portstatcheck':
			return "服务器"+item+"所被监控的端口不可访问"
		if subitem == 'databasecheck':
			return "数据库"+item+"异常"
		if subitem =='loginusercheck':
			return "服务器"+item+"登录用户数量过多"
		if subitem =='networkcheck':
			return "服务器"+item+"网络流量过大"
	if type == 'RECOVERNOTIFY':
		if subitem == 'webisalive':
			return "网页"+item+"状态已恢复正常"
		if subitem == 'diskspacecheck':
			return "服务器"+item+"磁盘使用率已经恢复正常"
		if subitem == 'topstatcheck':
			return "服务器"+item+"的负载已经恢复正常"
		if subitem == 'portstatcheck':
			return "服务器"+item+"所被监控的端口已恢复正常，继续监听"
		if subitem == 'databasecheck':
			return "数据库"+item+"已恢复正常"
		if subitem =='loginusercheck':
			return "服务器"+item+"登录用户数量已恢复正常"
		if subitem =='networkcheck':
			return "服务器"+item+"网络流量已经恢复正常"

def SendMail(type,user_list,item,subitem):
	'''
	邮件通知函数，建立邮件内容并发送给指定用户
	'''
	if type == 'RECOVERNOTIFY':
		mailbody = 'Message level:INFO,'+str(CreateMailBody('RECOVERNOTIFY',item,subitem))
		emailaddrlist = GetUserEmailAddress(user_list)
		for emailaddr in emailaddrlist:
			try:
				notifyByEmail(subject='[INFO]Notify from OSA',content = mailbody,address = emailaddr)
			except Exception as e:
				save_log('ERROR',"send mail fail! ERROR:"+str(e))
	if type == 'STATUS_EXCEPTION':
		mailbody = 'Message level:ERROR,'+str(CreateMailBody('STATUS_EXCEPTION',item,subitem))
		emailaddrlist = GetUserEmailAddress(user_list)
		for emailaddr in emailaddrlist:
			try:
				notifyByEmail(subject='[ERROR]Notify from OSA',content = mailbody,address = emailaddr)
			except Exception as e:
				save_log('ERROR',"send mail fail! ERROR:"+str(e))		
		
def CreateAlarmMsg(oItemName,oItemid,oServerip,oAlarmInfo,oType):
	'''
	增加新的告警信息:往osa_alarmmsg表里增加新的条目
	'''
	now = _get_time(1)	
	sql = "INSERT INTO  osa_alarmmsg (`oAddTime`, `oItemName`, `oItemid`, `oServerip`, `oAlarmInfo`, `oType`) VALUES ('"+now+"','"+oItemName+"',"+str(oItemid)+",'"+oServerip+"','"+oAlarmInfo+"',"+str(oType)+")"
	
	try:
		con = _get_pcon()
		cur = con.cursor()
		cur.execute(sql)
	
	except Exception as e:
		save_log('ERROR','sql INSERT fail! sql:'+sql+',ERROR:'+str(e))	
		_exit(con, cur)
		
		return 
	finally:
		_exit(con, cur)
		return 
		

def CalcNextCheckTime(entry):
	'''
	计算下次检测时间，返回字符串类型的时间
	'''
	begintime = entry[15]
	if entry[15] == None:
		begintime =  entry[12] 	
	
	now_sec = time.mktime(time.strptime(str(begintime), "%Y-%m-%d %H:%M:%S"))	
	
	midtime = int(time.mktime(time.localtime()) - now_sec) % int(entry[6])	
	
	next_sec = time.mktime(time.localtime()) + entry[6] - midtime
	
	nextchecktime = time.localtime(next_sec)	
	
	return time.strftime('%Y-%m-%d %H:%M:%S', nextchecktime)

def ServerIsAlive():
	'''
	单进程的入口函数，每隔10秒去检测服务器的状态
	'''
	while True:
		try:
			ipinfo = select('SELECT * FROM osa_ipinfo')
		except Exception as e:
			
			save_log('ERROR','select table osa_ipinfo fail:'+str(e))
			
		AllUserEmailAddress = GetUserEmailAddress('ALL')
		if ipinfo:
			for each_ipinfo in ipinfo:
				try:
					t=threading.Thread(target=ThreadForEachServer,args=[each_ipinfo,AllUserEmailAddress])
					#主进程退出后，子线程不跟着退出。
					t.setDaemon(0)
					t.start()
									
				except Exception as e:
					save_log('ERROR','ServerIsAlive subThreed is error:'+str(e))
		time.sleep(MONITOR['interval'])


				
def IsDataBaseNormal(oItemConfig,serverip):
	'''
	检测数据库的状态，并判断thread_connected和threads_running是否超过用户设定
	'''
	try:
		con=MySQLdb.connect(host=serverip,port=int(oItemConfig['port']),user=oItemConfig['user'],passwd=oItemConfig['password'])
	except Exception as error:
		save_log("ERROR","connect mysql "+serverip+":"+oItemConfig['user']+"fail! ERROR:"+str(error))
		return ('0',"不能连接到数据库"+str(serverip)+":"+str(oItemConfig['port']))
	
	res =""
	stat="1"
	cursor=con.cursor()
	n=cursor.execute('show status;')
	show_status = cursor.fetchall()
	
	for status in show_status:
		if status[0] == 'Threads_connected':
			if int(status[1]) > int(oItemConfig['pvalue']):
				stat="0"
				res = "Thread_connected"+":"+str(status[1])
		if status[0] == 'Threads_running':
			if int(status[1]) > int(oItemConfig['tvalue']):
				stat="0"
				res = "Threads_running"+":"+str(status[1])
	return (stat,res)
		
	
def IsDiskSpaceNormal(disk_dic,percentage):
	'''
	逐一检查分区使用率是否超过限定阈值，若超过：返回元组(False,分区名，分区使用率)
										若正常：返回元组(True,'','')
	'''
	if disk_dic == None:
		return False,'/','data not found!' 
	for partition in disk_dic.keys():
		partition_use = float(disk_dic[partition]['used'])/float(disk_dic[partition]['total'])
		if partition_use > float(percentage)/100:
			return False,partition,str(round(partition_use*100,2))+str('%')
	return True,'',''
def IsLoadStatNormal(loadstat,topvalue):
	'''
	检查cpu使用率是否超过限定阈值，若正常：返回(Ture,'')
									若超过：返回(False,负载值)
	'''
	if loadstat['one'] < topvalue:
		return True,''
	else:
		return False,loadstat['one']

def IsLoginUserOver(usernum,topvalue):
	'''
	检查登录用户数量是否超过限定阈值,若正常：返回(Ture,'')
									若超过：返回(False,用户数)
	'''
	if usernum <= topvalue:
		return True,''
	else:
		return False,usernum

def IsNetworkTrafficNormal(network,topvalue):
	'''
	检测网络流量是否超过限定阈值，只要检测到有某个网卡流量超限，就停止检测
	'''
	for nic in network.keys():
		if round((network[nic]['inbond']+network[nic]['outbond'])/1024/1024,2) > topvalue:
			return False,nic,round((network[nic]['inbond']+network[nic]['outbond'])/1024/1024,2)
	return True,'',''

def IsPortAlive(ip,portlist):
	'''
	检测所监控的端口是否存活，并返回未存活的端口号
	'''
	deadport=''
	deadportlist=[]
	for port in portlist.split(','):
		if PortIsAlive(ip,port) == False:
			deadportlist.append(port)
	if len(deadportlist) == 0:
		return True,deadportlist
	else:
		for a in deadportlist:
			deadport = deadport+a+"."
		return False,deadport
	
			
			
def  IsWebAlive(oItemConfig):
	'''
	检测网页是否存活，返回元组:包括状态和网页内容(如果需要)
	'''
	url = oItemConfig['url']
	try:
		socket.setdefaulttimeout(MONITOR['timeout'])
		result = urllib2.urlopen(url)		
	except:
		try:
			time.sleep(1)
			socket.setdefaulttimeout(MONITOR['timeout'])
			result = urllib2.urlopen(url)
		except Exception as e:		
			save_log('ERROR','request url fialed! url:'+url)
			return str(0),str(e)
	content = result.read()
	code = str(result.code)
	httpcode = oItemConfig['httpcode'].split(',')
	if code in httpcode:
		c1 = 1 #网页存活有两个条件，第一个条件是状态码存在
	else:
		c1 = 0	
	c2 = 1 #假设第二个条件为真：关键字都匹配
	for i in range(len(oItemConfig['keywords'].split(','))):
		if not oItemConfig['keywords'].split(',')[i] in content:
			c2 = 0	#满足某个关键字不匹配，则条件二为假
	if c1 and c2:
		return str(1),content
	else:
		return str(0),content
		

def ExistInserverinfo(oMonitorId,oIpOrUrl):
	'''
	在osa_serverinfo里查找某条记录。若存在，返回这条记录
	'''
	sql = "SELECT * FROM osa_serverinfo WHERE oMonitorId = "+str(oMonitorId)+" AND oIpOrUrl='"+oIpOrUrl+"'"
	result = select(sql)
	if result:
		return list(result[0])
	else:
		return None
def Update_osa_serverinfo(data):
	'''
	更新osa_serverinfo表里的oStatus和oNotiNum字段
	'''
	time.sleep(round(float(random.randrange(0, 100, 1))/100,2))
	sql="UPDATE osa_serverinfo SET oStatus='"+data[3]+"',oNotiNum="+str(data[4])+" WHERE id="+str(data[0])
	try:
		update(sql)
	except Exception as e:
		save_log('ERROR','sql:'+sql+'fail! ERROR:'+str(e))
		
def Update_osa_alarms(data):
	'''
	更新osa_alarms表里的oNextCheckTime字段 
	'''
	sql= "UPDATE osa_alarms SET oNextCheckTime='"+data[14]+"' WHERE id="+str(data[0])
	try:
		time.sleep(round(float(random.randrange(0, 100, 1))/100,2))
		update(sql)
	except Exception as e:
		save_log('ERROR','Update_osa_alarms sql:'+sql+'fail! ERROR:'+str(e))	

def InsertInto_osa_serverinfo(oMonitorId,oIpOrUrl,oStatus,oNotiNum):
	'''
	为osa_serverinfo表增加新的条目
	'''
	sql = "INSERT INTO  osa_serverinfo (`oMonitorId`, `oIpOrUrl`, `oStatus`, `oNotiNum`, `oAddTime`) VALUES ("+str(oMonitorId)+",'"+oIpOrUrl+"','"+oStatus+"',"+str(oNotiNum)+",'"+str(_get_time(1))+"')" 
	try:
		con = _get_pcon()
		cur = con.cursor()
		cur.execute(sql)
	except Exception as e:
		save_log('ERROR','sql INSERT fail! sql:'+sql+'ERROR:'+str(e))
		return 
	return 


def DatabaseUpdateWhenRecovey(result,entry_list):
	result[3] = '1'
	result[4] = 0
	Update_osa_serverinfo(result)
	entry_list[14]=CalcNextCheckTime(entry_list)
	Update_osa_alarms(entry_list)
	return
def DatabaseUpdateWhenException(result,entry_list):
	result[4]=result[4]+1
	result[3] = '0'
	Update_osa_serverinfo(result)
	entry_list[14]=CalcNextCheckTime(entry_list)
	Update_osa_alarms(entry_list)
	return
def SetNextCheckTime(entry_list):
	entry_list[14]=CalcNextCheckTime(entry_list)
	Update_osa_alarms(entry_list)
	return

		
def ThreadForEachServer(each_ipinfo,AllUserEmailAddress):
	'''
	子线程入口函数，检测服务器的状态
	'''
	if PortIsAlive(each_ipinfo[1],SOCKET['REMOTE_PORT']):
		if each_ipinfo[3]=='0' or each_ipinfo[4] != '正常':	
			sql="UPDATE osa_ipinfo SET oStatus = '正常',oIsAlive = '1',oIsAliveNum = 0 WHERE id ="+str(each_ipinfo[0])
			row = update(sql)
			CreateAlarmMsg('服务器存活检测',1,each_ipinfo[1],'服务器'+each_ipinfo[1]+"已恢复正常",0)
			for EmailAddress in AllUserEmailAddress:
				try:
					notifyByEmail(subject='[INFO]Notify from OSA',content = "Message level: INFO, Server "+each_ipinfo[1]+"already has recovered!",address = EmailAddress)
				except Exception as e:
					save_log('ERROR','send mail to '+EmailAddress+' failed!! ERROR:'+str(e))
			sys.exit()
		sys.exit()
	else:
		result = Ping(each_ipinfo[1])
		if True == result:
			if each_ipinfo[3]=='0':
				
				oIsAliveNum=int(each_ipinfo[5])
				
				if oIsAliveNum < 2:
					for EmailAddress in AllUserEmailAddress:
						try:
							notifyByEmail(subject='[WARNING]Notify from OSA',content = "Message level: WARNING,Server "+each_ipinfo[1]+":python client service exception now!",address = EmailAddress)
						except Exception as e:
							save_log('ERROR','send mail to '+EmailAddress+' failed!! ERROR:'+str(e))
					oIsAliveNum = oIsAliveNum + 1
					sql = "UPDATE osa_ipinfo SET oStatus = '服务异常',oIsAliveNum="+str(oIsAliveNum)+" WHERE id ="+str(each_ipinfo[0])
					row = update(sql)	
					sys.exit()
				elif oIsAliveNum >= 2:
					sql = "UPDATE osa_ipinfo SET oStatus = '服务异常' WHERE oIp ='"+each_ipinfo[1]+"'"
					row = update(sql)
					sys.exit()
			if each_ipinfo[3] == '1':
				CreateAlarmMsg('服务器存活检测',1,each_ipinfo[1],'服务器'+each_ipinfo[1]+"服务异常",2)
				sql = "UPDATE osa_ipinfo SET oStatus = '服务异常',oIsAlive = '0',oIsAliveNum=1 WHERE id ="+str(each_ipinfo[0])
				row = update(sql)
				for EmailAddress in AllUserEmailAddress:
					try:
						notifyByEmail(subject='[WARNING]Notify from OSA',content="Message level: WARNING,Server "+each_ipinfo[1]+":python client service exception now!",address = EmailAddress)
					except Exception as e:
						save_log('ERROR','send mail to '+EmailAddress+' failed!! ERROR:'+str(e))
				sys.exit()
		elif False == result:
			if  each_ipinfo[3] == '0':
				oIsAliveNum=int(each_ipinfo[5])
				if oIsAliveNum < 2:
					for EmailAddress in AllUserEmailAddress:
						try:
							notifyByEmail(subject='[CRITICAL]Notify from OSA',content  = "Message level: CRITICAL,Server "+each_ipinfo[1]+"doesn't reach!",address = EmailAddress)
						except Exception as e:
							save_log('ERROR','send mail to '+EmailAddress+' failed!! ERROR:'+str(e))
					oIsAliveNum = oIsAliveNum + 1
					sql = "UPDATE osa_ipinfo SET oStatus = '服务器不可达',oIsAliveNum="+str(oIsAliveNum)+" WHERE id ="+str(each_ipinfo[0])
					row = update(sql)
					sys.exit()
				if oIsAliveNum>=2:
					sql = "UPDATE osa_ipinfo SET oStatus = '服务器不可达' WHERE oIp ='"+each_ipinfo[1]+"'"
					row = update(sql)
					sys.exit()
			if  each_ipinfo[3] == '1':
				CreateAlarmMsg('服务器存活检测',1,each_ipinfo[1],'服务器'+each_ipinfo[1]+"不可达",1)
				for EmailAddress in AllUserEmailAddress:
					try:
						notifyByEmail(subject='[CRITICAL]Notify from OSA',content = "Message level: CRITICAL ,Server "+each_ipinfo[1]+"doesn't reach!!",address = EmailAddress)
					except Exception as e:
						save_log('ERROR','send mail to '+EmailAddress+' failed!! ERROR:'+str(e))
					oIsAliveNum=int(each_ipinfo[5])
					oIsAliveNum=oIsAliveNum+1
					sql = "UPDATE osa_ipinfo SET oStatus = '服务器不可达',oIsAlive = '0',oIsAliveNum="+str(oIsAliveNum)+" WHERE id ="+str(each_ipinfo[0])
					row = update(sql)
					sys.exit()
	sys.exit()
