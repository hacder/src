#!/usr/bin/python
#encoding=utf-8
'''
 Autor: osa开源团队
 Description:监控报警任务、
 create date：2012-05-29
'''
import  Queue
import  sys,signal,os,random
from ctrlpy.lib import cmdtosql
from  ctrlpy.lib.osaDamoClass import Daemon
from threading import Thread
from multiprocessing import Process
from ctrlpy.lib.osaMonitorLib import *
from ctrlpy.lib.osaUtil import save_log,save_Thread_log
from ctrlpy.etc.config import MONITOR,DIRS
from ctrlpy.lib.hostSocket import proSocket,PortIsAlive

import datetime #调试用

def GetItemNeedRun():
	'''
	检查oNextCheckTime字段，返回需要运行的监控条目
	'''
	return cmdtosql.select(tablename="osa_alarms",condition="(oNextCheckTime is null or oNextCheckTime <= '"+str(cmdtosql._get_time(flag=1))+"') and oIsAllow=1")


	
def DataBaseCheck(entry,serverip,x):
	'''
	数据库检测的入口函数
	'''
	entry_list=list(entry)
	oItemConfig = eval(entry_list[4])
	database_name = serverip+":"+str(oItemConfig['port'])
	a,b = IsDataBaseNormal(oItemConfig,serverip)
	
	
	result = ExistInserverinfo(entry_list[0],database_name)
	
	save_Thread_log('MONTHREAD',serverip+'_'+str(x)+'_'+oItemConfig['alarmcmd'],'subThread databasecheck func now!status is :'+a+',result is:'+str(result))
	#此次检测结果是正常的
	if a == '1':
		if result:
			if result[3] == '0':
				if entry_list[8] == '1':
					SendMail('RECOVERNOTIFY',entry_list[9],database_name,'databasecheck')
				CreateAlarmMsg(entry_list[1],entry_list[0],serverip,"数据库"+database_name+"已经恢复正常:",0)
				result[3] = '1'
				result[4] = 0
				Update_osa_serverinfo(result)				
				sys.exit()			
		if result == None:
			InsertInto_osa_serverinfo(entry_list[0],database_name,'1',0)			
			sys.exit()	
	if a == '0':
		
		if result:
			
			if result[3] == '1':
				CreateAlarmMsg(entry_list[1],entry_list[0],database_name,"数据库:"+database_name+"出现异常:"+str(b),3)
				SendMail('STATUS_EXCEPTION',entry_list[9],database_name,'databasecheck')
				result[4]=result[4]+1
				result[3] = '0'
				Update_osa_serverinfo(result)				
				sys.exit()
			if result[3] =='0':
				if result[4] <= entry_list[7]:
					SendMail('STATUS_EXCEPTION',entry_list[9],database_name,'databasecheck')
					result[4]=result[4]+1
					Update_osa_serverinfo(result)					
				sys.exit()
		if result == None:
			SendMail('STATUS_EXCEPTION',entry_list[9],database_name,'databasecheck')
			InsertInto_osa_serverinfo(entry_list[0],database_name,'0',1)			
			sys.exit()	
	sys.exit()
	

