#!/bin/bash

#define variable
Tools_Dir="/usr/local/src"
#Rrdtool_Dir="/usr/local/rrdtool"
Rrdtool_Dir=$1
Log_File="/tmp/tri_install.log"
Err_Log="/tmp/tri_install_err.log"

install_rrdtool(){
    printf "Installing Rrdtool,Please wait...\n"
    #judge 
    [ -d $Rrdtool_Dir ] || mkdir $Rrdtool_Dir -p
    #install rpm packet
    yum install cairo-devel libxml2-devel pango-devel pango libpng-devel freetype freetype-devel libart_lgpl-devel intltool -y >>$Log_File 2>&1
    #install libart_lgpl
    cd $Tools_Dir
    wget http://ftp.gnome.org/pub/gnome/sources/libart_lgpl/2.3/libart_lgpl-2.3.21.tar.bz2 >>$Log_File 2>&1
    tar jxf libart_lgpl-2.3.21.tar.bz2
    cd libart_lgpl-2.3.21
    ./configure >>$Log_File 2>&1
    [ $? -ne 0 ] && tail -30 $Log_File |tee -a $Err_Log && exit 1;
    make >>$Log_File 2>&1
    [ $? -ne 0 ] && tail -30 $Log_File |tee -a $Err_Log && exit 1;
    make install >>$Log_File 2>&1
    [ $? -ne 0 ] && tail -30 $Log_File |tee -a $Err_Log && exit 1;
    
    #install rrdtool
    cd $Tools_Dir
    wget http://oss.oetiker.ch/rrdtool/pub/rrdtool-1.4.7.tar.gz >>$Log_File 2>&1
    tar zxf rrdtool-1.4.7.tar.gz  >>$Log_File 2>&1
    cd rrdtool-1.4.7
    ./configure --prefix=$Rrdtool_Dir >>$Log_File 2>&1
    [ $? -ne 0 ] && tail -30 $Log_File |tee -a $Err_Log && exit 1;
    make >>$Log_File 2>&1
    [ $? -ne 0 ] && tail -30 $Log_File |tee -a $Err_Log && exit 1;
    make install >>$Log_File 2>&1
    [ $? -ne 0 ] && tail -30 $Log_File |tee -a $Err_Log && exit 1;

    #rename
    [ -f /usr/bin/rrdtool ] && rename rrdtool rrdtool-1.2_bak /usr/bin/rrdtool;
    #ln -s /usr/local/rrdtool/bin/rrdtool /usr/bin/rrdtool  
    /bin/cp $Rrdtool_Dir/bin/rrdtool /usr/bin/rrdtool  
    printf "Install Rrdtool Success\n"
}

install_rrdtool
