#!/bin/sh

#define variable
Tools_Dir="/usr/local/src"
Python_Dir="/usr/local/python-2.6"
Log_File="/tmp/tri_install.log"
Err_Log="/tmp/tri_install_err.log"

install_python(){
    printf "Installing Python,Please wait...\n"
    #judge 
    [ -d $Python_Dir ] || mkdir $Python_Dir -p
    #install python
    cd $Tools_Dir
    wget http://www.python.org/ftp/python/2.6.6/Python-2.6.6.tgz >>$Log_File 2>&1
    tar zxf Python-2.6.6.tgz >>$Log_File 2>&1
    cd Python-2.6.6
    ./configure --enable-shared --prefix=$Python_Dir >>$Log_File 2>&1
    [ $? -ne 0 ] && tail -30 $Log_File |tee -a $Err_Log && exit 1;
    make >>$Log_File 2>&1
    [ $? -ne 0 ] && tail -30 $Log_File |tee -a $Err_Log && exit 1;
    make install >>$Log_File 2>&1
    [ $? -ne 0 ] && tail -30 $Log_File |tee -a $Err_Log && exit 1;
    ln -s $Python_Dir /usr/local/python
    rename python python2.4_bak /usr/bin/python
    ln -s /usr/local/python/bin/python /usr/bin/python
    #modifiy yum command content
    sed -i 's@^#!/usr/bin/python$@#!/usr/bin/python2.4@g' /usr/bin/yum
    #load python2.6 lib
    echo "$Python_Dir/lib/" > /etc/ld.so.conf.d/python2.6.conf
    ldconfig
    /bin/cp $Python_Dir/lib/libpython2.6.so /usr/lib
    printf "Install Python Success\n"
}

install_python
