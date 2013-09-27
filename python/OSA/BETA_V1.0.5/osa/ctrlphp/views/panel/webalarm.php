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
						  <span class="font1">-网页存活报警</span>
					  </div>
				  </div>
				  <div class="clear"></div>
				  <!--one-->
				  <div class="rightcon_title">报警项目</div>
				  <div class="rightcon_mid">
					  <p class="pheight1"><label class="label6">*监控项目名称：</label><input type="text" class="style5" id="proname"/><span class="tips"></span></p>
					  <p class="pheight1"><label class="label6">*网页URL地址：</label><input type="text" class="style5" id="urlname" value="http://"/><span class="tips"></span></p>
					  <p class="light1">输入需要监控的网页URL地址，仅能输入一个。例如：http://www.openwebsa.org</p>
					  <p class="pheight1"><label class="label6">*对比关键字：</label><input type="text" class="style5" id="prokey"/><span class="tips"></span></p>
					  <p class="pheight light1">可以输入需要对比的关键字，多个关键字用"，"分割。</p>
					  <p class="pheight1">
					      <label class="label6">正常HTTP状态码：</label>
						  <span class="style13"><input type="checkbox" class="style11 http_status" name="http_status" checked="checked" value="200"/>200</span>
						  <span class="style13"><input type="checkbox" class="style11 http_status" name="http_status" value="301"/>301</span>
						  <span class="style13"><input type="checkbox" class="style11 http_status" name="http_status" value="302"/>302</span>
						  <span>其他：<input type="text" class="style17" id="httpstatus"></span>
					  </p>
					  <p class="light1">选择你认为网页正常的HTTP状态码，通常情况下：200</p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--one-->
				  <!--three-->
				  <?php include 'views/panel/commonadd.php';?>
				  <!--four-->
				  <p class="pheight specibut"><input type="button" class="enter" value="确定保存" id="webconfirm"/></p>
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
		  <div id="shadow"></div>
			<div class="window" id="searchusers" style="display:none;">
			    <div class="window_title">
				    <span class="window_text">选择用户</span>
					<input type="button" class="windbutton" />
				</div>
				<div class="window_con">
					 <p style="margin-left:80px;margin-top:-6px;">注：关键词可通过用户名,昵称来搜索</p>
					 <p><label class="label5">关键词：</label><input type="text" class="style7" id="keyword_user"/><input type="button" class="updatebut " id="search_user" value="查询" /></p>
					 <div class="clear"></div>
					 <br />
					 <p>
					 	<label class="font1" style="float:left;margin-right:10px;">查询结果:</label>
					 	<span class="style8 pointer" id="userall">全选 </span>
					 	<span class="style8 pointer" id="usercancel">全不选</span>
					 </p>
					 <div class="clear" id="result_user">

					 </div>
					 <div class="clear" id="user_page"><span class="pointer" id="user_last">上一页</span><span style="float:right;" class="pointer" id="user_next">下一页</span></div>
					 <div class="clear center"><input type="button" class="updatebut specibut" value="确定" id="userconfirm" /><input type="button" class="updatebut specibut cancel" value="取消" /></div>
				</div>
			</div>
		<script type="text/javascript" src="script/common/comlayer.js"></script>
		<script type="text/javascript" src="script/common/combitch.js"></script>
		  <script type="text/javascript" src="script/panel/webalarm.js"></script>
<?php include 'views/footer.php';?>

