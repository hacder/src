#!/usr/bin/env python
# encoding=utf-8

import sys, os, time, atexit
reload(sys)
sys.setdefaultencoding("utf-8")
from signal import SIGTERM 
from ctrlpy.lib.osaUtil import save_log
from ctrlpy.etc.config import DIRS

class Daemon:
	"""
	osa daemon class.	
	Usage: subclass the Daemon class and override the _run() method
	"""
	def __init__(self, pidfile, stdin='/dev/null', stdout='/dev/null', stderr='/dev/null'):
		self.stdin = stdin
		self.stdout = stdout
		self.stderr = stderr
		self.pidfile = pidfile		
	
	def _daemonize(self):
		"""
		@osa 守护进程主方法
		"""
		
		#脱离父进程
		try: 
			pid = os.fork() 
			if pid > 0:
				sys.exit(0) 
		except OSError, e: 
			sys.stderr.write("fork #1 failed: %d (%s)\n" % (e.errno, e.strerror))
			save_log('ERROR',"osa damo fork #1 failed:"+str(e.strerror))
			sys.exit(1)
	
		#脱离终端
		os.setsid() 
		#修改当前工作目录  
		os.chdir(DIRS['ROOT']) 
		#加载环境变量
		osapath = DIRS['ROOT']
					
		sys.path.append(osapath)
		#重设文件创建权限
		os.umask(0) 
	
		#第二次fork，禁止进程重新打开控制终端
		try: 
			pid = os.fork() 
			if pid > 0:
				sys.exit(0) 
		except OSError, e: 
			sys.stderr.write("fork #2 failed: %d (%s)\n" % (e.errno, e.strerror))
			save_log('ERROR',"osa damo fork #2 failed:"+str(e.strerror))
			sys.exit(1) 
	
		sys.stdout.flush()
		sys.stderr.flush()
		si = file(self.stdin, 'r')
		so = file(self.stdout, 'a+')
		se = file(self.stderr, 'a+', 0)
		#重定向标准输入/输出/错误
		os.dup2(si.fileno(), sys.stdin.fileno())
		os.dup2(so.fileno(), sys.stdout.fileno())
		os.dup2(se.fileno(), sys.stderr.fileno())
	
		#注册程序退出时的函数，即删掉pid文件
		atexit.register(self.delpid)
		pid = str(os.getpid())
		file(self.pidfile,'w+').write("%s\n" % pid)
	
	def delpid(self):
		os.remove(self.pidfile)
	def start(self):
		"""
		Start the daemon
		"""
		# Check for a pidfile to see if the daemon already runs
		try:
			pf = file(self.pidfile,'r')
			pid = int(pf.read().strip())
			pf.close()
		except IOError,e:
			pid = None
			#save_log('INFO',"osa damo ioerror :"+str(e))		
	
		if pid:
			message = "Start error,pidfile %s already exist. Daemon already running?\n"
			save_log('ERROR',message)
			sys.stderr.write(message % self.pidfile)
			sys.exit(1)
		
		# Start the daemon
		self._daemonize()
		self._run()
	def stop(self):
		"""
		Stop the daemon
		"""
		# Get the pid from the pidfile
		try:
			pf = file(self.pidfile,'r')
			pid = int(pf.read().strip())
			pf.close()
		except IOError:
			pid = None
	
		if not pid:
			message = "pidfile %s does not exist. osa Daemon not running?\n"
			sys.stderr.write(message % self.pidfile)
			return # not an error in a restart
		# Try killing the daemon process	
		try:
			while 1:
				os.kill(pid, SIGTERM)
				time.sleep(0.1)
		except OSError, err:
			err = str(err)
			if err.find("No such process") > 0:
				if os.path.exists(self.pidfile):
					os.remove(self.pidfile)
			else:
				save_log('ERROR','Stop error,'+str(err))
				sys.exit(1)
	def restart(self):
		"""
		Restart the daemon
		"""
		self.stop()
		self.start()
	def _run(self):
		"""
		You should override this method when you subclass Daemon. It will be called after the process has been
		daemonized by start() or restart().
		"""
