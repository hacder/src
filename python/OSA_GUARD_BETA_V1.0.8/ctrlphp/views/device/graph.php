
<?php include 'views/header.php';?>
<link rel="stylesheet" href="css/extend.css" type="text/css" />
<!--内容开始-->
<div class="main">
<!--左侧菜单开始-->
		<div class="menu2">
			<p class="height10"></p>
			<p>
				<a class="menu2_title"><span>按机房查看</span></a>
				<a href="index.php?c=device&a=graphindex" class="menu2_title_sub curr_sub"><span>所有机房</span></a>
				<?php foreach ($roominfo as $room){?>
				<a href="index.php?c=device&a=graphindex&room=<?php echo $room['oRoomName'];?>" class="menu2_title_sub"><span><?php echo $room['oRoomName'];?></span></a>
				<?php }?>
			</p>
			<p class="height10"></p>
			<p>
				<a class="menu2_title"><span>高级功能</span></a>
				<a href="index.php?c=snmp&a=snmpset" class="menu2_title_sub curr_sub"><span>全局snmp采集配置</span></a>
				<a href="index.php?c=snmp&a=autosearch" class="menu2_title_sub"><span>自动发现服务器</span></a>
			</p>
			<p class="height10"></p>
			<p>
				<a class="menu2_title"><span>监控信息</span></a>
				<a href="index.php?c=monitor&a=monitorlist" class="menu2_title_sub curr_sub"><span>监控项目列表</span></a>
				<a href="index.php?c=monitor&a=itemlist" class="menu2_title_sub "><span>创建监控项目</span></a>
			</p>
			<p class="height10"></p>
		</div>
<!--左侧菜单结束-->
<!--右侧内容开始-->
		<div class="mainright" id="mainright">
		<!--右侧title开始-->	
			<div class="currtitle">
				<div class="placing"><span>当前位置：</span><span>日常监控</span> <span>&gt;</span> <span>服务器列表</span></div>
				<div class="seltitle">
					<div id="tab_t_1" class="tab_02" onclick="window.location='index.php?c=device&a=listindex'">列表模式</div>
					<div id="tab_t_2" class="tab_01" >图例模式</div>
				</div>
			</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
			<div id="details_1" class="details_control">
				<div class="height10"></div>
				<div class="action">
					<div class="height10"></div>
					<div class="btn-toolbar left">
						<div class="btn_green1 left"><a id="record-add"><span class="spanL">添加记录</span><span class="spanR"></span></a></div>
						<div class="btn_gray1 left"><a id="record-pause"><span class="spanL">暂停</span><span class="spanR"></span></a></div>
						<div class="btn_gray2 left"><a id="record-open"><span class="spanL">启用</span><span class="spanR"></span></a></div>
						<div class="btn_gray3 left"><a id="record-del"><span class="spanL">删除</span><span class="spanR"></span></a></div>
					</div>
					<div class="search-bar right" id="search" style="position:relative;">
						<input type="text" class="record_search input2" id="record-search" value="" style="float:right;" >
					</div>		
				</div>
				<div class="height10"></div>
			</div>
			<div class="height10"></div>
			<div class="graphic_list">
				  <p class="legend">图例：</p>
				  <div class="cutline">
				      <dl>
					      <dt><a href="index.php?c=device&a=graphindex&status=正常"><img src="images/2.gif" /></a></dt>
						  <dd><b>正常</b></dd>
					  </dl>
				      <dl>
					      <dt><a href="index.php?c=device&a=graphindex&status=失去响应"><img src="images/1.gif" /></a></dt>
						  <dd><b>失去响应</b></dd>
					  </dl>
				      <dl>
					      <dt><a href="index.php?c=device&a=graphindex&status=服务器异常"><img src="images/3.gif" /></a></dt>
						  <dd><b>服务器异常</b></dd>
					  </dl>
				      <dl>
					      <dt><a href="index.php?c=device&a=graphindex&status=其它异常"><img src="images/4.gif" /></a></dt>
						  <dd><b>其它异常</b></dd>
					  </dl>
				  </div>
				  <div class="clear"></div>
				  <div class="line"></div>
				  <div class="Illustrations ">
				  	<?php foreach ($info as $key){?>
				      <dl class="graph-unit">
					      <dt><?php osa_show_graph($key['oStatus']) ?></dt>
						  <dd>
						  	<span><input type="checkbox" value="<?php echo $key['id'];?>" class="graph-checkbox"/><input type="hidden" class="input-hide" value="<?php echo $key['oIpid'];?>"/></span>
						  	<span ><a href="index.php?c=paint&a=serverable&ipid=<?php echo $key['oIpid'];?>" title="图形中心"><?php echo $key['oIp'];?></a></span>
						  </dd>
						  <dd>
								<span class="actedit"><a href="index.php?c=device&a=editindex&id=<?php echo $key['id'];?>">编辑</a></span>
								<span class="actpause graph-pause" <?php echo $key['oIsStop']==1?"style='display:none;'":"";?>><a href="#">暂停</a></span>
								<span class="actpause graph-open" <?php echo $key['oIsStop']==1?"":"style='display:none;'";?>><a href="#">启用</a></span>
								<span class="actdel graph-del"><a href="#">删除</a></span>
						  </dd>
					  </dl>
					 <?php }?>
				  </div>
			</div>
			
			<div class="height10"></div>
		<!--右侧content结束-->
		</div>
