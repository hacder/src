<?php
@header('Content-type: text/html;charset=UTF-8');
/*
	osa安装程序的主程序。
*/
//判断配置文件是否存在，如果存在就跳转到登录页面，如果不存在检查配置文件示例是否存在，如果存在就开始进入安装程序，如果不存在就提上错误。

$BASE_PATH=trim(preg_replace("/ctrlphp.*/",' ',str_replace('\\', '/', dirname(__FILE__))));

if(is_file($BASE_PATH."ctrlphp/etc/osa_config.inc.php")){
	Header("Location:../index.php");
}else if(is_file($BASE_PATH."ctrlphp/etc/osa_config.inc.sample.php")){
	require_once $BASE_PATH.'ctrlphp/etc/osa_config.inc.sample.php';
}else{
	die( "osa_config.inc.sample.php文件丢失，请上传次文件到osa/ctrlphp/etc/目录");
}

#定义默认的路径和安装的路径
//$BASE_PATH=trim(preg_replace("/ctrlphp.*/",' ',$_SERVER['DOCUMENT_ROOT']));
$DEFAULT_PATH='/usr/local/osa/';


function inTableStructure($fileName,$db)
{
  if(!file_exists($fileName)){echo "找不到表结构文件,请确认你的软件包正确!";exit;}
  $fp=fopen($fileName,"r");

  $sqlText=fread($fp,filesize($fileName));
  //$sqlText=str_replace("r","",$sqlText);
  //$sqlText=str_replace("n","",$sqlText);
  $db->query("SET NAMES utf8");
  $sqlArray=explode(';',$sqlText);
	try
	{
		$count=count($sqlArray);
		for($i=1;$i<$count;$i++)
		{
			$sql=$sqlArray[$i-1].";";
			$result=$db->exec($sql);
		}
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
		return false;
	}
} 

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

//开始安装的准备，分0,1,2,3步骤。默认进入第一步。
if (isset($_GET['step']))
    $step = $_GET['step'];
else
    $step = 0;

switch($step) {
//第0步，版权声明
    case 0:
	require $BASE_PATH.'ctrlphp/install/osa_template/wizard.html';
	break;
//第1步，检查安装环境是否支持
	case 1:
	require $BASE_PATH.'ctrlphp/install/osa_check.php';	
	require $BASE_PATH.'ctrlphp/install/osa_template/first.html';
	break;
//第二步，收集数据库信息
	case 2:
	if(isset($_POST['submit'])){
			$dbname = trim($_POST['dbname']);
			$uname	= trim($_POST['uname']);
			$passwd	= trim($_POST['pwd']);
			$dbhost = trim($_POST['dbhost']);
			$dbport = trim($_POST['dbport']);	
			$back=0;
			$osaInstallPdo="mysql:dbname=$dbname;host=$dbhost;port=$dbport"; 
		try {
		    $db = new PDO($osaInstallPdo,$uname,$passwd);
		} catch (PDOException $e) {
		    $errmessage= 'Connection failed: ' . $e->getMessage();
			$back=1;
		}
		if($back==0){
			$sample_config=file_get_contents($BASE_PATH.'ctrlphp/etc/osa_config.inc.sample.php');
			$system_key = sha1(date('YmdHis'));
			$new_config=str_replace('mysql:dbname=openwebsa;host=127.0.0.1;port=3306',$osaInstallPdo,$sample_config);
			$new_config=str_replace('openwebsa_conn_user',$uname,$new_config);
			$new_config=str_replace('openwebsa_conn_pw',$passwd,$new_config);
			$new_config=str_replace('/usr/local/osa/',$BASE_PATH,$new_config);
			$new_config=str_replace('1234567890',$system_key,$new_config);	
			file_put_contents($BASE_PATH.'ctrlphp/etc/osa_config.inc.temp.php',$new_config);
			
			$auth_key = osa_randomkey();
			$pyctrl_config=file_get_contents($BASE_PATH . 'ctrlpy/etc/config.py');
			$new_pyctrl=str_replace('127.0.0.1',$dbhost,$pyctrl_config);
			$new_pyctrl=str_replace('openwebsa_conn_user',$uname,$new_pyctrl);
			$new_pyctrl=str_replace('openwebsa_conn_pw',$passwd,$new_pyctrl);
			$new_pyctrl=str_replace('openwebsa_conn_db',$dbname,$new_pyctrl);
			$new_pyctrl=str_replace('openwebsa_conn_port',$dbport,$new_pyctrl);
			//add by jf
			$new_pyctrl=str_replace('_ids=lDEFABCNOPydsfdfdsT-UwxkVWXYZabcdef+IJK6/7nopqr89LMmGH012345uv',$auth_key,$new_pyctrl);
			file_put_contents($BASE_PATH . 'ctrlpy/etc/config.py',$new_pyctrl);
			
			//add by jf
			$unpyctrl_config=file_get_contents($BASE_PATH . 'unctrlpy/etc/config.py');
			$new_unpyctrl=str_replace('_ids=lDEFABCNOPydsfdfdsT-UwxkVWXYZabcdef+IJK6/7nopqr89LMmGH012345uv',$auth_key,$unpyctrl_config);
			file_put_contents($BASE_PATH . 'unctrlpy/etc/config.py',$new_unpyctrl);
			
			$fileName=$BASE_PATH . 'ctrlphp/install/osa.sql';
			inTableStructure($fileName,$db);
			$url = "index.php?step=3";
			echo "<script language='javascript' type='text/javascript'>";
			echo "window.location.href='$url'";
			echo "</script>"; 
		}
	}
	require $BASE_PATH.'ctrlphp/install/osa_template/secend.html';
	break;
//第三步，完成安装
	case 3:
	$params = array('key'=>$system_key,'type'=>'install');
	osa_restaction('POST',$params,OSA_WEBSERVER_DOMAIN.'/interface.php');	
	$targe_file=array('ctrlpy/etc/config.py','unctrlpy/etc/config.py');
	foreach($targe_file as $value){
		$file=$BASE_PATH . $value;
		file_put_contents($file,str_replace($DEFAULT_PATH,$BASE_PATH,file_get_contents($file))); 
	}
	rename($BASE_PATH."ctrlphp/etc/osa_config.inc.temp.php",$BASE_PATH."ctrlphp/etc/osa_config.inc.php");
	require $BASE_PATH.'ctrlphp/install/osa_template/third.html';
	break;
}
?>
