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
						  <span class="font1">-角色列表</span>
					  </div>
					  <div class="setup">
					      <span class="setup_icon"><a href="index.php?c=account&a=roleadd">创建新角色</a></span>
					  </div>
				  </div>
				  <div class="clear"></div>
				  <div class="table_setup">
				      <table cellspacing="0" cellpadding="0" width="100%">
					    <tr>
						    <th width="5%">选择</th>
							<th width="30%">角色</th>
							<th width="30%">状态</th>
							<th>操作</th>
						</tr>
						<?php foreach ($roleinfo as $key){?>
						<tr>
						    <td><input type="checkbox" class="checkbox" value="<?php echo $key['id'];?>"/></td>
							<td><?php echo $key['oRoleName'];?></td>
							<td><?php echo $key['oStatus']==0?'禁用':'启用';?></td>
							<td>
								<a href="index.php?c=account&a=roleedit&id=<?php echo $key['id'];?>">编辑</a>
								<a href="#" class="updatestatus" status="<?php echo $key['oStatus'];?>" rid="<?php echo $key['id'];?>"><?php echo $key['oStatus']==0?'启用':'禁用';?></a>
							</td>
						</tr> 
						<?php }?>
						<tr>
						   <td class="td_chect" colspan="7">
							   <span class="checkall"><input type="checkbox" class="style11" id="checkall"/>全选</span>
							   <span class="del"><input type="button" value="删除" class="delete" id="del_role"/></span>
						   </td>  
						</tr>
						<tr>
						    <td class="style2" colspan="7">
						    	<?php echo $page;?>
						    </td>
						</tr>
					  </table>
				  </div>
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
		  <script type="text/javascript" src="script/account/rolelist.js"></script>
<?php include 'views/footer.php';?>
