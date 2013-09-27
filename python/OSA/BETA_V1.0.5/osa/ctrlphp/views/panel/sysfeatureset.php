	<?php include 'views/header.php';?>
		  <!--content开始-->
		  <div class="content">
		      <!--左边开始-->
			  <?php include 'views/panel/left.php';?>
			  <!--左边结束-->
			  <!--右边开始-->
			  <div class="main_right">
			      <div class="linkmlist">
				      <div class="home_icom">
					      <span>当前位置：</span>
						  <span><a href="#">监控配置</a></span>
						  <span class="font1">-系统功能配置</span>
					  </div>
				  </div>
				  <div class="clear"></div>
				  <!--one-->
				  <div class="rightcon_title">采集选项</div>
				  <div class="rightcon_mid">
				      <p class="pheight">
					      <label class="label5">采集开关：</label>
						  <span class="style8"><input type="radio" class="style11 radio_switch" checked="checked" name="radio_switch" value="1" />开启</span>
						  <span class="style8"><input type="radio" class="style11 radio_switch" name="radio_switch" value="0" disabled="disabled"/>关闭</span>
					  </p>
					  <div id="show_method">
					      <p class="pheight">
						      <label class="label5">采集方式：</label>
							  <span class="style8"><input type="radio" checked="checked" class="style11 radio_method" name="radio_method" value="0"/>自带SHELL版本</span>
							  <span class="style8"><input type="radio" class="style11 radio_method" name="radio_method" value="1" disabled="disabled"/>SNMP协议</span>
						  </p>
						  <div class="time_pro"><p><img src="images/icon2.gif" />采用脚本采集，您无需配置，如果使用SNMP采集，需要配置SNMP协议！</p></div>
					  </div>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--one-->
				  <!--two-->
				  <div id="snmpset" style="display:none;">
					  <div class="rightcon_title">SNMP选项</div>
					  <div class="rightcon_mid">
					      <p class="pheight1">
						      <label class="label5">SNMP团体名称：</label><input type="text" class="style7" id="snmp_name"/><span class="tips"></span>
						  </p>
						  <p class="pheight light">输入该主机的可读SNMP团体名称。</p>
						  <p class="pheight1">
						      <label class="label5">SNMP端口：</label><input type="text" class="style7" id="snmp_port"/><span class="tips"></span>
						  </p>
						  <p class="pheight light">输入SNMP使用的UDP端口(默认是161)。</p>
						  <p class="pheight1">
						      <label class="label5">SNMP超时：</label><input type="text" class="style7" id="snmp_timeout"/><span class="tips"></span>
						  </p>
						  <p class="pheight light">程序等待SNMP响应的最大超时时间(单位:毫秒)。</p>
					  </div>
					  <div class="rightcon_bottom"></div>
				  </div>
				  <!--two-->
				  <p class="pheight specibut"><input type="button" class="enter" value="确定保存" id="snmpconfirm"/></p>
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
		  <script type="text/javascript" src="script/panel/sysfeature.js"></script>
<?php include 'views/footer.php';?>
