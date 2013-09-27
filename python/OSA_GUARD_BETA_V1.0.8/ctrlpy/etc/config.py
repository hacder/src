#encoding=utf-8

#############################################################
#   
#     #####          #######          #
#   ##    ##        #               #  #
#   ##    ##        ######         #####
#   ##    ##             #        #    #
#     #####        #######       #     #
#
#	file: config.py
###############################################################

####################
# directorys

# osa python home

DIRS = { 'PY_OSA_ROOT': '/usr/local/osa/ctrlpy/',
	 'PY_OSA_ETC': '/usr/local/osa/ctrlpy/etc/',
	 'PY_OSA_LOG': '/usr/local/osa/ctrlpy/log/',
	 'PY_OSA_LIB': '/usr/local/osa/ctrlpy/lib/',
	 'ROOT':'/usr/local/osa/',
	 'CFG_ROOT': '/usr/local/osa/etc/',
	 'CTEMP':'/usr/local/osa/etc/c_temp/',
	 'PYTHONPATH':'/usr/local/osa/python/bin/python'
       }

#######################
# mysql config
MYSQL = { 'HOST': '127.0.0.1',
          'USER': 'openwebsa_conn_user',
          'PASSWD': 'openwebsa_conn_pw',
          'DB': 'openwebsa_conn_db',
		  'PORT':'openwebsa_conn_port',
          'SIZE': 100
        }


#######################
# sockets
SOCKET = { 'PORT': 10623,
           'REMOTE_PORT': 10624,
	   'TIMEOUT': 60,
	   'PROTIMEOUT': 30,
	   'LISTEN': 50,
       'BUFSIZE':20480,
	   'FPROTIMEOUT': 0.5,
	   'DELAY':0.8,
	   'COUNT':3,
       'INTERVAL':1
	 }

########################
#  command encode or decode

# auth_key
COMMANDS = { '_MOD_KEY':'1234567890',
		'_AUTH_KEY': 'YWFka3Jpb3cyYzM3ZTJlYmQwMjgzYjU1N2RjZjUwMWM5NWZmYzY4ODI0MzEwY2M4NzE=',
		'JAMSTR': 'ZZZZZZZZ',
		'OFFSET':'5'
	   }
   
	
###########################
#   command queue

# queue size
QUEUE = { 'MAXSIZE': 50
	}

###########################
#    email and fetion notify 
EMAIL = { 'SMTP_SERVER': 'smtp.163.com',
	  'FROM': 'leequery@163.com',
	  'USER': 'leequery',
	  'PASSWD': '******'
	}


###########################
#    process num 采集并发进程数

PNUM = {
	
	'num' : 20

	}
###########################
#    filesocket 定义接收文件相关变量

FSOCKET = {
	     'listen' : '5',
	     'timeout' : '300', 
	     'portlist' : '10000-11000',
	     'bufsize'  : '1024',
	     'fmt'  : '128s32sI8s',
	     'cfmt' : '128s11I'
         }
###########################
#	snmp 定义
SNMP = {
		'agent':'my-agent',
		'key':'public',
		'port':'161'
		}
		

###########################
#    批量操作相关定义
BATCH = {		
		'maxthreed' : 10		
		}
###########################
#    批量操作指令集合		
BATCHLIST = {
			'BATCH_DOCUMENT_DISTRIBUTION':1,
			'BATCH_FILE_CLEANER':1,
			'BATCH_SERVICE_RESTART':1,
			'BATCH_COMMAND':1,
			'BATCH_INSTALLATION':1,
			'BATCH_DISKSPACE_CHECK':1,
			'BATCH_LOADSTATE_CHECK':1,
			'BATCH_CONFIG_UPDATE':1,
			'BATCH_CONFIG_BACKUP':1,
			'BATCH_DATABASE_BACKUP':1
			}
###########################
#     返回结果指令
RLIST = ['batchresult','modkeyresult'] 

###########################
#     计划任务
TASK = {
		'maxprocess': 5,
		'interval': 0.1
		}

###########################
#     日志显示控制，1为开启，0为关闭！

LOG = {
		'ERROR':1,
		'INFO':1,
		'DEBUG':0,
		'WARNING':0,
		'MONTHREAD':0,
		'TASKTHREAD':0
	}
###########################
#     监控报警项目配置	
MONITOR = {

		'maxthread':20,
		'maxprocess':4,
		'timeout':60,
		'interval':20
		
			}

###########################
#     用户接收的邮件级别配置
# 新版本要被取消
LEAVEL={

	'william@booksir.com':'ERROR,CRITICAL',
	'285524570@qq.com':'INFO,WARNING,ERROR,CRITICAL',
	'852748331@qq.com':'INFO,WARNING,ERROR,CRITICAL',
	'wangheshun@booksir.com':'ERROR,CRITICAL',
	'yedongwei@booksir.com':'ERROR,CRITICAL'

}

