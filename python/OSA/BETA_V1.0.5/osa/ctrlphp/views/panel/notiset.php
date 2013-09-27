	<?php include 'views/header.php';?>
		  <!--content开始-->
		  <div class="content">
		      <!--左边开始-->
			  <?php include 'views/panel/left.php';?>
			  <!--左边结束-->
			  <!--右边开始-->
			  <div class="main_right">
			      <div class="linkmlist">
				      <div class="home_icom">
					      <span>当前位置：</span>
						  <span><a href="#">监控配置</a></span>
						  <span class="font1">-通知方式设置</span>
					  </div>
				  </div>
				  <div class="clear"></div>
				  <!--one-->
				  <div class="rightcon_title">通知方式</div>
				  <div class="rightcon_mid">
				      <p class="pheight1">
					      <label class="label6">*SMTP服务器：</label><input type="text" class="style5" id="servername" value="<?php echo $notinfo[0]['oServerHost'];?>"/><span class="tips"></span>
					  </p>
					  <p class="pheight light1">输入SMTP服务器的主机名或IP，如:smtp.163.com。</p>
				      <p class="pheight1">
					      <label class="label6">*SMTP端口：</label><input type="text" class="style5" id="serverport" value="<?php echo empty($notinfo[0]['oServerPort'])?25:$notinfo[0]['oServerPort'];?>"/><span class="tips"></span>
					  </p>
					  <p class="pheight light1">SMTP服务器的端口，默认：25。</p>
					  <p class="pheight1">
					      <label class="label6">*SMTP用户名：</label><input type="text" class="style5" id="serveruser" value="<?php echo $notinfo[0]['oServerName'];?>"/><span class="tips"></span>
					  </p>
					  <p class="pheight light1">登录到SMTP服务器的用户名。</p>
				      <p class="pheight1">
					      <label class="label6">*SMTP密码：</label><input type="password" class="style5" id="serverpass" value="<?php echo $notinfo[0]['oServerPass'];?>"/><span class="tips"></span>
					  </p>
					  <p class="pheight light1">登录到SMTP服务器的用户名对应的密码。</p>
				      <p class="pheight">
					      <label class="label6">*发件人地址：</label><input type="text" class="style5" id="sendaddress" value="<?php echo $notinfo[0]['oSendAddress'];?>"/><span class="tips"></span>
					  </p>
				      <p class="pheight">
					      <label class="label6">*发件人名称：</label><input type="text" class="style5" id="sendname" value="<?php echo $notinfo[0]['oSendName'];?>"/><span class="tips"></span>
					  </p>
				      <p class="pheight1">
					      <label class="label6">*测试邮件接收地址：</label><input type="text" class="style5" id="receiveadd" value="<?php echo $notinfo[0]['oReceiveAddress'];?>"/><span class="tips"></span>
					  </p>
					  <p class="pheight light1">用于接收测试邮件。</p>
					  <p class="pheight light1" style="_margin-left:60px;"><input type="button" class="delete" value="发送测试邮件" id="testemail"/></p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--one-->
				  <p class="pheight specibut"><input type="button" class="enter" value="确定保存" id="smtpsave"/></p>
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
		  <script type="text/javascript" src="script/panel/notiset.js"> </script>
<?php include 'views/footer.php';?>
