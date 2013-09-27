#!/usr/bin/env python
#coding=utf-8

'''
	Author:	osa开源团队
	Description:	snmp获取windows状态
	Create Date：	2012/12/13
'''

from pysnmp.entity.rfc3413.oneliner import cmdgen
from pysnmp.proto.rfc1902 import ObjectName
from ctrlpy.lib.osaSnmpLib import ger_snmp_next

def getOStype(agent, ip, key, port):
    '''
    获取操作系统类型：
    bit --> getOStype[0]
    type --> getOStype[1]
    '''
    system_oid = ObjectName('1.3.6.1.2.1.1.1')
    system_get = ger_snmp_next(agent,ip,key,port,system_oid)
    system_name = str(system_get[0][0][1])
    if 'Windows' in system_name:
        system_type = 'Windows'
        if 'x86' in system_name:
            system_bit = "32bit"
        else:
            system_bit = "64bit"
    elif 'Linux' in system_name:
        system_type = 'Linux'
        if 'x86_64' in system_name:
            system_bit = "64bit"
        else:
            system_bit = "32bit"
    else:
        system_type = 'Null'
    return system_bit,system_type

#print getOStype('myagent', '127.0.0.1', 'public', '161')
