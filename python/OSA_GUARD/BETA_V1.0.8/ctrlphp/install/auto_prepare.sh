#/usr/bin/env bash
#       这个脚本用来安装OSA需要的py和相关依耐，部分代码参考了learn0208的auto_v1.sh。
#       version:        2012-2-29       frist version	by ISADBA|FH.CN
#                                       last version    by brian
#                               copy left
LANG=C
yum -y install wget bc zlib zlib-devel gcc-c++ mysql-devel python-devel&> /dev/null
#安装的相关路径定义，请再ctrlphp/install目录里运行此脚本
INSTALL_PATH=$(dirname $(readlink /proc/$$/fd/255))
BASE_PATH=`echo $INSTALL_PATH | sed 's/\/ctrlphp\/install//g'`
if [ ! -d $BASE_PATH/ctrlpy ] 
then
BASE_PATH=/usr/local/osa
fi

#创建临时目录
mkdir -p $BASE_PATH"/tools"
cd $BASE_PATH"/tools"

#判断网络状况
echo "This script probably need to spend you five minutes:"
ping -c 1 -q www.baidu.com &> /dev/null
PING=$?


#安装PYTHON的函数
function INSTALL_PYTHON(){
	echo "Now:Install Python-2.7.2.tgz............."
	if [ ! -f Python-2.7.2.tgz ]
	then
		if [ $PING -eq 0 ]
		then
			wget http://down.osapub.com/osatools/Python-2.7.2.tgz &> /dev/null
		else
			echo "Can't get Python-2.7.2.tgz,please check your network or upload Python-2.7.2.tar.bz2 to this directory."
			exit 2
		fi
    fi
	rm -rf Python-2.7.2
	tar xvf Python-2.7.2.tgz &> /dev/null
        cd Python-2.7.2
        ./configure --prefix=$BASE_PATH/python &> /dev/null		
        make &> /dev/null && make install &> /dev/null
		if [ $? == 0 ]
		then
			echo "Python-2.7.2.tar.bz2" > /tmp/.check_py
			echo "Python-2.7.2.tgz install sucessfull!!!"
		else
			echo "Python-2.7.2.tgz install fail,please check reason."
			exit 1
		fi
		
}

#安装SETUPTOOLS的函数
function INSTALL_SETUPTOOLS(){
	echo "Now:Install setuptools-0.6c10.tar.gz............."
	if [ ! -f setuptools-0.6c10.tar.gz ]
	then
		if [ $PING -eq 0 ]
		then
			wget http://down.osapub.com/osatools/setuptools-0.6c10.tar.gz &> /dev/null
		else
			echo "Can't get setuptools-0.6c10.tar.gz,please check your network or upload setuptools-0.6c10.tar.gz to this directory."
			exit 2
		fi
	fi
	rm -rf setuptools-0.6c10
    tar xvf setuptools-0.6c10.tar.gz &> /dev/null
    cd setuptools-0.6c10
    $BASE_PATH/python/bin/python setup.py build &> /dev/null
    $BASE_PATH/python/bin/python setup.py install &> /dev/null
		if [ $? == 0 ]
		then
			echo "setuptools-0.6c10.tar.gz" >> /tmp/.check_py
			echo "setuptools-0.6c10.tar.gz install sucessfull!!!"
		else
			echo "setuptools-0.6c10.tar.gz install fail,please check reason."
			exit 1
		fi
		
}

