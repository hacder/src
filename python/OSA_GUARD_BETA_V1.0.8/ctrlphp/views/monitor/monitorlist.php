<?php include 'views/header.php';?>
<link rel="stylesheet" href="css/extend.css" type="text/css" />
<!--内容开始-->
<div class="main">
<!--左侧菜单开始-->
		<?php include 'views/monitor/menulist.php';?>
<!--左侧菜单结束-->
<!--右侧内容开始-->
		<div class="mainright" id="mainright">
		<!--右侧title开始-->	
			<div class="currtitle">
				<div class="placing"><span>当前位置：</span><span>日常监控</span> <span>&gt;</span> <span>监控项目列表</span></span></div>
			</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
			<div id="details_1" class="details_control">
				<div class="height10"></div>
				<div class="action">
					<div class="height10"></div>
					<div class="btn-toolbar left">
						<div class="btn_green1 left"><a href="index.php?c=monitor&a=itemlist"><span class="spanL">创建监控项目</span><span class="spanR"></span></a></div>
						<div class="btn_gray1 left"><a id="mon-pause"><span class="spanL">暂停</span><span class="spanR"></span></a></div>
						<div class="btn_gray2 left"><a id="mon-play"><span class="spanL">启用</span><span class="spanR"></span></a></div>
						<div class="btn_gray3 left"><a id="mon-del"><span class="spanL">删除</span><span class="spanR"></span></a></div>
					</div>
					<div class="search-bar right" id="search" style="position:relative;">
						
						<div class="" style="width:60px;float:right;">
							<div class="select_box" style="z-index: 1; position: relative; width: 60px;">
								<input name="tag_input" class="tag_select tag_input" maxlength="8" value="所有" style="height:26px;">
								<ul class="tag_options" style="position: absolute; z-index: 999; width: 60px; display: none;top:26px;">
									<li class="open_selected tag_li">所有</li>
									<li class="open tag_li">正常</li>
									<li class="open tag_li">异常</li>
								</ul>
							</div>
						</div>					
						<input type="text" class="record_search input2"  style="float:right;" id="mon-search"  value="<?php echo $search;?>" />
					</div>		
				</div>
				<div class="height10"></div>
			</div>
			<div id="list_ajax">
				<?php include 'views/monitor/monitorlist_ajax.php'?>
			</div>	
		<!--右侧content结束-->
		</div>
<!--右侧内容结束-->
</div>
<script type="text/javascript" src="script/common/base.js"> </script>
<link rel="stylesheet" href="css/osa-tips.css" type="text/css" />
<link rel="stylesheet" href="css/osa-timeset.css" type="text/css" />
<script type="text/javascript" src="script/common/osa-timeset.js"></script>
<script type="text/javascript" src="script/device/common.js"> </script>
<script type="text/javascript" src="script/common/tips-base.js"></script>
<script type="text/javascript" src="script/monitor/monitorlist.js"></script>
<script type="text/javascript" src="script/common/osa-timeset.js"></script>

<!--内容结束-->
<?php include 'views/footer.php';?>
