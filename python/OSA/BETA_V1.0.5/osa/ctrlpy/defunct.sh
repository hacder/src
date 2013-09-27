#!/bin/bash

while(true)
do

zpid=` ps -A -ostat,ppid,pid,cmd,time | grep -e '^[Zz]'`

if [[ ! -z $zpid ]]
then

	echo $(date +"%Y-%m-%d %H:%M:%S") "$zpid" >> ./log/zpid.log

fi

sleep 1

done
