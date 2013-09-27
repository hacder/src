#!/usr/bin/env python
#encoding=utf-8

'''
	Author:		osa开源团队
	Description:	email 通知内容模版
	Create Date:	2011-08-17
'''

import os ,sys

def template_server_downtime():
	'''
	@宕机email模版
	@#$# :表示需要替换的变量
	'''
	template = "OSA监控到您的服务器（#devname#）[#ip#]不可达。\n\
				该服务器业务描述为：#workdescription#。\n\
				消息级别：严重故障通知 \n\
				故障时间：#time# \n\
				故障原因：不可达 \n\
				80%的用户会采取的解决方案《寻求帮助》：\n\
				1，通过OSA监控项目关联操作，监控到异常时自动将有冗余的业务切换。\n\
				2，通过负载均衡，实现冗余，监控到故障时自动切换。\n\
				3，通过手工将业务切换到备份服务器，马上进行故障恢复 \
				"
	return template
	
def template_website_abnormal():
	'''
	@网站异常email模版
	@#$# :表示需要替换的变量
	'''
	template = "OSA监控到您的网址：#website# 访问异常，己帮您保存快照，点击查看。\n\
				消息级别：普通故障通知  \n\
				故障时间：#time# \n\
				故障原因：#reason# \n\
				80%的用户会采取的解决方案《寻求帮助》：\n\
				1，通过OSA监控项目关联操作，监控到异常时自动重启WEB服务。\n\
				2，通过负载均衡，实现网站冗余，监控到故障时自动切换。\n\
				3，通过手工切换到镜像站，马上进行故障恢复。\
				"
	return template
	
def template_unctrpy_exception():
	'''
	@受控端py服务异常email模版
	@#$# :表示需要替换的变量
	@保留意见(这个可能没什么用)
	'''
	template = "OSA监控到您的受制端（#devname#）[#ip#]异常。\n\
				该服务器业务描述为：#workdescription#。\n\
				消息级别：普通故障通知 \n\
				故障时间：#time# \n\
				故障原因：受控端服务异常，负载高或者网络不稳定。\n\
				80%的用户会采取的解决方案《寻求帮助》：\n\
				1，通过手工，重启受控端，命令：osacliet restart。\n\
				2，根据响应速度和负载等图形，分析目标服务器的网络和负载状况。\n\
				"
	return template
	
def template_server_exception():
	'''
	@XXX服务异常email模版
	@#$# :表示需要替换的变量
	@保留意见(定位不清晰，xxx没有具体的范围和举例)
	'''
	template = "OSA监控到您的XXX服务异常。\n\
				消息级别：普通故障通知 \n\
				故障时间：#time# \n\
				故障原因：XX原因 \n\
				80%的用户会采取的解决方案《寻求帮助》：\n\
				1，通过OSA监控项目关联操作，监控到异常时自动执行指定操作。\n\
				2，通过负载均衡，实现冗余，监控到故障时自动切换。\n\
				3，通过手工将业务切换到备份服务器，马上进行故障恢复。\
				"
	return template
	
def template_mysql_exception():
	'''
	@数据库异常email模版
	@#$# :表示需要替换的变量
	'''
	template = "OSA监控到您的#ip#数据库服务异常。\n\
				消息级别：普通故障通知 \n\
				故障时间：#time# \n\
				故障原因：#reason# \n\
				80%的用户会采取的解决方案《寻求帮助》：\n\
				1，通过OSA监控项目关联操作，监控到异常时自动执行指定操作。\n\
				2，通过负载均衡，实现冗余，监控到故障时自动切换。\n\
				3，通过手工将业务切换到备份服务器，马上进行故障恢复。\
				"
	return template
	
def template_serverindex_error():
	'''
	@服务指标错误email模版
	@#$# :表示需要替换的变量
	'''
	template = "OSA监控到您的服务器（#devname#）[#ip#]#item#监控指标异常。\n\
				消息级别：普通故障通知 \n\
				故障时间：#time# \n\
				故障原因：#reason# \n\
				80%的用户会采取的解决方案《寻求帮助》：\n\
				1，	通过OSA监控项目关联操作，监控到异常时自动执行指定操作。\n\
				2，	通过负载均衡，实现冗余，监控到故障时自动切换。\n\
				3，	通过手工将业务切换到备份服务器，马上进行故障恢复。\n\
				4，	通过OSA图形分析中心，对各项目服务器指标进行分析，定位问题。\
				"
	return template
	
def template_network_exception():
	'''
	@网络异常email模版
	@#$# :表示需要替换的变量
	'''
	template = "OSA监控到您的#item#监控项目网络异常，该项目响应时间超过60S。\n\
				消息级别：普通故障通知 \n\
				故障时间：#time# \n\
				故障原因：网络响应时间超过60s \n\
				80%的用户会采取的解决方案《寻求帮助》：\n\
				1，通过OSA监控项目关联操作，监控到异常时自动执行指定操作。\n\
				2，通过负载均衡，实现冗余，监控到故障时自动切换。\n\
				3，通过手工将业务切换到备份服务器，马上进行故障恢复。\n\
				4，通过OSA图形分析中心，对各项目服务器指标进行分析，定位问题。\
				"
	return template

