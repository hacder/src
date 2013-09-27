<?php include 'views/header.php';?>
<link rel="stylesheet" href="css/extend.css" type="text/css" />
<!--内容开始-->
<div class="main">
<!--左侧菜单开始-->
		<div class="menu2">
			<p class="height10"></p>
			<p>
				<a class="menu2_title"><span>设备信息</span></a>
				<a href="index.php?c=device&a=listindex" class="menu2_title_sub curr_sub"><span>服务器列表</span></a>
				<a href="index.php?c=snmp&a=snmpset" class="menu2_title_sub"><span>全局snmp采集配置</span></a>
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
				<div class="placing"><span>当前位置：</span><span>设备管理</span> <span>&gt;</span> <span>编辑设备</span></div>
			</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
			<div class="edit_list">
			<!--one-->
					<div class="rightcon_title">编辑设备</div>
					<div class="rightcon_mid">	
						<div class="edit_con">
							 <div>
								<label class="label5">*设备名称：</label><input type="text" class="style17" id="devname" value="<?php echo $devinfo[0]['oDevName'];?>"/>
								<label class="label5">加入分类&nbsp;</label>
								<div id="div_sel2" style="float:left;">
									<div class="select_box" style="z-index: 1; position: relative; width: 116px;">
										<input id="typename" name="tag_input" class="tag_select tag_input" maxlength="8" value="<?php echo empty($devinfo[0]['oTypeName'])?'请选择类型':$devinfo[0]['oTypeName'];?>">
										<ul class="tag_options" style="position: absolute; z-index: 999; width: 116px; display: none;">
											<li <?php echo empty($devinfo[0]['oTypeName'])?'class="open_selected tag_li"':'class="open tag_li"';?> >请选择类型</li>
											<?php foreach ($typeinfo as $type){?>
											<li <?php echo $devinfo[0]['oTypeid']==$type['id']?'class="open_selected tag_li"':'class="open tag_li"';?>><?php echo $type['oTypeName'];?></li>
											<?php }?>
										</ul>
									</div>
								</div>
								<input id="hide_id" type="hidden" value="<?php echo $devinfo[0]['id'];?>">
							 </div>
							 <p class="light">设备名称可以为中文，字母，数字，下划线组成，比如：shanghai_192.168.1.5_osapub.com。
							 <br>
							 设备分类是快速搜索服务器的一个重要途径,可以为中文，字母，数字组成。</p>
							<p id="devname_tips" class="light" style="padding-top:0px;"></p>
							<div class="clear"></div>
								<label class="label5">*IP地址：</label><input type="text" class="style5" id="ipname" readonly="true" value="<?php echo $devinfo[0]['oIp'];?>"> 
								<span style="margin-left:10px;float:left;padding-top:5px;" id="ip_tips"></span>
							<div class="clear"></div>
							<p class="light">IP地址是用来管理的重要标识！</p> 
						</div>
					</div>
					<div class="rightcon_bottom"></div>
			<!--one-->
			<!--two-->
					<div class="rightcon_title">高级选项</div>
					<div class="rightcon_mid">			
						<div class="edit_con">
							<label class="label5">业务描述：</label><input type="text" class="style5" id="workdes" value="<?php echo $devinfo[0]['oWorkDes'];?>"/>
							<div class="clear"></div>
							<p class="light">可以用一句话介绍该服务器的用途，支撑的业务等,业务描述会作为告警信息的一部分！</p>
							<div class="clear"></div>
							<label class="label5">托管机房：</label>
							<div id="div_sel" style="float:left;">
								<div class="select_box" style="z-index: 1; position: relative; width: 116px;">
									<input id="roomname" name="tag_input" class="tag_select tag_input" maxlength="8" value="<?php echo empty($devinfo[0]['oEngineRoom'])?'请选择机房':$devinfo[0]['oEngineRoom'];?>">
									<ul class="tag_options" style="position: absolute; z-index: 999; width: 116px; display: none;">
										<li <?php echo empty($devinfo[0]['oEngineRoom'])?'class="open_selected tag_li"':'class="open tag_li"';?>>请选择机房</li>
										<?php foreach ($roominfo as $room){?>
										<li <?php echo $devinfo[0]['oRoomid']==$room['id']?'class="open_selected tag_li"':'class="open tag_li"';?>><?php echo $room['oRoomName'];?></li>
										<?php }?>
									</ul>
								</div>
							</div>
							<label class="label3">或者 请输入新机房名称：</label><input type="text" class="style15" id="input_devroom"> 
							<div class="clear"></div>
							<p class="light">托管机房是方便您管理服务器的快速入口和通道！</p>
							<div class="clear"></div>
							<label class="label5">上架时间：</label>
							<input type="text" class="style5" id="shelvetime" readonly="true" value="<?php echo $devinfo[0]['oShelveTime'];?>" />
							<div class="clear"></div>
							<p class="light">上架时间用来记录服务器最初上架的时间，方便管理。</p>
							<div class="clear"></div>
							<label class="label5">设备标签：</label><input type="text" id="devlabel" class="style5" value="<?php echo $devinfo[0]['oDevLabel'];?>" />
							<div class="clear"></div>
							<p class="light">标签可以用来更好的搜索服务器！</p>
							<div class="clear2"></div>
							<label class="label5">采购价格：</label><input type="text" class="style5" id="devprice" value="<?php echo $devinfo[0]['oDevPrice'];?>" />
							<div class="clear"></div>
							<p class="light">采购价格是指在采购设备时所花费的资金，单位：元,填写后可以进行资源预算分析。</p>
							<div class="clear"></div>
							<label class="label5">托管价格：</label><input type="text" class="style5" id="tgprice" value="<?php echo $devinfo[0]['oDevTgPrice'];?>" />
							<div class="clear"></div>
							<p class="light">托管价格是指在每月设备托管产生的费用，单位：元，例如：300元/月</p>
							<div class="clear"></div>
							<label class="label5">设备详情：</label><textarea class="textarea2" id="devdetail"><?php echo $devinfo[0]['oDevDetail'];?></textarea>
							<div class="clear"></div>
							<p class="light">可用于记录设备详细情况，比如CPU、内存、采购联系人等。</p>
						</div>
					</div>
					<div class="rightcon_bottom"></div>
			<!--two-->
			</div>
			
			<div class="edit_submit">
				<div style="" class="btn_green" id="submit"><a class=""><span class="spanL">确认添加</span><span class="spanR"></span></a></div>
				<div style="" class="btn_cancel"><a href="javascript:history.go(-1)"><span class="spanL">取消返回</span><span class="spanR"></span></a></div>
			</div>
			<div class="height10"></div>

		<!--右侧content结束-->
		</div>
<!--右侧内容结束-->
</div>
<!--内容结束-->
<script type="text/javascript" src="script/common/base.js"> </script>
<link rel="stylesheet" href="css/osa-tips.css" type="text/css" />
<script type="text/javascript" src="script/common/tips-base.js"></script>
<script type="text/javascript" src="script/device/edit.js"></script>
<script type="text/javascript" src="script/device/common.js"></script>
<script src="script/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.8.20.custom.css" type="text/css" />
<?php include 'views/footer.php';?>
