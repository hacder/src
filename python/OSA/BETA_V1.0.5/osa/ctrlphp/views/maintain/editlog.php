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
						  <span class="font1">-编辑操作记录</span>
					  </div>
				  </div>
				  <!--one-->
				  <div class="rightcon_title">基本信息</div>
				  <div class="rightcon_mid">
				      <p class="pheight"><label class="label5"><em class="em">*</em>日志标题：</label><input type="text" readonly="true" class="style5" id="logtitle" value="<?php echo $loginfo[0]['oLogTitle'];?>"/><span class="tips"></span></p>
				      <p class="pheight"><label class="label5"><em class="em">*</em>日志类型：</label>
					      <select class="select1" style="width:120px;" id="logtype">
					      	<option value="">请选择</option>
					      	<?php foreach ($logtype as $key) {?>
					      	<option value="<?php echo $key['id'];?>" <?php echo $key['id']==$loginfo[0]['oTypeid']?"selected='selected'":""?>><?php echo $key['oTypeText'];?></option>
					      	<?php }?>
					      </select>
					      <span id="logtypeadd" class="link"><a href="#">创建新分组</a></span>
				      </p>
				      <p class="pheight1"><label class="label5">日志标签：</label><input type="text" class="style5" id="loglabel" value="<?php echo $loginfo[0]['oLogLabel'];?>"/></p>
					  <p class="light pheight">使用标签可以更好管理日志，多个标签请用“,”隔开。</p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--one-->
				  <!--two-->
				  <div class="rightcon_title">日志详情</div>
				  <div class="rightcon_mid">
					  <p><label class="label5"><em class="em">*</em>日志内容：</label></p>
					  <p class="light"><textarea class="textarea1" id="detail" name="logtext"><?php echo $loginfo[0]['oLogText'];?></textarea></p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <p><input type="button" class="enter specibut" value="确认编辑" id="logedit"/><input type="button" class="enter specibut" value="取消返回" onclick="window.location='index.php?c=maintain&a=loglist';"/></p>
				  <!--two-->
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
		  <div id="shadow"></div>
		  <!--创建新类型-->
			<div class="window" id="addlogtype" style="display:none;">
			    <div class="window_title">
				    <span class="window_text">创建新类型</span>
					<input type="button" class="windbutton" />
				</div>
				<div class="window_con">
						 <p><label class="label5"><em class="em">*</em>类型名称：</label><input type="text" class="style5" id="typename" /></p>
						 <div class="clear"></div>
						 <br />
						 <div class="center"><input type="button" class="updatebut specibut" value="确定" id="loglayer" /><input type="button" class="updatebut specibut cancel" value="取消" /></div>
				</div>
			</div>
		<!--创建新类型结束-->
		  <script type="text/javascript" src="script/common/comlayer.js"></script>
		  <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
		  <script type="text/javascript">
			var editor = CKEDITOR.replace( 'logtext',
			{
				filebrowserImageUploadUrl : 'index.php?c=device&a=uploadimg&type=img'
			});
		  $(document).ready(function(){
			  var editurl = 'index.php?c=maintain&a=editlog&id=<?php echo $loginfo[0]['id'];?>';
			  $('#logedit').click(function(){		
					var oTypeid = $('#logtype').find(":checked").attr('value');
					if(oTypeid == ''){
						alert('日志类型不能为空');
						return false;
					}
					var oLogText = editor.getData();
					if(oLogText ==''){
						alert('脚本内容不能为空');
						return false;
					}
					var oLogTitle = $.trim($('#logtitle').val());
					var oLogLabel = $.trim($('#loglabel').val());
					var url = editurl;
					$.post(url,{
						'oLogTitle':oLogTitle,
						'oLogLabel':oLogLabel,
						'oTypeid':oTypeid,
						'oLogText':oLogText
					},function(msg){
						if(msg.indexOf('failure')!=-1){
							alert('日志编辑失败');
							return ;
						}else if(msg.indexOf('success')!=-1){
							alert('日志编辑成功');
							window.location = 'index.php?c=maintain&a=loglist';				
						}
					});
				});
			});
		  </script>
<?php include 'views/footer.php';?>