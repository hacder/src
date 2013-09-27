<input type="hidden" value="<?php echo $url;?>" id="hideUrl" />
<input type="hidden" value="<?php echo $ajaxurl;?>" id="hideAjaxUrl" />
<table cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<th width="5%"><input type="checkbox" id="checkall" /></th>
		<th width="30%">角色名称</th>
		<th width="30%">状态</th>
		<th>操作</th>
	</tr>
	<?php foreach ($roleinfo as $role){?>
	<tr>
		<td><input class="check_one" type="checkbox" value="<?php echo $role['id'];?>" /></td>
		<td><?php echo $role['oRoleName']?></td>
		<td><?php echo $role['oStatus']==1?'禁用':'启用';?></td>
		<td><a href="index.php?c=account&a=roledit&id=<?php echo $role['id'];?>">编辑</a><a href="index.php?c=account&a=userlists">用户列表</a></td>
	</tr> 
	<?php }?>

	<tr>
		<td class="style2" colspan="7">
		<div class="page">
			<div class="pageL">
			  <label>每页显示数量</label>
				<div class="page_sel">
					<div class="select_box" style="z-index: 1; position: relative;">
						<input id="page_input" name="tag_input" class="tag_select tag_input" maxlength="8" value="<?php echo isset($_SESSION['pagenum'])?$_SESSION['pagenum']:10;?>">
						<ul class="tag_options" style="position: absolute; z-index: 999;width:50px;top:23px; display: none;">
							<li class="<?php echo $_SESSION['pagenum']==10?'open_selected':'open';?> tag_li page_li">10</li>
							<li class="<?php echo $_SESSION['pagenum']==20?'open_selected':'open';?> tag_li page_li">20</li>
							<li class="<?php echo $_SESSION['pagenum']==50?'open_selected':'open';?> tag_li page_li">50</li>
						</ul>
					</div>
				</div>
			</div>
			<?php echo $page;?>
		</div>
		</td>
	</tr>
</table>