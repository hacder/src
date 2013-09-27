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
			<div class="rightcon_title">报警项目信息</div>
			<div class="rightcon_mid">
				<label class="label_more"><span class="red">*</span>监控项目名称：</label>
				<div class="left"><input type="text" class="style5" placeholder="请输入监控项目名称" id="itemname" /><span class="tips" style="margin:6px;"></span></div>
				<div class="height10"></div>
				<label class="label_more"><span class="red">*</span>域名或者IP：<input type="hidden" id="itemip" /></label>
				<div class="btn_green1 left"><a id="server-search"><span class="spanL">查询服务器</span><span class="spanR"></span></a></div>
				<div class="clear"></div> 
				<label class="label_more">已选择的对象：</label>
				<div class="left125" id="show_resultip">
									
				</div>
				<div class="height10"></div>
				<label class="label_more"><span class="red">*</span>SNMP监控项目：</label>
				<div class="left">
					<div class="btn_green1 left">
						<a class="custom-li" name="loadstat"><span class="spanL">负载状态</span><span class="spanR"></span></a>
					</div>
					<div class="btn_gray1 left">
						<a class="custom-li" name="network"><span class="spanL">网卡流量</span><span class="spanR"></span></a>
					</div>
					<div class="btn_gray1 left">
						<a class="custom-li" name="memory"><span class="spanL">内存使用率</span><span class="spanR"></span></a>
					</div>
					<div class="btn_gray1 left">
						<a class="custom-li" name="diskstat"><span class="spanL">磁盘使用率</span><span class="spanR"></span></a>
					</div>
					<div class="btn_gray1 left">
						<a class="custom-li" name="cpu"><span class="spanL">CPU使用率</span><span class="spanR"></span></a>
					</div>
					<div class="btn_gray1 left">
						<a class="custom-li" name="logins"><span class="spanL">用户登录数</span><span class="spanR"></span></a>
					</div>
					<div class="btn_gray1 left">
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
					<div class="col_4_con loadstat-con" >
						  <div class="col_con4_list1 left"  >
							<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
								<input name="tag_input" class="tag_select tag_input custom_norm" maxlength="20" readonly="readonly" value="最近1分钟平均负载">
								<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
									<li class="open_selected tag_li">最近1分钟平均负载</li>
									<li class="open tag_li">最近5分钟平均负载</li>
									<li class="open tag_li">最近15分钟平均负载</li>
								</ul>
							</div>
						  </div>
						  <div class="col_con4_list2 left">
							<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
								<input name="tag_input" class="tag_select tag_input" maxlength="20" readonly="readonly" value="大于">
							</div>
						  </div>
						  <div class="col_con4_list3 left" >
							<input type="text"  value="" class="threshold left"><span class="left threshold-tips"></span>
						  </div>
						  <div class="col_con4_list4 left" >
							<div class="btn_gray3">
								<a class="del-options"><span class="spanL">删除</span><span class="spanR"></span></a>
							</div>
						  </div>
					</div>
					
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
			<div class="rightcon_bottom"></div>
			<!--one-->
			<?php include 'views/monitor/common.php';?>
		</div>	

		<div class="height10"></div>
		<div class="edit_submit">
			<div class="btn_green" style=""><a id="custom-save"><span class="spanL">保存监控项目</span><span class="spanR"></span></a></div>
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

<script type="text/javascript" src="script/monitor/custom.js"> </script>

<?php include 'views/footer.php';?>
