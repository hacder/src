
<?php include 'views/header.php';?>
		  <!--content开始-->
		  <div class="content">
		      <!--左边开始-->
			   <?php include 'views/personcenter/left.php';?>
			  <!--左边结束-->
			  <!--右边开始-->
			  <div class="main_right">
			      <div class="linkmlist">
				      <div class="home_icom">
					      <span>当前位置：</span>
						  <span><a href="#">个人中心</a></span>
						  <span class="font1">-快捷菜单设置</span>
					  </div>
				  </div>
				  <div class="clear"></div>
				  <div class="shortcut_table">
				      <table cellspacing="0" cellpadding="0" width="100%">
					    <tr>
						    <th>设备管理</th>
						    <th>日常运维</th>
						    <th>运营分析</th>
							<th>账户管理</th>
						    <th>配置面板</th>
							<th>个人中心</th>
						</tr>	
						<tr class="shortcut_table">
						    <td><input type="checkbox" value="0" <?php echo osa_checkstr($user['oShortCut'],0)?"checked='checked'":"";?>/>设备信息</td>
							<td class="hui">单机管理</td>
							<td class="hui">图形分析</td>
							<td class="hui">账户管理</td>
							<td class="hui">配置管理</td>
							<td class="hui">个人中心</td>
						<tr>
						<tr>
						    <td>&nbsp;</td>
						    <td><input type="checkbox" value="11" <?php echo osa_checkstr($user['oShortCut'],11)?"checked='checked'":"";?>/>服务器列表</td>
						    <td><input type="checkbox" value="31" <?php echo osa_checkstr($user['oShortCut'],31)?"checked='checked'":"";?>/>内存状态分析</td>
							<td><input type="checkbox" value="51" <?php echo osa_checkstr($user['oShortCut'],51)?"checked='checked'":"";?>/>用户列表</td>
						    <td><input type="checkbox" value="61" <?php echo osa_checkstr($user['oShortCut'],61)?"checked='checked'":"";?>/>系统功能设置</td>
						    <td><input type="checkbox" value="71" <?php echo osa_checkstr($user['oShortCut'],71)?"checked='checked'":"";?>/>站内通知</td>
						</tr>
						<tr>
						    <td>&nbsp;</td>
						    <td class="hui">日常操作</td>
						    <td><input type="checkbox" value="32" <?php echo osa_checkstr($user['oShortCut'],32)?"checked='checked'":"";?>/>负载状态分析</td>
							<td class="hui">角色管理</td>
						    <td><input type="checkbox" value="62" <?php echo osa_checkstr($user['oShortCut'],62)?"checked='checked'":"";?>/>安全密钥设置</td>
						    <td><input type="checkbox" value="72" <?php echo osa_checkstr($user['oShortCut'],72)?"checked='checked'":"";?>/>个人资料</td>
						</tr>
						<tr>
						    <td>&nbsp;</td>
						    <td><input type="checkbox" value="12" <?php echo osa_checkstr($user['oShortCut'],12)?"checked='checked'":"";?>/>线上编写脚本</td>
						    <td><input type="checkbox" value="33" <?php echo osa_checkstr($user['oShortCut'],33)?"checked='checked'":"";?>/>登录用户分析</td>
							<td><input type="checkbox" value="52" <?php echo osa_checkstr($user['oShortCut'],52)?"checked='checked'":"";?>/>角色列表</td>
						    <td class="hui">报警配置</td>
						    <td><input type="checkbox" value="73" <?php echo osa_checkstr($user['oShortCut'],73)?"checked='checked'":"";?>/>修改密码</td>
						</tr>
						<tr>
						    <td>&nbsp;</td>
						    <td><input type="checkbox" value="13" <?php echo osa_checkstr($user['oShortCut'],13)?"checked='checked'":"";?>/>操作记录管理</td>
						    <td><input type="checkbox" value="34" <?php echo osa_checkstr($user['oShortCut'],34)?"checked='checked'":"";?>/>进程数量分析</td>
						    <td>&nbsp;</td>
						    <td><input type="checkbox" value="63" <?php echo osa_checkstr($user['oShortCut'],63)?"checked='checked'":"";?>/>通知方式设置</td>
							<td><input type="checkbox" value="74" <?php echo osa_checkstr($user['oShortCut'],74)?"checked='checked'":"";?>/>快捷菜单设置</td>
						</tr>
						<tr>
						    <td>&nbsp;</td>
						    <td><input type="checkbox" value="14" <?php echo osa_checkstr($user['oShortCut'],14)?"checked='checked'":"";?>/>数据库备份</td>
						    <td><input type="checkbox" value="35" <?php echo osa_checkstr($user['oShortCut'],35)?"checked='checked'":"";?>/>磁盘状态分析</td>
						    <td>&nbsp;</td>
						    <td><input type="checkbox" value="64" <?php echo osa_checkstr($user['oShortCut'],64)?"checked='checked'":"";?>/>项目监控列表</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
						    <td>&nbsp;</td>
						    <td><input type="checkbox" value="15" <?php echo osa_checkstr($user['oShortCut'],15)?"checked='checked'":"";?>/>运维知识库</td>
						    <td><input type="checkbox" value="36" <?php echo osa_checkstr($user['oShortCut'],36)?"checked='checked'":"";?>/>网络信息分析</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
						    <td>&nbsp;</td>
						    <td class="hui">批量管理</td>
						    <td><input type="checkbox" value="37" <?php echo osa_checkstr($user['oShortCut'],37)?"checked='checked'":"";?>/>连接数量分析</td>
						    <td>&nbsp;</td>
							<td>&nbsp;</td>
						    <td>&nbsp;</td>
						</tr>
						<tr>
						    <td>&nbsp;</td>
						    <td><input type="checkbox" value="16" <?php echo osa_checkstr($user['oShortCut'],16)?"checked='checked'":"";?>/>批量操作部署</td>
						    <td class="hui">综合信息报表</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
						    <td>&nbsp;</td>
						    <td><input type="checkbox" value="17" <?php echo osa_checkstr($user['oShortCut'],17)?"checked='checked'":"";?>/>批量操作记录</td>
						    <td><input type="checkbox" value="38" <?php echo osa_checkstr($user['oShortCut'],38)?"checked='checked'":"";?>/>故障分析报表</td>
						    <td>&nbsp;</td>
							<td>&nbsp;</td>
						    <td>&nbsp;</td>
						</tr>
						<tr>
						    <td>&nbsp;</td>
						    <td class="hui">配置文件</td>
						    <td><input type="checkbox" value="39" <?php echo osa_checkstr($user['oShortCut'],39)?"checked='checked'":"";?>/>日常操作报表</td>
						    <td>&nbsp;</td>
							<td>&nbsp;</td>
						    <td>&nbsp;</td>
						</tr>
						<tr>
						    <td>&nbsp;</td>
						    <td><input type="checkbox" value="18" <?php echo osa_checkstr($user['oShortCut'],18)?"checked='checked'":"";?>/>配置文件列表</td>
						    <td><input type="checkbox" value="40" <?php echo osa_checkstr($user['oShortCut'],40)?"checked='checked'":"";?>/>设备资费报表</td>
						    <td>&nbsp;</td>
							<td>&nbsp;</td>
						    <td>&nbsp;</td>
						</tr>
						<tr>
						    <td>&nbsp;</td>
						    <td><input type="checkbox" value="19" <?php echo osa_checkstr($user['oShortCut'],19)?"checked='checked'":"";?>/>批量配置更新</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						</tr>
						<tr>
						    <td>&nbsp;</td>
						    <td><input type="checkbox" value="20" <?php echo osa_checkstr($user['oShortCut'],20)?"checked='checked'":"";?>/>批量配置备份</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
						</tr>
						<tr>
						   <td class="td_chect" colspan="4">
						   <input type="button" value="确定保存" class="delete" id="shortcut_confirm" style="margin:10px 20px;"/></td>  
						</tr>
					  </table>					
				  </div>
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
		  <script type="text/javascript" src="script/personcenter/shortcut.js"></script>
<?php include 'views/footer.php';?>
