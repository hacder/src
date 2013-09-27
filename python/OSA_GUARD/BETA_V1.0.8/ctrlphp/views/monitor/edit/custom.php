<?php include 'views/header.php';?>
<link rel="stylesheet" href="css/extend.css" type="text/css" />
<!--内容开始-->
<div class="main">
<!--左侧菜单开始-->
	<?php include "views/monitor/menu.php";?>
<!--左侧菜单结束-->
<!--右侧内容开始-->
	<div class="mainright" id="mainright">
		<!--右侧title开始-->	
		<div class="currtitle">
			<div class="placing"><span>当前位置：</span><span>日常监控</span> <span>&gt;</span> <span>创建监控项目</span></div>
		</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
		<div class="edit_list">
			<?php $itemconfig = jsonDecode($itemdata[0]['oItemConfig']);
				  $indicators = $itemconfig['indicators'];?>

			<!--one-->
			<div class="rightcon_title">报警项目信息</div>
			<div class="rightcon_mid">
				<label class="label_more"><span class="red">*</span>监控项目名称：</label>
				<div class="left"><input type="text" class="style5" placeholder="请输入监控项目名称" id="itemname" value="<?php echo $itemdata[0]['oItemName'];?>" /><span class="tips" style="margin:6px;"></span></div>
				<div class="height10"></div>
				<label class="label_more"><span class="red">*</span>域名或者IP：</label>
				<div class="left"><input type="text" class="style5" id="itemip" value="<?php echo $itemdata[0]['oItemObject'];?>" readonly="readonly"/></div>
				<div class="height10"></div>
				<label class="label_more"><span class="red">*</span>SNMP监控项目：</label>
				<div class="left">
					<div class="left <?php echo $itemconfig['name']=='loadstat'?'btn_green1':'btn_gray1';?>">
						<a class="custom-li" name="loadstat"><span class="spanL">负载状态</span><span class="spanR"></span></a>
					</div>
					<div class="left <?php echo $itemconfig['name']=='network'?'btn_green1':'btn_gray1';?>">
						<a class="custom-li" name="network"><span class="spanL">网卡流量</span><span class="spanR"></span></a>
					</div>
					<div class="left <?php echo $itemconfig['name']=='memory'?'btn_green1':'btn_gray1';?>">
						<a class="custom-li" name="memory"><span class="spanL">内存使用率</span><span class="spanR"></span></a>
					</div>
					<div class="left <?php echo $itemconfig['name']=='diskstat'?'btn_green1':'btn_gray1';?>">
						<a class="custom-li" name="diskstat"><span class="spanL">磁盘使用率</span><span class="spanR"></span></a>
					</div>
					<div class="left <?php echo $itemconfig['name']=='cpu'?'btn_green1':'btn_gray1';?>">
						<a class="custom-li" name="cpu"><span class="spanL">CPU使用率</span><span class="spanR"></span></a>
					</div>
					<div class="left <?php echo $itemconfig['name']=='logins'?'btn_green1':'btn_gray1';?>">
						<a class="custom-li" name="logins"><span class="spanL">用户登录数</span><span class="spanR"></span></a>
					</div>
					<div class="left <?php echo $itemconfig['name']=='diskio'?'btn_green1':'btn_gray1';?>">
						<a class="custom-li" name="diskio"><span class="spanL">磁盘I/O</span><span class="spanR"></span></a>
					</div>
				</div>
				<div class="height10"></div>
				<div class="more_class" id="more-class">	
					<div class="window_hr left"></div>				
					<div class="col_4_title">
						<div class="col_con4_list1 left" style="width:210px;">指标名称</div>
						<div class="col_con4_list2 left" style="width:210px;">条件</div>
						<div class="col_con4_list3 left" style="width:190px;">阀值</div>
						<div class="col_con4_list4 left" style="width:170px;">操作</div>
					</div>
					<div class="height10"></div> 
					<?php include 'views/monitor/edit/custom_edit.php';?>				
				</div>
				<div class="more_class">	
					<div class="height10"></div> 
					<div class="window_hr left"></div>
					<div class="height10"></div> 
					<div class="btn_green1 left10">
						<a id="add-options"><span class="spanL">添加新条目</span><span class="spanR"></span></a>
					</div>
					<div class="light0 left20">您可以通过设定合理的阀值，结合条件表达式，对服务器各项指标进行合理的监控.</div>
				</div>
				<div class="clear"></div> 
			</div>
			<input type="hidden" value="<?php echo $itemid;?>" id="edit-itemid" />
			<div class="rightcon_bottom"></div>
			<!--one-->
			<?php include 'views/monitor/edit/common.php';?>
		</div>	
		<div class="height10"></div>
		<div class="edit_submit">
			<div class="btn_green" style=""><a id="custom-edit"><span class="spanL">编辑监控项目</span><span class="spanR"></span></a></div>
			<div style="" class="btn_cancel"><a href="javascript:history.go(-1)"><span class="spanL">取消返回</span><span class="spanR"></span></a></div>
		</div>

	<!--右侧content结束-->
	</div>
<!--右侧内容结束-->
</div>
<?php include 'views/monitor/custom_none.php'?>			
<!--内容结束-->
<script type="text/javascript" src="script/common/base.js"> </script>
<script type="text/javascript" src="script/device/common.js"></script>
<link rel="stylesheet" href="css/osa-tips.css" type="text/css" />
<link rel="stylesheet" href="css/osa-box.css" type="text/css" />
<script type="text/javascript" src="script/common/tips-base.js"></script>
<script type="text/javascript" src="script/common/osa-box.js"></script>
<script type="text/javascript">
<?php
$custom_scrpit = file_get_contents('script/monitor/custom.js');
if($_GET['itemid'] !=''){
$custom_scrpit = str_replace('loadstat',$customname,$custom_scrpit);
}
echo $custom_scrpit;
?>
</script>
<!-- <script type="text/javascript" src="script/monitor/custom.js"> </script> -->

<?php include 'views/footer.php';?>
