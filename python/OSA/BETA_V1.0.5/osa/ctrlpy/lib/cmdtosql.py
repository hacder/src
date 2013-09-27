#!/usr/bin/env python
#encoding=utf-8

'''
	Author:		osa开源团队
	Description:	数据库主要操作方法
	Create Date:	2011-07-20
'''

import time
import os
import sys
import random
import MySQLdb
import smtplib
from ctrlpy.etc.config import MYSQL
from DBUtils import PooledDB
from ctrlpy.lib import hostSocket
from ctrlpy.lib.osaUtil import save_log
from ctrlpy.etc.config import SOCKET,DIRS,LEAVEL

 


def addCmd(cmd, oCmdText=None, oCmdType=0, oCmdStatus=1):
	'''
	添加指令到数据库
	@cmd: 指令名称
	@oCmdText: 指令说明，默认为空
	@oCmdType: 指令类型，默认0代表系统指令，其他为自定义指令
	@oCmdStatus: 指令状态，默认为1代表正常，0代表禁用
	'''
	
	addTime = _get_time(1)
	con = _get_con()
	cur = con.cursor()
	sql = "insert into osa_command(oCmdaddTime, oCmdTitle, oCmdText, oCmdType, oCmdStatus) \
			values('%(addTime)s', '%(cmd)s', '%(oCmdText)s', %(oCmdType)d, %(oCmdStatus)d)" % vars()
	cur.execute(sql)
	_exit(con, cur)

	
def setLock():
	'''
	开锁，防止进程同时争取数据库资源
	'''
	lock =  DIRS['CFG_ROOT']+'py.table.lock'

	while os.path.exists(lock):
		time.sleep(random.randint(0,10))

	os.system('touch '+ lock)
	
	return lock
	
	
def insertMonitor(ip, cmd, monitime):
	'''
	监控记录入库
	@ip: 被监控主机Ip
	@cmd: 包括指令名称
	'''

	oipid = 'null'
	# 获取oipid
	oipid = getIdByIp(ip)
	if oipid == 'null' or not oipid:
		oipid = 0

	oMonTextRemote = False

	if hostSocket.PortIsAlive(ip, SOCKET['REMOTE_PORT']):
        # 从远程服务器获取数据  
		oMonTextRemote = hostSocket.proSocket(ip, SOCKET['REMOTE_PORT'], cmd)
                

	if oMonTextRemote:
		oMonText = oMonTextRemote.split('!')
	else:
		oMonText = ['mon_all_stat','null']

	#开锁，解决数据库资源争用问题
	lock = setLock()
	cmdid = 'null'
	# 获取cmdid(指令ID)
	try:	
		cmdid = addCmdgetId(cmd)
		if cmdid == 'null' or not cmdid:
			time.sleep(random.randint(0,10))
			cmdid = addCmdgetId(cmd)
	except Exception as e:
		time.sleep(random.randint(0,10))
		cmdid = 0
		save_log( 'ERROR',  'cmdid error:'+str(e) +','+ cmd )
	
	cmdtext = oMonText[1]
	
	try:
		sql = "insert into osa_monitor(oIpid, oCmdid, oMonTime, oMonText) \
				values (%(oipid)d, %(cmdid)d, '%(monitime)s','%(cmdtext)s')" % vars()
		con = _get_pcon()
		cur = con.cursor()
		cur.execute(sql)
	
	except TypeError,e:
		con = _get_con()
		cur = con.cursor()
		cur.execute(sql)
		os.system('rm -f ' + lock)
		save_log('WARNING','insertMonitor : '+str(e))
		sys.exit()

	except AttributeError,error:
		time.sleep(random.randint(0,10))
		con = _get_con()
		cur = con.cursor()
		cur.execute(sql)
		os.system('rm -f ' + lock)
		save_log('WARNING','insertMonitor : '+str(error))
		sys.exit()
	
	except Exception as e:
		time.sleep(random.randint(0,10))
		con = _get_con()
		cur = con.cursor()
		os.system('rm -f ' + lock)
		save_log('ERROR', 'insertMonitor error : '+str(e))
		sys.exit()
	
	finally:
		os.system('rm -f ' + lock)
		_exit(con, cur)
		sys.exit()
		
	
