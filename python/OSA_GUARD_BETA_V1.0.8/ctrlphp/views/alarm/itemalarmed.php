<?php include 'views/header.php';?>
<link rel="stylesheet" href="css/extend.css" type="text/css" />

<!--内容开始-->
<div class="main">
<!--左侧菜单开始-->
		<?php include 'views/alarm/menu.php';?>
<!--左侧菜单结束-->
<!--右侧内容开始-->
		<div class="mainright" id="mainright">
		<!--右侧title开始-->	
			<div class="currtitle">
				<div class="placing"><span>当前位置：</span><span>日常监控</span> <span>&gt;</span><span>已发送告警通知(项目监控)</span></div>
			</div>	
			<div class="time_sel">
				<div class="left">
					<span class="dt_select" id="time-select"><a class="link1" >选择时间范围</a><?php if (!empty($stime)&&!empty($etime)){echo "(".$stime."至".date("Y-m-d",strtotime($etime)).")";}?></span>
				</div>
				<div class="right">
					&nbsp;<label class="icon_msg_fault">故障消息</label>
					&nbsp;<label class="icon_msg_notice">提醒消息</label>
					&nbsp;<label class="icon_msg_sys">系统消息</label>
					(<input type="checkbox" checked="checked" class="sys_info_input">
					<label for="sys_info_include">显示系统消息</label>)
				</div>
			</div>
			<div class="height10"></div>
			<div id="time-toggle" class="morecond_div" style="display:none;">
				<div class="height10"></div>
				<label class="label5">选择时间：</label><span class="sty_ip"><input type="text" value="<?php echo $stime;?>" id="datetime1" readonly="readonly" />-</span>
				<span class="sty_ip"><input type="text" value="<?php echo !empty($etime)?date("Y-m-d",strtotime($etime)):"";?>" id="datetime2" readonly="readonly" /> </span>
				<div class="btn_green1 left10">
				<a id="alarm-search"><span class="spanL">应用</span><span class="spanR"></span></a>
				</div>
				<div class="height10"></div>
			</div>
			<input type="hidden" value="<?php echo $url ;?>" id="hideUrl" />
		<!--右侧title结束-->
		<!--右侧content开始-->
		<div class="edit_list">
			<!--one-->
			<div id="record_title" class="record_title">
				<table cellspacing="0" class="totable">
					<thead>
						<tr>
							<th class="totd0" ><span>检查时间</span></th>
							<th class="totd4"><span>监控项目</span></th>
							<th class="totd5"><span>所在域/服务器</span></th>
							<th class="totd6"><span>消息内容</span></th>
						</tr>
					</thead>
				</table>
			</div>
			<div class="rightcon_mid">
				<?php if(!empty($alarminfo['today'])){?>
				<div class="totitle"><b>今天</b> (<span class="newfd underline"><?php echo count($alarminfo['today']);?></span>)</div>
				<?php foreach ($alarminfo['today'] as $data){?>
				<div class="toarea listli_1">
					<table cellspacing="0" class="totable">
						<tbody>						
						<!--	<tr <?php var_dump($data['oAlarmLevel']);  var_dump($oAlarmLevel); if ($data['oAlarmLevel'] == 1){?> style="background-color:#fff;" <?php }?>> -->
							<tr>
								<td class="totd1"><?php osa_alarm_show_graph($data['oAlarmLevel']);?></td>
								<td class="totd3"><div><?php echo $data['oAlarmTime'];?></div></td>
								<td class="totd4"><div><a href="index.php?c=paint&a=distribution&itemid=<?php echo $data['id'];?>&type=<?php echo $data['oItemType'];?>"><?php echo $data['oItemName'];?></a></div></td>
								<td class="totd5"><div><?php echo eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="\1" target="_blank">\1</a>',$data['oItemObject']);?></div></td>
								<td class="totd6"><div><?php echo $data['oAlarmText'];?></div></td>
							</tr>
						</tbody>
					</table>
				</div>
				<?php } }?>
				<?php if(!empty($alarminfo['yesterday'])){?>
				<div class="totitle"><b>昨天</b> (<span class="newfd underline"><?php echo count($alarminfo['yesterday']);?></span>)</div>
				<?php foreach ($alarminfo['yesterday'] as $data){?>
				<div class="toarea listli_1">
					<table cellspacing="0" class="totable">
						<tbody>
							<tr>
								<td class="totd1"><?php osa_alarm_show_graph($data['oAlarmLevel']);?></td>
								<td class="totd3"><div><?php echo $data['oAlarmTime'];?></div></td>
								<td class="totd4"><div><a href="index.php?c=paint&a=distribution&itemid=<?php echo $data['id'];?>&type=<?php echo $data['oItemType'];?>"><?php echo $data['oItemName'];?></a></div></td>
								<td class="totd5"><div><?php echo eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="\1" target="_blank">\1</a>',$data['oItemObject']);?></div></td>
								<td class="totd6"><div><?php echo $data['oAlarmText'];?></div></td>
							</tr>
						</tbody>
					</table>
				</div>
				<?php } }?>
				<?php if(!empty($alarminfo['earlier'])){?>
				<div class="totitle"><b>更早</b> (<span class="newfd underline"><?php echo count($alarminfo['earlier']);?></span>)</div>
				<?php foreach ($alarminfo['earlier'] as $data){?>
				<div class="toarea listli_1">
					<table cellspacing="0" class="totable">
						<tbody>
							<tr>
								<td class="totd1"><?php osa_alarm_show_graph($data['oAlarmLevel']);?></td>
								<td class="totd3"><div><?php echo $data['oAlarmTime'];?></div></td>
								<td class="totd4"><div><a href="index.php?c=paint&a=distribution&itemid=<?php echo $data['id'];?>&type=<?php echo $data['oItemType'];?>"><?php echo $data['oItemName'];?></a></div></td>
								<td class="totd5"><div><?php echo eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="\1" target="_blank">\1</a>',$data['oItemObject']);?></div></td>
								<td class="totd6"><div><?php echo $data['oAlarmText'];?></div></td>
							</tr>
						</tbody>
					</table>
				</div>
				<?php } }?>
			</div>
			<div class="rightcon_bottom"></div>
			<!--one-->
		</div>
		<div class="page">
			<div class="pageL">
			  <label>每页显示数量</label>
				<div class="page_sel">
					<div class="select_box" style="z-index: 1; position: relative;">
						<input id="page_input" name="tag_input" class="tag_select tag_input" maxlength="8" value="<?php echo isset($_SESSION['pagenum'])?$_SESSION['pagenum']:10;?>">
						<ul class="tag_options" style="position: absolute; z-index: 999;width:50px;top:23px; display: none;">
							<li class="<?php echo $_SESSION['pagenum']==10?'open_selected':'open';?> tag_li page_li">10</li>
							<li class="<?php echo $_SESSION['pagenum']==20?'open_selected':'open';?> tag_li page_li">20</li>
							<li class="<?php echo $_SESSION['pagenum']==50?'open_selected':'open';?> tag_li page_li">50</li>
						</ul>
					</div>
				</div>
			</div>
			<?php echo $page;?>
		</div>

		<!--右侧content结束-->
		</div>
<!--右侧内容结束-->
</div>
<!--内容结束-->
<script type="text/javascript" src="script/common/base.js"> </script>
<link rel="stylesheet" href="css/osa-tips.css" type="text/css" />
<script type="text/javascript" src="script/device/common.js"> </script>
<script type="text/javascript" src="script/common/tips-base.js"></script>
<script type="text/javascript" src="script/alarm/alarmlist.js"></script>
<script src="script/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.8.20.custom.css" type="text/css" />
<!--内容结束-->
<?php include 'views/footer.php';?>
