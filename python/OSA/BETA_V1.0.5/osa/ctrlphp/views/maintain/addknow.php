	<?php include 'views/header.php';?>
		  <!--content开始-->
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
						  <span class="font1">-添加新知识</span>
					  </div>
				  </div>
				  <!--one-->
				  <div class="rightcon_title">基本信息</div>
				  <div class="rightcon_mid">
				      <p class="pheight"><label class="label5"><em class="em">*</em>知识标题：</label><input type="text" class="style5" id="knowtitle"/><span class="tips"></span></p>
				      <p class="pheight"><label class="label5"><em class="em">*</em>知识类型：</label>
					      <select class="select1" style="width:120px;" id="knowtype">
					      	<option value="">请选择</option>
					      	<?php foreach ($knowtype as $key) {?>
					      	<option value="<?php echo $key['id'];?>"><?php echo $key['oTypeName'];?></option>
					      	<?php }?>
					      </select>
					      <span id="knowtypeadd" class="link"><a href="#">创建新类型</a></span>
				      </p>
				      <p class="pheight1"><label class="label5">知识标签：</label><input type="text" class="style5" id="knowlabel"/></p>
					  <p class="light pheight">使用标签可以更好管理日志，多个标签请用“,”隔开。</p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--one-->
				  <!--two-->
				  <div class="rightcon_title">知识详情</div>
				  <div class="rightcon_mid">
					  <p><label class="label5"><em class="em">*</em>知识内容：</label></p>
					  <p class="light"><textarea class="textarea1" id="detail" name="knowtext"></textarea></p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <p><input type="button" class="enter specibut" value="确认添加" id="knowconfirm"/><input type="button" class="enter specibut" value="取消返回" onclick="window.location='index.php?c=maintain&a=knowlist';"/></p>
				  <!--two-->
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
		  <div id="shadow"></div>
		  <!--创建新类型-->
			<div class="window" id="addknowtype" style="display:none;">
			    <div class="window_title">
				    <span class="window_text">创建新类型</span>
					<input type="button" class="windbutton" />
				</div>
				<div class="window_con">
						 <p><label class="label5"><em class="em">*</em>类型名称：</label><input type="text" class="style5" id="typename" /></p>
						 <div class="clear"></div>
						 <br />
						 <div class="center"><input type="button" class="updatebut specibut" value="确定" id="knowlayer" /><input type="button" class="updatebut specibut cancel" value="取消" /></div>
				</div>
			</div>
		<!--创建新类型结束-->
		  <script type="text/javascript" src="script/common/comlayer.js"></script>
		  <script type="text/javascript" src="script/maintain/addknow.js"></script>
		  <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
		  <script type="text/javascript">
			var editor = CKEDITOR.replace( 'knowtext',
			{
				filebrowserImageUploadUrl : 'index.php?c=device&a=uploadimg&type=img'
			});
			</script>
<?php include 'views/footer.php';?>