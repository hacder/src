	<?php include 'views/header.php';?>
		  <!--content开始-->
		  <div class="content">
		      <!--左边开始-->
			  <?php include 'views/account/left.php';?>
			  <!--右边开始-->
			  <div class="main_right">
			      <div class="linkmlist">
				      <div class="home_icom">
					      <span>当前位置：</span>
						  <span><a href="#">账户管理</a></span>
						  <span class="font1">-创建新角色</span>
					  </div>
				  </div>
				  <!--one-->
				  <div class="rightcon_title">基本信息</div>
				  <div class="rightcon_mid">
				      <p class="pheight">
					      <label class="label5"><em class="em">*</em>角色名称：</label>
						  <input type="text" class="style5" id="rolename"/><span class="tips"></span>
					  </p>
					  <p><label class="label5">角色描述：</label><textarea class="textarea1" id="roledescript"></textarea></p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--one-->
				  <!--two-->
				  <div class="rightcon_title">权限列表</div>
				  <div class="rightcon_mid">
				      <p class="pheight power_title">设备管理</p>
					  <p class="pheight">
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="9"/>设备信息查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="1"/>设备信息添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="2"/>设备信息编辑</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="3"/>设备信息删除</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="4"/>设备信息复制</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="10"/>分组信息查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="11"/>分组信息添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="12"/>分组信息编辑</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="13"/>分组信息删除</span>
					  </p>
				      <p class="pheight power_title">日常运维</p>
					  <p class="pheight">
					  	  <span class="style8"><input type="checkbox" class="style11 perm"  value="130"/>服务器列表</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="131"/>即时信息</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="132"/>控制中心</span>
					  	  
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="20"/>线上脚本查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="21"/>线上脚本添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="22"/>线上脚本编辑</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="23"/>线上脚本删除</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="24"/>线上脚本复制</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="30"/>操作记录查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="31"/>操作记录添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="32"/>操作记录编辑</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="33"/>操作记录删除</span>
						  
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="40"/>数据库备份查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="41"/>数据库备份线上脚本添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="42"/>批量操作查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="43"/>批量操作添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="44"/>配置文件更新查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="45"/>配置文件更新添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="46"/>配置文件备份查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="47"/>配置文件备份添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="48"/>批量操作结果删除</span>
						  
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="50"/>运维知识库查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="51"/>运维知识库添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="52"/>运维知识库编辑</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="53"/>运维知识库删除</span>
						  
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="60"/>配置文件查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="61"/>配置文件添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="62"/>配置文件编辑</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="63"/>配置文件删除</span>
					  </p>
					  <p class="pheight power_title">运营分析</p>
					  <p class="pheight">
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="120"/>运营分析</span>
					  </p>
					  <p class="pheight power_title">账户管理</p>
					  <p class="pheight">
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="70"/>用户信息查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="71"/>用户信息添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="72"/>用户信息编辑</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="73"/>用户信息删除</span>

						  <span class="style8"><input type="checkbox" class="style11 perm"  value="80"/>角色信息查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="81"/>角色信息添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="82"/>角色信息编辑</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="83"/>角色信息删除</span>
					  </p>
					  <p class="pheight power_title">配置面版</p>
					  <p class="pheight">
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="90"/>系统功能设置</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="91"/>安全密钥设置</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="92"/>通知方式设置</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="93"/>监控项目信息查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="94"/>监控项目添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="95"/>监控项目删除</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="96"/>监控项目编辑</span>
					  </p>
					  <p class="pheight power_title">个人中心</p>
					  <p class="pheight">
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="100"/>告警信息查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="101"/>修改密码</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="102"/>个人资料</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="103"/>快捷菜单设置</span>
					  	  <span class="style8"><input type="checkbox" class="style11 perm"  value="104"/>删除告警信息</span>
					  </p>
					  <p class="right"><span class="link pointer" id="checkall">全选</span><span class="link pointer" id="cancelall">全不选</span><span class="link pointer" id="invert">反选</span></p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--two-->
				  <p><input type="button" class="enter specibut" value="确认添加" id="role_add"/><input type="button" class="enter specibut" value="取消返回" onclick="window.location='index.php?c=account&a=rolelist';"/></p>
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
		  <script type="text/javascript" src="script/account/roleadd.js"> </script>
<?php include 'views/footer.php';?>
