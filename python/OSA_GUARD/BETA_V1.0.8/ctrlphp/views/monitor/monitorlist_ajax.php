<div class="height10"></div>
<div class="record_title" id="record_title">
	<div class="selectall"><span><input type="checkbox" class="sel_all_input" id="checkall"/></span></div>
	<div class="rdname3"><span>项目名称</span></div>
	<div class="rdip3"><span>所在域/服务器</span></div>	
	<div class="rdtype3"><span>监控类型</span></div>
	<div class="rdper3"><span>可用率</span></div>
	<div class="rdrate3"><span>监控频率</span></div>
	<div class="rdrelati3"><span>关联指令</span></div>
	<div class="rdaction3"><span>操作</span></div>
</div>
<div class="rightcon_mid" id="rightcon_mid" style="overflow:hidden;">

	<?php foreach ($iteminfo as $key){?>
	<div class="record-list listli_1" >
		<div class="selectall"><span><input type="checkbox" class="sel_all_input check_one" value="<?php echo $key['id'];?>" /></span></div>
		<div class="rdname3">
			<span>
				<i <?php echo $key['oStatus'] ==0?"class='stateimg1' title='异常'":"class='stateimg2' title='正常'"; ?>"></i>
				<a title="图形中心" href="index.php?c=paint&a=distribution&itemid=<?php echo $key['id'];?>&type=<?php echo $key['oItemType'];?> "><?php echo $key['oItemName'];?></a>
			</span>
		</div>
		<div class="rdip3"><span><?php echo eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="\1" target="_blank">\1</a>', $key['oItemObject']);?></span></div>	
		<div class="rdtype3"><span><?php echo $key['oItemType'];?></span></div>
		<div class="rdper3"><span><?php echo $itemrate[$key['id']];?></span></div>
		<div class="rdrate3"><span class="ratevalue" rate="<?php echo $key['oCheckRate'];?>"><?php osa_monitor_timeset($key['oCheckRate']);?></span><i title='快速修改监控频率' class="rateimg pointer">&nbsp;</i></div>
		<div class="rdrelati3"><span class="ratevalue"><a href="#" title="需要此功能请和OSA开发团队取得联系！">关联指令</a><i class="relateimg">&nbsp;</i></span></div>
		<div class="rdaction3">
			<a href="index.php?c=paint&a=distribution&itemid=<?php echo $key['id'];?>&type=<?php echo $key['oItemType'];?> "><img src="images/graph_zoom.gif" title="图形中心"/></a>
			<a href="index.php?c=monitor&a=monitoredit&itemid=<?php echo $key['id'];?>&type=<?php echo $key['oItemType'];?>"><img src="images/mon_edit.gif" title="编辑" /></a>
			<a class="">
				<img class="mon-pause" src="images/mon_pause.gif" title="暂停" <?php if($key['oIsStop']==1){echo "style='display:none;'";}?> />
				<img class="mon-play" src="images/mon_play.gif" title="启动" <?php if($key['oIsStop']==0){echo "style='display:none;'";}?> />
			</a>
			<a class="mon-del"><img src="images/mon_trash.gif" title="删除" /></a>
		</div>
	</div>
	<?php }?>
</div>

<div class="rightcon_bottom" id="rightcon_bottom"></div>
<div class="height10"></div>
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
<input type="hidden" value="<?php echo $url;?>" id="hideUrl" />	
<input type="hidden" value="<?php echo $ajaxurl;?>" id="hideAjaxUrl" />	