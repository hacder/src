<?php

/**
 * osa rose
 */

function osa_rose_check($rosearray,$ndb){
	$sql = "select oUserName,oPerStr from osa_users a left join osa_roles b on(a.oRoleid = b.id) where oUserName='".$_SESSION['username']."'";
	$rs = $ndb->select($sql);
	if($rs[0]){
		
	$rosestr = $rs[0]['oPerStr'];
	$slink = 'c='.$_GET['c'].'&a='.$_GET['a'] ;
	$tag = 0;
	foreach($rosearray as $k => $v){
		$link = $v['link'];
		if(strpos('__'.$link,$slink)){
			if(strpos('__'.$rosestr,strval($k))){				
				return true;
			}
			return false;
		}
	}
	
	return true;		
	}
	return true;
}