def  WebStateCheck(entry):
	'''
	检测web是否正常的子进程入口函数
	'''
	
	#转换为LIST,方便操作
	entry_list=list(entry)
		
	#获取配置项
	oItemConfig = eval(entry_list[4])
	#检查网页是否正常
	oStatus = '0'
	webcontent = ''	
	try:
		oStatus,webcontent = IsWebAlive(oItemConfig)
	except Exception as e:
		rtime = round(float(random.randrange(0, 100, 1))/10,2)
		time.sleep(rtime)
		oStatus,webcontent = IsWebAlive(oItemConfig)
	
	#检查是否存在结果
	result = ExistInserverinfo(entry_list[0],oItemConfig['url'])
	
	if oStatus == '1':#此次检测结果是正常的		
		if result:
			if result[3] == '0':#上次检测结果是不正常的
				if entry_list[8] == '1':
					#发送恢复告警通知
					SendMail('RECOVERNOTIFY',entry_list[9],oItemConfig['url'],'webisalive')
				#写记录到告警记录表
				CreateAlarmMsg(entry_list[1],entry_list[0],oItemConfig['url'],"网站"+oItemConfig['url']+"访问已经恢复正常",0)
				#初始化状态
				result[3] = '1'
				result[4] = 0
				Update_osa_serverinfo(result)				
				sys.exit()
				
		if result == None:
			#写入记录到osa_serverinfo
			InsertInto_osa_serverinfo(entry_list[0],oItemConfig['url'],'1',0)			
			sys.exit()
			
	
	if oStatus == '0':#此次检测结果是不正常的
		if result:
			if result[3] == '1':#上次检测结果是正常的
				
				SendMail('STATUS_EXCEPTION',entry_list[9],oItemConfig['url'],'webisalive')
				##记录快照
				logdir = DIRS['PY_OSA_LOG'] + time.strftime('%Y_%m', time.localtime()) + '/'
	
				if not os.path.exists(logdir):
					os.system('mkdir -p ' + logdir)	
				log_file = logdir + time.strftime('%Y_%m_%d_%s', time.localtime()) + '_sitecode.log'
				f = open(log_file, 'a')
				f.write('code info:' + ' ' + time.strftime('%Y-%m-%d %H:%M:%S', time.localtime()) + '\n' + str(webcontent) + '\n')
				f.close()	
				#创建告警记录
				CreateAlarmMsg(entry_list[1],entry_list[0],oItemConfig['url'],oItemConfig['url']+"站点出现异常,OSA己帮助您保存快照,文件名为："+str(log_file),3)
				#更新状态和告警次数
				result[4]=result[4]+1
				result[3] = '0'
				Update_osa_serverinfo(result)
				sys.exit()
			if result[3] =='0':
				
				#判断是否己经超过告警次数
				if result[4] < entry_list[7]:
					SendMail('STATUS_EXCEPTION',entry_list[9],oItemConfig['url'],'webisalive')
					result[4]=result[4]+1
					Update_osa_serverinfo(result)				
				sys.exit()
				
		if result == None:
			SendMail('STATUS_EXCEPTION',entry_list[9],oItemConfig['url'],'webisalive')
			##记录快照
			logdir = DIRS['PY_OSA_LOG'] + time.strftime('%Y_%m', time.localtime()) + '/'
	
			if not os.path.exists(logdir):
				os.system('mkdir -p ' + logdir)	
			log_file = logdir + time.strftime('%Y_%m_%d_%s', time.localtime()) + '_sitecode.log'
			f = open(log_file, 'a')
			f.write('code info:' + ' ' + time.strftime('%Y-%m-%d %H:%M:%S', time.localtime()) + '\n' + str(webcontent) + '\n')
			f.close()	
			CreateAlarmMsg(entry_list[1],entry_list[0],oItemConfig['url'],oItemConfig['url']+"站点出现异常,OSA己帮助您保存快照,文件名为："+str(log_file),3)
			InsertInto_osa_serverinfo(entry_list[0],oItemConfig['url'],'0',1)				
			sys.exit()		
	sys.exit()
	
	
	
def DatabaseUpdateWhenRecovey(result,entry_list):
	'''
	告警恢复时数据库更新
	'''
	result[3] = '1'
	result[4] = 0
	
	Update_osa_serverinfo(result)
	return
	
def DatabaseUpdateWhenAlwaysOK(entry_list):
	###TypeError: 'tuple' object does not support item assignment
	####
	entry_list = list(entry_list)
	entry_list[14]=CalcNextCheckTime(entry_list)
	Update_osa_alarms(entry_list)
	return

def getMonitorIplist(entry):
	'''
	获取需要监控的服务器IP地址
	'''
	serveriplist = []
	entry_list = list(entry)		
	oServerList = entry_list[5]
	if oServerList == None:
		return False
	if oServerList ==  'ALL':
		server_ip_list = select("SELECT oIp FROM osa_ipinfo WHERE oIsAlive = '1'")
		for ip in server_ip_list:
			serveriplist.append(ip[0])
	else: 
		serveriplist = oServerList.split('|')
	return serveriplist

