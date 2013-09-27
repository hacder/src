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
				<div class="placing"><span>当前位置：</span><span>账户中心</span> <span>&gt;</span> <span>编辑用户</span></div>
			</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
			<div class="edit_list">
			<!--one-->
				  <div class="rightcon_title">基本信息</div>
				  <div class="rightcon_mid">
				      <p class="pheight">
				      	<label class="label5"><em class="em">*</em>用户名：</label><input type="text" readonly="readonly" class="style5" id="username" value="<?php echo $userinfo[0]['oUserName'];?>" />
				      	<input type="hidden" id="hide-id" value="<?php echo $userinfo[0]['id'];?>" />
			      	 </p>
					  <p class="light">&nbsp;用户名用于登录系统和社区，请谨慎填写！</p>
					  <p class="pheight">
				      	<label class="label5"><em class="em"></em>姓名：</label><input type="text" class="style5" id="nickname" value="<?php echo $userinfo[0]['oRealName'];?>" />
			      	 </p>
				     <div class="pheight">
				      		<label class="label5"><em class="em">*</em>角色：</label>
				      		<div style="float:left">
					      		<div class="select_box" style="z-index: 1; position: relative; width: 116px;">
									<input id="rolename" name="tag_input" class="tag_select tag_input" maxlength="8" readonly="readonly" value="<?php echo $userinfo[0]['oRoleName'];?>"/>
									<ul class="tag_options" style="position: absolute; z-index: 999; width: 116px; display: none;">
										<li class="open tag_li">请选择角色</li>
										<?php foreach ($roleinfo as $role) { ?>
											<li class="<?php echo $role['oRoleName']==$userinfo[0]['oRoleName']?'open_selected':'open';?> tag_li"><?php echo $role['oRoleName'];?></li>
										<?php }?>
									</ul>
								</div>
							</div>
							<div style="float:left;padding:2px 10px;" class="btn_green1">
								<a href="index.php?c=account&a=roleadd">
									<span class="spanL">创建角色</span>
									<span class="spanR"></span>
								</a>
							</div>
			      	  </div>
				      <p class="pheight">
				      		<label class="label5"><em class="em">*</em>邮箱：</label><input type="text" class="style5" id="email" value="<?php echo $userinfo[0]['oEmail'];?>"/>
				      		<span class="tips" style="float:left;margin-left:10px;color:red;"></span>
				      		<input type="hidden" id="hide-email" value="<?php echo $userinfo[0]['oEmail'];?>" />
				      </p>
					  <p class="light">&nbsp;用于接收报警的重要途径。</p>
				      <p class="pheight">
				      		<label class="label5"><em class="em">*</em>手机号码：</label><input type="text" class="style5" id="iphone" value="<?php echo $userinfo[0]['oPhone'];?>"/>
				      		<span class="tips" style="float:left;margin-left:10px;color:red;"></span>
				      </p>
					  <p class="light">&nbsp;用于接收报警短信，方便同事之间进行联系。</p>
					  <p class="clear"></p>
					  <div class="btn_useredit" style="width:450px;">
							<div class="btn_green" id="user-edit"><a class=""><span class="spanL">确认编辑</span><span class="spanR"></span></a></div>
							<div class="btn_cancel" id="cancel"><a href="javascript:history.go(-1)"><span class="spanL">取消返回</span><span class="spanR"></span></a></div>
					  </div>  
				  </div>
				  <div class="rightcon_bottom"></div>
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
<script type="text/javascript" src="script/device/common.js"> </script>
<script type="text/javascript" src="script/account/useredit.js"></script>

<!--内容结束-->
<?php include 'views/footer.php';?>