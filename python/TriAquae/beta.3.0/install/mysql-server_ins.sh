#!/bin/sh

Log_File="/tmp/tri_install.log"

printf "Installing mysql-server,Please wait...\n"
yum install mysql-server mysql mysql-devel MySQL-python -y >>$Log_File 2>&1
if [ $? -eq 0 ];then
    printf "Install mysql-server Success\n"
    #start httpd server
    /etc/init.d/mysqld start >>$Log_File 2>&1
else
    printf "Install mysql-server Error\n"
fi

