#!/usr/bin/env python
# encoding=utf-8

'''
	Author:		osa开源团队
	Description:	监控项目 tcp 端口报警
	Create Date:	2011-08-31
'''
import socket
import sys,simplejson
import time

from datetime import datetime
from ctrlpy.lib.osaLogLib import *
from ctrlpy.etc.config import SOCKET
from ctrlpy.lib import cmdtosql


def tcp_connect_check(host,port,count=SOCKET['COUNT']):
	'''
	@Tcp 连接检测 | hostSocket 已经存在同类函数
	'''
	for i in xrange(count):
		sock = socket.socket()
		sock.settimeout(SOCKET['FPROTIMEOUT'])
		try:		
			start = datetime.now()
			sock.connect((host, int(port)))
			sock.send('PORT_IS_ALIVE')		
			end = datetime.now()
		except Exception as e:
			log_info("tcp连接失败:"+str(e))
			time.sleep(SOCKET['INTERVAL'])
			continue
		responsetime = (end - start).microseconds / 1000
		return True ,simplejson.dumps({"responsetime":responsetime})
	return False ,simplejson.dumps({"responstime":0})
	


	
if __name__ == '__main__':
	'''
	@test
	'''
	pass
		
