#!/usr/bin/env python
#encoding=utf-8

'''
	Author:		osa开源团队
	Description:	文件发送方法
	Create Date:	2011-07-20
'''

import os
import struct
import socket

from unctrlpy.lib.osaUtil import save_log
from unctrlpy.lib.encode import encode
from unctrlpy.etc.config import FSOCKET


def osaSendFile(filename='',remoteip='',port=''):
	'''
	@filename 发送的文件名
	@remoteip 远程IP地址
	'''
	if not filename or not remoteip:
		return 0
	
	BUFSIZE = int(FSOCKET['bufsize'])
	sendSock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
	sendSock.connect((remoteip,port))
	
	try:
		
		#fhead=struct.pack(FSOCKET['cfmt'],filename,0,0,0,0,0,0,0,0,os.stat(filename).st_size,0,0)
		####修改传送方式，文件名和长度设置为定长,不能超过1024####               
                #fhead=struct.pack(FSOCKET['cfmt'],filename,0,0,0,0,0,0,0,0,os.stat(filename).st_size,0,0)              
                fhead = str(filename) + '||||' + str( os.stat(filename).st_size ) + '||||'
                if len(fhead) > 1024:
                        save_log('ERROR','filename or file path is too long,Recommend less than 1024。')
                        return 0

                nfhead = ''
                for i in xrange(1024 - len(fhead)):
                        nfhead = '@' + nfhead

                fhead = fhead + nfhead	
	
		sendSock.send(fhead)

	except Exception as e:
		save_log('ERROR',e)

	fp = open(filename,'rb')

	while True:
		filedata = fp.read(BUFSIZE)

		if not filedata: 
			break
		try:
			sendSock.send(filedata)
		except Exception as e:
			save_log('ERROR',e)
			return None
	fp.close()
	sendSock.close()
	
	return 1
