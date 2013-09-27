<?php

$get = $post = '';
//过滤$_GET
foreach ($_GET as $get_key=>$get_var)
{   
	if (is_numeric($get_var)) {
		$get[strtolower($get_key)] = get_int($get_var);
	} else {
		$get[strtolower($get_key)] = get_str($get_var);
	}
}
$_GET = $get;

//过滤$_POST
foreach ($_POST as $post_key=>$post_var)
{
	if (is_numeric($post_var)) {
		$post[$post_key] = get_int($post_var);
	} else if(is_string($post_var)){
		$post[$post_key] = get_str($post_var);
	}else if(is_array($post_var)){
		$post[$post_key] = $post_var;
	}
}
//$_POST = ($_GET['menu'] == 'serverconfig') ? $_POST : $post;
$_POST = $post ;

function get_int($number)
{
    //return intval($number);
    return $number;
}

function get_str($string)
{    
	
	$string = addslashes($string);
	
	$string = htmldecode($string);
	
	return htmlspecialchars($string);
}

function htmldecode($str)
{
	if(empty($str)) return;
	if($str=="") return $str;	
	$str=str_replace("&",chr(34),$str);
	$str=str_replace(">",">",$str);
	$str=str_replace("<","<",$str);
	$str=str_replace("&","&",$str);
	$str=str_replace(" ",chr(32),$str);
	$str=str_replace(" ",chr(9),$str);
	$str=str_replace("'",chr(39),$str);
	$str=str_replace("<br />",chr(13),$str);
	$str=str_replace("''","'",$str);
	$str=str_replace("select","\"_select\"",$str);
	$str=str_replace("join","\"_join\"",$str);
	$str=str_replace("union","\"_union\"",$str);
	$str=str_replace("where","\"_where\"",$str);
	$str=str_replace("insert","\"_insert\"",$str);
	$str=str_replace("delete","\"_delete\"",$str);
	$str=str_replace("update","\"_update\"",$str);
	$str=str_replace("like","\"_like\"",$str);
	$str=str_replace("drop","\"_drop\"",$str);
	$str=str_replace("create","\"_create\"",$str);
	$str=str_replace("modify","\"_modify\"",$str);
	$str=str_replace("rename","\"_rename\"",$str);
	$str=str_replace("alter","\"_alter\"",$str);
	$str=str_replace("cast","\"_cast\"",$str);
	$str=str_replace("and","\"_and\"",$str);
	$farr = array( 
	"/\s+/" , //过滤多余的空白 
	"/<(\/?)(img|script|i?frame|style|html|body|title|link|meta|\?|\%)([^>]*?)>/isU" , //过滤 <script 防止引入恶意内容或恶意代码,如果不需要插入flash等,还可以加入<object的过滤 
	"/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU" , //过滤javascript的on事件 
	); 
	$tarr = array( 
	" " , 
	"<\\1\\2\\3>" , //如果要直接清除不安全的标签，这里可以留空 
	"\\1\\2" , 
	); 
	$str = preg_replace ( $farr , $tarr , $str ); 
	$str=htmlspecialchars($str);
	return $str;
} 
?>