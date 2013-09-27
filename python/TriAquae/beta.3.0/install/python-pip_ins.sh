#!/bin/bash

#define variable
Tools_Dir="/usr/local/src"
Log_File="/tmp/tri_install.log"
Err_Log="/tmp/tri_install_err.log"

install_pip(){
    printf "Installing python-pip,Please wait...\n"
    #install setuptools
    cd $Tools_Dir
    wget --no-check-certificate https://pypi.python.org/packages/source/s/setuptools/setuptools-0.9.8.tar.gz >>$Log_File 2>&1
    tar zxf setuptools-0.9.8.tar.gz
    cd setuptools-0.9.8
    python setup.py install >>$Log_File 2>&1
    [ $? -ne 0 ] && tail -30 $Log_File |tee -a $Err_Log && exit 1;
    #install pip
    cd $Tools_Dir
    wget --no-check-certificate https://pypi.python.org/packages/source/p/pip/pip-1.1.tar.gz >>$Log_File 2>&1
    tar zxf pip-1.1.tar.gz
    cd pip-1.1
    python setup.py install >>$Log_File 2>&1
    [ $? -ne 0 ] && tail -30 $Log_File |tee -a $Err_Log && exit 1;

    #add pip command
    /bin/cp /usr/local/python/bin/pip /usr/bin/
    
    printf "Install python-pip Success\n"
    
}

install_pip
