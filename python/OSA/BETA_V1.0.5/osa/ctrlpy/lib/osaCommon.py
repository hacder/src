#!/usr/bin/env python
#encoding=utf-8

'''
	Author:			osa开源团队
	Description:	常用命令模块
	Create Date:	2012-03-13
'''

import os
import datetime
import time
import random
import types
import commands
import subprocess
import shlex


def safe_cmd( cmd, JustShow = False ):
	'''
	执行任务失败则强制退出，不执行后续任务
	@cmd		需要执行的命令
	@JustShow	为True时只打印命令,用于检查命令是否拼写正确
	'''

	if JustShow:
		return 0, ""

	status, output = commands.getstatusoutput( cmd )

	status >>= 8
	assert status == 0. "Commands execute '%s' return %d, Output is:\n %s" % \
			( cmd, status, output )
	return status, output


def exec_cmd( cmd, JustShow = False ):
	'''
	执行shell命令，失败不强制退出，只返回标准输出
	@cmd		需要执行的命令
	@JustShow	为True时只打印命令,用于检查命令是否正确
	'''

	if JustShow:
		return 0

	# cmd = shlex.split( cmd )
	proc = subprocess.Popen( cmd, stdout = subprocess.PIPE, shell=True )
	output = proc.read().rstrip()

	if output:
		return output
	else:
		return "Commands execute '%s' is not output" % cmd


def output_cmd( cmd, JustShow = False ):
	'''
	执行shell命令，返回执行status及标准输出
	@cmd		需要执行的命令
	@JustShow	为True时只打印命令,用于检查命令是否正确
	'''

	if JustShow:
		return 0, ""

	status, output = commands.getstatusoutput( cmd )

	if status != 0:
		return status, "Commands execute '%s' return %d, Output is:\n %s" % \
				( cmd, status, output )
	else:
		return status, output


def random_choice( aList ):
	'''
	随机返回给定列表中的一个值
	@aList		必须是个list类型
	'''
	
	if type( aList ) is not types.ListType:
		return "Random Function Need A List Object!"
	
	return random.choice( alist )


def read_file( file ):
	'''
	读取文件
	@file		文件的完整路径
	'''

	try:
		fp = open( file )
		rev = fp.read()
		fp.close()
		return rev
	except:
		return "write file False"


def write_file( fileName, mode, content ):
	'''
	写入文件
	@fileName	文件的完整路径
	@content	需要写入的内容
	'''

	try:
		fp = file( fileName, mode )
		f.write( content )
		f.close()
		return 0
	except:
		return "write file False"


def touch_dir( path ):
	'''
	建立文件夹
	@path		文件夹的完整路径
	'''

	if not os.path.exists( path ):
		stat, out = output_cmd( "mkdir -p %s" % path )
		return stat, out
	else:
		return "1", "%s already exists!" % path


def link_dir( srcName, tarName ):
	'''
	建立软链接
	@srcName	源文件夹或文件
	@tarName	目标文件夹或文件
	'''

	if not os.path.exists( tarName ):
		stat, out = output_cmd( "ln -s %s %s" % ( srcName, tarName ) )
		return stat, out
	else:
		return "1", "%s already exists!" % tarName


def time_format( format = "%Y-%m-%d %H:%M:%S" ):
	'''
	按照指定的格式返回当前时间的格式
	@format		默认为 “年-月-日 时:分:秒” 格式
	'''

	return datetime.datetime.now().strftime( format )
