#!/usr/bin/python
# encoding=utf-8

'''
	Author:		osa开源团队
	Description:	server端入口
	Create Date:	2011-07-20
'''

import socket
import sys,signal
import threading
import time,random
from ctrlpy.lib.encode import encode, decode
from ctrlpy.lib.osaUtil import save_log, ayCmdToConsole, putCmd
from ctrlpy.lib import hostSocket
from ctrlpy.lib.osaException import queueEmptyException, queueFullException
from ctrlpy.etc.config import SOCKET,DIRS
from ctrlpy.lib.osaFilelib import isMySelfCmd,OsaChooseDef
from ctrlpy.lib.osaBatch import isBatchCmd,chooseBatchDef
from ctrlpy.lib.osaResult import chooseResultDef,isConseResult
from ctrlpy.lib import osaDamoClass

			
def socketRev(connection,addr):
		 
	data = rev = ''
	i=0
	j=0
	while True:
		data = connection.recv(SOCKET['BUFSIZE'])
		if data == '':
				i=i+1
		rev = rev + data
		if data.endswith('EOF') or addr[0] == '127.0.0.1' or rev == 'PORT_IS_ALIVE' or i > 2 or j > 10:
			break
		time.sleep(round(float(random.randrange(0, 10000, 1))/10000,4))
		j=j+1
	
	if rev == 'PORT_IS_ALIVE':
		connection.close()
		sys.exit()
	
	if addr[0] == '127.0.0.1':
		return rev
	else:		
		try:
			rev=decode(rev)			
		except Exception as e:	
			try:
				time.sleep(round(float(random.randrange(0, 10000, 1))/10000,4))
				rev = decode(rev + connection.recv(SOCKET['BUFSIZE']))
			except Exception as e:
				save_log('ERROR','server cmd decode error:'+str(e))
					
	if rev.isspace() or not rev:
		save_log('WARNING','Empty command.')
		connection.send(encode('Illegal Command '))		
		connection.close()
		sys.exit()
	
	return rev

def socketDo(connection,addr):
	'''
	server指令处理模块
	@connection: 指令连接对象
	'''

	try:
		
		rev = socketRev(connection,addr)

		if isConseResult(rev):
			connection.send(encode('result_send_ok'))
		#处理从unctrlpy 传过来的结果信息
		try:
			chooseResultDef(rev)
		except Exception as e:						
			save_log('ERROR','chooseResultDef error:'+str(e))
				
		cmdstr, ip = ayCmdToConsole(rev)
		
		if not ip:
			save_log('ERROR', 'IP IS NULL')
			connection.send('Ip is null.')
			connection.close()
			sys.exit()
		
		save_log('INFO', str(cmdstr.split('!')))
		
		# if cmd for myself
		cname = isMySelfCmd(cmdstr)
		#if batch cmd
		batch = isBatchCmd(cmdstr)
		toPhpData = ""
		if cname:			
			try:				
				toPhpData = OsaChooseDef( defname = isMySelfCmd(cmdstr) , ip = ip , fromPhpCmd = cmdstr)
			except Exception as e:				
				save_log('ERROR', e)
		elif batch:				
			toPhpData = 'BATCH_CMD_OK'			
		else:
			toPhpData = hostSocket.proSocket(ip, SOCKET['REMOTE_PORT'], cmdstr)
			save_log('DEBUG','cmd info:'+str(encode(cmdstr)))
			save_log('DEBUG','cmd info:'+str(cmdstr))

		if toPhpData == False:
			toPhpData = 'Data_receive_Failed!'		
		connection.send('%s!%s' % (toPhpData, ip))
		connection.close()
		if toPhpData == 'BATCH_CMD_OK':
			
			try:
				chooseBatchDef(cmdstr,rev)
			except Exception as e:
				save_log('ERROR','BATCH chooseBatchDef error:'+str(e))
		sys.exit()	

	except queueEmptyException, value:
		save_log('ERROR', 'server socketDo:'+str(value))
		connection.close()
		sys.exit()

	except queueFullException, value:
		save_log('WARNING', str(value))
		connection.close()
		sys.exit()

	except socket.timeout:
		connection.send('Time out!')
		save_log('WARNING', 'Time out!')
		connection.close()	
		sys.exit()

	except TypeError, args:
		errlog = 'Connect server failed: %s ' % args
		save_log('ERROR', errlog)
		connection.close()
		sys.exit()
	except socket.error, args:
		(errno, err_msg) = args
		errlog = 'Connect server failed: %s, errno=%d' % (err_msg, errno)
		save_log('ERROR', errlog)
		connection.close()
		sys.exit()
	except Exception as e:
		save_log('ERROR', 'socketRev other error:'+str(e))
		connection.close()
		sys.exit()


def socketAccp(host = '0.0.0.0', port = SOCKET['PORT']):
	'''
	server指令接收模块
	@host: 主机Ip
	@port: socket端口
	'''

	sock = socket.socket(socket.AF_INET,socket.SOCK_STREAM)
	sock.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
	sock.bind((host,port))
	sock.listen(SOCKET['LISTEN'])
	while True:
		try:
			connection,address = sock.accept()
			connection.settimeout(SOCKET['TIMEOUT'])
			t = threading.Thread(target=socketDo,args=[connection,address])
			t.start()
		except Exception as e:
			save_log('ERROR','server socket accept error : '+str(e))
		
		
class serverDaemon(osaDamoClass.Daemon):
    def _run(self):
        socketAccp()	


if __name__ == '__main__':
	
	
	daemon = serverDaemon(DIRS['ROOT']+'server.pid')
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
