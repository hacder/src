<?php include 'views/header.php';?>
<link rel="stylesheet" href="css/extend.css" type="text/css" />
<!--内容开始-->
<div class="main">
<!--左侧菜单开始-->
		<?php include 'views/filecenter/menu.php';?>
<!--左侧菜单结束-->
<!--右侧内容开始-->
		<div class="mainright" id="mainright">
		<!--右侧title开始-->	
			<div class="currtitle">
				<div class="placing"><span>当前位置：</span><span>账户中心</span> <span>&gt;</span> <span>文件管理</span></div>
			</div>
			<div id="details_1" class="details_control">
				<div class="height10"></div>
				<div class="action">
					<div class="height10"></div>
					<div class="btn-toolbar left">
						<div class="btn_green1 left"><a id="show-upload"><span class="spanL">文件上传</span><span class="spanR"></span></a></div>
						<div class="btn_gray3 left"><a id="file-del"><span class="spanL">删除</span><span class="spanR"></span></a></div>
					</div>
					<div class="search-bar right" id="search" style="position:relative;">
						<input type="text" class="record_search input2"  style="float:right;" id="mon-search"  value="<?php echo $search;?>" />
					</div>		
				</div>
			</div>
			<div  id="list_ajax">
			<?php include 'views/filecenter/file_ajax.php';?>
			</div>		
			<div class="height10"></div>
		<!--右侧content结束-->
		</div>
<!--右侧内容结束-->
</div>
<script type="text/javascript" src="script/common/base.js"> </script>
<link rel="stylesheet" href="css/osa-tips.css" type="text/css" />
<script type="text/javascript" src="script/common/tips-base.js"></script>
<script type="text/javascript" src="script/filecenter/filelist.js"> </script>
<script type="text/javascript" src="script/device/common.js"> </script>
<!-- 上传相关 -->
<script type="text/javascript" src="script/swfupload/swfupload.js"></script>
<script type="text/javascript" src="script/osaUpload/swfupload.swfobject.js"></script>
<script type="text/javascript" src="script/osaUpload/swfupload.queue.js"></script>
<script type="text/javascript" src="script/osaUpload/fileprogress.js"></script>
<script type="text/javascript" src="script/osaUpload/handlers.js"></script>
<link rel="stylesheet" href="css/osa-upload.css" type="text/css" />
<script type="text/javascript" src="script/common/osa-upload.js"></script>
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