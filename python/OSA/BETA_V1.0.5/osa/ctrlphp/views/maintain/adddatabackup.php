	<?php include 'views/header.php';?>
		  <!--content开始-->
		  <script type="text/javascript" src="script/common/setTime.js"></script>
		  <link href="css/ui-lightness/jquery-ui-1.8.20.custom.css" rel="stylesheet" type="text/css"/>
		  <script src="script/jquery-ui-1.8.20.custom.min.js"></script>
		  <script src="script/jquery-ui-timepicker.js"></script>
		  <div class="content">
		      <!--左边开始-->
			  <?php include 'views/maintain/left.php';?>		  
			  <!--左边结束-->
			  <!--右边开始-->
			  <div class="main_right">
			      <div class="linkmlist">
				      <div class="home_icom">
					      <span>当前位置：</span>
						  <span><a href="#">日常运维</a></span>
						  <span class="font1">-添加备份计划</span>
					  </div>
				  </div>
				  <!--one-->
				  <div class="rightcon_title">基本信息</div>
				  <div class="rightcon_mid">
				      <p class="pheight"><label class="label5"><em class="em">*</em>备份任务名称：</label><input type="text" class="style5" id="backupname" /></p>
					  <p class="pheight"><label class="label5">执行周期：</label>
						  <span class="style8"><input type="radio" name="runcycle" class="style11 runcycle" checked="checked" value="Every-day"/>每天</span>
						  <span class="style8"><input type="radio" name="runcycle" class="style11 runcycle" value="Weekly"/>每周</span>
						  <span class="style8"><input type="radio" name="runcycle" class="style11 runcycle" value="Monthly"/>每月</span>
						  <span class="style8"><input type="radio" name="runcycle" class="style11 runcycle" value="One-time"/>一次性</span>
					  </p>
					  <p class="pheight plantime" id="Weekly" style="display:none;">
					      <label class="label5">执行时间：</label>
						  <span class="style8"><input type="checkbox" class="style11 weekly-check" value="Mon"  />星期一</span>
						  <span class="style8"><input type="checkbox" class="style11 weekly-check" value="Tue"  />星期二</span>
						  <span class="style8"><input type="checkbox" class="style11 weekly-check" value="Wed"  />星期三</span>
						  <span class="style8"><input type="checkbox" class="style11 weekly-check" value="Thu"  />星期四</span>
						  <span class="style8"><input type="checkbox" class="style11 weekly-check" value="Fri"  />星期五</span>
						  <span class="style8"><input type="checkbox" class="style11 weekly-check" value="Sat"  />星期六</span>
						  <span class="style8"><input type="checkbox" class="style11 weekly-check" value="Sun"  />星期日</span>
					      <input type="text" class="style9" onclick="_SetTime(this)" readonly="true"/>
					  </p>
					  <p class="pheight plantime" id="Monthly" style="display:none;">
						  <label class="label5">执行时间：</label>
						  <span style="float:left;">每月</span><select class="select2" style="width:60px;"><option>20</option></select>
						  <input type="text" class="style9" onclick="_SetTime(this)" readonly="true"/>
					  </p>
				      <p class="pheight plantime" id="Every-day">
					      <label class="label5">执行时间：</label>
					      <input type="text" class="style4" onclick="_SetTime(this)" readonly="true"/>
				      </p>
				      <p class="pheight plantime" id="One-time" style="display:none;">
					      <label class="label5">执行时间：</label>
					      <input type="text" class="style4" readonly="true" id="oncedate"/>
				      </p>
				      
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--one-->
				  <!--two-->
				  <div class="rightcon_title">选择服务器</div>
				  <div class="rightcon_mid">
				      <p class="pheight" style="display:none;">
					      <label class="label6">服务器ip：</label><input type="text" class="style7 hui" id="serverip" readonly="true"/>
					  </p>
					  <p class="pheight">
					  	  <label class="label5">请点击按钮：</label>
					  	  <span class="link">
					  	  	<input type="button" id="showip" value="选择服务器" />
					  	  </span>
					  </p>
					  <!--搜索脚本库弹窗-->
					  <div class="selected" style="">
					      <div style="float:left;"><label class="label6">已选择的服务器：</label></div>
					      <div style="width:630px;float:left;" id="showselectip">