def serverChooseAlarm(alarmcmd,serverChooseDict,serverip,entry,oMonText,x):
	'''
	根据不同监控类型执行不同的告警方式
	'''

	oItemConfig = {}
	oItemConfig['alarmcmd'] = alarmcmd
	save_Thread_log('MONTHREAD',serverip+'_'+str(x)+'_'+oItemConfig['alarmcmd'],'subThread in serverChooseAlarm,clientdata now!! oMonText is: '+str(oMonText))
	
	#从字典中提出键值，比如：'diskstat'
	ckey = serverChooseDict[alarmcmd]['oMonText']	
	
	try:
		oMonTextDic = eval(oMonText[1])
	except Exception as e:
		save_log('ERROR','oMonTextDic is error:'+str(e)+',ip is:'+str(serverip)+', str : '+str(oMonText))
		sys.exit()
	
	clientdata = None
	
	if ckey in oMonTextDic:
		clientdata = oMonTextDic[ckey]
	else:
		save_Thread_log('MONTHREAD',serverip+'_'+str(x)+'_'+oItemConfig['alarmcmd'],'subThread in serverChooseAlarm,clientdata error exit now!! oMonText is '+str(oMonText))
		sys.exit(0)
	

	entry_list = list(entry)
	
	okey = serverChooseDict[alarmcmd]['oItemConfig']
	
	oItemConfig = eval(entry_list[4])
	
	
	a = []
	
	if alarmcmd == 'diskspacecheck':
		a = IsDiskSpaceNormal(clientdata,int(oItemConfig[okey]))
	elif alarmcmd == 'topstatcheck':
		a = IsLoadStatNormal(clientdata,int(oItemConfig[okey]))
	elif alarmcmd == 'loginusercheck':
		a = IsLoginUserOver(clientdata,int(oItemConfig[okey]))
	elif alarmcmd == 'networkcheck':
		a = IsNetworkTrafficNormal(clientdata,int(oItemConfig[okey]))
	else:
		sys.exit()
	
	#恢复时通知信息
	rstr = serverChooseDict[alarmcmd]['strrecov']
	
	#异常时告警通知信息
	estr = serverChooseDict[alarmcmd]['errorstr1'] 	+ str(a[1])
	
	if len(a) > 2:
		estr = estr+  serverChooseDict[alarmcmd]['errorstr2'] + str(a[2])
	
	save_Thread_log('MONTHREAD',serverip+'_'+str(x)+'_'+oItemConfig['alarmcmd'],'subThread in serverChoose func now! status is '+str(a[0]))
	#此次检测结果是正常的	
	if a[0] == True:			
			
		result = ExistInserverinfo(entry_list[0],serverip)
		save_Thread_log('MONTHREAD',serverip+'_'+str(x)+'_'+oItemConfig['alarmcmd'],'subThread in serverChoose func exit now! result is '+str(result))
		#如果osa_serverinfo存在记录了
		if result:
			#如果原来记录为不正常
			if result[3] == '0':
				#是否恢复通知为 1 发送邮件
				if entry_list[8] == '1':
					SendMail('RECOVERNOTIFY',entry_list[9],serverip,alarmcmd)
					
				#往osa_alarmmsg表里增加新的条目
				CreateAlarmMsg(entry_list[1],entry_list[0],serverip,"服务器"+serverip+rstr,0)
					
				#恢复时更新数据状态和下一次检测时间
				DatabaseUpdateWhenRecovey(result,entry_list)
				sys.exit()
			
					
		#如果osa_serverinfo不存在记录，则添加记录，更新下一次检测时间
		if result == None:
			
			InsertInto_osa_serverinfo(entry_list[0],serverip,'1',0)			
			sys.exit()
				
	#此次检测结果是异常的		
	if a[0] == False:
		    
		result = ExistInserverinfo(entry_list[0],serverip)
		
		save_Thread_log('MONTHREAD',serverip+'_'+str(x)+'_'+oItemConfig['alarmcmd'],'subThread in serverChoose func exit now! result is '+str(result))
		#如果osa_serverinfo 存在记录
		if result:
			#如果之前记录为正常
			if result[3] == '1':
				#发送异常告警通知，添加告警记录。
				CreateAlarmMsg(entry_list[1],entry_list[0],serverip,"服务器"+serverip+estr,3)
				SendMail('STATUS_EXCEPTION',entry_list[9],serverip,alarmcmd)
					
				#告警次数加1
				result[4]=result[4]+1
				#状态为0，表示不正常
				result[3] = '0'
					
				#更新osa_serverinfo记录！
				Update_osa_serverinfo(result)
				#更新下一次时间
				sys.exit()
					
			#如果之前记录为异常
			if result[3] =='0':
				#如果当前告警次数小于用户设定的告警次数，则发送告警
				if result[4] < entry_list[7]:
					SendMail('STATUS_EXCEPTION',entry_list[9],serverip,alarmcmd)
					result[4]=result[4]+1
					Update_osa_serverinfo(result)
				sys.exit()
			
		#如果osa_serverinfo不存在记录，则添加记录，更新下一次检测时间
		################补充###########################################
		if result == None:
			#发送异常告警通知，添加告警记录。
			CreateAlarmMsg(entry_list[1],entry_list[0],serverip,"服务器"+serverip+estr,3)
			SendMail('STATUS_EXCEPTION',entry_list[9],serverip,alarmcmd)					
									
			#更新状态为0，告警次数为1
			InsertInto_osa_serverinfo(entry_list[0],serverip,'0',1)
			sys.exit()
	sys.exit()
	
