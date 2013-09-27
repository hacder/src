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

DIRS = { 'PY_OSA_ROOT': '/usr/local/osa/unctrlpy/',
	 'PY_OSA_SHELL_ROOT': '/usr/local/osa/unctrlsh/',
	 'PY_OSA_ETC': '/usr/local/osa/unctrlpy/etc/',
	 'PY_OSA_LOG': '/usr/local/osa/unctrlpy/log/',
	 'PY_OSA_LIB': '/usr/local/osa/unctrlpy/lib/',
	 'CFG_ROOT': '/usr/local/osa/etc/',
	 'ROOT': '/usr/local/osa/',
	 'PY_OSA_TEMP':'/usr/local/osa/data/temp/'
       }

#######################
# sockets
SOCKET = { 'PORT': 10624,
	   'TIMEOUT': 60,
	   'LISTEN': 60,
           'BUFSIZE': 20480,
	   'AGENTPORT':10623,
	   'PROTIMEOUT':60 ,
	   'COUNT':3,
	   'FPROTIMEOUT':0.5
	}

########################
#  command encode or decode

# auth_key
COMMANDS = { '_AUTH_KEY': '_ids=lDEFABCNOPydsfdfdsT-UwxkVWXYZabcdef+IJK6/7nopqr89LMmGH012345uv',
	     'JAMSTR': 'ZZZZZZZZ',
	     'CMDS': ['RUN', 'ADD', 'UPDATE', 'DEL'],
	     'OFFSET':'5'
	   }
	     
###########################
#   command queue

# queue size
QUEUE = { 'MAXSIZE': 50
	}


###########################
#    filesocket 定义发送接收文件相关变量

FSOCKET = {
             'listen' : '5',
             'timeout' : '300',
             'portlist' : '10880-11000',
             'bufsize'  : '1024',
             'fmt' : '128s32sI8s',
	     'cfmt' : '128s11I'

         }
###########################
#    cfilename 定义配置文件路径

CFILENAME = {
		'iptables' : '/etc/sysconfig/iptables',
		'crond':'/etc/crontab',
		'nginx':'/data/conf/nginx/nginx.conf'
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
	

