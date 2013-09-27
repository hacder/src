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
				<div class="placing"><span>当前位置：</span><span>账户中心</span> <span>&gt;</span> <span>快捷功能设置</span></span></div>
			</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
			<div class="edit_list">
			<div class="time_pro" style="display:none;">
				<p>
					<span class="time_pro_img"></span>
					<span>快捷功能设置成功。</span>
				</p>
			</div>
			<!--one-->
				<div class="rightcon_title">请勾选以下菜单，勾选后将显示在首页左侧的快捷菜单列表。</div>
				<div class="rightcon_mid" style="overflow:hidden;">
					<div class="rightcon3">
				      <p class="pheight power_title">日常监控</p>
					  <p class="pheight_c4">
						<span class="style_c4"><input type="checkbox" class="input_c4" value="01" <?php echo osa_checkstr($shortcut,01)?"checked='checked'":"";?>/>服务器列表</span>
						<span class="style_c4"><input type="checkbox" class="input_c4" value="02" <?php echo osa_checkstr($shortcut,02)?"checked='checked'":"";?>/>创建监控项目</span>
						<span class="style_c4"><input type="checkbox" class="input_c4" value="03" <?php echo osa_checkstr($shortcut,03)?"checked='checked'":"";?>/>监控项目列表</span>
						<span class="style_c4"><input type="checkbox" class="input_c4" value="04" <?php echo osa_checkstr($shortcut,04)?"checked='checked'":"";?>/>已发送告警通知</span>
						<span class="style_c4"><input type="checkbox" class="input_c4" value="05" <?php echo osa_checkstr($shortcut,05)?"checked='checked'":"";?>/>告警通知设定</span>
					 </p>
					</div>
					
					<div class="rightcon3">
				      <p class="pheight power_title">图形报表</p>
					  <p class="pheight_c4">
						  <span class="style_c4"><input type="checkbox" class="input_c4" value="11" <?php echo osa_checkstr($shortcut,11)?"checked='checked'":"";?>/>图形分析中心</span>
						  <span class="style_c4"><input type="checkbox" class="input_c4" value="12" <?php echo osa_checkstr($shortcut,12)?"checked='checked'":"";?>/>响应时间报表</span>
						  <span class="style_c4"><input type="checkbox" class="input_c4" value="13" <?php echo osa_checkstr($shortcut,13)?"checked='checked'":"";?>/>可用率报告</span>
						  <span class="style_c4"><input type="checkbox" class="input_c4" value="14" <?php echo osa_checkstr($shortcut,14)?"checked='checked'":"";?>/>故障综合报表</span>
						  <span class="style_c4"><input type="checkbox" class="input_c4" value="15" <?php echo osa_checkstr($shortcut,15)?"checked='checked'":"";?>/>自定义视图</span>
					  </p>
					</div>
					
					<div class="rightcon3">
				      <p class="pheight power_title">账户中心</p>
					  <p class="pheight_c4">
						  <span class="style_c4"><input type="checkbox" class="input_c4" value="21" <?php echo osa_checkstr($shortcut,21)?"checked='checked'":"";?>/>告警通知</span>
						  <span class="style_c4"><input type="checkbox" class="input_c4" value="22" <?php echo osa_checkstr($shortcut,22)?"checked='checked'":"";?>/>修改个人密码</span>
						  <span class="style_c4"><input type="checkbox" class="input_c4" value="23" <?php echo osa_checkstr($shortcut,23)?"checked='checked'":"";?>/>个性化设定</span>
						  <span class="style_c4"><input type="checkbox" class="input_c4" value="24" <?php echo osa_checkstr($shortcut,24)?"checked='checked'":"";?>/>密钥管理</span>
						  <span class="style_c4"><input type="checkbox" class="input_c4" value="25" <?php echo osa_checkstr($shortcut,25)?"checked='checked'":"";?>/>用户列表</span>
					  </p>
					</div>
					<div class="rdselect">
						<div class="aselect left">
							<a class="aselect1" id="checkall"><span class="a_color1">全选</span></a>
							<span class="a_color1">/</span>
							<a class="aselect1" id="invert"><span class="a_color1">反选</span></a>
							<span class="a_color1">/</span>
							<a class="aselect1" id="cancelall"><span class="a_color1">全不选</span></a>
						</div>
					</div>	
					
				</div>
				<div class="rightcon_bottom"></div>
				<div class="height10"></div>  
				<div class="btn_submit2">
					<div class="btn_green" id="shortcut-save"><a href="#"><span class="spanL">确认保存</span><span class="spanR"></span></a></div>
				</div>
			<!--one-->
			</div>		
			<div class="height10"></div>
		<!--右侧content结束-->
		</div>
<!--右侧内容结束-->
</div>

<script type="text/javascript" src="script/common/base.js"> </script>
<link rel="stylesheet" href="css/osa-tips.css" type="text/css" />
<script type="text/javascript" src="script/common/tips-base.js"></script>
<script type="text/javascript" src="script/account/shortcut.js"></script>

<!--内容结束-->
<?php include 'views/footer.php';?>