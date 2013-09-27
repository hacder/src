<div class="rightcon_title">
	<span>项目信息</span>
</div>
<div class="rightcon_mid"  style="overflow-y:hidden;">
	<p style="padding:5px 10px;">
		<span class="left" style="font-weight:bold;">项目名称：</span><span class="left"><?php echo $itemname['oItemName'];?></span>
	</p>
	<p style="padding:5px 10px;">
		<span class="left" style="font-weight:bold;">所在域/服务器：</span><span class="left"><?php echo $itemname['oItemObject'];?></span>
	</p>
	<p style="padding:5px 10px;">
		<span class="left" style="font-weight:bold;">监控频率：</span><span class="left"><?php osa_monitor_timeset($itemname['oCheckRate']);?></span>
	</p>
</div>
<div class="rightcon_bottom"></div>