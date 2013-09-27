<?php include 'views/header.php';?>
<link rel="stylesheet" href="css/extend.css" type="text/css" />
<!--内容开始-->
<div class="main">
<!--左侧菜单开始-->
		<?php include 'views/account/menu.php';?>
<!--左侧菜单结束-->
<!--右侧内容开始-->
		<div class="mainright" id="mainright">
		<!--右侧title开始-->	
			<div class="currtitle">
				<div class="placing"><span>当前位置：</span><span>账户中心</span> <span>&gt;</span> <span>个性化设定</span></span></div>
			</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
			<div class="edit_list">
			<div class="time_pro" >
				<p>
					<span class="time_pro_img"></span>
					<span>提示:配置修改后自动保存,个性化设定优先级别最高，您修改配置时请确保认真了解该配置项的含义，以免错失重要告警信息。需要了解更多告警配置，请参考<a href="index.php?c=alarm&a=notiset">告警通知设定</a>。</span>
				</p>
			</div>
			<!--one-->
				  <div class="rightcon_title">周末和晚上设定</div>
				  <div class="rightcon_mid">
				    <p>
						<label class="label14"></label>
						<input type="radio" class="radio1 receive_set" name="receive_set" value="defaults" <?php echo $persondata['oEmailSet']=='defaults'?"checked='checked'":"";?> />
						<label class="label_c1">默认随时接收邮件</label>
						<input type="radio" class="radio1 receive_set" name="receive_set" value="next" <?php echo $persondata['oEmailSet']=='next'?"checked='checked'":"";?>/>
						<label class="label_c1">转存到下次接收</label>
						<input type="radio" class="radio1 receive_set" name="receive_set" value="refuse" <?php echo $persondata['oEmailSet']=='refuse'?"checked='checked'":"";?>/>
						<label class="label_c1">完全不接此类收邮件</label>
					</p>
					<p class="light105">周末是指星期六星期天，周末转存到下次接收，将在周一统一发送。</p>
					<p class="light105">晚上指的是下午18点以后到第二天早上8点，转存到下次接收，将在第二天的8点进行发送。</p>
					<p class="clear"></p> 
				  </div>
				  <div class="rightcon_bottom"></div>
			<!--one-->
			<!--two-->
				  <div class="rightcon_title">接收指定类型的邮件</div>
				  <div class="rightcon_mid">
				    <p>
						<label class="label14"></label>
						<input type="checkbox" class="radio1 infotype" <?php echo strpos($persondata['oInfoType'],'1')!==false?"checked='checked'":'';?> value="1"/>
						<label class="label_c1">故障消息</label>
						<input type="checkbox" class="radio1 infotype" <?php echo strpos($persondata['oInfoType'],'2')!==false?"checked='checked'":'';?> value="2"/>
						<label class="label_c1">系统消息</label>
						<input type="checkbox" class="radio1 infotype" <?php echo strpos($persondata['oInfoType'],'3')!==false?"checked='checked'":'';?> value="3"/>
						<label class="label_c1">提醒消息</label>
						<input type="checkbox" class="radio1 infotype" <?php echo strpos($persondata['oInfoType'],'4')!==false?"checked='checked'":'';?> value="4"/>
						<label class="label_c1">恢复消息</label>
					</p>
					<p class="light105">故障消息：数据包丢失、连接超时、网页打开失败、数据库连接异常等，通常我们会重试一次后才发送告警通知。</p>
					<p class="light105">系统消息：服务异常，服务器SNMP获取数据失败是的系统消息，通常这种情况只需要运维人员及时接收即可。</p>
					<p class="light105">提醒消息：监控项目自定义指标的报警信息。</p>
					<p class="light105">恢复消息：服务器和监控项目恢复正常的提示消息，可以方便的了解到服务的处理情况和进度。</p>
					<p class="clear"></p> 
				  </div>
				  <div class="rightcon_bottom"></div>
			<!--two-->
			<!--three-->
				  <div class="rightcon_title">接收指定服务器告警邮件</div>
				  <div class="rightcon_mid">
					<label class="label14"><span class="red">*</span>服务器IP:</label>
					<div class="btn_green1 left20"><a id="server-search"><span class="spanL">查询服务器</span><span class="spanR"></span></a></div>
					<div class="height10"><input type="hidden" value="<?php echo $persondata['oAcceptIp'];?>" id="itemip"/></div>
					<label class="label14">已选择的服务器:</label>
					<div class="left105" id="show_resultip">
					<?php if(!empty($persondata['oAcceptIp'])){
							$iplist = explode(',',$persondata['oAcceptIp']);
							foreach ($iplist as $key){
						?>
						<div class="left width150">
							<label class="label_c1 li_server"><?php echo $key;?></label>
							<div class="window_ipclose server_close"> </div>
						</div>
					<?php }}?>
					</div>	
					<p class="light105">当您只对部分核心业务关注时，可以合理的设定您需要关注的服务器，只接收指定服务器的告警邮件即可，默认是所有服务器。</p>
					<p class="clear"></p> 
				  </div>
				  <div class="rightcon_bottom"></div>
			<!--three-->
			<!--four-->
				  <div class="rightcon_title">接收指定类型的报表</div>
				  <div class="rightcon_mid">
				    <p>
						<label class="label14"></label>
						<input type="checkbox" class="radio1 reportset" <?php echo strpos($persondata['oReportType'],'daily')!==false?"checked='checked'":'';?> value="daily"/>
						<label class="label_c1">日报表</label>
						<input type="checkbox" class="radio1 reportset" <?php echo strpos($persondata['oReportType'],'weekly')!==false?"checked='checked'":'';?> value="weekly"/>
						<label class="label_c1">周报表</label>
						<input type="checkbox" class="radio1 reportset" <?php echo strpos($persondata['oReportType'],'month')!==false?"checked='checked'":'';?> value="month"/>
						<label class="label_c1">月报表</label>
						<input type="checkbox" class="radio1 reportset" <?php echo strpos($persondata['oReportType'],'years')!==false?"checked='checked'":'';?> value="years"/>
						<label class="label_c1">年报表</label>
					</p>
					<p class="light105">您可以选择您所感兴趣的报表进行接收，方便您对运维综合信息了如指掌！</p>
					<p class="clear"></p> 
				  </div>
				  <div class="rightcon_bottom"></div>
			<!--four-->
			<!--five-->
				  <div class="rightcon_title">告警通知总开关</div>
				  <div class="rightcon_mid">
				    <p>
						<label class="label14"></label>
						<input type="checkbox" class="radio1 switchset" value="email" <?php echo strpos($persondata['oCloseType'],'email')!==false?"checked='checked'":'';?>/>
						<label class="label_c1">暂停所有邮件接收</label>
						<input type="checkbox" class="radio1 switchset" value="sms" <?php echo strpos($persondata['oCloseType'],'sms')!==false?"checked='checked'":'';?>/>
						<label class="label_c1">暂停所有短信接收</label>
						<input type="checkbox" class="radio1 switchset" value="msn" <?php echo strpos($persondata['oCloseType'],'msn')!==false?"checked='checked'":'';?>/>
						<label class="label_c1">暂停所有ＭＳＮ接收</label>
						<input type="checkbox" class="radio1 switchset" value="gtalk" <?php echo strpos($persondata['oCloseType'],'gtalk')!==false?"checked='checked'":'';?>/>
						<label class="label_c1">暂停所有Gtalk接收</label>
					</p>
					<p class="light105">当您更换邮箱或者账号变更时，可以通过暂停接收来缓解监控服务器压力。</p>
					<p class="clear"></p> 
				  </div>
				  <div class="rightcon_bottom"></div>
				  <div class="height10"></div>  
				<div class="btn_submit2">
					<div class="btn_green" id="personset-save"><a href="#"><span class="spanL">确认保存</span><span class="spanR"></span></a></div>
				</div>
			<!--five-->
			</div>	
			<div class="height10"></div>
			
		<!--右侧content结束-->
		</div>
<!--右侧内容结束-->
</div>

<script type="text/javascript" src="script/common/base.js"> </script>
<link rel="stylesheet" href="css/osa-tips.css" type="text/css" />
<link rel="stylesheet" href="css/osa-box.css" type="text/css" />
<script type="text/javascript" src="script/common/tips-base.js"></script>
<script type="text/javascript" src="script/common/osa-box.js"></script>
<script type="text/javascript" src="script/account/personset.js"></script>
<!--内容结束-->
<?php include 'views/footer.php';?>