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
			<!--one-->
				<div class="rightcon_title">报警项目</div>
				<div class="rightcon_mid">
					<label class="label_more"><span class="red">*</span>监控项目名称：</label>
					<div class="left"><input type="text" class="style5" placeholder="请输入监控项目名称" id="itemname" value="<?php echo $itemdata[0]['oItemName'];?>" /><span class="tips" style="margin-left:6px;"></span></div>
					<div class="height10"></div>
					<label class="label_more"><span class="red">*</span>网页URL地址：</label>
					<div class="left"><input type="text" class="style5" value="<?php echo $itemdata[0]['oItemObject'];?>" id="urlname" readonly="readonly"/><span class="tips" style="margin-left:6px;margin-top:2px;"></span></div>
					<p class="light0"><label class="label_more">&nbsp;</label>输入需要监控的网页URL地址，仅能输入一个。例如：http://www.osapub.com </p>
					<p class="clear"></p> 
					<div class="height10"></div>
					<div class="btn_gray2 left20 mar20L">
						<a class="more-set"><span class="spanL">更多高级设置</span><span class="spanR"></span></a>
					</div>
					<?php $itemconfig = jsonDecode($itemdata[0]['oItemConfig']);
					?>
					<div class="more_class" style="display:<?php echo count($itemconfig)>1?'block':'none';?>;">
						<div class="window_hr"></div>
						<div class="height10"></div>
						<label class="label_more">对比关键字：</label>
						<div class="light0"><input type="text" class="style5" placeholder="请输入对比关键字" id="keyword" value="<?php echo $itemconfig['keywords'];?>"/></div>
						<div class="clear"></div>
						<label class="label_more">&nbsp;</label>
						<div class="light0">可以输入需要对比的关键字，多个关键定用","分割。</div>
						<div class="clear"></div>
						<label class="label_more">指定服务器：</label>
						<div class="input0"><input type="text" class="style5" placeholder="请输入域名或指定IP" id="itemip" value="<?php echo $itemconfig['ip'];?>"></div>
						<div class="clear"></div>
						<label class="label_more">&nbsp;</label>
						<div class="light0">可以输入域名指定的IP地址，对一个域名对应多个IP地址的情况下非常有帮助。</div>
						<div class="clear"></div>
						<label class="label_more">正常HTTP状态码：</label>
						<div class="light0">
								<input type="checkbox" class="radio1 http_status" <?php echo strpos($itemconfig['httpcode'],'200')!==false?"checked='checked'":'';?> value="200"/>
								<label class="label_c1">200</label>
								<input type="checkbox" class="radio1 http_status" <?php echo strpos($itemconfig['httpcode'],'301')!==false?"checked='checked'":'';?> value="301"/>
								<label class="label_c1">301</label>
								<input type="checkbox" class="radio1 http_status" <?php echo strpos($itemconfig['httpcode'],'302')!==false?"checked='checked'":'';?> value="302"/>
								<label class="label_c1">302</label>
								<label class="label_c1">其他：</label>
								<input type="text" class="style15" id="httpstatus" value="<?php echo trim(str_replace(array('200','301','302'),'',$itemconfig['httpcode']),',');?>">
						</div>
						<div class="clear"></div>
						<label class="label_more">&nbsp;</label>
						<div class="light0">选择您认为网页正常的HTTP状态码，通常情况下：200,301,302都是正常的状态码。</div>						
					</div>
					<div class="height10"></div>
				</div>
				<input type="hidden" value="<?php echo $itemid;?>" id="edit-itemid" />
				<div class="rightcon_bottom"></div>
			<!--one-->
			<?php include 'views/monitor/edit/common.php';?>
		</div>
				
		<div class="height10"></div>
		<div class="edit_submit">
			<div class="btn_green" style=""><a id="website-edit"><span class="spanL">编辑监控项目</span><span class="spanR"></span></a></div>
			<div style="" class="btn_cancel"><a href="javascript:history.go(-1)"><span class="spanL">取消返回</span><span class="spanR"></span></a></div>
		</div>
		<!--右侧content结束-->
		</div>
<!--右侧内容结束-->
</div>
<script type="text/javascript" src="script/common/base.js"> </script>
<link rel="stylesheet" href="css/osa-tips.css" type="text/css" />
<link rel="stylesheet" href="css/osa-box.css" type="text/css" />
<script type="text/javascript" src="script/common/tips-base.js"></script>
<script type="text/javascript" src="script/common/osa-box.js"></script>
<script type="text/javascript" src="script/monitor/website.js"> </script>
<?php include 'views/footer.php';?>
