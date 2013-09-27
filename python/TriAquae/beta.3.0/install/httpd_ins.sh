#!/bin/sh

TriAquae_PATH=$1
Tools_Dir="/usr/local/src"
Log_File="/tmp/tri_install.log"

printf "Installing httpd,Please wait...\n"
yum install httpd httpd-devel -y >>$Log_File 2>&1
if [ $? -eq 0 ];then
    printf "Install httpd Success\n"

    #install mod_wsgi
    printf "Installing mod_wsgi,Please wait...\n"
    wsgi_conf="/etc/httpd/conf.d/wsgi.conf"
    wsgi_file="$TriAquae_PATH/wsgi/django.wsgi"
    [ -f $TriAquae_PATH/wsgi ] || mkdir $TriAquae_PATH/wsgi -p
    cd $Tools_Dir
    wget http://modwsgi.googlecode.com/files/mod_wsgi-3.4.tar.gz >>$Log_File 2>&1
    tar zxf mod_wsgi-3.4.tar.gz >>$Log_File 2>&1
    cd mod_wsgi-3.4/
    ./configure --with-apxs=/usr/sbin/apxs --with-python=/usr/bin/python >>$Log_File 2>&1
    make >>$Log_File 2>&1
    make install >>$Log_File 2>&1
    if [ $? -eq 0 ];then
        printf "Install mod_wsgi Success\n"
        #create wsgi file
        echo "import os" >> $wsgi_file
        echo "import sys" >> $wsgi_file
        echo $TriAquae_PATH------------------
        echo "sys.path.append(\"$TriAquae_PATH\")" >> $wsgi_file
        echo "os.environ['DJANGO_SETTINGS_MODULE'] = 'TriAquae.settings'" >> $wsgi_file
        echo "os.environ['PYTHON_EGG_CACHE'] = '/tmp/.python-eggs'" >> $wsgi_file
        echo "current_dir = os.path.dirname(__file__)" >> $wsgi_file
        echo "if current_dir not in sys.path: sys.path.append(current_dir)" >> $wsgi_file
        echo "import django.core.handlers.wsgi" >> $wsgi_file
        echo "application = django.core.handlers.wsgi.WSGIHandler()" >> $wsgi_file

        #create wsgi conf
        echo "#load python django module" >> $wsgi_conf
        echo "LoadModule wsgi_module modules/mod_wsgi.so" >> $wsgi_conf
        echo "" >> $wsgi_conf
        echo "WSGIScriptAlias / $wsgi_file" >> $wsgi_conf
        echo "<Directory "$TriAquae_PATH">" >> $wsgi_conf 
        echo "    Order Deny,Allow" >> $wsgi_conf 
        echo "    Allow from all" >> $wsgi_conf 
        echo "</Directory>" >> $wsgi_conf 
        echo "" >> $wsgi_conf 
        echo "Alias /static "$TriAquae_PATH/TriAquae/static"" >> $wsgi_conf 
        echo "<Directory "$TriAquae_PATH/TriAquae/static">" >> $wsgi_conf 
        echo "    Order allow,deny" >> $wsgi_conf 
        echo "    Allow from all" >> $wsgi_conf 
        echo "</Directory>" >> $wsgi_conf 

        #start httpd server
        /etc/init.d/httpd start >>$Log_File 2>&1

    else
        printf "Install mod_wsgi Error\n"
    fi
else
    printf "Install httpd Error\n"
fi

