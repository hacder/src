<?php include 'views/header.php';?>
<link rel="stylesheet" href="css/extend.css" type="text/css" />
<!--内容开始-->
<div class="main">
<!--左侧菜单开始-->
		<?php include 'views/snmp/snmpmenu.php';?>
<!--左侧菜单结束-->
<!--右侧内容开始-->
		<div class="mainright" id="mainright">
		<!--右侧title开始-->	
			<div class="currtitle">
				<div class="placing"><span>当前位置：</span><span>日常监控</span> <span>&gt;</span> <span>自动发现服务器设备</span></div>
			</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
			<div id="details_1" class="details_control">
				<div class="height10"></div>
				<div style="display:block;" class="info_div">
					<div class="height10"></div>
					<div class="info_prompt_img"></div>
					<div class="info_prompt1">
						<strong>该功能暂不开放！</strong><br>
						该功能正在规划中... ,这个版本暂不开放
					</div>
					<div class="height10"></div>
				</div>
			</div>
			<div class="height10"></div>

		<!--右侧content结束-->
		</div>
<!--右侧内容结束-->
</div>

<script type="text/javascript" src="script/common/base.js"> </script>
<link rel="stylesheet" href="css/osa-tips.css" type="text/css" />
<script type="text/javascript" src="script/common/tips-base.js"></script>
<script type="text/javascript" src="script/snmp/snmpset.js"></script>
<!--内容结束-->
<?php include 'views/footer.php';?>