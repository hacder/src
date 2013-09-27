#!/usr/bin/env python
#encoding=utf-8

from unctrlpy.lib.osaUtil import save_log
from unctrlpy.lib.encode import decode
from unctrlpy.etc.config import FSOCKET
import time,os,sys,socket,struct

"""
   @文件接收相关方法

"""

def get_fsocket_port(portlist = FSOCKET['portlist']):
	'''
           @portlist: 起止端口号
           @return:   可以使用的端口号
        '''
	if not portlist:
		return 0
	portstart  =  int(portlist.split('-')[0])
	portend    =  int(portlist.split('-')[1])
	while ( portstart < portend ):
		sk = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    		try:
       			sk.bind(('0.0.0.0',portstart))
    		except Exception as e:
			portstart = portstart + 1
			continue
    		else:
			sk.close()
			return portstart

def file_recv_main(host='0.0.0.0',port=int(get_fsocket_port()),filename = ''):
	'''
	   @host 监听本机
	   @port 接收文件时监控的端口
	   @filename 接收文件的临时保存路径和名称
	'''
	BUFSIZE = FSOCKET['bufsize']
	
	#用来计算特定格式的输出的大小是几个字节
	FILEINFO_SIZE = struct.calcsize(FSOCKET['fmt'])
	
	recvSock = socket.socket(socket.AF_INET,socket.SOCK_STREAM)

	recvSock.bind((host,port))

	recvSock.listen(int(FSOCKET['listen']))
	
	
	while True:
		
		try:
		
			conn,addr = recvSock.accept()
			info = 'File recv from:'+ str(addr)
			save_log('INFO', info)
		except socket.error, args:
			(errno, err_msg) = args
                	errlog = 'Connect server failed: %s, errno=%d' % (err_msg, errno)
                	save_log('ERROR', errlog)
			
		#####修改接收长度为定值，兼容端口检测######
		fhead = conn.recv(1024)
		
		if fhead == 'PORT_IS_ALIVE':
			continue
			
		info = 'File recv from:'+ str(addr)
		save_log('INFO', info)
		
		try:	
			f=fhead.split('||||')[0]
			filesize=int(fhead.split('||||')[1])
		except Exception as e:
			save_log('ERROR', str(e)+''+str(fhead))

		fp = open(filename,'wb')
	
		restsize = filesize

		while restsize > 0:
    			if restsize > BUFSIZE:
       				filedata = conn.recv(BUFSIZE)
    			else:
        			filedata = conn.recv(restsize)
    			fp.write(filedata)
    			restsize = restsize-len(filedata)
		fp.close()
		conn.close()
		if filesize != os.stat(filename).st_size :
			save_log('ERROR','File transfer fails ! ' )
			return False
		info = info + ' File name: '+ str(f) +' ,filesize:'+ str(filesize) + ' byte, save as :'+ str(filename)
		save_log('INFO',info )
		break	
		recvSock.close()
	return filesize
	
