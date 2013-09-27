#!/usr/bin/env python
#encoding=utf-8

'''
	Author:		osa开源团队
	Description:	default mqueue(threading)
	Create Date:	2011-07-20
'''

import threading

class deQueue(threading.Thread):
	def __init__(self, threadname, queue, lock=None):
		threading.Thread.__init__(self, name=threadname)
		self.cmdqueue = queue
		self.lock = lock

	def run(self):
		pass


class serviceQueue(deQueue):
	def __init__(self, threadname, queue, lock):
		super(serviceQueue, self).__init__(threadname, queue, lock)

	def run(self):
		pass