def serverMonitor(serverip,entry,x):
	'''
	   服务器信息监控入口线程函数
	'''	
	entry_list = list(entry)
	#配置项
	oItemConfig = eval(entry_list[4])
	
	save_Thread_log('MONTHREAD',serverip+'_'+str(x)+'_'+oItemConfig['alarmcmd'],'subThread start now!------------------------------')
	
	save_Thread_log('MONTHREAD',serverip+'_'+str(x)+'_'+oItemConfig['alarmcmd'],'subThread name:' + oItemConfig['alarmcmd'])
	
	#取信息指令
	cmd = 'SYSTEM_RUN_COMMAND!{"mon_all_stat":""}'
	
	
	save_Thread_log('MONTHREAD',serverip+'_'+str(x)+'_'+oItemConfig['alarmcmd'],'subThread oMonText begin,Cmd is :'+cmd)
	
	##如果是端口或者数据库检测就不需要取客户端信息了
	if oItemConfig['alarmcmd'] == 'portstatcheck' or oItemConfig['alarmcmd'] == 'databasecheck':
		oMonText  = '1!1'	
		save_Thread_log('MONTHREAD',serverip+'_'+str(x)+'_'+oItemConfig['alarmcmd'],'subThread subtype is portstatcheck or databasecheck ,subtype is :'+oItemConfig['alarmcmd'])

	elif PortIsAlive(serverip,SOCKET['REMOTE_PORT']) == False:	
	
		each_ipinfo = None
		#端口如果不通,说明服务器异常!
		each_ipinfo_list = select("SELECT * FROM osa_ipinfo where oIp = '"+serverip+"'")
		if each_ipinfo_list :
			each_ipinfo = each_ipinfo_list[0]
		else:
			save_log('INFO','ip not found:'+str(serverip))
			sys.exit()
		AllUserEmailAddress = GetUserEmailAddress('ALL')
		
		save_Thread_log('MONTHREAD',serverip+'_'+str(x)+'_'+oItemConfig['alarmcmd'],'subThread server PortIsAlive == false,exit now!,IP is :'+serverip+',each_info is:'+str(each_ipinfo)+',AllUserEmailAddress is :'+str(AllUserEmailAddress))
		#检测单个服务器是否正常，是否需要告警
		try:
			
			ThreadForEachServer(each_ipinfo,AllUserEmailAddress)	
		except Exception as e:
			save_log('ERROR','ThreadForEachServer run fail first:'+str(e))
			sys.exit()		
		sys.exit()
	
	else:
		rtime = round(float(random.randrange(0, 100, 1))/10,2)
		time.sleep(rtime)
	
		oMonText  = proSocket(serverip, SOCKET['REMOTE_PORT'], cmd, type=None)
	
	if oMonText:	
		oMonText = oMonText.split('!')
		save_Thread_log('MONTHREAD',serverip+'_'+str(x)+'_'+oItemConfig['alarmcmd'],'subThread get oMonText over!,oMonText is :'+str(oMonText))
	else:
		save_Thread_log('MONTHREAD',serverip+'_'+str(x)+'_'+oItemConfig['alarmcmd'],'subThread get oMonText faild ,exit now!')
		each_ipinfo = None
		#这里如果没取到数据,说明服务器异常!
		each_ipinfo_list = select("SELECT * FROM osa_ipinfo where oIp = '"+serverip+"'")
		if each_ipinfo_list :
			each_ipinfo = each_ipinfo_list[0]
		AllUserEmailAddress = GetUserEmailAddress('ALL')
		
		save_Thread_log('MONTHREAD',serverip+'_'+str(x)+'_'+oItemConfig['alarmcmd'],'subThread server PortIsAlive == false,exit now!,IP is :'+serverip+',each_info is:'+str(each_ipinfo)+',AllUserEmailAddress is :'+str(AllUserEmailAddress))
			
		#检测单个服务器是否正常，是否需要告警
		try:
			
			ThreadForEachServer(each_ipinfo,AllUserEmailAddress)	
		except Exception as e:
			save_log('ERROR','ThreadForEachServer run fail again:'+str(e))			
			sys.exit()		
		sys.exit()
		
	save_log('INFO','serverMonitor oMonText : '+str(oMonText))
	
	#将不同的配置项提出来！
	serverChooseDict={

		'diskspacecheck' : {'oMonText' : 'diskstat','oItemConfig' : 'percentage', 'strrecov' : '的磁盘空间率已经恢复!' , 'errorstr1' : '的磁盘使用率过高,分区 ' , 'errorstr2' : '当前使用率为：'},
		'topstatcheck' : {'oMonText' : 'loadstat','oItemConfig' : 'topvalue', 'strrecov' : '的负载已经恢复正常!' , 'errorstr1':'的负载状态过高。当前负载值：','errorstr2' : ''},
		'loginusercheck' : {'oMonText' : 'login','oItemConfig' : 'usernum', 'strrecov' : '的登录用户数量已经恢复正常!' , 'errorstr1':'登录用户过多。当前用户数：','errorstr2':''},
		'networkcheck' : {'oMonText' : 'network','oItemConfig' : 'topvalue', 'strrecov' : '的网络流量已经恢复正常!' , 'errorstr1':'流量过载。网卡 ','errorstr2':',当前进出流量峰值(MB)： '}
	
	}
	
	#接下来走不同的函数完成监控
	if oItemConfig['alarmcmd'] in serverChooseDict.keys():
		save_Thread_log('MONTHREAD',serverip+'_'+str(x)+'_'+oItemConfig['alarmcmd'],'subThread serverChoose now!')
		return serverChooseAlarm(oItemConfig['alarmcmd'],serverChooseDict,serverip,entry,oMonText,x)
	
	#数据库报警
	if oItemConfig['alarmcmd'] == 'databasecheck':
		save_Thread_log('MONTHREAD',serverip+'_'+str(x)+'_'+oItemConfig['alarmcmd'],'subThread databasecheck now!')
		return DataBaseCheck(entry,serverip,x)	
	
	
	#端口报警
	if oItemConfig['alarmcmd'] == 'portstatcheck':		
		a=IsPortAlive(serverip,oItemConfig['portlist'])
		save_Thread_log('MONTHREAD',serverip+'_'+str(x)+'_'+oItemConfig['alarmcmd'],'subThread portstatcheck func now!status is :'+str(a[0]))
		if a[0] == True:#此次检测结果是正常的
			result = ExistInserverinfo(entry_list[0],serverip)
			save_Thread_log('MONTHREAD',serverip+'_'+str(x)+'_'+oItemConfig['alarmcmd'],'subThread portstatcheck exit now!result is :'+str(result))
			if result:
				if result[3] == '0':
					if entry_list[8] == '1':
						SendMail('RECOVERNOTIFY',entry_list[9],serverip,'portstatcheck')
					CreateAlarmMsg(entry_list[1],entry_list[0],serverip,"服务器"+serverip+"的端口访问已经恢复正常:"+oItemConfig['portlist'],0)
					result[3] = '1'
					result[4] = 0
					Update_osa_serverinfo(result)					
					sys.exit()				
			if result == None:
				InsertInto_osa_serverinfo(entry_list[0],serverip,'1',0)				
				sys.exit()
		if a[0] == False:
			result = ExistInserverinfo(entry_list[0],serverip)
			save_Thread_log('MONTHREAD',serverip+'_'+str(x)+'_'+oItemConfig['alarmcmd'],'subThread portstatcheck exit now!result is :'+str(result))
			if result:
				if result[3] == '1':
					CreateAlarmMsg(entry_list[1],entry_list[0],serverip,"服务器"+serverip+"端口:"+a[1]+"异常",3)
					SendMail('STATUS_EXCEPTION',entry_list[9],serverip,'portstatcheck')
					result[4]=result[4]+1
					result[3] = '0'
					Update_osa_serverinfo(result)					
					sys.exit()
				if result[3] =='0':
					if result[4] < entry_list[7]:
						SendMail('STATUS_EXCEPTION',entry_list[9],serverip,'portstatcheck')
						result[4]=result[4]+1
						Update_osa_serverinfo(result)									
					sys.exit()
		#如果osa_serverinfo不存在记录，则添加记录，更新下一次检测时间
			if result == None:
			#发送异常告警通知，添加告警记录。
				CreateAlarmMsg(entry_list[1],entry_list[0],serverip,"服务器"+serverip+'端口:'+a[1]+' 未存活！',3)
				SendMail('STATUS_EXCEPTION',entry_list[9],serverip,oItemConfig['alarmcmd'])					
									
			#更新状态为0，告警次数为1
				InsertInto_osa_serverinfo(entry_list[0],serverip,'0',1)
				sys.exit()
	sys.exit()



