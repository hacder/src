#!/usr/bin/env python
# encoding=utf-8

'''
	Author:		osa开源团队
	Description:	加密及解密模块
	Create Date:	2011-07-20
'''

import time
import string
import sys
import random
import base64
from hashlib import md5

from ctrlpy.etc.config import COMMANDS


def encode(cmdstr, _auth_key = COMMANDS['_AUTH_KEY'] , offset = COMMANDS['OFFSET']):
	'''
	@estr: 加密字符
	@_auth_key: 密钥
	'''

	canstr = cmdstr
	
	if len(cmdstr) < 10:
		cmdstr = cmdstr + COMMANDS['JAMSTR']

	encode_str = md5(_auth_key).hexdigest()
	encode_cmdstr = md5(canstr).hexdigest()
	encode_str = ''.join( [ i.upper() if i.isalpha() and random.randint(0, 1)==1 \
			else i for i in encode_str ] )
	encode_num = int((''.join([ i if i.isdigit() and int(i)>3 \
			else "" for i in encode_str ]))[0:1])

	cmdstr = base64.urlsafe_b64encode(cmdstr)

	stra = cmdstr[:encode_num]
	strb = cmdstr[encode_num:]
	encode_stra = encode_str[encode_num:]
	encode_strb = encode_str[:encode_num]

	enstr = strb + encode_stra + stra + encode_strb
	enstr = COMMANDS['JAMSTR'] + '_____' + enstr + '_____' + str(len(canstr)) + \
			'_____' + COMMANDS['JAMSTR']
	enlist = list(enstr)
	rstr = ''
	
	for en in enlist:
		lastnum = int(ord(en)) + int(offset)
		rstr = rstr + str(lastnum)+'|'
	
	return rstr


def decode(rstr, _auth_key = COMMANDS['_AUTH_KEY'],offset = COMMANDS['OFFSET']):
	'''
	@estr: 解密字符
	@_auth_key: 密钥
	'''

	enlist = rstr.split('|')
	dlist = []
	enstr = ''

	for r in enlist:
		if r:
			fiststr = chr(int(r) - int(offset))
			enstr = enstr + fiststr

	enstrlen=enstr.split('_____')[2]
	enstr = enstr.split('_____')[1]

	# 开始解密
	encode_str = md5(_auth_key).hexdigest()
	encode_num = int((''.join([ i if i.isdigit() and int(i)>3 \
			else "" for i in encode_str ]))[0:1])

	str_len = len(enstr)-len(encode_str)
	strb = enstr[:str_len - encode_num]

	len_encode_strb = len(enstr[-encode_num:])
	stra = enstr[-(encode_num + len_encode_strb):-len_encode_strb]

	rstr = base64.urlsafe_b64decode(str(stra + strb)).replace(COMMANDS['JAMSTR'], '')
	
	if len(rstr) != int(enstrlen):
		return 'Receive is not complete.'        

	return rstr