<!--右侧内容结束-->
</div>
<!--内容结束-->
<script type="text/javascript" src="script/common/base.js"> </script>
<script type="text/javascript" src="script/device/common.js"></script>
<link rel="stylesheet" href="css/osa-tips.css" type="text/css" />
<script type="text/javascript" src="script/common/tips-base.js"></script>
<script type="text/javascript" src="script/device/graph.js"></script>
<script type="text/javascript">
	var typeArr = new Array();
	function initTypeArr(){
		var value = '';
		var arr = new Array();
		<?php foreach ($typeinfo as $type){?>
		value = "<?php echo $type['oTypeName'];?>";
		arr.push(value);
		<?php }?>
		return arr;
	}
	typeArr = initTypeArr();
</script>
<?php include 'views/footer.php';?>


<!-- 弹出窗 添加设备 -->
<div class="window window_add" id="device-pop" style="display:none;">
	<div class="window_title">
		<span class="window_text">添加设备</span>
		<input type="button" class="windbutton" id="close-pop"/>
	</div>
	<div class="window_con">
		<div>
			<label class="label5"><span class="red">*</span>设备名称：</label><input type="text" class="style17" id="devname-pop" />
			<label class="label5">加入分类？</label>
			<div id="div_sel2" style="float:left;width:116px;">
				<div class="select_box" style="z-index: 1; position: relative; width: 116px;">
					<input name="tag_input" class="tag_select tag_input" maxlength="8" value="请选择类型">
					<ul class="tag_options" style="position: absolute; z-index: 999; width: 116px; display: none;">
						<li class="open_selected tag_li">请选择类型</li>
						<?php foreach ($typeinfo as $type){?>
						<li class="open tag_li"><?php echo $type['oTypeName'];?></li>
						<?php }?>
					</ul>
				</div>
			</div>
		</div>
		<p class="light">设备名称可以为中文，字母，数字，下划线组成，比如：shanghai_192.168.1.5_osapub.com。
		<br>
		设备分类是快速搜索服务器的一个重要途径,可以为中文，字母，数字组成。
		</p>
		<p id="devname-tips" class="red light" style="padding-top:0px;"></p>
		<p>
			<label class="label5"><span class="red">*</span>IP地址：</label>
			<input type="text" class="style5" id="ipname-pop">
			<span id="ip-tips" class="red" style="margin-left:10px;"></span>
		</p>
		<p class="light">IP地址是用来管理的重要标识！</p>	
		<div class="window_end"></div>
		<div class="right">
			<input type="button" id="confirm-pop" class="updatebut specibut" value="确定" />
			<input type="button" id="cancel-pop" class="cancelbut specibut" value="取消" />
		</div>
	</div>
</div>