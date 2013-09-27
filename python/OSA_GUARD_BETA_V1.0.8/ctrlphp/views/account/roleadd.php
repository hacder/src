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
				<div class="placing"><span>当前位置：</span><span>账户中心</span> <span>&gt;</span> <span>创建角色</span></span></div>
			</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
			<div class="edit_list">
			<!--one-->
				  <div class="rightcon_title">基本信息</div>
				  <div class="rightcon_mid">
				      <p class="pheight_c4">
				      	<label class="label5">角色名称：</label><input type="text" class="style5" id="rolename"/>
				      	<span class="tips" style="margin-left:10px;"></span>
				      </p>
				      <p class="pheight_c4"><label class="label5">角色描述：</label><textarea class="textarea2" id="roledes" ></textarea></p>
					  <p class="clear"></p> 
				  </div>
				  <div class="rightcon_bottom"></div>
			<!--one-->
			<!--two-->
				<div class="rightcon_title">权限列表</div>
				<div class="rightcon_mid" style="overflow:hidden;">
					<div class="rightcon4">
				      <p class="pheight power_title">设备管理</p>
					  <p class="pheight_c4">
						  <span class="style_c4"><input type="checkbox" class="input_c4"  value="01" />查看设备</span>
						  <span class="style_c4"><input type="checkbox" class="input_c4"  value="02" />创建设备</span>
						  <span class="style_c4"><input type="checkbox" class="input_c4"  value="03" />编辑设备</span>
						  <span class="style_c4"><input type="checkbox"  class="input_c4" value="04" />删除设备</span>
					  </p>
					</div>
					<div class="rightcon4">
				      <p class="pheight power_title">监控项目</p>
					  <p class="pheight_c4">
						  <span class="style_c4"><input type="checkbox" class="input_c4"  value="11" />查看监控项目</span>
						   <span class="style_c4"><input type="checkbox" class="input_c4" value="12" />创建监控项目</span>
						  <span class="style_c4"><input type="checkbox" class="input_c4"  value="13" />编辑监控项目</span>
						  <span class="style_c4"><input type="checkbox"  class="input_c4" value="14"/>删除监控项目</span>
					  </p>
					</div>
					<div class="rightcon4">
				      <p class="pheight power_title">账户管理</p>
					  <p class="pheight_c4">
					  	  <span class="style_c4"><input type="checkbox" class="input_c4" value="21"  />查看用户列表</span>
						  <span class="style_c4"><input type="checkbox" class="input_c4" value="22"  />添加用户信息</span>
						  <span class="style_c4"><input type="checkbox" class="input_c4" value="23"  />编辑用户信息</span>
						  <span class="style_c4"><input type="checkbox" class="input_c4" value="24"  />删除用户信息</span>
						  <span class="style_c4"><input type="checkbox" class="input_c4" value="31"  />查看角色列表</span>
						  <span class="style_c4"><input type="checkbox" class="input_c4" value="32"  />添加角色信息</span>
						  <span class="style_c4"><input type="checkbox" class="input_c4" value="33"  />编辑角色信息</span>
						  <span class="style_c4"><input type="checkbox" class="input_c4" value="34"  />删除角色信息</span>
					  </p>
					</div>
					<div class="rightcon4">
				      <p class="pheight power_title">其他相关</p>
					  <p class="pheight_c4">
						  <span class="style_c4"><input type="checkbox" class="input_c4"  value="41" />SNMP采集配置</span>
						  <span class="style_c4"><input type="checkbox" class="input_c4"  value="42" />告警通知设定</span>
						  <span class="style_c4"><input type="checkbox"  class="input_c4" value="43" />个性化设定</span>
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
				
				<!--two-->
				<div class="btn_submit2">
						<div class="btn_green" id="role-add" ><a class="" ><span class="spanL">确认添加</span><span class="spanR"></span></a></div>
						<div class="btn_cancel" id="cancel" ><a href="javascript:history.go(-1)"><span class="spanL">取消返回</span><span class="spanR"></span></a></div>
				</div>
			</div>		
			<div class="height10"></div>
		<!--右侧content结束-->
		</div>
<!--右侧内容结束-->
</div>

<script type="text/javascript" src="script/common/base.js"> </script>
<link rel="stylesheet" href="css/osa-tips.css" type="text/css" />
<script type="text/javascript" src="script/common/tips-base.js"></script>
<script type="text/javascript" src="script/account/roleadd.js"></script>

<!--内容结束-->
<?php include 'views/footer.php';?>