def template_item_unstable():
	'''
	@监控项目不稳定email模版
	@#$# :表示需要替换的变量
	@保留意见(这个是指根据监控记录进行分析后的结果，不应该放在监控项目里面的告警消息，可以放在生成的报表中)
	'''
	template = "OSA监控到您的#item#监控项目不稳定，该项目1小时内告警超过10次。\n\
				消息级别：普通故障通知 \n\
				故障时间：#time# \n\
				故障原因：该项目1小时内告警超过10次 \n\
				80%的用户会采取的解决方案《寻求帮助》：\n\
				1，通过OSA监控项目关联操作，监控到异常时自动执行指定操作。\n\
				2，通过负载均衡，实现冗余，监控到故障时自动切换。\n\
				3，通过手工将业务切换到备份服务器，马上进行故障恢复。\n\
				4，通过OSA图形分析中心，对各项目服务器指标进行分析，定位问题。\
				"
	return template
	
def template_response_timeout():
	'''
	@响应超时email模版
	@#$# :表示需要替换的变量
	@保留意见(在这里定义的网络超时跟网络不稳定的原因如出一辙，不知道有什么特殊)
	'''
	template = "OSA监控到您的#item#响应超时。\n\
				消息级别：普通故障通知 \n\
				故障时间：#time# \n\
				故障原因：响应超时 \n\
				80%的用户会采取的解决方案《寻求帮助》：\n\
				1，通过OSA监控项目关联操作，监控到异常时自动执行指定操作。\n\
				2，通过负载均衡，实现冗余，监控到故障时自动切换。\n\
				3，通过手工将业务切换到备份服务器，马上进行故障恢复。\n\
				4，通过OSA图形分析中心，对各项目服务器指标进行分析，定位问题。\
				"
	return template
	
def template_item_unable():
	'''
	@监控项目不可用email模版
	@#$# :表示需要替换的变量
	@保留意见(这个是指根据监控记录进行分析后的结果，不应该放在监控项目里面的告警消息，可以放在生成的报表中)
	'''
	template = "OSA监控到您的#item#监控项目可用率低于10%。\n\
				消息级别：普通故障通知 \n\
				故障时间：#time# \n\
				故障原因：监控项目可用率低于10% \n\
				80%的用户会采取的解决方案《寻求帮助》：\n\
				1，通过OSA监控项目关联操作，监控到异常时自动执行指定操作。\n\
				2，通过负载均衡，实现冗余，监控到故障时自动切换。\n\
				3，通过手工将业务切换到备份服务器，马上进行故障恢复。\n\
				4，通过OSA图形分析中心，对各项目服务器指标进行分析，定位问题。\
				"
	return template
	
def template_unknown_exception():
	'''
	@未知异常email模版
	@#$# :表示需要替换的变量
	'''
	template = "OSA监控到您的#item#监控项目出现未知异常。\n\
				消息级别：普通故障通知 \n\
				故障时间：#time# \n\
				故障原因：未知 \n\
				80%的用户会采取的解决方案《寻求帮助》：\n\
				1，通过OSA监控项目关联操作，监控到异常时自动执行指定操作。\n\
				2，通过负载均衡，实现冗余，监控到故障时自动切换。\n\
				3，通过手工将业务切换到备份服务器，马上进行故障恢复。\n\
				4，通过OSA图形分析中心，对各项目服务器指标进行分析，定位问题。\
				"
	return template
	
def template_port_exception():
	'''
	@端口异常email模版
	@#$# :表示需要替换的变量
	'''
	template = "OSA监控到您的#item#监控项目出现#port#端口异常。\n\
				消息级别：普通故障通知 \n\
				故障时间：#time# \n\
				故障原因：端口异常  \n\
				80%的用户会采取的解决方案《寻求帮助》：\n\
				1，通过OSA监控项目关联操作，监控到异常时自动执行指定操作。\n\
				2，通过负载均衡，实现冗余，监控到故障时自动切换。\n\
				3，通过手工将业务切换到备份服务器，马上进行故障恢复。\n\
				4，通过OSA图形分析中心，对各项目服务器指标进行分析，定位问题。\
				"
	return template
	
def template_item_remind():
	'''
	@项目恢复通知email模版
	@#$# :表示需要替换的变量
	'''
	template = "OSA监控到您的#item#监控项目恢复正常。\n\
				消息级别：普通故障通知 \n\
				故障持续时间：#faultTime# \n\
				上一次故障原因：#lastReason# \
				"
	return template
	
def template_server_remind():
	'''
	@服务器恢复通知email模版
	@#$# :表示需要替换的变量
	'''
	template = "OSA监控到您的服务器（#devname#）[#ip#]恢复正常。\n\
				消息级别：普通故障通知 \n\
				故障持续时间：#faultTime# \n\
				上一次故障原因：#lastReason# \
				"
	return template
	
def choose_template(template):
	'''
	@根据template选择需要的模版
	@template:unknown_exception,item_unable,response_timeout,server_downtime,website_abnormal,unctrpy_exception,server_exception
	@mysql_exception,serverindex_error,network_exception,item_unstable,server_remind,item_remind,port_exception
	'''
	if template == 'server_remind':
		return template_server_remind()
	elif template == 'item_remind':
		return template_item_remind()
	elif template == 'port_exception':
		return template_port_exception()
	elif template == 'server_downtime':
		return template_server_downtime()
	elif template == 'website_abnormal':
		return template_website_abnormal()
	elif template == 'mysql_exception':
		return template_mysql_exception()
	elif template == 'network_exception':
		return template_network_exception()
	elif template == 'unctrpy_exception':
		return template_unctrpy_exception()
	elif template == 'server_exception':
		return template_server_exception()
	elif template == 'serverindex_error':
		return template_serverindex_error()
	elif template == 'unknown_exception':
		return template_unknown_exception()
	elif template == 'response_timeout':
		return template_response_timeout()
	elif template == 'item_unable':
		return template_item_unable()
	elif template == 'item_unstable':
		return template_item_unstable()
	