def ServerStateCheck(entry):
	'''
	磁盘空间、负载状态、端口存活、登录用户数量、网络峰值告警的主要函数
	使用循环取出每一个需要监控的ip，然后使用if判断具体监控类型，走不同分支
	'''
	
	#IP列表
	serveriplist = getMonitorIplist(entry)
	
	if not serveriplist or serveriplist == False:
		sys.exit()
		os._exit(0)
	
	#准备队列
	ipQ =  Queue.Queue()
	for serverip in serveriplist:
	    #ip地址入队		
		ipQ.put(serverip)
	
	while True:
		if ipQ.qsize() == 0:
			break
		for x in xrange(MONITOR['maxthread']):
			if ipQ.qsize() == 0:
				break	
				
			serverip = ipQ.get()
			
			try:
				#子线程开始			
				tt = threading.Thread(target=serverMonitor,args=[serverip,entry,x])				
				tt.setDaemon(0)
				tt.start()				
			except Exception as e:
				save_log('ERROR','Monitor subThread is error:'+str(e))
	time.sleep(60)
	sys.exit()
		
				
				


def PutMonitorToQueue(result):
	'''
	把从数据库里获得任务先放入队列
	'''
	q = Queue.Queue()
	for i in result:
		q.put(i)
	return q
	
def GetMonitorFromQueue(queue):
	'''
	从队列里取出数据
	'''
	try:
		entry = queue.get(block=False)
		return entry
	except Queue.Empty:
		return None

