#!/bin/sh

#define variable
Tools_Dir="/usr/local/src"
Log_File="/tmp/tri_install.log"
Err_Log="/tmp/tri_install_err.log"

install_gmp(){
    printf "Installing gmp,Please wait...\n"
    #install apache
    cd $Tools_Dir
    wget ftp://ftp.gnu.org/gnu/gmp/gmp-5.1.2.tar.bz2 >>$Log_File 2>&1
    tar jxf gmp-5.1.2.tar.bz2 >>$Log_File 2>&1
    cd gmp-5.1.2
    ./configure --enable-cxx >>$Log_File 2>&1
    [ $? -ne 0 ] && tail -30 $Log_File |tee -a $Err_Log && exit 1;
    make >>$Log_File 2>&1
    [ $? -ne 0 ] && tail -30 $Log_File |tee -a $Err_Log && exit 1;
    make check >>$Log_File 2>&1
    [ $? -ne 0 ] && tail -30 $Log_File |tee -a $Err_Log && exit 1;
    make install >>$Log_File 2>&1
    [ $? -ne 0 ] && tail -30 $Log_File |tee -a $Err_Log && exit 1;
    printf "Install gmp Success\n"
}

install_gmp
