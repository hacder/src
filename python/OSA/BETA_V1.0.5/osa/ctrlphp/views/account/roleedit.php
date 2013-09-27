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
						  <span class="font1">-编辑角色</span>
					  </div>
				  </div>
				  <!--one-->
				  <div class="rightcon_title">基本信息</div>
				  <div class="rightcon_mid">
				      <p class="pheight">
					      <label class="label5"><em class="em">*</em>角色名称：</label>
						  <input type="text" class="style5 hui" id="rolename" value="<?php echo $role[0]['oRoleName'];?>" readonly="true"/><span class="tips"></span>
					  </p>
					  <p><label class="label5">角色描述：</label><textarea class="textarea1" id="roledescript"><?php echo $role[0]['oDescription'];?></textarea></p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--one-->
				  <!--two-->
				  <div class="rightcon_title">权限列表</div>
				  <div class="rightcon_mid">
				      <p class="pheight power_title">设备管理</p>
					  <p class="pheight">
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="9" <?php echo osa_checkstr($role[0]['oPerArr'],9)?"checked='checked'":"";?>/>设备信息查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="1" <?php echo osa_checkstr($role[0]['oPerArr'],1)?"checked='checked'":"";?>/>设备信息添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="2" <?php echo osa_checkstr($role[0]['oPerArr'],2)?"checked='checked'":"";?>/>设备信息编辑</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="3" <?php echo osa_checkstr($role[0]['oPerArr'],3)?"checked='checked'":"";?>/>设备信息删除</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="4" <?php echo osa_checkstr($role[0]['oPerArr'],4)?"checked='checked'":"";?>/>设备信息复制</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="10" <?php echo osa_checkstr($role[0]['oPerArr'],10)?"checked='checked'":"";?>/>分组信息查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="11" <?php echo osa_checkstr($role[0]['oPerArr'],11)?"checked='checked'":"";?>/>分组信息添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="12" <?php echo osa_checkstr($role[0]['oPerArr'],12)?"checked='checked'":"";?>/>分组信息编辑</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="13" <?php echo osa_checkstr($role[0]['oPerArr'],13)?"checked='checked'":"";?>/>分组信息删除</span>
					  </p>
				      <p class="pheight power_title">日常运维</p>
					  <p class="pheight">
					      <span class="style8"><input type="checkbox" class="style11 perm"  value="130" <?php echo osa_checkstr($role[0]['oPerArr'],130)?"checked='checked'":"";?>/>服务器列表</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="131" <?php echo osa_checkstr($role[0]['oPerArr'],131)?"checked='checked'":"";?>/>即时信息</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="132" <?php echo osa_checkstr($role[0]['oPerArr'],132)?"checked='checked'":"";?>/>控制中心</span>
					      
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="20" <?php echo osa_checkstr($role[0]['oPerArr'],20)?"checked='checked'":"";?>/>线上脚本查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="21" <?php echo osa_checkstr($role[0]['oPerArr'],21)?"checked='checked'":"";?>/>线上脚本添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="22" <?php echo osa_checkstr($role[0]['oPerArr'],22)?"checked='checked'":"";?>/>线上脚本编辑</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="23" <?php echo osa_checkstr($role[0]['oPerArr'],23)?"checked='checked'":"";?>/>线上脚本删除</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="24" <?php echo osa_checkstr($role[0]['oPerArr'],24)?"checked='checked'":"";?>/>线上脚本复制</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="30" <?php echo osa_checkstr($role[0]['oPerArr'],30)?"checked='checked'":"";?>/>操作记录查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="31" <?php echo osa_checkstr($role[0]['oPerArr'],31)?"checked='checked'":"";?>/>操作记录添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="32" <?php echo osa_checkstr($role[0]['oPerArr'],32)?"checked='checked'":"";?>/>操作记录编辑</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="33" <?php echo osa_checkstr($role[0]['oPerArr'],33)?"checked='checked'":"";?>/>操作记录删除</span>
						  
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="40" <?php echo osa_checkstr($role[0]['oPerArr'],40)?"checked='checked'":"";?>/>数据库备份查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="41" <?php echo osa_checkstr($role[0]['oPerArr'],41)?"checked='checked'":"";?>/>数据库备份线上脚本添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="42" <?php echo osa_checkstr($role[0]['oPerArr'],42)?"checked='checked'":"";?>/>批量操作查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="43" <?php echo osa_checkstr($role[0]['oPerArr'],43)?"checked='checked'":"";?>/>批量操作添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="44" <?php echo osa_checkstr($role[0]['oPerArr'],44)?"checked='checked'":"";?>/>配置文件更新查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="45" <?php echo osa_checkstr($role[0]['oPerArr'],45)?"checked='checked'":"";?>/>配置文件更新添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="46" <?php echo osa_checkstr($role[0]['oPerArr'],46)?"checked='checked'":"";?>/>配置文件备份查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="47" <?php echo osa_checkstr($role[0]['oPerArr'],47)?"checked='checked'":"";?>/>配置文件备份添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="48" <?php echo osa_checkstr($role[0]['oPerArr'],48)?"checked='checked'":"";?>/>批量操作结果删除</span>
						  
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="50" <?php echo osa_checkstr($role[0]['oPerArr'],50)?"checked='checked'":"";?>/>运维知识库查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="51" <?php echo osa_checkstr($role[0]['oPerArr'],51)?"checked='checked'":"";?>/>运维知识库添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="52" <?php echo osa_checkstr($role[0]['oPerArr'],52)?"checked='checked'":"";?>/>运维知识库编辑</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="53" <?php echo osa_checkstr($role[0]['oPerArr'],53)?"checked='checked'":"";?>/>运维知识库删除</span>
						  
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="60" <?php echo osa_checkstr($role[0]['oPerArr'],60)?"checked='checked'":"";?>/>配置文件查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="61" <?php echo osa_checkstr($role[0]['oPerArr'],61)?"checked='checked'":"";?>/>配置文件添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="62" <?php echo osa_checkstr($role[0]['oPerArr'],62)?"checked='checked'":"";?>/>配置文件编辑</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="63" <?php echo osa_checkstr($role[0]['oPerArr'],63)?"checked='checked'":"";?>/>配置文件删除</span>
					  </p>
					  <p class="pheight power_title">运营分析</p>
					  <p class="pheight">
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="120" <?php echo osa_checkstr($role[0]['oPerArr'],120)?"checked='checked'":"";?>/>运营分析</span>
					  </p> 
					  <p class="pheight power_title">账户管理</p>
					  <p class="pheight">
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="70" <?php echo osa_checkstr($role[0]['oPerArr'],70)?"checked='checked'":"";?>/>用户信息查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="71" <?php echo osa_checkstr($role[0]['oPerArr'],71)?"checked='checked'":"";?>/>用户信息添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="72" <?php echo osa_checkstr($role[0]['oPerArr'],72)?"checked='checked'":"";?>/>用户信息编辑</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="73" <?php echo osa_checkstr($role[0]['oPerArr'],73)?"checked='checked'":"";?>/>用户信息删除</span>

						  <span class="style8"><input type="checkbox" class="style11 perm"  value="80" <?php echo osa_checkstr($role[0]['oPerArr'],80)?"checked='checked'":"";?>/>角色信息查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="81" <?php echo osa_checkstr($role[0]['oPerArr'],81)?"checked='checked'":"";?>/>角色信息添加</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="82" <?php echo osa_checkstr($role[0]['oPerArr'],82)?"checked='checked'":"";?>/>角色信息编辑</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="83" <?php echo osa_checkstr($role[0]['oPerArr'],83)?"checked='checked'":"";?>/>角色信息删除</span>
					  </p>
					  <p class="pheight power_title">配置面版</p>
					  <p class="pheight">
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="90" <?php echo osa_checkstr($role[0]['oPerArr'],90)?"checked='checked'":"";?>/>系统功能设置</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="91" <?php echo osa_checkstr($role[0]['oPerArr'],91)?"checked='checked'":"";?>/>安全密钥设置</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="92" <?php echo osa_checkstr($role[0]['oPerArr'],92)?"checked='checked'":"";?>/>通知方式设置</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="93" <?php echo osa_checkstr($role[0]['oPerArr'],93)?"checked='checked'":"";?>/>监控项目信息查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="94" <?php echo osa_checkstr($role[0]['oPerArr'],94)?"checked='checked'":"";?>/>监控项目添加</span>
					  	  <span class="style8"><input type="checkbox" class="style11 perm"  value="95" <?php echo osa_checkstr($role[0]['oPerArr'],95)?"checked='checked'":"";?>/>监控项目删除</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="96" <?php echo osa_checkstr($role[0]['oPerArr'],96)?"checked='checked'":"";?>/>监控项目编辑</span>
					  </p>
					  <p class="pheight power_title">个人中心</p>
					  <p class="pheight">
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="100" <?php echo osa_checkstr($role[0]['oPerArr'],100)?"checked='checked'":"";?>/>告警信息查看</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="101" <?php echo osa_checkstr($role[0]['oPerArr'],101)?"checked='checked'":"";?>/>修改密码</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="102" <?php echo osa_checkstr($role[0]['oPerArr'],102)?"checked='checked'":"";?>/>个人资料</span>
						  <span class="style8"><input type="checkbox" class="style11 perm"  value="103" <?php echo osa_checkstr($role[0]['oPerArr'],103)?"checked='checked'":"";?>/>快捷菜单设置</span>
					  	  <span class="style8"><input type="checkbox" class="style11 perm"  value="104" <?php echo osa_checkstr($role[0]['oPerArr'],104)?"checked='checked'":"";?>/>快捷菜单设置</span>
					  </p>
					  <p class="right"><span class="link pointer" id="checkall">全选</span><span class="link pointer" id="cancelall">全不选</span><span class="link pointer" id="invert">反选</span></p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--two-->
				  <p>
					  <input type="button" class="enter specibut" value="确认编辑" id="role_edit"/>
					  <input type="button" class="enter specibut" value="取消返回" onclick="window.location='index.php?c=account&a=rolelist';"/>
				  	  <input type="hidden" value="<?php echo $hideurl;?>" id="hideurl" />
				  </p>
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
		  <script type="text/javascript" src="script/account/roleadd.js"> </script>
<?php include 'views/footer.php';?>