def _get_con():
	'''
	获取数据库连接
	'''
	
	host = MYSQL['HOST']
	user = MYSQL['USER']
	passwd = MYSQL['PASSWD']
	db = MYSQL['DB']
	port = int(MYSQL['PORT'])
	
	i=0
	while True:
		if i > 10:
			save_log('ERROR', 'pooldb connection error !')
			sys.exit()
		time.sleep(round(float(random.randrange(0, 100, 1))/100,2))
		try:
			pooldb = PooledDB.PooledDB(MySQLdb, maxusage=MYSQL['SIZE'], 
					host=host, user=user, passwd=passwd, db=db ,port= port,charset='utf8')	
			return pooldb.connection()
			
		except Exception as e:
			i=i+1
			continue
	
			
	


def _get_pcon():
	'''
	获取数据库连接
	'''
	j=0
	while True:
		if j>10:
			save_log('ERROR', 'MySQLdb connection error !')
			sys.exit()
		time.sleep(round(float(random.randrange(0, 100, 1))/100,2))
		try:
			MDB = MySQLdb.connect(host=MYSQL['HOST'],user=MYSQL['USER'],
					passwd=MYSQL['PASSWD'],db=MYSQL['DB'],port=int(MYSQL['PORT']),charset='utf8')
			return MDB
		except Exception as e:		
			j=j+1
			continue


def _exit(con, cur):
	'''
	关闭数据库连接
	'''

	cur.close()
	con.close()


def select(tablename,condition=None,field=None):
	'''
	获取指写数据库指定字段的值
	@tablename 表名或者是SQL语句，表名不能以select开头
	@condition 条件，默认为空
	@field 字段名,默认为空代表*号
	return 元组格式的数据
	'''

	if tablename[0:6].upper() == 'SELECT':
		sql = tablename
	else:
		if field:
			sql = "SELECT "+field+ " FROM `"+tablename+"`"
		else:
			sql = "SELECT * FROM `"+tablename+"`"
		
		if condition:
			sql = sql + " WHERE " + condition
	try:
		con = _get_pcon()
		cur = con.cursor()
		cur.execute(sql)
		res=cur.fetchall()		
		_exit(con, cur)
		return res
	except Exception as e:		
		con = _get_con()
		cur = con.cursor()	
		cur.execute(sql)
		res=cur.fetchall()		
		_exit(con, cur)
		return res
	


def update(sqlstr):
	'''
	更新数据库,update操作
	@sqlstr SQL语句
	return 更新的行数
	'''

	if sqlstr[0:6].upper() == 'UPDATE':
		sql = sqlstr
	else:
		return 0
	try:
		con = _get_pcon()
		cur = con.cursor()
		cur.execute(sql)
	except Exception as uerror:
		save_log('ERROR','update sql error 1:'+str(uerror)+',sql is :'+sqlstr)
		time.sleep(random.randint(0,10))
		
		try:
			con = _get_con()
			cur = con.cursor()
			cur.execute(sql)	
		except Exception as uerror:
			save_log('ERROR','update sql error 2:'+str(uerror)+',sql is :'+sqlstr)
			
	_exit(con, cur)
		
	return int(cur.rowcount)


def _get_time(flag=0):
	'''
	获取时间
	@flag: 默认为0，返回年月日，如1900-10-10; 其他返回如1900-10-10 10:10:10
	'''

	if flag:
		return time.strftime('%Y-%m-%d %H:%M:%S', time.localtime())
	else:
		return time.strftime('%Y-%m-%d', time.localtime())


def getIdByCmd(cmdTitle):
	'''
	根据指令名获取指令Id
	@cmdTitle: 指令名称
	'''
	
	con = _get_pcon()
	cur = con.cursor()
	
	try:
		cur.execute("select id from osa_command where oCmdTitle='%s'" % cmdTitle)
	except Exception as e:
		time.sleep(0.01)
		cur.execute("select id from osa_command where oCmdTitle='%s'" % cmdTitle)
		save_log('ERROR',e)
		return 'null'
	
	res=cur.fetchone()	
	
	if not res:
		id = 'null'
	else:
		id = res[0]
	
	_exit(con, cur)
	
	return id