#安装MYSQL-PY的函数
function INSTALL_PY_MYSQL(){
	echo "Now:Install  MySQL-python-1.2.3.tar.gz............."
	if [ ! -f MySQL-python-1.2.3.tar.gz ] 
	then
		if [ $PING -eq 0 ]
                then
			wget http://down.osapub.com/osatools/MySQL-python-1.2.3.tar.gz &> /dev/null
		else
			echo "Can't get MySQL-python-1.2.3.tar.gz,please check your network or upload MySQL-python-1.2.3.tar.gz to this directory."
			exit 2
		fi
	fi
	rm -rf MySQL-python-1.2.3
        tar xvf MySQL-python-1.2.3.tar.gz &> /dev/null
        cd MySQL-python-1.2.3
        mysql_config=`which mysql_config`
        if [ $? -ne 0 ] || [ ! -f $mysql_config ];then
			if [ -f '/usr/local/mysql/bin/mysql_config' ];then
				mysql_config='/usr/local/mysql/bin/mysql_config'
			else
				read -p "please enter the mysql_config command path,
				exp:/usr/local/mysql/bin/mysql_config:" mysql_config_path				
                mysql_config=`echo $mysql_config_path`
			fi
        fi
       	
	/bin/sed -i "/mysql_config.path/ s#\"mysql_config\"#\"$mysql_config\"#g" setup_posix.py
        $BASE_PATH/python/bin/python setup.py build &> /dev/null
       	$BASE_PATH/python/bin/python setup.py install &> /dev/null
		if [ $? == 0 ]
		then
			echo "MySQL-python-1.2.3.tar.gz" >> /tmp/.check_py
			echo "MySQL-python-1.2.3.tar.gz install sucessfull!!!"
		else
			echo "MySQL-python-1.2.3.tar.gz install fail,please check reason."
			exit 1
		fi
		
}

INSTALL_JSON(){
	echo "Now:Install  simplejson-2.6.0.tar.gz............."
	if [ ! -f simplejson-2.6.0.tar.gz ]
        then
                if [ $PING -eq 0 ]
                then
                        wget http://down.osapub.com/osatools/simplejson-2.6.0.tar.gz &> /dev/null
                else
                        echo "Can't get simplejson-2.6.0.tar.gz,please check your network or upload simplejson-2.6.0.tar.gz to this directory."
                        exit 2
                fi
        fi
	rm -rf simplejson-2.6.0
	tar xvf simplejson-2.6.0.tar.gz &> /dev/null
	cd simplejson-2.6.0
	$BASE_PATH/python/bin/python setup.py build &> /dev/null
	$BASE_PATH/python/bin/python setup.py install &> /dev/null
	if [ $? == 0 ]
    then
		echo "simplejson-2.6.0.tar.gz" >> /tmp/.check_py
        echo "simplejson-2.6.0.tar.gz install sucessfull!!!"
    else
        echo "simplejson-2.6.0.tar.gz install fail,please check reason."
        exit 1
    fi
		
}

INSTALL_PYSNMP(){
	echo "Now:Install pysnmp-4.2.2 now............."
	if [ ! -f pysnmp-4.2.2.tar.gz ]
        then
                if [ $PING -eq 0 ]
                then
                        wget http://down.osapub.com/soft/pysnmp/{pyasn1-0.1.3.tar.gz,pysnmp-4.2.2.tar.gz} &> /dev/null
                else
                        echo "Can't get pysnmp soft,please check your network or upload akismet-0.2.0.zip,pyasn1-0.1.3.tar.gz,pysnmp-4.2.2.tar.gz to this directory."
                        exit 2
                fi
        fi
		
	rm -rf pyasn1-0.1.3
	tar xf pyasn1-0.1.3.tar.gz	
	cd pyasn1-0.1.3
	$BASE_PATH/python/bin/python setup.py install &> /dev/null
	
	if [ $? == 0 ]
    then
		echo "pyasn1-0.1.3.tar.gz" >> /tmp/.check_py
        echo "pyasn1-0.1.3.tar.gz install sucessfull!!!"
    else
        echo "pyasn1-0.1.3.tar.gz install fail,please check reason."
        exit 1
    fi
	
	cd ..
	rm -rf pysnmp-4.2.2
	tar xf pysnmp-4.2.2.tar.gz
	cd pysnmp-4.2.2
	$BASE_PATH/python/bin/python setup.py install &> /dev/null
	if [ $? == 0 ]
    then
		echo "pysnmp-4.2.2.tar.gz" >> /tmp/.check_py
        echo "pysnmp-4.2.2.tar.gz install sucessfull!!!"
    else
        echo "pysnmp-4.2.2.tar.gz install fail,please check reason."
        exit 1
    fi
}

