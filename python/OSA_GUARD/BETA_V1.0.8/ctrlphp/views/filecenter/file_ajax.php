<div class="height10"></div>
<div class="record_title" id="record_title">
	<div class="selectall"><span><input type="checkbox" class="sel_all_input" id="checkall"/></span></div>
	<div class="rdname3" style="width:20%;"><span>文件名称</span></div>
	<div class="rdip3" style="width:40%;"><span>文件路径</span></div>	
	<div class="rdtype3" style="width:10%;"><span>文件大小</span></div>
	<div class="rdper3" style="width:15%;"><span>上传时间</span></div>
	<div class="rdaction3" style="width:5%;"><span>操作</span></div>
</div>
<div class="rightcon_mid" id="rightcon_mid" style="overflow:hidden;">

	<?php foreach ($fileinfo as $key){?>
	<div class="record-list listli_1" >
		<div class="selectall"><span><input type="checkbox" class="sel_all_input check_one" value="<?php echo $key['id'];?>" /></span></div>
		<div class="rdname3" style="width:20%;">
			<span><?php echo $key['oFileName'];?></span>
		</div>
		<div class="rdip3" style="width:40%;text-align:left;"><span title="<?php echo $key['oRealPath'];?>"><?php echo $key['oRealPath'];?></span></div>	
		<div class="rdtype3" style="width:10%;"><span><?php echo $key['oFileSize'];?></span></div>
		<div class="rdper3" style="width:15%;"><span><?php echo $key['oCreateTime'];?></span></div>
		<div class="rdaction3" style="width:5%;">
			<a class="file-del" style="color: #1A6EC4;">删除</a>
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