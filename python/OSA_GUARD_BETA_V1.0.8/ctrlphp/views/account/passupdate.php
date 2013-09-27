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
				<div class="placing"><span>当前位置：</span><span>账户中心</span> <span>&gt;</span> <span>修改密码</span></span></div>
			</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
			<div class="edit_list">
			<!--one-->
				  <div class="rightcon_title">修改个人密码</div>
				  <div class="rightcon_mid">
				      <p class="pheight">
				      	<label class="label5"><em class="em">*</em>旧密码：</label><input type="text"  class="style5" id="oldpasswd"  />
				   		<span class="tips" style="float:left;margin-left:10px;color:red;"></span>
			      	 </p>
			      	  <p class="pheight">
					      <label class="label5"><em class="em">*</em>新密码：</label><input type="password" class="style5" id="passwd"/>
						  <span class="notice"></span>
					  </p>
				      <p class="pheight">
					      <label class="label5"><em class="em">*</em>确认密码：</label><input type="password" class="style5" id="confirmpasswd"/>
					      <span class="tips" style="float:left;margin-left:10px;color:red;"></span>
					  </p>
					  <p class="light">&nbsp;</p>
					  <p class="clear"></p>
					  <div class="btn_passupdate" style="width:450px;">
							<div class="btn_green" id="passwd-update"><a class=""><span class="spanL">确认编辑</span><span class="spanR"></span></a></div>
							<div class="btn_cancel" id="cancel"><a class=""><span class="spanL">取消返回</span><span class="spanR"></span></a></div>
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
<script type="text/javascript" src="script/account/passupdate.js"></script>

<!--内容结束-->
<?php include 'views/footer.php';?>