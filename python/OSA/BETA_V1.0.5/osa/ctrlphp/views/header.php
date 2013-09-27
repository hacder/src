<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>OSA-运维管理平台</title>
<link rel="stylesheet" href="css/css.css" type="text/css" />
<script type="text/javascript" src="script/jquery.min.js"></script>
<script type="text/javascript">
var menu = '.'+'<?php echo $menu;?>';
var left = '.'+'<?php echo $left;?>';
$(document).ready(function(){
	$(menu).addClass('on');
	if(left !='.'){
		$(left).addClass('on');
	}
});
</script>
</head>
<body>
  <div id="body">
      <div id="box">
	      <!--头部开始-->
	      <div class="header">
		      <div class="header_logo"></div>
			  <div class="header_right">
			      <div class="header_login">
				      <ul>
					      <li>您好,<a href="#"><?php echo $_SESSION['username'];?></a>&nbsp;欢迎来到OSA开源管理平台</li>
						  <li><a href="index.php?c=personcenter&a=index">告警通知</a></li>
						  <li><a href="index.php?c=personcenter&a=aboutme">个人中心</a></li>
						  <li><a href="index.php?c=login&a=logout" style="color:red;">退出</a></li>
					  </ul>
				  </div>
				  <div class="header_nav">
				      <ul>
					      <li><a href="index.php" class="menu home">首页</a></li>
						  <li><a href="index.php?c=device&a=index" class="menu device">设备管理</a></li>
						  <li><a href="index.php?c=maintain&a=serverlist" class="menu maintain">日常运维</a></li>
						  <li><a href="index.php?c=operate&a=graphcenter" class="menu operate">运营分析</a></li>
						  <li><a href="index.php?c=account&a=userlist" class="menu account">账户管理</a></li>
						  <li><a href="index.php?c=panel&a=monitorlist" class="menu panel">监控配置</a></li>
						  <li><a href="index.php?c=personcenter&a=index" class="menu personcenter">个人中心</a></li>
						  <li><a href="http://bbs.osapub.com/forum.php" target="_blank" class="menu">支持与服务</a></li>
					  </ul>
				  </div>
			  </div>
		  </div>
		  <!--头部结束-->
		  
