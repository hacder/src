#!/usr/bin/env python
#encoding=utf-8
import time
from ctrlpy.lib.osaUtil import save_log
from ctrlpy.lib import cmdtosql


def getoRunCycle():
	'''
	@获取执行周期，具体执行时间等数据
    @oRunNextTime 默认为空
	'''
	
	
	qsql = "SELECT * from `osa_taskplan` WHERE oRunNextTime is null or oRunNextTime = '0000-00-00 00:00:00' or oRunNextTime <= '"+str(cmdtosql._get_time(flag=1))+"'"
	
	return cmdtosql.select(qsql)

def getoCombinCmd(Taskid,oCmdType):
	'''
	@根据指令类型获取具体指令
	'''
	defaultDict = {
					'BATCH_CONFIG_UPDATE':'osa_configupdate',
					'BATCH_CONFIG_BACKUP':'osa_configbackup',
					'BATCH_DATABASE_BACKUP':'osa_databackup',
					'BATCH_DOCUMENT_DISTRIBUTION':'osa_operations',
					'BATCH_FILE_CLEANER':'osa_operations',
					'BATCH_SERVICE_RESTART':'osa_operations',
					'BATCH_COMMAND':'osa_operations',
					'BATCH_INSTALLATION':'osa_operations',
					'BATCH_DISKSPACE_CHECK':'osa_operations',
					'BATCH_LOADSTATE_CHECK':'osa_operations'	
	
				}
	
	tabName = defaultDict[oCmdType]
	
	sql = "SELECT oCombinCmd from "+tabName+" WHERE oTaskplanid = "+str(Taskid)
	return cmdtosql.select(sql)
	
	
def getNextRunTime(r):
	'''
	@获取任务下次执行时间
	'''
	if r[1] == 'Every-day':
		if r[7] !=None:
			date = time.strftime('%Y-%m-%d',time.localtime(time.time()+24*60*60))		
			dt = str(date) + ' ' + str(r[3]) 
			return dt
		else:
			
			date = time.strftime('%Y-%m-%d',time.localtime(time.time()))
		
			dt = str(date) + ' ' + str(r[3]) 
		
			if dt < time.strftime('%Y-%m-%d %H:%M:%S',time.localtime(time.time())):
		
				date = time.strftime('%Y-%m-%d',time.localtime(time.time()+24*60*60))
			
				dt = str(date) + ' ' + str(r[3]) 
			return dt
		
	elif r[1] == 'Weekly':
	
		j = 0	
		if r[7] != None:			
			
			todyW = time.strftime("%a",time.localtime(time.time()))
			
			wlist = r[2].split('|')
			
						
			for i in range(len(wlist)):
				if todyW == wlist[i]:
					j = i + 1
					if j >= len(wlist):
						j = 0
					break
			for x in xrange(0,7):
				nextW = time.strftime("%a",time.localtime(time.time()+24*60*60*x))
				
								
				if wlist[j] == nextW:
					date = time.strftime('%Y-%m-%d',time.localtime(time.time()+24*60*60*x))
					return str(date) + ' ' + str(r[3]) 	
						
		else:
		
			todyW = time.strftime("%a",time.localtime(time.time()))
			
			wlist = r[2].split('|')
			for i in range(len(wlist)):
				if todyW == wlist[i]:
					j = i
					break
			for x in xrange(0,7):
				nextW = time.strftime("%a",time.localtime(time.time()+24*60*60*x))
				
				
				if wlist[j] == nextW:
					date = time.strftime('%Y-%m-%d',time.localtime(time.time()+24*60*60*x))					
					dt = str(date) + ' ' + str(r[3]) 
					break
			
			if dt < time.strftime('%Y-%m-%d %H:%M:%S',time.localtime(time.time())):
				
				j = j + 1
				if j >= len(wlist):
					j = 0
				for x in xrange(0,7):
					nextW = time.strftime("%a",time.localtime(time.time()+24*60*60*x))
				
					if wlist[j] == nextW:
						date = time.strftime('%Y-%m-%d',time.localtime(time.time()+24*60*60*x))
						dt = str(date) + ' ' + str(r[3]) 
			
			return dt
			
					
	elif r[1] == 'Monthly':
		
		
		if r[7] != None:
			
			month = time.strftime('%m',time.localtime(time.time()))
			
			yeah = time.strftime('%Y',time.localtime(time.time()))
			
			day = r[2]
			
			if int(month) < 12 :
				month = int(month) + 1
			
			if month == 12:
				month = 1
				yeah = int(yeah) + 1
				
			if int(month) < 10:
				month = '0'+str(int(month))
				
			if int(yeah) < 10:
				yeah = '0'+str(int(yeah))
			
			dt = str(yeah)+'-'+str(month)+'-'+str(day)+' '+str(r[3])
	
			
		else:
		
			day = time.strftime('%d',time.localtime(time.time()))
			
			if int(r[2]) >= int(day):
		
				datetime = time.strftime('%Y-%m-',time.localtime(time.time()))
				
				dt =  (datetime)+str(r[2])+' '+str(r[3])
				
			else:
				month = time.strftime('%m',time.localtime(time.time()))
			
				yeah = time.strftime('%Y',time.localtime(time.time()))
			
				day = r[2]
			
				if int(month) < 12 :
					month = int(month) + 1
			
				if month == 12:
					month = 1
					yeah = int(yeah) + 1
				
				if int(month) < 10:
					month = '0'+str(int(month))
					
				if int(yeah) < 10:
					yeah = '0'+str(int(yeah))
			
				dt = str(yeah)+'-'+str(month)+'-'+str(day)+' '+str(r[3])
		
		return dt
		
	elif r[1] == 'One-time':
		
		if r[7] != None:
			return False
		else:
			if r[3] >= time.strftime('%Y-%m-%d %H:%M:%S',time.localtime(time.time())):
				return r[3]
			else:
				save_log('ERROR','One-time task,next time must > now time!,Task id:'+str(r[0]))



