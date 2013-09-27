#!/usr/bin/env python
#encoding=utf-8
'''
	Author: 	OSA开源团队
	Description:	(监控项目及项目报警辅助函数)
	Date: 		2011-08-20
	@ 依赖pyDNS 需要安装pyDNS模块
'''
import re ,DNS,simplejson,chardet
import urllib2 ,socket,httplib
import subprocess,time,signal,sys
from datetime import datetime
from ctrlpy.lib.osaLogLib import *
from ctrlpy.etc.config import MONITOR
from ctrlpy.lib import cmdtosql
from ctrlpy.lib.osaPing import Ping



def website_get_domain(host,itemconfig):
	'''
	@获取域名
	@return domain
	'''
	try:
		if itemconfig['ip'] != '':
			domain = itemconfig['ip']
		else:			
			domain = host		
	except Exception as e:
		log_error("website_get_domain():"+str(e))
	return domain

def website_get_newUrl(url):
	'''
	@对url进行初步解析替换
	@return newUrl
	'''
	try:
		if url.find("http://") != -1:
			newUrl = url.replace("http://","")
		elif url.find("https://") != -1:
			newUrl = url.replace("https://","")
		else:
			newUrl = url
		return newUrl
	except Exception as e:
		log_error("website_get_newUrl():"+str(e))
	
	
def website_url_analyze(url,itemconfig):
	'''
	@url 解析
	'''
	try:
		newUrl = website_get_newUrl(url)
		index = newUrl.find("/")
		if index != -1:
			host = newUrl[0:index]
			
			urlsuffix = newUrl.replace(host,"")
		else:
			host = newUrl
			urlsuffix = "/"
			
		port = 80
		spix = host.find(':')		
		if spix != -1:
			port = host[spix:]
			host = host[0:spix]
			port = port.replace(':','')
		domain = website_get_domain(host,itemconfig)
		return domain,host,urlsuffix,port
	except Exception as e:
		log_error("website_url_analyze():"+str(e))

def dns_test(domain):
	'''
	@DNS 解析 测试
	'''
	try:
		domain = website_get_newUrl(domain)
		index = domain.find("/")
		if index != -1:
			domain = domain[0:index]		
		domain = domain.replace('/','')
		spix = domain.find(':')
		if spix != -1:
			domain = domain[0:spix]
		DNS.DiscoverNameServers()
		req = DNS.Request()
		ans = req.req(name = domain , qtype = DNS.Type.ANY)
		return ans.answers
	except Exception as e:
		log_error("dns_test():"+str(e)+str(domain))		
		dnsresult = []
		return dnsresult
		
def get_domain_ip(domain):
	'''
	@域名 解析 IP 
	'''
		
	domain = website_get_newUrl(domain)
	index = domain.find("/")
	if index != -1:
		domain = domain[0:index]
	domain = domain.replace('/','')
	
	spix = domain.find(':')
	if spix != -1:
		domain = domain[0:spix]	
	result=[]
	try:
		result=socket.getaddrinfo(domain,None)
	except Exception as e:
		log_error("get_domain_ip:"+str(e)+str(domain))	

	return result

def dns_test_three(domain):
	'''
	@域名解析IP ,重试3次
	@return 元组
	'''
	try:
		isip = re.findall(r'\d+.\d+.\d+.\d+', domain)
		if len(isip) > 0:
			return ['ip']
	except Exception as e:
		log_error("dns_test_three() isip:"+str(e))
	ipresult = []
	try:
		i=0
		max=3		
		ipresult = get_domain_ip(domain)		
		while(i < max):
			if len(ipresult) > 0:
				return ipresult			
			time.sleep(0.5)			
			ipresult = get_domain_ip(domain)
			i=i+1					
		return ipresult			
	except Exception as e:
		log_error("dns_test_three():"+str(e))		
		return ipresult

