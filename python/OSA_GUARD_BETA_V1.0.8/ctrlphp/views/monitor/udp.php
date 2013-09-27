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
				<div class="rightcon_title">报警项目信息</div>
				<div class="rightcon_mid">
					<label class="label_more"><span class="red">*</span>监控项目名称：</label>
					<div class="left"><input type="text" class="style5" placeholder="请输入监控项目名称" id="itemname" /><span class="tips" style="margin:6px;"></span></div>
					<div class="height10"></div>
					<label class="label_more"><span class="red">*</span>域名或者IP：<input type="hidden" id="itemip"  /></label>
					<div class="btn_green1 left"><a id="server-search"><span class="spanL">查询服务器</span><span class="spanR"></span></a></div>
					<div class="clear"></div> 
					<label class="label_more">已选择的对象：</label>
					<div class="left125" id="show_resultip">
						
					</div>
					<div class="clear"></div> 
					<label class="label_more"></label>
					<div class="light0">您可以选择当前己有服务器，或者录入指定的域名或者IP地址。没有搜索到指定服务器？</div>
					<div class="btn_gray3 left10"><a href="index.php?c=device&a=addindex"><span class="spanL">马上添加一台新服务器。</span><span class="spanR"></span></a></div>
					<div class="clear"></div> 
					<label class="label_more"><span class="red">*</span>UDP端口号：</label>
					<div class="left"><input type="text" class="style5" placeholder="请输入端口号" id="udp_port"><span class="tips" style="margin:6px;"></span></div>
					<p class="light0"><label class="label_more">&nbsp;</label>多个端口号使用','分隔，例如：80,3306。</p>
					<div class="height10"></div>
					<div class="clear"></div> 
				</div>
				<div class="rightcon_bottom"></div>
			<!--one-->
			<?php include 'views/monitor/common.php';?>
		</div>

		<div class="height10"></div>
		<div class="edit_submit">
			<div class="btn_green" style=""><a id="udp-save"><span class="spanL">保存监控项目</span><span class="spanR"></span></a></div>
			<div style="" class="btn_cancel"><a href="javascript:history.go(-1)"><span class="spanL">取消返回</span><span class="spanR"></span></a></div>
		</div>

		<!--右侧content结束-->
		</div>
<!--右侧内容结束-->
</div>
<!--内容结束-->
<script type="text/javascript" src="script/common/base.js"> </script>
<link rel="stylesheet" href="css/osa-tips.css" type="text/css" />
<link rel="stylesheet" href="css/osa-box.css" type="text/css" />
<script type="text/javascript" src="script/common/tips-base.js"></script>
<script type="text/javascript" src="script/common/osa-box.js"></script>
<script type="text/javascript" src="script/monitor/udp.js"> </script>
<?php include 'views/footer.php';?>
