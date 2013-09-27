<?php include 'views/header.php';?>
<link rel="stylesheet" href="css/extend.css" type="text/css" />
<!--内容开始-->
<div class="main">
<!--左侧菜单开始-->
		<div class="menu2">
			<p class="height10"></p>
			<p>
				<a class="menu2_title"><span>按机房查看</span></a>
				<a href="index.php?c=device&a=listindex" class="menu2_title_sub curr_sub"><span>所有机房</span></a>
				<?php foreach ($roominfo as $rkey){?>
				<a href="index.php?c=device&a=listindex&room=<?php echo $rkey['oRoomName'];?>" class="menu2_title_sub"><span><?php echo $rkey['oRoomName'];?></span></a>
				<?php }?>
			</p>
			<p class="height10"></p>
			<p>
				<a class="menu2_title"><span>高级功能</span></a>
				<a href="index.php?c=snmp&a=snmpset" class="menu2_title_sub curr_sub"><span>全局snmp采集配置</span></a>
				<!-- <a href="index.php?c=snmp&a=autosearch" class="menu2_title_sub"><span>自动发现服务器</span></a> -->
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
					<div id="tab_t_1" class="tab_01" >列表模式</div>
					<div id="tab_t_2" class="tab_02" onclick="window.location='index.php?c=device&a=graphindex'">图例模式</div>
				</div>
			</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
			<div id="details_1" class="details_control">
				<div class="height10"></div>
				<div class="action">
					<div class="height10"></div>
					<div class="btn-toolbar left">
						<div class="btn_green1 left" id="record-add"><a class=""><span class="spanL">添加记录</span><span class="spanR"></span></a></div>
						<div class="btn_gray1 left" id="record-pause"><a class=""><span class="spanL">暂停</span><span class="spanR"></span></a></div>
						<div class="btn_gray2 left" id="record-open"><a class=""><span class="spanL">启用</span><span class="spanR"></span></a></div>
						<div class="btn_gray3 left" id="record-del"><a class=""><span class="spanL">删除</span><span class="spanR"></span></a></div>
					</div>
					<div class="search-bar right" id="search" style="position:relative;">
						<input type="text" class="record_search input2" id="record_search" value="" ><label id="more_select">更多高级选项》</label>
					</div>		
				</div>
				<div class="height10"></div>
				<div class="morecond_div" id="morecond_div" style="display:none;">
					<div class="height10"></div>
					<div class="label100">设备类型:</div>
					<div class="href1"><a class="type-select">全部类型</a></div>
					<?php foreach ($typeinfo as $type){?>
					<div class="href1 "><a class="type-select"><?php echo $type['oTypeName'];?></a></div>
					<?php }?>
					<div class="href1"><a href="#">更多类型>></a></div> 
					
					<div class="clear"></div>
					<div class="label100">热门标签:</div> 	
					<div class="href1"><a class="label-select">所有标签</a></div> 
					<?php foreach ($labelinfo as $label){?>
					<div class="href1"><a class="label-select"><?php echo $label['oLabelName'];?></a></div>
					<?php }?>
					<div class="clear"></div>
					<div class="label100">己选择条件:</div>
					<div class="href2"><a class="type-selected">全部类型</a></div>
					<div class="href2"><a class="label-selected">所有标签</a></div>
					<div class="recondi"><a class="clear-select">[清除所有条件]</a></div>
					<div class="height10"></div>
				</div>	
			</div>
			<div class="height10"></div>
			<div class="record_title" id="record_title">
				<div class="selectall"><span><input type="checkbox" class="sel_all_input" id="check_all"></span></div>
				<div class="rdname"><span>设备名称</span></div>
				<div class="rd_server"><span>设备类型</span></div>
				<div class="rdip"><span>IP地址</span></div>
				<div class="rdplace"><span>托管机房</span></div>
				<div class="rdbuy"><span>采购价格</span></div>
				<div class="rddeposit"><span>托管价格</span></div>
				<div class="rdaction"><span>操作</span></div>
			</div>
			<div class="rightcon_mid" id="rightcon_mid">
				<?php foreach ($info as $key){?>
				<div class="list-unit">
					<div class="record-list listli_1 list-li">
						<div class="selectall">
							<input type="checkbox" class="select_all sel_all_input" value="<?php echo $key['id'];?>" />
							<input type="hidden" class="select_hide" value="<?php echo $key['oIpid'];?>" />
						</div>
						<div class="rdname show-li"><?php echo $key['oDevName'];?></div>
						<div class="rd_server show-li"><?php echo $key['oTypeName'];?></div>
						<div class="rdip show-li"><?php echo $key['oIp'];?></div>
						<div class="rdplace show-li"><?php echo $key['oEngineRoom'];?></div>
						<div class="rdbuy show-li"><?php echo $key['oDevPrice'];?></div>
						<div class="rddeposit show-li"><?php echo $key['oDevTgPrice'];?></div>
						<div class="rdaction">	
							<div class="actdes">
								<a title="图形中心" href="index.php?c=paint&a=serverable&ipid=<?php echo $key['oIpid']?>">&nbsp;</a>
							</div>
							<div class="actprompt0">
								<a class="list-msg" title="补充信息">&nbsp;</a>
							</div>							
							<div class="actedit"><a title="编辑" href="index.php?c=device&a=editindex&id=<?php echo $key['id'];?>">EDIT</a></div>
							<div class="actpause">
								<a class="list-pause" <?php echo $key['oIsStop']==1?"style='display:none;'":"";?>>
								<img src="images/mon_pause.gif" /></a>
								<a class="list-open" <?php echo $key['oIsStop']==1?"":"style='display:none;'";?>>
								<img src="images/mon_play.gif" />
								</a>
								</div>
							<div class="actdel">
								<a class="list-del"><img src="images/mon_trash.gif" /></a>
							</div>					
						</div>
					</div>
					<div class="record-list listli_edit list-edit none">
						<div class="selectall"><span><input type="checkbox" class="sel_all_input" value="<?php echo $key['id'];?>"></span></div>
						<div class="rdname"><span><input value="<?php echo $key['oDevName'];?>"></span></div>
						<div class="rd_server" style="width:116px;">
							<div class="select_box" style="z-index: 1; position: relative; width: 116px;">
								<input name="tag_input" class="tag_select tag_input" maxlength="8" value="<?php echo $key['oTypeName'];?>">
								<ul class="tag_options" style="position: absolute; z-index: 999; width: 116px; display: none;">
									<li class="open tag_li">请选择或输入类型</li>
									<?php foreach ($typeinfo as $type){?>
									<li <?php echo $key['oTypeid']==$type['id']?'class="open_selected tag_li"':'class="open tag_li"';?>><?php echo $type['oTypeName'];?></li>
									<?php }?>
								</ul>
							</div>
						</div>
						<div class="rdip"><span class="ipvalue"><input value="<?php echo $key['oIp'];?>" readonly="readonly" /></span></div>
						<div class="rdplace" style="width:116px;">
							<div class="select_box" style="z-index: 1; position: relative; width: 116px;">
								<input name="tag_input" class="tag_select tag_input" maxlength="8" value="<?php echo $key['oEngineRoom'];?>">
								<ul class="tag_options" style="position: absolute; z-index: 999; width: 116px; display: none;">
									<li class="open tag_li">请选择或输入机房</li>
									<?php foreach ($roominfo as $room){?>
									<li <?php echo $key['oRoomid']==$room['id']?'class="open_selected tag_li"':'class="open tag_li"';?>><?php echo $room['oRoomName'];?></li>
									<?php }?>
								</ul>
							</div>
						</div>
						<div class="rdbuy"><span><input value="<?php echo $key['oDevPrice'];?>"></span></div>
						<div class="rddeposit"><span><input value="<?php echo $key['oDevTgPrice'];?>"></span></div>
						<div class="rdaction">
							<div class="actsave list-edit-save"><span>保存</span></div>
							<div class="actexit list-edit-exit"><span>取消</span></div>
						</div>
					</div>
				</div>
				<?php }?>		
			</div>
			
			<div class="rightcon_bottom" id="rightcon_bottom"></div>	
		<!--右侧content结束-->
		</div>