def website_urlopen_reuslt(url,itemconfig):
	'''
	@访问url,获取返回结果
	@return 元组
	'''
	try:
		domain,host,urlsuffix,port = website_url_analyze(url,itemconfig)
		#dnsresult = []
		#dnsresult = dns_test_three(url)
		
		#if len(dnsresult) <= 0:		
		#	log_error("dns_test(url):"+str(url))
		#	result = {"responsetime":0,"status":url+" DNS解析失败!"}
		#	return str(0),simplejson.dumps(result),str(1),url+" DNS解析失败!"
		
		#if Ping(host) == False:
		#	result = {"responsetime":0,"status":"网站服务器("+host+")不可达!"}
		#	return str(0),simplejson.dumps(result),str(1),"网站服务器("+host+")不可达!"
		
		#header = {"Host":host,"Accept": "text/plain"}
		header = {"Host":host}
		try:
			if url.find("https://") != -1:#https访问
				conn = httplib.HTTPSConnection(domain,port=int(port))
			else:#http访问
				conn = httplib.HTTPConnection(domain,port=int(port))
		except httplib.HTTPException as e:
			conn.close()
			result = {"responsetime":0,"status":str(e)}
			return str(0),simplejson.dumps(result),str(1),str(e)
		except httplib.NotConnected as e:
			conn.close()
			result = {"responsetime":0,"status":str(e)}
			return str(0),simplejson.dumps(result),str(1),str(e)
		except httplib.InvalidURL as e:
			conn.close()
			result = {"responsetime":0,"status":str(e)}
			return str(0),simplejson.dumps(result),str(1),str(e)
		except httplib.UnknownProtocol as e:
			conn.close()
			result = {"responsetime":0,"status":str(e)}
			return str(0),simplejson.dumps(result),str(1),str(e)
		except httplib.UnknownTransferEncoding as e:
			conn.close()
			result = {"responsetime":0,"status":str(e)}
			return str(0),simplejson.dumps(result),str(1),str(e)
		except httplib.UnimplementedFileMode as e:
			conn.close()
			result = {"responsetime":0,"status":str(e)}
			return str(0),simplejson.dumps(result),str(1),str(e)
		except httplib.IncompleteRead as e:
			conn.close()
			result = {"responsetime":0,"status":str(e)}
			return str(0),simplejson.dumps(result),str(1),str(e)
		except httplib.CannotSendRequest as e:
			conn.close()
			result = {"responsetime":0,"status":str(e)}
			return str(0),simplejson.dumps(result),str(1),str(e)
		except httplib.CannotSendHeader as e:
			conn.close()
			result = {"responsetime":0,"status":str(e)}
			return str(0),simplejson.dumps(result),str(1),str(e)
		except httplib.ResponseNotReady as e:
			conn.close()
			result = {"responsetime":0,"status":str(e)}
			return str(0),simplejson.dumps(result),str(1),str(e)
		except httplib.BadStatusLine as e:
			conn.close()
			result = {"responsetime":0,"status":str(e)}
			return str(0),simplejson.dumps(result),str(1),str(e)
		except Exception as e:
			conn.close()
			result = {"responsetime":0,"status":str(e)}
			return str(0),simplejson.dumps(result),str(1),str(e)
		finally:
			conn.close()
			
			
		start = datetime.now()
		conn.request("GET",urlsuffix,headers = header)
		res = conn.getresponse()
		content = res.read()
		status = res.status
		reason = res.reason
		conn.close()
		end = datetime.now()
	except Exception as e:
		log_error("website_urlopen_result():"+str(e))
		result = {"responsetime":0,"status":str(e)}
		return str(0),simplejson.dumps(result),str(1),str(e)
		
	responsetime = (end - start).microseconds / 1000
	result = {"responsetime":responsetime,"status":str(status)}
	return str(1),simplejson.dumps(result),content,status


def website_alarm_analyze(status,content,itemconfig):
	'''
	@网页存活高级设置判定
	@return 元组
	'''
		
	httpcode = itemconfig['httpcode'].split(',')
	if str(status) in httpcode:
		c1 = 1 #网页存活有两个条件，第一个条件是状态码存在
	else:
		c1 = 0	
	c2 = 1 #假设第二个条件为真：关键字都匹配
	if itemconfig['keywords'] and itemconfig['keywords']!="":
		###处理GKB编码的关键字对比
		r=chardet.detect(content)
		if r['encoding'].upper() != 'UTF-8':
			content = content.decode('gbk')
			content = content.encode('utf-8')
		for i in range(len(itemconfig['keywords'].split(','))):
			if not itemconfig['keywords'].split(',')[i] in content:
				c2 = 0	#满足某个关键字不匹配，则条件二为假
				break
	if c1 and c2:#满足条件不报警
		return str(0),'',''
	elif c1==0 and c2==1:#状态码不匹配
		reason = "网页返回状态码("+str(status)+")跟用户定义状态码不匹配"
		return str(1),reason,str(3)
	elif c1==1 and c2==0:
		reason = "网页返回内容跟用户定关键字不匹配"
		return str(1),reason,str(3)
	else:#关键字不匹配
		reason = "网页返回状态码跟用户定义状态码不匹配，且网页返回内容跟用户定关键字不匹配"
		return str(1),reason,str(3)
	

def website_alive_check(url,itemconfig):
	'''
	@网页存活检测
	@url:网页地址，itemconfig:网页存活高级设置
	@return 
	'''
	#url访问
	try:
		isTrue,result,content,status = website_urlopen_reuslt(url,itemconfig)
		if isTrue == '0':#访问异常
			reason = "网页访问失败，返回状态:"+status
			return str(1),result,reason,str(1)
		#高级设置验证
		check ,reason,level = website_alarm_analyze(status,content,itemconfig)
		return check,result,reason,level
	except Exception as e:
		log_error("website_alive_check():"+str(e))
		return str(1),result,str(e),str(1)
	
##########################分割线----------- website 存活验证结束 -------------分割线######################	


	


if __name__ == '__main__':
	'''
	@test
	'''
	pass
	
