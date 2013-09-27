<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="zh-CN" xml:lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>OSA监控精灵</title>
<link href="favicon.ico" type="image/x-icon" rel=icon />
<link rel="stylesheet" href="css/osa.css" type="text/css" />
<script src="script/jquery.min.js" type="text/javascript"></script>
</head>
<body>
<!--头部开始-->
<div class="userbar">
	<div class="container">
		<div class="welcome">您好，<span><?php echo $_SESSION['username'];?></span>欢迎来到OSA开源监控管理平台</div>
		<div class="user_info">
			<ul>
				<li class="creat_moniter"><a href="index.php?c=monitor&a=itemlist"><i class="icon_add_arrow"></i><span>创建监控项目</span></a></li>
				<li class="alarm" >
					<a href="index.php?c=alarm&a=itemalarmed"><span>告警通知</span></a>
					<div class="alarm_notice" id="alarm-content-notice" style="display:none;">
						<a class="alarm_content" href="index.php?c=alarm&a=itemalarmed"><span>您有<i id="ialarm-content-num">2</i>条未读消息，请查看！</span></a>
						<a class="close" id="alarm-content-close"><span>X</span></a>
					</div>
				</li>
				<li class="quit"><a href="index.php?c=login&a=loginout"><span>退出</span></a></li>
			</ul>
		</div>
	</div>
</div>
<div class="navibar">
	<div class="logo">OSA监控精灵</div>
	<div class="nav">
		<a href="index.php?c=home&a=index" class="<?php echo $_SESSION['header']=='home'?'curr':'';?>"><span>首页</span></a>
		<a href="index.php?c=device&a=listindex" class="device <?php echo $_SESSION['header']=='device'?'curr':'';?>"><span>服务器管理</span></a>
		<a href="index.php?c=monitor&a=monitorlist" class="monitor <?php echo $_SESSION['header']=='monitor'?'curr':'';?>"><span>日常监控</span></a>
		<a href="index.php?c=alarm&a=itemalarmed" class="alarm <?php echo $_SESSION['header']=='alarm'?'curr':'';?>"><span>告警中心</span></a>
		<a href="index.php?c=account&a=userlists" class="account <?php echo $_SESSION['header']=='account'?'curr':'';?>"><span>帐户管理</span></a>                     
		<!-- 
		<a href="index.php?c=filecenter&a=filelist" class="filecenter <?php echo $_SESSION['header']=='filecenter'?'curr':'';?>"><span>文件管理</span></a>  
		-->
	</div>
</div>
<div class="hr"></div>
<!--头部结束-->