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
						  <span class="font1">-磁盘空间报警</span>
					  </div>
				  </div>
				  <div class="clear"></div>
				   <!--one-->
				  <div class="rightcon_title">报警项目</div>
				  <div class="rightcon_mid">
					  <p class="pheight1"><label class="label6">*监控项目名称：</label><input type="text" class="style5" id="proname" value="<?php echo $alarminfo['oItemName'];?>"/><span class="tips"></span></p>
					  <p class="pheight1"><label class="label6">*当分区使用超过：</label><input type="text" class="style17" value="<?php echo $itemconfig['percentage'];?>" id="threshold"/>% 时发送通知消息!</p>
					  <p class="light1">硬盘空间超负荷是导致服务异常的最常见的原因。设置一个合理的报警阀值可在出现故障前提醒您定期清理！</p>
					  <p class="light1"><input type="hidden" value="<?php echo $alarminfo['id'];?>" id="hide_alarmid" /></p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--one-->
				  <!--two-->
				  <div class="rightcon_title">选择服务器</div>
				  <div class="rightcon_mid">
				      <p class="pheight" style="display:none;">
					      <label class="label6">服务器ip：</label><input type="text" class="style7 hui" readonly="true" id="serverip" value="<?php echo $alarminfo['oServerList'];?>" />
					  </p>
					  <p class="pheight">
					  	  <label class="label5">请点击按钮：</label>
					  	  <span class="link">
					  	  	<input type="button" id="showip" value="选择服务器" />
					  	  </span>
					  </p>
					  <!--搜索脚本库弹窗-->
					  <div class="selected" style="">
					      <div style="float:left;"><label class="label6">已选择的服务器：</label></div>
					      <div style="width:630px;float:left;" id="showselectip">
							<?php $serverlist = explode('|',$alarminfo['oServerList']);
							 	foreach ($serverlist as $key) { ?>
							 		<span class="left mr10"><label class="ip_tips"><?php echo $key;?></label><img src="images/erase.png" class="delselectip ml5 pointer"/></span>
							<?php } ?>
						  </div>
					  </div>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--two-->
				 <?php include 'views/panel/commonedit.php';?>
				  <p class="pheight specibut"><input type="button" class="enter" value="确定保存" id="diskalarmedit"/></p>
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
		  <div id="shadow"></div>
		  <!--搜索服务器弹出层-->
			<div class="window" id="searchip" style="display:none;">
			    <div class="window_title">
				    <span class="window_text">选择服务器</span>
					<input type="button" class="windbutton" />
				</div>
				<div class="window_con">
					 <p style="margin-left:80px;margin-top:-6px;">注：关键词可通过设备名,地区,IP来搜索</p>
					 <p>
					 	<label class="label5 font1">设备类型：</label>
					 	<a class="serverall pointer ml5" type="">全部</a>
					 	<?php foreach ($servertype as $key) {?>
					 		<a class="servertype pointer ml5" type="<?php echo $key['id'];?>"><?php echo $key['oTypeName'];?></a>
					 	<?php }?>
				 	</p>
					 <p><label class="label5 font1">关键词：</label><input type="text" class="style7" id="keyword"/><input type="button" class="updatebut " id="search_ip" value="查询" /></p>
					 <p>
					 	<label class="font1" style="float:left;margin-right:10px;">查询结果:</label>
					 	<span class="style8 pointer" id="ipall">全选 </span>
					 	<span class="style8 pointer" id="ipcancel">全不选</span>
				 	 </p>
					 <div class="clear"></div>
					 <div id="result_ip">
			
					 </div>
					 <div class="clear" id="ip_page" style="display:none;"><span class="pointer" id="lastpage">上一页</span><span style="float:right;" class="pointer" id="nextpage">下一页</span></div>
					 <div class="clear center" ><input type="button" class="updatebut specibut" id="selectipconfirm" value="确定" id="searchlayer" /><input type="button" class="updatebut specibut cancel" value="取消" /></div>
				</div>
			</div>
			<div class="window" id="searchusers" style="display:none;">
			    <div class="window_title">
				    <span class="window_text">选择用户</span>
					<input type="button" class="windbutton" />
				</div>
				<div class="window_con">
					 <p style="margin-left:80px;margin-top:-6px;">注：关键词可通过用户名,昵称来搜索</p>
					 <p><label class="label5">关键词：</label><input type="text" class="style7" id="keyword_user"/><input type="button" class="updatebut " id="search_user" value="查询" /></p>
					 <div class="clear"></div>
					 <br />
					 <p>
					 	<label class="font1" style="float:left;margin-right:10px;">查询结果:</label>
					 	<span class="style8 pointer" id="userall">全选 </span>
					 	<span class="style8 pointer" id="usercancel">全不选</span>
					 </p>
					 <div class="clear" id="result_user">

					 </div>
					 <div class="clear" id="user_page"><span class="pointer" id="user_last">上一页</span><span style="float:right;" class="pointer" id="user_next">下一页</span></div>
					 <div class="clear center"><input type="button" class="updatebut specibut" value="确定" id="userconfirm" /><input type="button" class="updatebut specibut cancel" value="取消" /></div>
				</div>
			</div>
		  <script type="text/javascript" src="script/common/comlayer.js"></script>
		  <script type="text/javascript" src="script/common/combitch.js"></script>
		  <script type="text/javascript" src="script/panel/diskalarm.js"></script>
<?php include 'views/footer.php';?>
