#/usr/bin/env bash

BASE_PATH=/usr/local/osa

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
	chmod -R 755 $BASE_PATH
	
	for f in $FLIST
	do
		chmod -R 777 ${f}
	done
}
cd /usr/local/osa/
if [ ! -f $BASE_PATH"/update.lock" ]
then
CHMOD_FILE
/usr/local/osa/php/bin/php /usr/local/osa/ctrlphp/install/key.php
touch $BASE_PATH"/update.lock"
fi
