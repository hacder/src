
<?php if(!empty($indicators['one'])){?>
<div class="col_4_con loadstat-con" >
	  <div class="col_con4_list1 left"  >
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input custom_norm" maxlength="20" readonly="readonly" value="最近1分钟平均负载">
			<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
				<li class="open_selected tag_li">最近1分钟平均负载</li>
				<li class="open tag_li">最近5分钟平均负载</li>
				<li class="open tag_li">最近15分钟平均负载</li>
			</ul>
		</div>
	  </div>
	  <div class="col_con4_list2 left">
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input" maxlength="20" readonly="readonly" value="大于">
			
		</div>
	  </div>
	  <div class="col_con4_list3 left" >
		<input type="text"  value="<?php echo $indicators['one']['value'];?>" class="threshold left"><span class="left threshold-tips"></span>
	  </div>
	  <div class="col_con4_list4 left" >
		<div class="btn_gray3">
			<a class="del-options"><span class="spanL">删除</span><span class="spanR"></span></a>
		</div>
	  </div>
</div>
<?php } if(!empty($indicators['five'])){ ?>
<div class="col_4_con loadstat-con" >
	  <div class="col_con4_list1 left"  >
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input custom_norm" maxlength="20" readonly="readonly" value="最近5分钟平均负载">
			<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
				<li class="open tag_li">最近1分钟平均负载</li>
				<li class="open_selected tag_li">最近5分钟平均负载</li>
				<li class="open tag_li">最近15分钟平均负载</li>
			</ul>
		</div>
	  </div>
	  <div class="col_con4_list2 left">
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input" maxlength="20" readonly="readonly" value="大于">
			
		</div>
	  </div>
	  <div class="col_con4_list3 left" >
		<input type="text"  value="<?php echo $indicators['five']['value'];?>" class="threshold left"><span class="left threshold-tips"></span>
	  </div>
	  <div class="col_con4_list4 left" >
		<div class="btn_gray3">
			<a class="del-options"><span class="spanL">删除</span><span class="spanR"></span></a>
		</div>
	  </div>
</div>
<?php }if(!empty($indicators['fifteen'])){ ?>
<div class="col_4_con loadstat-con" >
	  <div class="col_con4_list1 left"  >
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input custom_norm" maxlength="20" readonly="readonly" value="最近15分钟平均负载">
			<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
				<li class="open tag_li">最近1分钟平均负载</li>
				<li class="open tag_li">最近5分钟平均负载</li>
				<li class="open_selected tag_li">最近15分钟平均负载</li>
			</ul>
		</div>
	  </div>
	  <div class="col_con4_list2 left">
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input" maxlength="20" readonly="readonly" value="大于">
			
		</div>
	  </div>
	  <div class="col_con4_list3 left" >
		<input type="text"  value="<?php echo $indicators['fifteen']['value'];?>" class="threshold left"><span class="left threshold-tips"></span>
	  </div>
	  <div class="col_con4_list4 left" >
		<div class="btn_gray3">
			<a class="del-options"><span class="spanL">删除</span><span class="spanR"></span></a>
		</div>
	  </div>
</div>
<?php } if(!empty($indicators['inbond'])) {?>
<div class="col_4_con network-con"  >
	  <div class="col_con4_list1 left"  >
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input custom_norm" maxlength="20" readonly="readonly" value="网卡流入速率">
			<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
				<li class="open_selected tag_li">网卡流入速率</li>
				<li class="open tag_li">网卡流出速率</li>
			</ul>
		</div>
	  </div>
	  <div class="col_con4_list2 left">
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input" maxlength="20" readonly="readonly" value="大于">
			
		</div>
	  </div>
	  <div class="col_con4_list3 left" >
		<input type="text"  value="<?php echo $indicators['inbond']['value'];?>" class="threshold left"><span class="left threshold-tips">KB/s</span>
	  </div>
	  <div class="col_con4_list4 left" >
		<div class="btn_gray3">
			<a class="del-options"><span class="spanL">删除</span><span class="spanR"></span></a>
		</div>
	  </div>
</div>
<?php } if(!empty($indicators['outbond'])) {?>
<div class="col_4_con network-con"  >
	  <div class="col_con4_list1 left"  >
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input custom_norm" maxlength="20" readonly="readonly" value="网卡流出速率">
			<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
				<li class="open tag_li">网卡流入速率</li>
				<li class="open_selected tag_li">网卡流出速率</li>
			</ul>
		</div>
	  </div>
	  <div class="col_con4_list2 left">
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input" maxlength="20" readonly="readonly" value="大于">
			
		</div>
	  </div>
	  <div class="col_con4_list3 left" >
		<input type="text"  value="<?php echo $indicators['outbond']['value'];?>" class="threshold left"><span class="left threshold-tips">KB/s</span>
	  </div>
	  <div class="col_con4_list4 left" >
		<div class="btn_gray3">
			<a class="del-options"><span class="spanL">删除</span><span class="spanR"></span></a>
		</div>
	  </div>
</div>
<?php } if(!empty($indicators['real'])){?>
<div class="col_4_con memory-con"  >
	  <div class="col_con4_list1 left"  >
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input custom_norm" maxlength="20" readonly="readonly" value="内存使用率">
			<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
				<li class="open_selected tag_li">内存使用率</li>
				<li class="open tag_li">SWAP内存使用率</li>
			</ul>
		</div>
	  </div>
	  <div class="col_con4_list2 left">
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input" maxlength="20" readonly="readonly" value="大于">
			
		</div>
	  </div>
	  <div class="col_con4_list3 left" >
		<input type="text"  value="<?php echo $indicators['real']['value'];?>" class="threshold left"><span class="left threshold-tips">%</span>
	  </div>
	  <div class="col_con4_list4 left" >
		<div class="btn_gray3">
			<a class="del-options"><span class="spanL">删除</span><span class="spanR"></span></a>
		</div>
	  </div>
</div>
<?php }if(!empty($indicators['swap'])){?>
<div class="col_4_con memory-con"  >
	  <div class="col_con4_list1 left"  >
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input custom_norm" maxlength="20" readonly="readonly" value="SWAP内存使用率">
			<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
				<li class="open tag_li">内存使用率</li>
				<li class="open_selected tag_li">SWAP内存使用率</li>
			</ul>
		</div>
	  </div>
	  <div class="col_con4_list2 left">
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input" maxlength="20" readonly="readonly" value="大于">
			
		</div>
	  </div>
	  <div class="col_con4_list3 left" >
		<input type="text"  value="<?php echo $indicators['swap']['value'];?>" class="threshold left"><span class="left threshold-tips">%</span>
	  </div>
	  <div class="col_con4_list4 left" >
		<div class="btn_gray3">
			<a class="del-options"><span class="spanL">删除</span><span class="spanR"></span></a>
		</div>
	  </div>
</div>
<?php } if(!empty($indicators['used'])){?>
<div class="col_4_con diskstat-con"  >
	  <div class="col_con4_list1 left"  >
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input custom_norm" maxlength="20" readonly="readonly" value="磁盘空间使用率">
			<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
				<li class="open_selected tag_li">磁盘空间使用率</li>

			</ul>
		</div>
	  </div>
	  <div class="col_con4_list2 left">
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input" maxlength="20" readonly="readonly" value="大于">
			
		</div>
	  </div>
	  <div class="col_con4_list3 left" >
		<input type="text"  value="<?php echo $indicators['used']['value'];?>" class="threshold left"><span class="left threshold-tips">%</span>
	  </div>
	  <div class="col_con4_list4 left" >
		<div class="btn_gray3">
			<a class="del-options"><span class="spanL">删除</span><span class="spanR"></span></a>
		</div>
	  </div>
</div>
<?php } if(!empty($indicators['use'])){?>
<div class="col_4_con cpu-con"  >
	  <div class="col_con4_list1 left">
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input custom_norm" maxlength="20" readonly="readonly" value="当前CPU使用率">
			<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
				<li class="open_selected tag_li">当前CPU使用率</li>
			</ul>
		</div>
	  </div>
	  <div class="col_con4_list2 left">
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input" maxlength="20" readonly="readonly" value="大于">
			
		</div>
	  </div>
	  <div class="col_con4_list3 left" >
		<input type="text"  value="<?php echo $indicators['use']['value'];?>" class="threshold left"><span class="left threshold-tips">%</span>
	  </div>
	  <div class="col_con4_list4 left" >
		<div class="btn_gray3">
			<a class="del-options"><span class="spanL">删除</span><span class="spanR"></span></a>
		</div>
	  </div>
</div>
<?php } if(!empty($indicators['logins'])){?>
<div class="col_4_con logins-con"  >
	  <div class="col_con4_list1 left"  >
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input custom_norm" maxlength="20" readonly="readonly" value="当前登录用户数">
			<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
				<li class="open_selected tag_li">当前登录用户数</li>
			</ul>
		</div>
	  </div>
	  <div class="col_con4_list2 left">
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input" maxlength="20" readonly="readonly" value="大于">
			
		</div>
	  </div>
	  <div class="col_con4_list3 left" >
		<input type="text"  value="<?php echo $indicators['logins']['value'];?>" class="threshold left"><span class="left threshold-tips"></span>
	  </div>
	  <div class="col_con4_list4 left" >
		<div class="btn_gray3">
			<a class="del-options"><span class="spanL">删除</span><span class="spanR"></span></a>
		</div>
	  </div>
</div>
<?php } if(!empty($indicators['write'])){?>
<div class="col_4_con diskio-con"  >
	  <div class="col_con4_list1 left"  >
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input custom_norm" maxlength="20" readonly="readonly" value="磁盘写入速率">
			<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
				<li class="open_selected tag_li">磁盘写入速率</li>
				<li class="open tag_li">磁盘读取速率</li>
			</ul>
		</div>
	  </div>
	  <div class="col_con4_list2 left">
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input" maxlength="20" readonly="readonly" value="大于">
			
		</div>
	  </div>
	  <div class="col_con4_list3 left" >
		<input type="text"  value="<?php echo $indicators['write']['value'];?>" class="threshold left"><span class="left threshold-tips">KB/s</span>
	  </div>
	  <div class="col_con4_list4 left" >
		<div class="btn_gray3">
			<a class="del-options"><span class="spanL">删除</span><span class="spanR"></span></a>
		</div>
	  </div>
</div>
<?php } if(!empty($indicators['read'])){?>
<div class="col_4_con diskio-con"  >
	  <div class="col_con4_list1 left"  >
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input custom_norm" maxlength="20" readonly="readonly" value="磁盘读取速率">
			<ul class="tag_options" style="position: absolute; z-index: 999; width: 200px; display: none;">
				<li class="open tag_li">磁盘写入速率</li>
				<li class="open_selected tag_li">磁盘读取速率</li>
			</ul>
		</div>
	  </div>
	  <div class="col_con4_list2 left">
		<div class="select_box" style="z-index: 1; position: relative; width: 200px;">
			<input name="tag_input" class="tag_select tag_input" maxlength="20" readonly="readonly" value="大于">
			
		</div>
	  </div>
	  <div class="col_con4_list3 left" >
		<input type="text"  value="<?php echo $indicators['read']['value'];?>" class="threshold left"><span class="left threshold-tips">KB/s</span>
	  </div>
	  <div class="col_con4_list4 left" >
		<div class="btn_gray3">
			<a class="del-options"><span class="spanL">删除</span><span class="spanR"></span></a>
		</div>
	  </div>
</div>
<?php }?>