###根据周期获取执行任务列表，更新下一次执行时间

def chooseoRunCycle():
	'''
	@根据周期，返回数据列表
	'''
	rinfo = getoRunCycle()
	
	rlist = []
	
	for r in rinfo:
		
		if r[7] != None:
			cmdinfo = ''
			try:
				cmdinfo = getoCombinCmd(int(r[0]),r[4])						
			except Exception as e:
				save_log('ERROR','getoCombinCmd error:'+str(e))
			
			if cmdinfo:
				save_log('INFO','TASK CMD:'+str(cmdinfo[0][0]))
				rlist.append(cmdinfo[0][0])
				nexttime = getNextRunTime(r)
				if nexttime == False or not nexttime:
					Isql = "INSERT INTO `osa_complantask`(oCmdType,oTaskplanid,oRunCycle,oRunDate,oRunTime) select oCmdType,id,oRunCycle,oRunDate,oRunTime from osa_taskplan"
					try:
						con = cmdtosql._get_pcon()
						cur = con.cursor()
						cur.execute(Isql)			
						
					except Exception as Ierror:
						save_log('ERROR','osa_complantask INSERT ERROR:'+str(Ierror)+',sql is: '+Isql)
					finally:
						cmdtosql._exit(con, cur)
					Dsql = 	"DELETE from osa_taskplan WHERE id = "+str(r[0])
					try:
						con = cmdtosql._get_pcon()
						cur = con.cursor()
						cur.execute(Dsql)							
					except Exception as Ierror:
						save_log('ERROR','osa_taskplan DELETE ERROR:'+str(Ierror)+',sql is: '+Dsql)						
					finally:
						cmdtosql._exit(con, cur)						
				else:				
				
					usql = "UPDATE `osa_taskplan` set oStatus = '运行中',oRunNextTime = '"+nexttime+"',oRunLastTime = '"+str(cmdtosql._get_time(flag=1))+"' WHERE id = "+str(r[0])				
					cmdtosql.update(usql)
		else:
			nexttime = getNextRunTime(r)
			usql = "UPDATE `osa_taskplan` set oStatus = '运行中',oRunNextTime = '"+nexttime+"',oRunLastTime = '"+str(cmdtosql._get_time(flag=1))+"' WHERE id = "+str(r[0])				
			cmdtosql.update(usql)
			
	return rlist
			

	
