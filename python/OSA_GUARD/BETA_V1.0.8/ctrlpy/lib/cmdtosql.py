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
from ctrlpy.lib.osaLogLib import *
from ctrlpy.etc.config import SOCKET,DIRS,LEAVEL


def setLock():
	'''
	开锁，防止进程同时争取数据库资源
	'''
	lock =  DIRS['CFG_ROOT']+'py.table.lock'

	while os.path.exists(lock):
		time.sleep(random.randint(0,10))

	os.system('touch '+ lock)
	
	return lock
		
def _get_con():
	'''
	获取数据库连接
	'''
	host = MYSQL['HOST']
	user = MYSQL['USER']
	passwd = MYSQL['PASSWD']
	db = MYSQL['DB']
	port = int(MYSQL['PORT'])
	pooldb = PooledDB.PooledDB(MySQLdb, maxusage=MYSQL['SIZE'], 
			host=host, user=user, passwd=passwd, db=db ,port= port,charset='utf8')
	return pooldb.connection()


def _get_pcon():
	'''
	获取数据库连接
	'''
	MDB = MySQLdb.connect(host=MYSQL['HOST'],user=MYSQL['USER'],
			passwd=MYSQL['PASSWD'],db=MYSQL['DB'],port=int(MYSQL['PORT']),charset='utf8')
	return MDB

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
		_exit(con, cur)
		con = _get_con()
		cur = con.cursor()	
		cur.execute(sql)
		res=cur.fetchall()		
		_exit(con, cur)
		return res
	
def execsql(sqlstr):
	'''
	@执行sql语句
	'''
	try:
		con=_get_pcon()
		cur=con.cursor()
		cur.execute(sqlstr)
	except Exception as e:
		_exit(con, cur)
		con = _get_con()
		cur = con.cursor()	
		cur.execute(sqlstr)
		_exit(con,cur)
		
	
def update(sqlstr):
	'''
	更新数据库,update操作
	@sqlstr SQL语句
	return 更新的行数
	'''
	con = _get_pcon()
	cur = con.cursor()
	
	if sqlstr[0:6].upper() == 'UPDATE':
		sql = sqlstr
	else:
		return 0
	try:
		cur.execute(sql)
	except Exception as uerror:
		_exit(con, cur)
		log_error('update sql error 1:'+str(uerror)+',sql is :'+sqlstr)
		time.sleep(random.randint(0,10))
		con = _get_con()
		cur = con.cursor()
		try:
			cur.execute(sql)	
		except Exception as uerror:
			log_error('update sql error 2:'+str(uerror)+',sql is :'+sqlstr)
			
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
	

		
