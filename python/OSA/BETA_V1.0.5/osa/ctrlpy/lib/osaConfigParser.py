#!/usr/bin/env python
#encoding=utf-8

'''
	Author:		osa开源团队
	Description:	osa_alert.ini文件解析模块
	Create Date:	2011-07-20
'''

from __future__ import with_statement
from ConfigParser import SafeConfigParser as parser

_parser = parser()

class osaParser(object):
	'''
	@ osa .ini文件解析类
	'''

	def __init__(self, ini, oparser = _parser):
		with open(ini) as fobj:
			oparser.readfp(fobj)
		
		self._ini = ini
		self._oparser = oparser

	def getValue(self, sec='global', key=None):
		
		if self._oparser.has_option(sec, key):
			return self._oparser.get(sec, key)

		return self._oparser.get('global', key)

	def getItems(self, sec):
		return self._oparser.items(sec)
	
	def getSecs(self):
		return self._oparser.sections()

	def setValue(self, sec, key, value):
		self._oparser.set(sec, key, value)
		self._oparser.write(open(self._ini, 'w'))        		