<!--							  <span class="left mr10"><label class="ip_tips">192.168.0.32</label><img src="images/erase.png" class="delselectip ml5 pointer"/></span>-->
						  </div>
					  </div>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--two-->
				  <!--three-->
				  <div class="rightcon_title">选择脚本</div>
				  <div class="rightcon_mid">
				      <p class="pheight">
					      <label class="label6">数据库备份脚本：</label><input type="text" class="style7" id="scriptfile"/><span class="link">或者<a href="#" id="showscript">搜索脚本库</a></span>
					  </p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--three-->
				  <p><input type="button" class="enter specibut" value="确认添加" id="dataconfirm"/><input type="button" class="enter specibut" value="取消返回" onclick="window.location='index.php?c=maintain&a=databackuplist';"/></p>
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
	  	  <div id="shadow"></div>
		  <!--搜索服务器弹出层-->
			<div class="window" id="searchip" style="display:none;">
			    <div class="window_title">
				    <span class="window_text">选择服务器</span>
					<input type="button" class="windbutton" />
				</div>
				<div class="window_con">
					 <p style="margin-left:80px;margin-top:-6px;">注：关键词可通过设备名,地区,IP来搜索</p>
					 <p>
					 	<label class="label5 font1">设备类型：</label>
					 	<a class="serverall pointer ml5" type="">全部</a>
					 	<?php foreach ($servertype as $key) {?>
					 		<a class="servertype pointer ml5" type="<?php echo $key['id'];?>"><?php echo $key['oTypeName'];?></a>
					 	<?php }?>
				 	</p>
					 <p><label class="label5 font1">关键词：</label><input type="text" class="style7" id="keyword"/><input type="button" class="updatebut " id="search_ip" value="查询" /></p>
					 <p>
					 	<label class="font1" style="float:left;margin-right:10px;">查询结果:</label>
					 	<span class="style8 pointer" id="ipall">全选 </span>
					 	<span class="style8 pointer" id="ipcancel">全不选</span>
				 	 </p>
					 <div class="clear"></div>
					 <div id="result_ip">
			
					 </div>
					 <div class="clear" id="ip_page" style="display:none;"><span class="pointer" id="lastpage">上一页</span><span style="float:right;" class="pointer" id="nextpage">下一页</span></div>
					 <div class="clear center" ><input type="button" class="updatebut specibut" id="selectipconfirm" value="确定" id="searchlayer" /><input type="button" class="updatebut specibut cancel" value="取消" /></div>
				</div>
			</div>
			<!--搜索备份脚本弹出层-->
			<div class="window" id="searchscript" style="display:none;">
			    <div class="window_title">
				    <span class="window_text">选择脚本</span>
					<input type="button" class="windbutton" />
				</div>
				<div class="window_con">
					 <p style="margin-left:80px;margin-top:-6px;">注：关键词可通过脚本名成,标签来搜索</p>
					 <p><label class="label5 font1">关键词：</label><input type="text" class="style7" id="keyword_script"/><input type="button" class="updatebut " id="search_script" value="查询" /></p>
					 <div class="clear"></div>
					 <br />
					 <p>查询结果:</p>
					 <div id="result_script">

					 </div>
					 <div class="clear" id="script_page" style="display:;"><span class="pointer" id="script_last">上一页</span><span style="float:right;" class="pointer" id="script_next">下一页</span></div>
					 <div class="clear center"><input type="button" class="updatebut specibut" value="确定" id="scriptconfirm" /><input type="button" class="updatebut specibut cancel" value="取消" /></div>
				</div>
			</div>
			<script type="text/javascript" src="script/common/comlayer.js"></script>
			<script type="text/javascript" src="script/common/combitch.js"></script>
			<script type="text/javascript" src="script/maintain/adddatabackup.js"></script>
	<?php include 'views/footer.php';?>