INSTALL_PYDNS(){
	echo "Now:Install pydns-2.3.6 now............."
	if [ ! -f pydns-2.3.6.tar.gz ]
        then
                if [ $PING -eq 0 ]
                then
                        wget http://down.osapub.com/osatools/pydns-2.3.6.tar.gz &> /dev/null
                else
                        echo "Can't get pydns soft,please check your network or upload pydns-2.3.6.tar.gz to this directory."
                        exit 2
                fi
        fi
		
	rm -rf pydns-2.3.6
	tar xf pydns-2.3.6.tar.gz	
	cd pydns-2.3.6
	$BASE_PATH/python/bin/python setup.py install &> /dev/null
	
	if [ $? == 0 ]
    then
		echo "pydns-2.3.6.tar.gz" >> /tmp/.check_py
        echo "pydns-2.3.6.tar.gz install sucessfull!!!"
    else
        echo "pydns-2.3.6.tar.gz install fail,please check reason."
        exit 1
    fi	
	
}

INSTALL_CHARDET(){
        echo "Now:Install chardet-1.0.1.tar.gz now............."
        if [ ! -f chardet-1.0.1.tar.gz ]
        then
                if [ $PING -eq 0 ]
                then
                        wget http://down.osapub.com/osatools/chardet-1.0.1.tar.gz &> /dev/null
                else
                        echo "Can't get pydns soft,please check your network or upload chardet-1.0.1.tar.gz to this directory."
                        exit 2
                fi
        fi

        rm -rf chardet-1.0.1
        tar xf chardet-1.0.1.tar.gz
        cd chardet-1.0.1
        $BASE_PATH/python/bin/python setup.py install &> /dev/null

        if [ $? == 0 ]
    then
        echo "chardet-1.0.1.tar.gz install sucessfull!!!"
    else
        echo "chardet-1.0.1.tar.gz install fail,please check reason."
        exit 1
    fi

}

#需要权限的列表

CHMOD_FILE(){

	FLIST="
	    $BASE_PATH/ctrlphp/data
	    $BASE_PATH/ctrlphp/session
	    $BASE_PATH/ctrlphp/log
	    $BASE_PATH/ctrlphp/etc
	    $BASE_PATH/ctrlpy/log
	    $BASE_PATH/ctrlpy/etc
	    $BASE_PATH/unctrlpy/log
	    $BASE_PATH/unctrlpy/etc
	    $BASE_PATH/unctrlsh/log
	    $BASE_PATH/unctrlsh/etc
	    $BASE_PATH/etc
	"
	/bin/chmod -R 755 $BASE_PATH
	
	for f in $FLIST
	do
		if [ -d $f ]
		then
			/bin/chmod -R 777 ${f}
		fi
	done
	
	/bin/chmod -R 777 /tmp/.check_py
}

#主程序
MAIN_INSTALL(){
		cd $BASE_PATH"/tools"

		INSTALL_PYTHON			
		
		if [ "$1" == "client" ]
		then
			return
		fi 
		cd $BASE_PATH"/tools"

		INSTALL_SETUPTOOLS			

		cd $BASE_PATH"/tools"

		INSTALL_PY_MYSQL		

		cd $BASE_PATH"/tools"

	    	INSTALL_JSON 
			
		cd $BASE_PATH"/tools"
		INSTALL_PYSNMP

		cd $BASE_PATH"/tools"

		INSTALL_PYDNS

		cd $BASE_PATH"/tools"

		INSTALL_CHARDET

}

if [ ! -f "$BASE_PATH/python/bin/python" ] || [ ! -f "/tmp/.check_py" ] || [ ! -f $BASE_PATH"/tools/pysnmp-4.2.2.tar.gz" ]
then

MAIN_INSTALL $1

fi

CHMOD_FILE 

#添加环境变量
if ! grep $BASE_PATH /etc/profile
then	
echo "PYTHONPATH=$BASE_PATH
export PYTHONPATH" >>/etc/profile
source /etc/profile
fi

cd $BASE_PATH"/bin"

for f in `ls`
do
	echo $BASE_PATH"/bin/"$f
	ln -f -s $BASE_PATH"/bin/"$f /usr/local/bin/$f
done


