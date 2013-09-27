#!/usr/bin/env python
# encoding=utf-8

'''
	Author:		osa开源团队
	Description:	client端入口
	Create Date:	2011-07-20
'''

import socket
import commands
import sys
import threading
import time,random
from unctrlpy.lib.encode import decode, encode
from unctrlpy.lib.osaUtil import save_log, ayCmdToExec, reWithToPhp
from unctrlpy.etc.config import SOCKET,DIRS
from unctrlpy.lib.script import execShellScript
from unctrlpy.lib.osaFilelib import isMySelfCmd,OsaChooseDef
from unctrlpy.lib.osaBatchClient import isBatchCmd,chooseBatchDef,ayCmd
from unctrlpy.lib.hostSocket import RproSocket,FproSocket
from unctrlpy.lib import osaDamoClass

def _with_cmd(lcmd, rcmd=''):
	'''
	@lcmd: 原始cmd
	@rcmd: 组合后cmd
	'''

	for i in lcmd:
		rcmd += str(i) + ' '
	return rcmd.strip()


def execCMD(cmd):
	'''
	@execCMD: 执行指令
	@cmd: 指令
	return status（指令执行状态）
	info （指令执行返回的数据）
	'''
	
	try:
		infos = ayCmdToExec(cmd)
		
		if len(infos) == 3:
			return infos[0], infos[1]
		execC = execShellScript(infos[0])
				
		if len(execC[1]) > 10:
			return infos[0], reWithToPhp(infos[1], execC[0], value=execC[1])
		else:
			if len(execC[0]) == 1:
				return infos[0], reWithToPhp(infos[1], execC[0], value=execC[1])
			return infos[0], reWithToPhp(infos[1], execC[0][0], execC[0][1], execC[1])
	except Exception as e:
		save_log("ERROR", 'Command Error!'+ cmd + ' 可能原因是：'+ str(e))
		return 'Command Error!','Command Error'


def socketAccp(host = '0.0.0.0', port = SOCKET['PORT']):
	'''
	@host: 主机Ip
	@port: socket port
	'''

        # 创建流式套接字
	sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
	sock.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
	
	try:
		sock.bind((host, port))
	except Exception as e:
		save_log('ERROR','client bind error:'+str(e))
	
	sock.listen(SOCKET['LISTEN'])
	
	while True:
		con, addr = sock.accept()
		con.settimeout(SOCKET['TIMEOUT'])
		t = threading.Thread(target=doSocket, args=[con,addr])
		t.setDaemon(0)
		t.start()


def doSocket(connection,addr):
	'''
	@connection: socket连接
	'''

	try:
		i=0
		j=0
		data = recvs = ''
		while True:
			data = connection.recv(SOCKET['BUFSIZE'])
			if data == '':
				i=i+1
			recvs = recvs + data
			if data.endswith('EOF') or recvs == 'PORT_IS_ALIVE' or i > 2 or j > 10:
				break
			time.sleep(round(float(random.randrange(0, 10000, 1))/10000,4))
			j = j + 1
		#recvs = connection.recv(SOCKET['BUFSIZE'])
		
		ip=addr[0]
		
		if recvs == 'PORT_IS_ALIVE':
			save_log('INFO', recvs)			
			connection.close()
			sys.exit()
		
		if not recvs or recvs == '':
			connection.close()
                        sys.exit()		

		try:
			cmd = decode(recvs)
		except Exception as e:			
			save_log('ERROR', 'decode error:'+str(e)+',recvs :'+str(recvs))
			connection.close()
			sys.exit()
			
		if cmd == 'CMD_STAT':
			save_log('INFO', cmd)
			connection.send(encode('CMD_STAT_OK'))
			connection.close()
			sys.exit()
		
		if cmd.endswith('check'):
			save_log('CHECK', cmd)
			connection.send(encode('error'))
			connection.close()
			sys.exit()	
		
		# is myself def
		cname = isMySelfCmd(cmd)
		
		
		#if batch cmd
		
		batch = isBatchCmd(ayCmd(cmd))
		
		if cname:
			try:
				infos = OsaChooseDef(defname=cname,ip = ip,fromPhpCmd=cmd)
			except Exception,e:
				connection.close()
				save_log('ERROR', str(e))
		#处理批量操作分支
		elif batch:
			connection.close()
			rinfo = chooseBatchDef(ayCmd(cmd),cmd)
			save_log('INFO',str(ayCmd(cmd)))
			##处理批量操作结果			
			ret = "{'batchinfo':"+str(cmd)+",'command':'batchresult','batchresult':"+str(rinfo)+"}"
			port=SOCKET['AGENTPORT']
			r = RproSocket(ip, port, ret)
			if r:
				sys.exit()
			else:
				time.sleep(0.3)
				r = RproSocket(ip, port, str(ret), type=None)
				sys.exit()
		else:
			infos = execCMD(cmd)
		save_log('INFO', infos[0])
		ldata=len(encode(infos[1]))
		toAngentData=encode(infos[1])
		
		try:
			connection.send(toAngentData)
		except Exception as e:
			connection.send(infos[1])
			sys.exit()
		
		connection.close()
		sys.exit()
	except Exception as e:
		save_log('ERROR', 'python recv error:'+str(e))
		connection.close()
		sys.exit()



class clientDaemon(osaDamoClass.Daemon):
    def _run(self):
        socketAccp()	


if __name__ == '__main__':
	
	
	daemon = clientDaemon( DIRS['ROOT']+'client.pid')
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
