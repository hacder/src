<?php
/**
osa注释!

-------------------
配置文件注释：核心配置文件，定义数据库配置，socket端口等!

write by:osa PHP开发团队
*/

//定义程序安装路径
define('OSA_INSTALL_PATH','/usr/local/osa/');  

//定义程序版本名称
define('OSA_VERSION','OSA_BETA_V1.0.5');  

//定义程序版本号
define('OSA_VERSION_NUM','1.0.5');  

//定义osaweb域名
define('OSA_WEBSERVER_DOMAIN','http://interface.osapub.com');

//定义密钥
define('OSA_SYSTEM_KEY','1234567890');

//定义公共配置文件目录
define('OSA_PUBETC_PATH','/usr/local/osa/etc/'); 

//定义PHP配置文件存档路径
define('OSA_PHPETC_PATH',OSA_PHPROOT_PATH.'/etc/'); 

//定义PHP库文件路径
define('OSA_PHPLIB_PATH',OSA_PHPROOT_PATH.'/lib/'); 

//定义osa 中模块封装方法路径
define('OSA_PHPMODEL_PATH',OSA_PHPROOT_PATH.'/models/'); 

//定义osa 中controllers封装方法路径
define('OSA_PHPCONTROLLERS_PATH',OSA_PHPROOT_PATH.'/controllers/'); 

//定义osa 中模块封装方法路径
define('OSA_PHPVIEWS_PATH',OSA_PHPROOT_PATH.'/views/'); 

//定义osa 中数据存储路径
define('OSA_PHPDATA_PATH', OSA_PHPROOT_PATH.'/data/');

//定义PHP日志文件路径
define('OSA_PHPLOG_PATH',OSA_PHPROOT_PATH.'/log/'); 

//定义PHP session文件路径
define('OSA_PHPSESSION_PATH',OSA_PHPROOT_PATH.'/session/'); 

//定义PHP session超时时间
define('OSA_SESSION_EXPIRE_TIME',84000); 

//定义PHPinclude路径
define('OSA_PHPINCLUDE_PATH',OSA_PHPROOT_PATH.'/include/');  

//定义PHPserver路径
define('OSA_PHPSERVER_PATH',OSA_PHPROOT_PATH.'/server/');   

//定义邮件类路径
define('OSA_PHPMAIL_PATH',OSA_PHPROOT_PATH.'/phpmailer/');  

//定义agent IP！
define('OSA_PHPSOCKET_IP','127.0.0.1'); 

//定义socket 端口！
define('OSA_PHPSOCKET_PORT','10623'); 

//定义socket超时时间
define('OSA_PHPSOCKET_TIMEOUT','10');

//定义fsocket日志文件
define('OSA_PHPFSOCKET_LOGFILENAME','fsocket.log'); 

//定义重试次数，0为关闭重试，1为重试一次，以此类推！
define('OSA_PHPSOCKET_RECONN_STATUS','1');  

/**
*定义数据库连接信息
*osa_MYSQL_CONN_DNS 连接字符串
*osa_MYSQL_CONN_USER 用户名
*osa_MYSQL_CONN_PASSWD 密码
*osa_MYSQL_CONN_USER 字符集
*/
define('OSA_MYSQL_CONN_DNS','mysql:dbname=openwebsa;host=127.0.0.1;port=3306'); 

define('OSA_MYSQL_CONN_USER','openwebsa_conn_user');

define('OSA_MYSQL_CONN_PASSWD','openwebsa_conn_pw');

define('OSA_MYSQL_CONN_CHARSET','utf8');

define('OSA_MYSQL_CONN_PRIFIX','osa_');

/*数据库配置定义结束 */

/**
*定义登录部分安全设置
osa_BLOCK_IP 是否开启IP限制登录，默认为0不开启,1为开启！
osa_BLOCK_FILE  锁定IP的文件，一行一个！
osa_TRY_LOGIN_NUM 最多可尝试几次登录。
osa_PASSWORD_PREFIX 密码前缀
*/


define('OSA_BLOCK_IP',0); 

define('OSA_BLOCK_FILE',OSA_PHPLOG_PATH.'/blockip.txt'); 

define('OSA_TRY_LOGIN_NUM',3); 

define('OSA_PASSWORD_PREFIX','osa'); 


/**
 * 定义一下列表默认开始时间 
 * 安装的时候修改其为安装时间
 */
define('OSA_DEFAULT_STARTTIME',date("Y-m-d H:i:s",strtotime('2012-05-01')));

/**
 * 定义每页显示的数量
 */
define('OSA_DEFAULT_PERPAGE' ,10);

?>
