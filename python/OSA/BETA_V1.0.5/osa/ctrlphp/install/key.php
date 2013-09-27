<?php
/**
 * 生成python通讯密钥
 */
$BASE_PATH = '/usr/local/osa';
require_once $BASE_PATH.'/ctrlphp/etc/osa_config.inc.php';

function osa_randomkey(){
	$str = "1,2,3,4,5,6,7,8,9,a,b,c,d,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z";
	$list = explode(",", $str);
	$cmax = count($list) - 1;
	$key = '';
	for ( $i=0; $i < 10; $i++ ){
	    $randnum = mt_rand(0, $cmax);
	    $key .= $list[$randnum]; 
	}
	$key .=sha1(date('YmdHis'));
	return base64_encode($key);
}

/**
 * 接口调用方法
 */
function osa_restaction($method ,$params ,$url){
	$ch = curl_init();
	$timeout = 5;
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	switch($method){
		case 'DELETE': 
            curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params)); 
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, self::DELETE); 
            break; 
        case 'POST': 
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_POST, true); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
            break; 
        case 'GET': 
            curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params)); 
            break; 
	}
	$file_contents = curl_exec($ch);//获得返回值
	curl_close($ch);
	return $file_contents;
}


//生成密钥
$auth_key = osa_randomkey();
if(file_exists($BASE_PATH . '/ctrlphp/etc/osa_config.inc.php')){
//修改初始话文件 -- 生成安装的唯一密钥标识 ，通过接口记录这个密钥
$sample_config=file_get_contents($BASE_PATH.'/ctrlphp/etc/osa_config.inc.php');
$system_key = sha1(date('YmdHis'));
$new_config=str_replace('1234567890',$system_key,$sample_config);	

if(is_writable($BASE_PATH.'/ctrlphp/etc/osa_config.inc.php')){
	file_put_contents($BASE_PATH.'/ctrlphp/etc/osa_config.inc.php',$new_config);
}
$params = array('key'=>OSA_SYSTEM_KEY,'type'=>'install');
osa_restaction('POST',$params,OSA_WEBSERVER_DOMAIN.'/interface.php');
}

if(file_exists($BASE_PATH . '/ctrlpy/etc/config.py')){
//修改python 服务端密钥
$pyctrl_config=file_get_contents($BASE_PATH . '/ctrlpy/etc/config.py');
$new_pyctrl=str_replace('_ids=lDEFABCNOPydsfdfdsT-UwxkVWXYZabcdef+IJK6/7nopqr89LMmGH012345uv',$auth_key,$pyctrl_config);

if(is_writable($BASE_PATH . '/ctrlpy/etc/config.py')){
	file_put_contents($BASE_PATH . '/ctrlpy/etc/config.py',$new_pyctrl);
}
}

if(file_exists($BASE_PATH . '/unctrlpy/etc/config.py')){
//修改python 客户端密钥
$unpyctrl_config=file_get_contents($BASE_PATH . '/unctrlpy/etc/config.py');
$new_unpyctrl=str_replace('_ids=lDEFABCNOPydsfdfdsT-UwxkVWXYZabcdef+IJK6/7nopqr89LMmGH012345uv',$auth_key,$unpyctrl_config);

if(is_writable($BASE_PATH . '/unctrlpy/etc/config.py')){
	file_put_contents($BASE_PATH . '/unctrlpy/etc/config.py',$new_unpyctrl);
}
}
