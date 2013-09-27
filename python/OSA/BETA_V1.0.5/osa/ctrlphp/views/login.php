<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>OSA登录页面</title>
<link href="css/login.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="script/jquery.min.js"></script>
<script type="text/javascript" src="script/login.js"></script>
</head>
<body>
  <div id="login">
       <div class="login_top">
       <img src="images/logo_03.gif" />
       <p><a href="" onclick="this.style.behavior='url(#default#homepage)';this.setHomePage(window.location.href);">设为首页</a> | <a href="" id="collect">收藏</a> | <a href="#">帮助</a></p>
    </div>
       <div  class="login_box">
           <div class="login_con">
               <p class="lab"><b>管理登录</b></p>
               <p class="lab">
               		<span class="text_bg">
               			<label for="username" id="nameprev">邮箱/手机号/用户名</label>
               			<input name="1" type="text" value="<?php echo $_COOKIE['username'];?>"  id="username" />
               		</span>
               </p>
               <p class="lab">
               		<span class="text_bg">
	               		<label for="password" id="pwdprev">请输入你的密码</label>
	               		<input name="2" type="password" value=""  id="password" />
               		</span>
               </p>
               <p class="lab">
               		<span class="s1"><input name="3" type="button" value="" class="login_b pointer" id="login_submit"/></span>
               		<span class="s2"><input type="checkbox" value="1" id="remember" <?php echo $_COOKIE['remember'] == 1?"checked='checked'":""?>/>记住用户名</span>
               	</p>
               <p class="lab_t" id="msg_show">*欢迎来到OSA管理后台！</p>
           </div>
           <div class="clearfloat"></div>
           <p class="login_last">版权所有：OSA开源团队 | 技术支持：OSA开源团队 群号：22250846(技术支持)，39947756(linux爱好者群)<br />
               特别感谢：厦门书生天下软件开发有限公司 对开源工作的大力支持！</p>
       </div>
  </div>
  <div style="clear:both;"></div>
</body>
</html>
