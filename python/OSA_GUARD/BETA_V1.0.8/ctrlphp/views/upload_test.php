<?php include 'views/header.php';?>
<link rel="stylesheet" href="css/extend.css" type="text/css" />
<!--内容开始-->
<div class="main">
<!--左侧菜单开始-->
		<div class="menu2">
			<p class="height10"></p>
			<p>
			<a href="#" class="menu2_title"><span>设备信息</span></a>
			<a href="#" class="menu2_title_sub curr_sub"><span>服务器列表</span></a>
			<a href="#" class="menu2_title_sub"><span>全局snmp采集配置</span></a>
			</p>
			<p class="height10"></p>
			<p>
			<a href="#" class="menu2_title"><span>监控信息</span></a>
			<a href="#" class="menu2_title_sub curr_sub"><span>创建监控项目</span></a>
			<a href="#" class="menu2_title_sub"><span>监控项目列表</span></a>
			</p>
			<p class="height10"></p>
			<p>
			<a href="#" class="menu2_title"><span>告警信息</span></a>
			<a href="#" class="menu2_title_sub curr_sub"><span>已发送告警通知</span></a>
			<a href="#" class="menu2_title_sub"><span>未发送告警通知</span></a>
			<a href="#" class="menu2_title_sub"><span>告警通知设定</span></a>
			</p>
			<p class="height10"></p>
		</div>
<!--左侧菜单结束-->
<!--右侧内容开始-->
		<div class="mainright" id="mainright">
		<!--右侧title开始-->	
			<div class="currtitle">
				<div class="placing"><span>当前位置：</span><span>账户中心</span> <span>&gt;</span> <span>角色列表</span></div>
			</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
			<div class="edit_list">
			<!--one-->
				 <input type="text" id="upload-file" /><input type="button" id="show-upload"  value="文件上传"/>
			<!--one-->
			</div>

		<!--右侧content结束-->
		</div>
<!--右侧内容结束-->
</div>

<script type="text/javascript" src="script/common/base.js"> </script>
<script type="text/javascript" src="script/swfupload/swfupload.js"></script>
<script type="text/javascript" src="script/osaUpload/swfupload.swfobject.js"></script>
<script type="text/javascript" src="script/osaUpload/swfupload.queue.js"></script>
<script type="text/javascript" src="script/osaUpload/fileprogress.js"></script>
<script type="text/javascript" src="script/osaUpload/handlers.js"></script>
<link rel="stylesheet" href="css/osa-upload.css" type="text/css" />
<script type="text/javascript" src="script/common/osa-upload.js"></script>
<link rel="stylesheet" href="css/osa-tips.css" type="text/css" />
<script type="text/javascript" src="script/common/tips-base.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	
	$("#show-upload").click(function(){
		var session_id = "<?php echo session_id();?>";
		osaFileUpload(session_id);
		//tipsAlert('测试');
	});

});
</script>
<!--内容结束-->
<?php include 'views/footer.php';?>