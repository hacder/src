	<?php include 'views/header.php';?>
		  <!--content开始-->
		  <div class="content">
		      <!--左边开始-->
			  <?php include 'views/account/left.php';?>
			  <!--左边结束-->
			  <!--右边开始-->
			  <div class="main_right">
			      <div class="linkmlist">
				      <div class="home_icom">
					      <span>当前位置：</span>
						  <span><a href="#">账户管理</a></span>
						  <span class="font1">-用户列表</span>
					  </div>
					  <div class="setup">
					      <span class="setup_icon"><a href="index.php?c=account&a=useradd">创建新用户</a></span>
					  </div>
				  </div>
				  <div class="clear"></div>
				  <form method="post" action="<?php echo $url;?>" >
				  <p class="pheight" style="margin-top:10px;">
				      <label class="label7">用户名：</label><input type="text"  class="style4" name="keyword" value="<?php echo $_SESSION['name'];?>"/>
					  <label class="label7">角色：</label>
					  <select class="select1" name="role">
					  	  <option value="">请选择</option>
					  	  <?php foreach ($roles as $key ) {?>
					  	  <option value="<?php echo $key['id'];?>" <?php echo $key['id']==$_SESSION['role']?"selected='selected'":"";?> ><?php echo $key['oRoleName'];?></option>
					  	  <?php }?>
					  </select>
					  <label class="label7">状态：</label>
					  <select class="select1" name="status">
					  	  <option value="">请选择</option>
					  	  <option value="0" <?php echo 0===$_SESSION['status']?"selected='selected'":"";?>>禁用</option>
					  	  <option value="1" <?php echo 1==$_SESSION['status']?"selected='selected'":"";?>>启用</option>
					  </select>
					  <input type="submit" class="updatebut"  value="查询"/>
					  <a class="timea" href="index.php?c=account&a=userlist&clean=1">[清空条件]</a>
				  </p>
				  </form>
				  <div class="table_setup">
				      <table cellspacing="0" cellpadding="0" width="100%">
					    <tr>
						    <th width="5%">选择</th>
							<th>用户</th>
							<th>邮箱</th>
							<th>角色</th>
							<th>状态</th>
							<th>操作</th>
						</tr>
						<?php foreach ($userinfo as $user){?>
						<tr>
						    <td><input type="checkbox" class="checkbox" value="<?php echo $user['id'];?>"/></td>
							<td><?php echo $user['oUserName'];?></td>
							<td><?php echo $user['oEmail'];?></td>
							<td><?php echo $user['oRoleName'];?></td>
							<td><?php echo $user['oStatus']==0?'禁用':'启用';?></td>
							<td>
								<a href="index.php?c=account&a=useredit&id=<?php echo $user['id'];?>">编辑</a>
								<a href="#" class="updatestatus" status="<?php echo $user['oStatus'];?>" rid="<?php echo $user['id'];?>"><?php echo $user['oStatus']==0?'启用':'禁用';?></a>
								<a href="#" class="initpasswd" rid="<?php echo $user['id'];?>" uname="<?php echo $user['oUserName'];?>" title="初始化密码为：osapub">初始化密码</a>
							</td>
						</tr> 
						<?php }?>
						<tr>
						    <td class="td_chect" colspan="7">
							   <span class="checkall"><input type="checkbox" class="style11" id="checkall"/>全选</span>
							   <span class="del"><input type="button" value="删除" class="delete" id="del_user"/></span>
						    </td>  
						</tr>
						<tr>
						    <td class="style2" colspan="7">
						    <?php echo $page; ?>
						    </td>
						</tr>
					  </table>
				  </div>
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
  		  <script type="text/javascript" src="script/account/userlist.js"></script>
<?php include 'views/footer.php';?>