<!--右侧内容结束-->
</div>
<div class="list-unit none list-clone">
	<div class="record-list listli_edit list-edit">
		<div class="selectall"><span><input type="checkbox" class="sel_all_input"></span></div>
		<div class="rdname"><span><input value="请输入设备名称"></span></div>
		<div class="rd_server" style="width:116px;">
			<div class="select_box" style="z-index: 1; position: relative; width: 116px;">
				<input name="tag_input" class="tag_select tag_input" maxlength="8" value="请选择或输入类型">
				<ul class="tag_options" style="position: absolute; z-index: 999; width: 116px; display: none;">
					<li class="open_selected tag_li">请选择或输入类型</li>
					<?php foreach ($typeinfo as $type){?>
					<li class="open tag_li"><?php echo $type['oTypeName'];?></li>
					<?php }?>
				</ul>
			</div>
		</div>
		<div class="rdip"><span class="ipvalue"><input value="127.0.0.1"></span></div>
		<div class="rdplace" style="width:116px;">
			<div class="select_box" style="z-index: 1; position: relative; width: 116px;">
				<input name="tag_input" class="tag_select tag_input" maxlength="8" value="请选择或输入机房">
				<ul class="tag_options" style="position: absolute; z-index: 999; width: 116px; display: none;">
					<li class="open_selected tag_li">请选择或输入机房</li>
					<?php foreach ($roominfo as $room){?>
					<li class="open tag_li"><?php echo $room['oRoomName'];?></li>
					<?php }?>
				</ul>
			</div>
		</div>
		<div class="rdbuy"><span><input value="0"></span></div>
		<div class="rddeposit"><span><input value="0"></span></div>
		<div class="rdaction">
			<div class="actsave list-add-save"><span>保存</span></div>
			<div class="actexit list-add-exit"><span>取消</span></div>
		</div>
	</div>
