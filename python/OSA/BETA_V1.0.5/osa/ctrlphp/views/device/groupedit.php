	<?php include 'views/header.php';?>
	<script type="text/javascript" src="script/device/region.js"></script>
		  <!--content开始-->
		  <div class="content">
		      <!--左边开始-->
			  <?php include 'views/device/left.php';?>
			  <!--左边结束-->
			  <!--右边开始-->
			  <div class="main_right">
			      <div class="linkmlist">
				      <div class="home_icom">
					      <span>当前位置：</span>
						  <span><a href="#">设备管理</a></span>
						  <span class="font1">-编辑分组</span>
					  </div>
				  </div>
				  <!--one-->
				  <div class="rightcon_title">基本信息</div>
				  <div class="rightcon_mid">
				      <p class="pheight"><label class="label5"><em class="em">*</em>分组名称：</label><input type="text" class="style5 hui" id="groupname" value="<?php echo $ginfo[0]['oGroupName'];?>" readonly="true"/></p>
					  <p><label class="label5">分组描述：</label><textarea class="textarea1" id="descript" name="textarea1"><?php echo $ginfo[0]['oDescription'];?></textarea></p>
				 	  <p class="light">把有相同特性的服务器，比如某一个集群，某种跑了相同应用的服务器<br />比如：安装了MYSQL的服务器 放到一个分组，方便筛选和管理!。</p>				  
				  </div>
				  <div class="rightcon_bottom"></div>
				  <div class="rightcon_title">选择服务器</div>
					  <div class="rightcon_mid">
					      <p class="pheight" style="display:none;">
						      <label class="label6">服务器ip：</label><input type="text" class="style7" id="serverip" readonly="true" value="<?php echo $ginfo[0]['oServerList'];?>"/>
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
					  <p>
					  	  <input type="hidden" id="hideurl" value="<?php echo $hideurl;?>" />
						  <input type="button" class="enter specibut" id="group_edit" value="确认编辑" />
						  <input type="button" class="enter specibut" value="取消返回" onclick="window.location='index.php?c=device&a=devgrouplist'" />
					  </p>
				  <!--two-->
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->

  <div id="shadow"></div>
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
		 	<?php foreach ($type as $key) {?>
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
<script type="text/javascript" src="script/device/groupadd.js"></script>	
<?php include 'views/footer.php';?>
