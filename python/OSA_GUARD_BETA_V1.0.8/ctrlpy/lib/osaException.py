#!/usr/bin/env python
# encoding=utf-8

'''
	Author:		osa开源团队
	Description:	osa自定义异常模块
	Create Date:	2011-07-20
'''

class osaException(Exception):
	'''
	osa异常接口
	'''
	pass


class logException(osaException):
	'''
	@日志异常
	'''
	
	def __init__(self, value):
		self.value = value
	
	def __str__(self):
		return repr(self.value)


class queueEmptyException(osaException):
	'''
	@指令队列空异常
	'''

	def __init__(self, value):
		self.value = value

	def __str__(self):
		return repr(self.value)


class queueFullException(osaException):
	'''
	@指令队列满异常
	'''
	
	def __init__(self, value):
		self.value = value

	def __str__(self):
		return repr(self.value)


class cmdException(osaException):
	'''
	@指令入库异常
	'''
        
	def __init__(self, value):
		self.value = value

	def __str__(self):
		return repr(self.value)


class NotifyException(osaException):
	'''
	@报警异常
	'''
	
	def __init__(self, value):
		self.value = value

	def __str__(self):
		return repr(self.value)
