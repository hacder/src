<?php include 'views/header.php';?>
<link rel="stylesheet" href="css/extend.css" type="text/css" />
<!--内容开始-->
<div class="main">
<!--左侧菜单开始-->
		<?php include "views/alarm/menu.php";?>
<!--左侧菜单结束-->
<!--右侧内容开始-->
		<div class="mainright" id="mainright">
		<!--右侧title开始-->	
			<div class="currtitle">
				<div class="placing"><span>当前位置：</span><span>日常监控</span> <span>&gt;</span> <span>告警通知设定</span></span></div>
			</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
		<div class="edit_list">
			<!--one-->
				<div class="rightcon_title">通知方式</div>
				<div class="rightcon_mid">
					<label class="label1">&nbsp;</label>
					<div class="light0">
						<input type="checkbox" class="radio1 isemail" checked="checked" disabled="disabled"/>
						<label class="label_c1">Email告警</label>
<!--						<input type="checkbox" class="radio1 issms" <?php //echo $notiset[0]['oIsSms']=='1'?"checked='checked'":"";?> />-->
<!--						<label class="label_c1">短信告警</label>-->
<!--						<input type="checkbox" class="radio1 ismsn" <?php //echo $notiset[0]['oIsMsn']=='1'?"checked='checked'":"";?> />-->
<!--						<label class="label_c1">MSN告警</label>-->
<!--						<input type="checkbox" class="radio1 isgtalk" <?php //echo $notiset[0]['oIsGtalk']=='1'?"checked='checked'":"";?> />-->
<!--						<label class="label_c1">Gtalk告警</label>-->
					</div>
					<p >
						<label class="label1">&nbsp;</label>
						<label class="light0 left">通知方式可以同时选择多种,使用Email，MSN,Gtalk等方式需要您进行相应配置,只有特定信息通过短信发送,比如服务器宕机,重要故障消息,系统初始赠送短信10条。</label>
					</p>
					<div class="clear"></div> 	
				</div>
				<div class="rightcon_bottom"></div>
			<!--one-->
			<!--two-->
				<div class="rightcon_title">每天告警次数上限</div>
				<div class="rightcon_mid">
					<label class="label1">&nbsp;</label>
					<div class="left">
						<span>每监控项目每天最多发送告警次数：</span>
						<input type="text" class="style3" value="<?php echo $notiset[0]['oMnumItem'];?>" id="mnumitem"/>
						<span>次,合理设定告警发送上限,防止告警通知过多。</span>
					</div>
					<div class="clear"></div>
					<label class="label1">&nbsp;</label>
					<div class="light0">每项目指的是单个监控项目，每天最多发送告警的总次数，包含恢复通知，系统通知，故障通知。</div>						
					<div class="height10"></div>
					<label class="label1">&nbsp;</label>
					<div class="left">
						<span>服务器不可达每天最多告警次数：</span>
						<input type="text" class="style3" value="<?php echo $notiset[0]['oMnumIp'];?>" id="mnumip"/>
						<span>次,合理设定告警发送上限,防止告警通知过多。</span>
					</div>
					<div class="clear"></div>
					<label class="label1">&nbsp;</label>
					<div class="light0">服务器不可达指的指的是服务器ping不通，这里的设定针对每一台服务器每天针对服务器不可达最多告警次数。</div>						
					<div class="height10"></div>
					<div class="clear"></div> 					
				</div>
				<div class="rightcon_bottom"></div>
			<!--two-->
				<div style="display:none;">
			<!--three-->
				<div id="sms_set" style="display:<?php echo $notiset[0]['oIsSms']=='1'?'block':'none';?>">
					<div class="rightcon_title"><span>指定短信接收人</span><span class="right10 txt_green">只有在下面选择的用户才能接收到手机短信。</span></div>
					<div class="rightcon_mid">
						<label class="label1">通知对像：</label>
						<div class="left">
							<input type="radio" class="radio1 notiset_sms" name="notiset_sms" value="0" <?php echo $smsset[0]['oNoticeUsers']=='ALL'?"checked='checked'":"";?>/>
							<label class="label_c1">任何时间都通知所有人</label>
							<input type="radio" class="radio1 notiset_sms" name="notiset_sms" value="1" <?php echo $smsset[0]['oNoticeUsers']!='ALL'?"checked='checked'":"";?>/>
							<label class="label_c1">任何时间都只通知指定用户</label>
						</div>
						<div class="height10"></div>
						<div style="display:<?php echo $smsset[0]['oNoticeUsers']!='ALL'?'block':'none';?>;" id="seleuser">
							<label class="label1">用户：<input type="hidden" class="style5" value="<?php echo $smsset[0]['oNoticeUsers']!='ALL'?$smsset[0]['oNoticeUsers']:'';?>" id="users"></label>
							<div class="btn_green1 left">
								<a id="user-select"><span class="spanL">选择用户</span><span class="spanR"></span></a>
							</div>
							<p class="clear"></p> 
							<label class="label1">已选择的用户：</label>
							<div class="left125" id="show_resultuser">
								<?php if(!empty($smsset[0]['oNoticeUsers'])&&$smsset[0]['oNoticeUsers']!='ALL'){
								$users = explode(',',$smsset[0]['oNoticeUsers']);
								foreach ($users as $key){
								?>
								<div class="left width150">
									<label class="label_c1 li_users"><?php echo $key;?></label>
									<div class="window_ipclose user_close"> </div>
								</div>						
							<?php }}?>	
							</div>
							<p class="clear"></p> 
						</div>	
						<div class="clear"></div> 	
					</div>
					<div class="rightcon_bottom"></div>
				</div>
			<!--three-->
			<!--four-->
				<div id="msn_set" style="display:<?php echo $notiset[0]['oIsMsn']=='1'?'block':'none';?>">
					<div class="rightcon_title">MSN告警设定</div>
					<div class="rightcon_mid">
						<label class="label1">接收信息的MSN账号：</label>
						<div class="left">
							<input type="text" class="style5" value="<?php echo $msnset[0]['oNoticeMsn']?>" id="notimsn" />
							<label class="light0 left10">多个账号使用逗号分隔!</label>
						</div>
						<div class="clear"></div> 					
					</div>
					<div class="rightcon_bottom"></div>
				</div>
			<!--four-->
			<!--five-->
				<div id="gtalk_set" style="display:<?php echo $notiset[0]['oIsGtalk']=='1'?'block':'none';?>">
					<div class="rightcon_title">Gtalk告警设定</div>
					<div class="rightcon_mid">
						<label class="label1">接收信息的Gtalk账号：</label>
						<div class="left">
							<input type="text" class="style5" value="<?php echo $gtalkset[0]['oNoticeGtalk'];?>" id="notigtalk" />
							<label class="light0 left10">多个账号使用逗号分隔!</label>
						</div>
						<div class="clear"></div> 
					</div>
					<div class="rightcon_bottom"></div>
				</div>
				</div >
			<!--five-->
			<!--six-->
				<div id="email_set">
					<div class="rightcon_title">Email告警设定</div>
					<div class="rightcon_mid">
						<p>
							<label class="label1"><span class="red">*</span>SMTP服务器：</label>
							<input type="text" class="style5" value="<?php echo $emailset[0]['oServerHost'];?>" class="left" id="servername" />
							<span class="tips left" style="margin-left:6px;"></span>
						</p>
						<p>
							<label class="label1">&nbsp;</label>
							<label class="light0 left">输入SMTP服务器的主机名或IP，如：smtp.163.com。</label>
						</p>
						<p>
							<label class="label1"><span class="red">*</span>SMTP端口：</label>
							<input type="text" class="style5" value="<?php echo $emailset[0]['oServerPort'];?>" class="left" id="serverport" />
							<span class="tips left" style="margin-left:6px;"></span>
						</p>
						<p>
							<label class="label1">&nbsp;</label>
							<label class="light0 left">SMTP服务器的端口，默认：25。</label>
						</p>
						<p >
							<label class="label1"><span class="red">*</span>SMTP用户名：</label>
							<input type="text" class="style5" value="<?php echo $emailset[0]['oServerName'];?>" class="left" id="serveruser" />
							<span class="tips left" style="margin-left:6px;"></span>
						</p>
						<p >
							<label class="label1">&nbsp;</label>
							<label class="light0 left">登录到SMTP服务器的用户名。</label>
						</p>
						<p >
							<label class="label1"><span class="red">*</span>SMTP密码：</label>
							<input type="password" class="style5" value="<?php echo $emailset[0]['oServerPass'];?>" class="left" id="serverpass" />
							<span class="tips left" style="margin-left:6px;"></span>
						</p>
						<p >
							<label class="label1">&nbsp;</label>
							<label class="light0 left">登录到SMTP服务器的用户名对应的密码。</label>
						</p>
						<p >
							<label class="label1"><span class="red">*</span>发送人地址：</label>
							<input type="text" class="style5" value="<?php echo $emailset[0]['oSendAddress'];?>" class="left" id="sendaddress" />
							<span class="tips left" style="margin-left:6px;"></span>
						</p>
						<p style="padding-top:10px;">
							<label class="label1"><span class="red">*</span>发送人名称：</label>
							<input type="text" class="style5" value="<?php echo $emailset[0]['oSendName'];?>" class="left" id="sendname" />
							<span class="tips left" style="margin-left:6px;"></span>
						</p>
						<p style="padding-top:10px;">
							<label class="label1"><span class="red">*</span>测试邮件接收地址：</label>
							<input type="text" class="style5" value="<?php echo $emailset[0]['oReceiveAddress'];?>" class="left" id="receiveadd" />
							<span class="tips left" style="margin-left:6px;"></span>
						</p>
						<p >
							<label class="label1">&nbsp;</label>
							<label class="light0 left">用于接收测试邮件</label>
						</p>
						<p>
							<label class="label1">&nbsp;</label>
							<span class="btn_green1 left">
								<a id="testemail"><span class="spanL">发送测试邮件</span><span class="spanR"></span></a>
							</span>
						</p>
						<p style="padding-top:10px;">
							<label class="label1">&nbsp;</label>
							<label class="light0 left">您PHP环境需要支持 fsockopen函数才能使用《发送测试邮件》功能。</label>
						</p>
						<div class="clear"></div> 				
					</div>
					<div class="rightcon_bottom"></div>
				</div>
				<div class="height10"></div>
				<div class="edit_submit">
					<div class="btn_green" style=""><a id="notiset-save"><span class="spanL">保存告警设置</span><span class="spanR"></span></a></div>
				</div>
			<!--six-->			
		</div>
		<div class="height10"></div>

		<!--右侧content结束-->
		</div>
<!--右侧内容结束-->
</div>
<!--内容结束-->
<!--内容结束-->
<script type="text/javascript" src="script/common/base.js"> </script>
<link rel="stylesheet" href="css/osa-tips.css" type="text/css" />
<link rel="stylesheet" href="css/osa-box.css" type="text/css" />
<script type="text/javascript" src="script/common/tips-base.js"></script>
<script type="text/javascript" src="script/common/osa-box.js"></script>
<script type="text/javascript" src="script/alarm/notiset.js"> </script>
<?php include 'views/footer.php';?>

