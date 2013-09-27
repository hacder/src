<?php include 'views/header.php';?>
<link rel="stylesheet" href="css/extend.css" type="text/css" />
<!--内容开始-->
<div class="main">
<!--左侧菜单开始-->
	<?php include "views/monitor/menu.php";?>
<!--左侧菜单结束-->
<!--右侧内容开始-->
	<div class="mainright" id="mainright">
		<!--右侧title开始-->	
		<div class="currtitle">
			<div class="placing"><span>当前位置：</span><span>日常监控</span> <span>&gt;</span> <span>创建监控项目</span></div>
		</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
		<div class="edit_list">
			<?php $itemconfig = jsonDecode($itemdata[0]['oItemConfig']);?>
			<!--one-->
			<div class="rightcon_title">报警项目信息</div>
			<div class="rightcon_mid">
				<label class="label_more"><span class="red">*</span>监控项目名称：</label>
				<div class="left"><input type="text" class="style5" placeholder="请输入监控项目名称" id="itemname" value="<?php echo $itemdata[0]['oItemName'];?>" /><span class="tips" style="margin-left:6px;"></span></div>
				<div class="height10"></div>
				<label class="label_more"><span class="red">*</span>状态页面URL：</label>
				<div class="left"><input type="text" class="style5" placeholder="请输入状态页面URL" id="itemurl" value="<?php echo $itemdata[0]['oItemObject'];?>" readonly="readonly" /><span class="tips" style="margin-left:6px;"></span></div>
				<div class="clear"></div> 
				<label class="label_more">&nbsp;</label>
				<div class="light0">
					请填写您的MongoDB状态页面URL，比如：http://www.domain.com:11001/_status。
				</div>
				<div class="clear"></div>
								
				<div class="height10"></div>
				<div class="btn_gray2 left20 mar20L">
					<a id="more-options"><span class="spanL">自定义报警设置</span><span class="spanR"></span></a>
				</div>
				<div class="more_class" id="more-class" style="display:<?php echo count($itemconfig)>1?'block':'none';?>;">
		
					<div class="window_hr left"></div>		
					<div class="col_4_title">
						<div class="col_con4_list1 left" style="width:210px;">指标名称</div>
						<div class="col_con4_list2 left" style="width:210px;">条件</div>
						<div class="col_con4_list3 left" style="width:190px;">阀值</div>
						<div class="col_con4_list4 left" style="width:170px;">操作</div>
					</div>
					<div class="height10"></div> 
					<?php if(count($itemconfig)>1){
					if(!empty($itemconfig['page_num'])){?>
					<div class="col_4_con">
						  <div class="col_con4_list1 left"  >
							<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
								<input name="tag_input" class="tag_select tag_input mongodb_norm" maxlength="20" readonly="readonly" value="MongoDB分页次数"/>
								<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
									<li class="open tag_li">MongoDB当前连接数</li>
									<li class="open_selected tag_li">MongoDB分页次数</li>
									<li class="open tag_li">MongoDB锁定时间比例</li>
								</ul>
							</div>
						  </div>
						  <div class="col_con4_list2 left">
							<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
								<input name="tag_input" class="tag_select tag_input" maxlength="20" readonly="readonly" value="大于">
								<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
									<li class="open_selected tag_li">大于</li>
							
								</ul>
							</div>
						  </div>
						  <div class="col_con4_list3 left" >
							<input type="text"  value="<?php echo $itemconfig['page_num']['value']?>" class="threshold left"><span class="left threshold-tips"></span>
						  </div>
						  <div class="col_con4_list4 left" >
							<div class="btn_gray3">
								<a class="del-options"><span class="spanL">删除</span><span class="spanR"></span></a>
							</div>
						  </div>
					</div>
					<?php } if(!empty($itemconfig['lock_ratio'])){?>
					<div class="col_4_con">
						  <div class="col_con4_list1 left"  >
							<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
								<input name="tag_input" class="tag_select tag_input mongodb_norm" maxlength="20" readonly="readonly" value="MongoDB锁定时间比例"/>
								<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
									<li class="open tag_li">MongoDB当前连接数</li>
									<li class="open tag_li">MongoDB分页次数</li>
									<li class="open_selected tag_li">MongoDB锁定时间比例</li>
								</ul>
							</div>
						  </div>
						  <div class="col_con4_list2 left">
							<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
								<input name="tag_input" class="tag_select tag_input" maxlength="20" readonly="readonly" value="大于">
								<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
									<li class="open_selected tag_li">大于</li>
							
								</ul>
							</div>
						  </div>
						  <div class="col_con4_list3 left" >
							<input type="text"  value="<?php echo $itemconfig['lock_ratio']['value']?>" class="threshold left"><span class="left threshold-tips">%</span>
						  </div>
						  <div class="col_con4_list4 left" >
							<div class="btn_gray3">
								<a class="del-options"><span class="spanL">删除</span><span class="spanR"></span></a>
							</div>
						  </div>
					</div>
					<?php }if(!empty($itemconfig['curr_connects'])){ ?>
					<div class="col_4_con">
						  <div class="col_con4_list1 left"  >
							<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
								<input name="tag_input" class="tag_select tag_input mongodb_norm" maxlength="20" readonly="readonly" value="MongoDB当前连接数"/>
								<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
									<li class="open_selected tag_li">MongoDB当前连接数</li>
									<li class="open tag_li">MongoDB分页次数</li>
									<li class="open tag_li">MongoDB锁定时间比例</li>
								</ul>
							</div>
						  </div>
						  <div class="col_con4_list2 left">
							<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
								<input name="tag_input" class="tag_select tag_input" maxlength="20" readonly="readonly" value="大于">
								<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
									<li class="open_selected tag_li">大于</li>
								</ul>
							</div>
						  </div>
						  <div class="col_con4_list3 left" >
							<input type="text"  value="<?php echo $itemconfig['curr_connects']['value']?>" class="threshold left"><span class="left threshold-tips"></span>
						  </div>
						  <div class="col_con4_list4 left" >
							<div class="btn_gray3">
								<a class="del-options"><span class="spanL">删除</span><span class="spanR"></span></a>
							</div>
						  </div>
					</div>
					<?php }}else {?>
					<div class="col_4_con">
						  <div class="col_con4_list1 left"  >
							<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
								<input name="tag_input" class="tag_select tag_input mongodb_norm" maxlength="20" readonly="readonly" value="MongoDB当前连接数"/>
								<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
									<li class="open_selected tag_li">MongoDB当前连接数</li>
									<li class="open tag_li">MongoDB分页次数</li>
									<li class="open tag_li">MongoDB锁定时间比例</li>
								</ul>
							</div>
						  </div>
						  <div class="col_con4_list2 left">
							<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
								<input name="tag_input" class="tag_select tag_input" maxlength="20" readonly="readonly" value="大于">
								<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
									<li class="open_selected tag_li">大于</li>
							
								</ul>
							</div>
						  </div>
						  <div class="col_con4_list3 left" >
							<input type="text"  value="" class="threshold left"><span class="left threshold-tips"></span>
						  </div>
						  <div class="col_con4_list4 left" >
							<div class="btn_gray3">
								<a class="del-options"><span class="spanL">删除</span><span class="spanR"></span></a>
							</div>
						  </div>
					</div>
					<?php }?>
					<div class="height10 append"></div> 
					<div class="window_hr left"></div>
					<div class="height10"></div> 
					<div class="btn_green1 left10">
						<a id="add-options"><span class="spanL">添加新条目</span><span class="spanR"></span></a>
					</div>
					<div class="light0 left20">您可以通过设定合理的阀值，结合条件表达式，对MongoDB各项指标进行合理的监控.</div>
				</div>
				<div class="clear"></div> 		
			</div>
			<input type="hidden" value="<?php echo $itemid;?>" id="edit-itemid" />
			<div class="rightcon_bottom"></div>
			<!--one-->
			<?php include 'views/monitor/edit/common.php';?>
		</div>	

		<div class="height10"></div>
		<div class="edit_submit">
			<div class="btn_green" style=""><a id="mongodb-edit"><span class="spanL">编辑监控项目</span><span class="spanR"></span></a></div>
			<div style="" class="btn_cancel"><a href="javascript:history.go(-1)"><span class="spanL">取消返回</span><span class="spanR"></span></a></div>
		</div>

	<!--右侧content结束-->
	</div>
<!--右侧内容结束-->
</div>
<!--内容结束-->
<script type="text/javascript" src="script/common/base.js"> </script>
<script type="text/javascript" src="script/device/common.js"></script>
<link rel="stylesheet" href="css/osa-tips.css" type="text/css" />
<link rel="stylesheet" href="css/osa-box.css" type="text/css" />
<script type="text/javascript" src="script/common/tips-base.js"></script>
<script type="text/javascript" src="script/common/osa-box.js"></script>
<script type="text/javascript" src="script/monitor/mongodb.js"> </script>

<?php include 'views/footer.php';?>
