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
			<div class="placing"><span>当前位置：</span><span>日常监控</span> <span>&gt;</span> <span>创建监控项目</span></span></div>
		</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
		<div class="edit_list">
			<!--one-->
			<?php $itemconfig = jsonDecode($itemdata[0]['oItemConfig']);?>
			<div class="rightcon_title">报警项目信息</div>
			<div class="rightcon_mid">
				<label class="label_more"><span class="red">*</span>监控项目名称：</label>
				<div class="left"><input type="text" class="style5" placeholder="请输入监控项目名称" id="itemname" value="<?php echo $itemdata[0]['oItemName'];?>"/><span class="tips" style="margin-left:6px;"></span></div>
				<div class="height10"></div>
				<label class="label_more"><span class="red">*</span>FTP主机：</label>
				<div class="left"><input type="text" class="style5" placeholder="请输入FTP服务的主机域名或者IP" id="itemurl" value="<?php echo $itemdata[0]['oItemObject'];?>" readonly="readonly"/><span class="tips" style="margin-left:6px;"></span></div>
				<div class="clear"></div> 
				<label class="label_more">&nbsp;</label>
				<div class="light0">请填写提供FTP服务的主机域名或者IP地址，比如：www.osapub.com 或者 10.0.1.10。</div>
				<div class="clear"></div>
				<label class="label_more"><span class="red">*</span>FTP端口：</label>
				<div class="left"><input type="text" class="style5" value="<?php echo $itemconfig['port'];?>" id="ftpport"/><span class="tips" style="margin-left:6px;"></span></div>
				<div class="clear"></div> 
				<label class="label_more">&nbsp;</label>
				<div class="light0">请填写提供FTP服务的端口，默认为21。</div>
				<div class="clear"></div> 
				<label class="label_more">FTP身份验证选项：</label>
				<div class="left">
					<input type="radio" class="radio1 ftp-radio" name="ftp-radio" value="0" <?php echo $itemconfig['defaults']==0?"checked='checked'":"";?>/>
					<label class="label_c2">需要身份验证</label>
					<input type="radio" class="radio1 ftp-radio" name="ftp-radio" value="1" <?php echo $itemconfig['defaults']==1?"checked='checked'":"";?>/>
					<label class="label_c2">匿名登录</label>
				</div>
				<div class="clear"></div> 
				<div class="ftp-identity" id="ftp-identity" style="display:<?php echo $itemconfig['defaults']==0?"block":"none";?>;">
					<label class="label_more"></label>
					<div class="light0">请选择FTP身份验证方式。</div>
					<div class="clear"></div>
					
					<label class="label_more"><span class="red">*</span>FTP用户名：</label>
					<div class="left"><input type="text" class="style5" id="ftpuser" value="<?php echo $itemconfig['ftpuser'];?>" /><span class="tips" style="margin-left:6px;"></span></div>
					<div class="clear"></div> 
					<label class="label_more">&nbsp;</label>
					<div class="light0">请填写FTP用户名，匿名FTP则不需要填写。</div>
					
					<div class="clear"></div>
					<label class="label_more"><span class="red">*</span>FTP密码：</label>
					<div class="left"><input type="password" class="style5" id="ftppass" value="<?php echo $itemconfig['ftppass'];?>"/><span class="tips" style="margin-left:6px;"></span></div>
					<div class="clear"></div> 
					<label class="label_more">&nbsp;</label>
					<div class="light0">请填写FTP密码，匿名FTP则不需要填写。</div>
					<div class="clear"></div> 
				</div>
			</div>
			<input type="hidden" value="<?php echo $itemid;?>" id="edit-itemid" />
			<div class="rightcon_bottom"></div>
			<!--one-->
			<?php include 'views/monitor/edit/common.php';?>
		</div>	
		<div class="height10"></div>
		<div class="edit_submit">
			<div class="btn_green" style=""><a id="ftp-edit"><span class="spanL">编辑监控项目</span><span class="spanR"></span></a></div>
			<div style="" class="btn_cancel"><a href="javascript:history.go(-1)"><span class="spanL">取消返回</span><span class="spanR"></span></a></div>
		</div>

	<!--右侧content结束-->
	</div>
<!--右侧内容结束-->
</div>
<!--内容结束-->
<script type="text/javascript" src="script/common/base.js"> </script>
<script type="text/javascript" src="script/device/common.js"></script>
<link rel="stylesheet" href="css/osa-tips.css" type="text/css" />
<link rel="stylesheet" href="css/osa-box.css" type="text/css" />
<script type="text/javascript" src="script/common/tips-base.js"></script>
<script type="text/javascript" src="script/common/osa-box.js"></script>
<script type="text/javascript" src="script/monitor/ftp.js"> </script>

<?php include 'views/footer.php';?>
