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
						  <span class="font1">-批量配置备份</span>
					  </div>
				  </div>
				  <!--one-->
				  <div class="rightcon_title">选择服务器</div>
				  <div class="rightcon_mid">
				      <p class="pheight" style="display:none;">
					      <label class="label6">服务器ip：</label><input type="text" class="style7 hui" readonly="true" id="serverip"/>
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
				  <!--one-->
				  <!--two-->
				  <div class="rightcon_title">配置操作选项</div>
				  <div class="rightcon_mid">
				      <p class="">
					      <label class="label6">源文件：</label>
						  <textarea class="textarea1" cols="5" id="sourcefile"></textarea>
					  </p>
					  <p class="pheight light1">多个文件请用回车间隔，一行一个。</p>
					  <p class="pheight">
					      <label class="label6">备份目录：</label>
						  <input type="text" class="style5" id="backupdir"/>
					  </p>
					  <p class="pheight">
					      <label class="label6">备份命令规则：</label>
						  <select class="select1" style="width:150px;" id="backuprule">
						  		<option value='0'>请选择</option>
						  		<option value='1'>文件名+后缀</option>
						  		<option value='2'>文件名+后缀+时间</option>
					  	  </select>
					  </p>
 			     </div>
				  <div class="rightcon_bottom"></div>
				  <!--two-->
				  <!--three-->
				  <div class="rightcon_title">选择时间</div>
				  <div class="rightcon_mid">
				     <p class="pheight">
				     	<label class="label5">&nbsp;</label>
					     <span class="style8"><input type="radio" class="style11 taskplan" name="taskplan" value='0' checked="checked" />马上执行</span>
					     <span class="style8"><input type="radio" class="style11 taskplan" name="taskplan" value='1' />计划任务</span>
					 </p>
					 <div id="plan_block"> 
						 <p class="pheight" id="execplan" style="display:none;">
						 	  <label class="label5">执行周期：</label>
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
					      <p class="pheight plantime" id="Every-day" style="display:none;">
						      <label class="label5">执行时间：</label>
						      <input type="text" class="style4" onclick="_SetTime(this)" readonly="true"/>
					      </p>
					      <p class="pheight plantime" id="One-time" style="display:none;">
						      <label class="label5">执行时间：</label>
						      <input type="text" class="style4" readonly="true" id="oncedate"/>
					      </p>
				      </div>
				 </div>
				  <div class="rightcon_bottom"></div>
				  <!--three-->
				  <p><input type="button" class="enter specibut" value="确认添加" id="addconfigbackup"/></p>
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
			<script type="text/javascript" src="script/common/comlayer.js"></script>
			<script type="text/javascript" src="script/common/combitch.js"></script>
			<script type="text/javascript" src="script/maintain/addconfigbackup.js"></script>
	<?php include 'views/footer.php';?>
