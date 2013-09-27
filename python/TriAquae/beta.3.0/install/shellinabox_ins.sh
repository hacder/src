#!/bin/bash

#define variable
Script_Dir="/server/scripts"
Tools_Dir="/usr/local/src"
#Shellinabox_Dir="/usr/local/shellinabox"
Shellinabox_Dir=$1
Key_Dir="/usr/local/shellinabox/key"
key_pass="coral"
#Ip=`ifconfig eth0 |awk 'BEGIN {FS=":"}/inet addr/ {print $2}' |cut -d' ' -f1`
Log_File="/tmp/tri_install.log"
Err_Log="/tmp/tri_install_err.log"

install_shellinabox(){
    printf "Installing Shellinabox,Please wait...\n"
    #judge 
    [ -d $Shellinabox_Dir ] || mkdir $Shellinabox_Dir -p
    [ -d $Key_Dir ] || mkdir $Key_Dir -p
    #install rpm packet
    yum install expect -y >>$Log_File 2>&1
    yum install gcc gcc-c++ openssl-devel zlib-devel -y >>$Log_File 2>&1
    yum install openssl openssl-devel -y >>$Log_File 2>&1
    #install shellinabox
    cd $Tools_Dir
    wget http://shellinabox.googlecode.com/files/shellinabox-2.10.tar.gz >>$Log_File 2>&1
    tar zxf shellinabox-2.10.tar.gz >>$Log_File 2>&1
    cd shellinabox-2.10/
    ./configure --prefix=$Shellinabox_Dir >>$Log_File 2>&1
    [ $? -ne 0 ] && tail -30 $Log_File |tee -a $Err_Log && exit 1;
    make >>$Log_File 2>&1
    [ $? -ne 0 ] && tail -30 $Log_File |tee -a $Err_Log && exit 1;
    make install >>$Log_File 2>&1
    [ $? -ne 0 ] && tail -30 $Log_File |tee -a $Err_Log && exit 1;
    #Generate the pem certificate
    #create key
    #cd $Script_Dir
    #/usr/bin/expect ./shellinabox_ins.exp $Key_Dir $key_pass
    #cd $Key_Dir
    #cat my.crt my.key > certificate.pem

    #add shellinaboxd command
    /bin/cp $Shellinabox_Dir/bin/shellinaboxd /usr/bin

    #start shellinabox
    #$Shellinabox_Dir/bin/shellinaboxd -t -u root -b           #http start
    #$Shellinabox_Dir/bin/shellinaboxd -c $Key_Dir -u root -b  #https start
    #if [ -n "`netstat -tnl |grep 4200`" ];then
    #    printf "Install Shellinabox Success"
    #fi

    printf "Install Shellinabox Success"
}                                                                                                           #    fi

install_shellinabox