</div>
<!--prompt resume-->
<div id="actprompt" class="actprompt_m2" style="display:none;">
<table border="0" cellpadding="0" cellspacing="0" style="" class="ctable">
  <tr>
    <td class="c1"></td>
    <td class="c0"></td>
    <td class="c2"></td>
  </tr>
  <tr>
    <td colspan="3" class="data">
业务描述：XX重要数据库，前台。<br>
托管机房：上海机房 上架时间：XXX<br>
设备标签：mysqn nginx web前台<br>
XXX 设备详情：cpu e5506 内存24GB<br>
硬盘1TB</td>
  </tr>
  <tr>
    <td class="c4"></td>
    <td class="c0"></td>
    <td class="c3"></td>
  </tr>
  <tr>
    <td colspan="3" class="c5">&nbsp;</td>
  </tr>
</table>
</div>
<script type="text/javascript" src="script/common/base.js"> </script>
<script type="text/javascript" src="script/device/common.js"></script>
<link rel="stylesheet" href="css/osa-tips.css" type="text/css" />
<link rel="stylesheet" href="css/osa-timeset.css" type="text/css" />
<link rel="stylesheet" href="css/osa-supplyinfo.css" type="text/css" />
<script type="text/javascript" src="script/common/osa-supplyinfo.js"></script>
<script type="text/javascript" src="script/common/tips-base.js"></script>
<script type="text/javascript" src="script/device/list.js"></script>
<script type="text/javascript" src="script/common/osa-info.js"></script>
<script src="script/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.8.20.custom.css" type="text/css" />
<script type="text/javascript">
	var typeArr = new Array();
	var roomArr = new Array();
	function initTypeArr(){
		var value = '';
		var arr = new Array();
		<?php foreach ($typeinfo as $type){?>
		value = "<?php echo $type['oTypeName'];?>";
		arr.push(value);
		<?php }?>
		return arr;
	}
	function initRoomArr(){
		var value = '';
		var arr = new Array();
		<?php foreach ($roominfo as $room){?>
		value = "<?php echo $room['oRoomName'];?>";
		arr.push(value);
		<?php }?>
		return arr;
	}
	typeArr = initTypeArr();
	roomArr = initRoomArr();
</script>
<!--内容结束-->
<?php include 'views/footer.php';?>