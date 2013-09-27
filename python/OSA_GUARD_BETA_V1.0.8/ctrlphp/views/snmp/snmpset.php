<?php include 'views/header.php';?>
<link rel="stylesheet" href="css/extend.css" type="text/css" />
<!--内容开始-->
<div class="main">
<!--左侧菜单开始-->
		<?php include 'views/snmp/snmpmenu.php';?>
<!--左侧菜单结束-->
<!--右侧内容开始-->
		<div class="mainright" id="mainright">
		<!--右侧title开始-->	
			<div class="currtitle">
				<div class="placing"><span>当前位置：</span><span>设备管理</span> <span>&gt;</span> <span>服务器snmp采集配置</span></span></div>
			</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
			<div class="edit_list">
		
				<div class="time_pro" style="display:block;">
					<p>
						<span class="time_pro_img"></span>
						<span>该配置应用到所有服务器，目前不支持为单台服务器设定单独的团队名(Community).
						SNMP服务器支持同时配置多个团体名(Community),相关文档请参考《<a href="http://wiki.osapub.com/SNMP%E7%9B%B8%E5%85%B3%E6%8C%87%E5%BC%95" target="_blank">SNMP相关指引</a>》.
						</span>
					</p>
				</div>
				  <!--two-->
				  <div class="rightcon_title">配置snmp信息</div>
				  <div class="rightcon_mid">
				  	<div class="edit_con">
						  <p class="pheight_per">
							  <label class="label5">SNMP版本：</label><input id="snmpname" type="text" class="style5" value="v2c" disabled="disabled"/>
						  	  <span class="snmp-tips" style="float:left;padding-left:10px;color:red;"></span>
						  </p>
						  <p class="light">默认支持snmp v2c 版本数据的获取方式。</p>
						  <p class="pheight_per">
							  <label class="label5">Community：</label><input id="snmpkey" type="text" class="style5" value="<?php echo $snmpinfo[0]['oSnmpKey'];?>"/>
						 	  <span class="snmp-tips" style="float:left;padding-left:10px;color:red;"></span>
						  </p>
						  <p class="light">用于访问SNMP代理的Community字符串，比如：public。</p>
						  <p class="pheight_per">
							  <label class="label5">SNMP端口：</label><input id="snmpport" type="text" class="style5" value="<?php echo $snmpinfo[0]['oSnmpPort'];?>"/>
						 	  <span class="snmp-tips" style="float:left;padding-left:10px;color:red;"></span>
						  </p>
						  <p class="light">服务器SNMP使用的默认UDP端口.通常是161。</p>
					</div>
					 <div class="edit_submit" style="width:40%;margin-left:10px;">
						<div class="btn_green" id="snmp-submit"><a href="#"><span class="spanL">确认保存</span><span class="spanR"></span></a></div>
				  	</div>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--two-->		
			</div>			
			<div class="height10"></div>
		<!--右侧content结束-->
		</div>
<!--右侧内容结束-->
</div>

<script type="text/javascript" src="script/common/base.js"> </script>
<link rel="stylesheet" href="css/osa-tips.css" type="text/css" />
<script type="text/javascript" src="script/common/tips-base.js"></script>
<script type="text/javascript" src="script/snmp/snmpset.js"></script>
<!--内容结束-->
<?php include 'views/footer.php';?>