def ChooseMonitorFunction(oItemConfig):
	'''
	判断监控的类型，返回对应的入口函数的函数名
	'''
	oItemConfig=eval(oItemConfig)
	if oItemConfig['alarmcmd'] == 'webisalive':
		return WebStateCheck
	elif oItemConfig['alarmcmd'] == 'diskspacecheck':
		return ServerStateCheck
	elif oItemConfig['alarmcmd'] == 'topstatcheck':
		return ServerStateCheck
	elif oItemConfig['alarmcmd'] == 'portstatcheck':
		return ServerStateCheck
	elif oItemConfig['alarmcmd'] == 'databasecheck':
		return ServerStateCheck
	elif oItemConfig['alarmcmd'] == 'loginusercheck':
		return ServerStateCheck
	elif oItemConfig['alarmcmd'] == 'networkcheck':
		return ServerStateCheck
	else:
		return None
	
	

def MonitorRun():
	'''
	服务监控进程
	'''
	while True:
		#获取需要执行的监控项目
		result=GetItemNeedRun()			
		
		if result:
			#将监控项目放入队列
			q = PutMonitorToQueue(result)
			#记录监控项目的条数
			save_log('INFO','qsize:'+str(q.qsize()))
			while True:
				if q.qsize() == 0:
					break
				for i in range(MONITOR['maxprocess']):
					entry = GetMonitorFromQueue(q)
					
					if entry == None:
						break
						
					#更新下一次执行时间
					DatabaseUpdateWhenAlwaysOK(entry)					
					
					#选择执行函数
					doMonitor = ChooseMonitorFunction(entry[4])					
					
					save_log('INFO','monitor subprocess start:'+str(doMonitor)+',type:'+entry[3])
					try:
						#处理子进程退出信号
						signal.signal(signal.SIGCHLD,signal.SIG_IGN)						
						p = Process(target=doMonitor,args=[entry])						
						p.start()
						#p.join() #防止阻塞主进程循环
					except Exception as e:
						save_log('ERROR','run monitor subprocess fail:'+str(e))
		#给数据库一点空闲时间处理其他事务		
		time.sleep(0.5)

class osaMonitorDaemon(Daemon):
	def _run(self):
		MonitorRun()

if __name__=='__main__':
	daemon=osaMonitorDaemon(DIRS['ROOT']+'osaMonitor.pid')
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
