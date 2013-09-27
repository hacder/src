#!/usr/bin/env python
#encoding=utf-8

'''
	Author:		osa开源团队
	Description:	批量操作部分功能函数
	Create Date:	2012-05-15
'''
import os,sys,random,hashlib,subprocess
from unctrlpy.etc.config import DIRS

def revToDict(rev):
	'''
	@把接收来的字符串处理为字典格式
	'''
	return eval(rev)
	
def getConfigItem(rev):
	'''
	@提取配置项
	'''
	rdict = revToDict(rev)
	return rdict['config_items']

def getConfigPort(rev):
	'''
	@提取文件传输端口
	'''
	rdict = revToDict(rev)
	return rdict['fport']
	
def tempFilePath(rev):
	'''
	@临时文件保存路径
	'''	
	if not rev:
		return False
	if os.path.exists(DIRS['PY_OSA_TEMP']):
		tempPath = DIRS['PY_OSA_TEMP'] + 'tempfile.' + str(random.randint(10000,1000000))
	else:
		os.system('mkdir -p '+DIRS['PY_OSA_TEMP'])
		tempPath = DIRS['PY_OSA_TEMP'] + 'tempfile.' + str(random.randint(10000,1000000))
		
	return tempPath


def md5sum(filepath):
    with open(filepath,'rb') as f:
        md5obj = hashlib.md5()
        md5obj.update(f.read())
        hash = md5obj.hexdigest()
        print(hash)
        return hash

def scriptOrCmd(s):
	'''
	@判断是脚本还是指令
	'''
	if s == None or not s:
		return False
	if os.path.exists(s):
		return 'script'
	return 'cmd'

def runCmdOrScript(s):
	'''
	@执行指令或者脚本
	'''
	if not s or s == False:
		return False
	if os.path.exists(s):
		os.chmod(s,stat.S_IRWXU|stat.S_IREAD|stat.S_IEXEC|stat.S_IRUSR|stat.S_IXUSR|stat.S_IRGRP|stat.S_IXGRP|stat.S_IROTH|stat.S_IXOTH)
	p = subprocess.Popen(s, stdout = subprocess.PIPE, shell = True)			
	pstr = p.stdout.read().replace("\n",'@@@@')
	if str(s[0:7]) == 'service':
                return 'OK'
	return pstr