def addCmdgetId(cmd):
	'''
	获取指令：cmd 的oCmdid
	判断系统指令是否存在，不存在则添加指令到数据库
	'''
	id = 'null'
	try:
		id = getIdByCmd(cmd)		
		if id == 'null' or not id:
			addCmd(cmd)
			time.sleep(0.001)
			id = getIdByCmd(cmd)
			
	except Exception:
		if id == 'null' or not id:
			addCmd(cmd)
			time.sleep(0.001)
			id = getIdByCmd(cmd)
	return id


def getIdByIp(ip):
	'''
	根据ip获取oipid
	@ip: 主机ip地址
	'''
	
	con = _get_pcon()
	cur = con.cursor()
	try:
		cur.execute("select id from osa_ipinfo where oIp='%s'" % ip)
	
	except Exception as e:
		save_log('ERROR',e)
		return 'null'
	
	res=cur.fetchone()
        
	if not res:
		id = 'null'
	else:
		id = res[0]
	
	_exit(con, cur)
	return id


def getIpList():
	'''
	获取服务器IP列表
	'''

	ilist=[]
	con = _get_pcon()
	cur = con.cursor()
	cur.execute("select oIp from osa_ipinfo")
	iplist = cur.fetchall()

	for ip in iplist:
		ilist.append(ip[0])
	
	_exit(con, cur)
	return ilist


def save_db_log(logstr='python service monitor error info!'):
	'''
	@logstr: 日志内容
	'''

	con = _get_pcon()
	cur = con.cursor()
	logtype = '系统报警日志'
	QlogSql = "select id from osa_syslog_cfg where oTypeText = '" + logtype +'\''
	cur.execute(QlogSql)
	res=cur.fetchone()
	
	if not res:
		InlogSql = "insert into osa_syslog_cfg(oTypeText) values \
				('%(logtype)s')" % vars()
		cur.execute(InlogSql)
		cur.execute(QlogSql)
		res=cur.fetchone()

	oTypeid = res[0]
	oUserName = 'pyrobot'
	oLogTitle = 'PYTHON 监控到服务异常'
	Time = _get_time(1)
	lInlogSql = "insert into osa_syslog(oTypeid , oUserName , oLogTitle , oLogText , oLogAddTime) \
			values (%(oTypeid)d, '%(oUserName)s', '%(oLogTitle)s', '%(logstr)s', '%(Time)s')" % vars()
	cur.execute(lInlogSql)
	_exit(con, cur)
	

def notifyByEmail(subject='Notify From OSA', content=None, address = ''):
	'''
	邮件报警
	'''
	
	maillist = LEAVEL.keys()
	m=False
	tag='0'
	for mail in maillist:
		if mail != address:
			continue
		tag='1'
		leavel = LEAVEL[mail].split(',')
		for l in leavel:
			if subject.find(l) != -1 or content.find(l) != -1:
				m=True				
				break
					
	if m == False and tag == '1':
		save_log('INFO', 'Mail is not sent to '+str(address)+',content is :'+content)
		return
				
	
	sql = "select * from osa_noticonfig limit 1"
	result = select(sql)
	if not result or result == None:
		save_log('ERROR', 'smtp empty error'+str(e))
		return	
	
	SMTPserver = result[0][2]
	FROM = result[0][5]
	USER = result[0][1]
	PASS = result[0][4]
	PORT = result[0][3]

	smtp_server = smtplib.SMTP(SMTPserver,str(PORT))

	try:
		smtp_server.login(USER,PASS)
		msgHead = ['From:  '+str(FROM), 'To:'+ str(address), \
				'Subject: %s' % subject ]
		msg = '\r\n\r\n'.join(['\r\n'.join(msgHead), content])
		smtp_server.sendmail(FROM, address, msg)
		save_log('INFO','send email : ' + str(msg))
	except smtplib.SMTPException , e:
		save_log('ERROR', 'smtp login error:'+str(e))
	finally:
		smtp_server.quit()
		
