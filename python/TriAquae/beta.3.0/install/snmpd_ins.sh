#!/bin/sh

Log_File="/tmp/tri_install.log"

printf "Installing net-snmp,Please wait...\n"
yum install net-snmp net-snmp-utils -y >>$Log_File 2>&1
if [ $? -eq 0 ];then
    printf "Install net-snmp Success\n"
else
    printf "Install Shellinabox Error\n"
fi

