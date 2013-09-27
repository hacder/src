<?php include 'views/header.php';?>
<link rel="stylesheet" href="css/extend.css" type="text/css" />
<!--内容开始-->
<div class="main">
<!--左侧菜单开始-->
		<?php include 'views/account/menu.php';?>
<!--左侧菜单结束-->
<!--右侧内容开始-->
		<div class="mainright" id="mainright">
		<!--右侧title开始-->	
			<div class="currtitle">
				<div class="placing"><span>当前位置：</span><span>账户中心</span> <span>&gt;</span> <span>用户列表</span></div>
			</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
			<div class="edit_list">
			<!--one-->
				<div class="rightcon_title">用户列表</div>
				<div class="rightcon_mid">
					<div id="details_1" class="details_control">
						<div class="height10"></div>
						<div class="action">
							<div class="height10"></div>
							<div class="btn-toolbar left">
								<div class="btn_green1 left"><a href="index.php?c=account&a=useradd"><span class="spanL">创建用户</span><span class="spanR"></span></a></div>
								<div class="btn_gray1 left"><a id="user-pause"><span class="spanL">暂停</span><span class="spanR"></span></a></div>
								<div class="btn_gray2 left"><a id="user-open"><span class="spanL">启用</span><span class="spanR"></span></a></div>
								<div class="btn_gray3 left"><a id="user-del"><span class="spanL">删除</span><span class="spanR"></span></a></div>
							</div>
							<div class="search-bar right" id="search" style="position:relative;">
								<input type="text" class="record_search input2"  style="float:right;" id="mon-search"  value="<?php echo $search;?>" />
							</div>		
						</div>
					</div>
					<div class="clear"></div>
					<div class="table_setup" id="list_ajax">
					<?php include 'views/account/user_ajax.php';?>
					</div>
				</div>
				<div class="rightcon_bottom"></div>
			<!--one-->
			</div>			
			<div class="height10"></div>
		<!--右侧content结束-->
		</div>
<!--右侧内容结束-->
</div>
<script type="text/javascript" src="script/common/base.js"> </script>
<link rel="stylesheet" href="css/osa-tips.css" type="text/css" />
<script type="text/javascript" src="script/common/tips-base.js"></script>
<script type="text/javascript" src="script/device/common.js"> </script>
<script type="text/javascript" src="script/account/userlist.js"> </script>
<!--内容结束-->
<?php include 'views/footer.php';?>