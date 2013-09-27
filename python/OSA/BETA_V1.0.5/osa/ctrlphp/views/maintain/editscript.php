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
						  <span class="font1">-编辑脚本</span>
					  </div>
				  </div>
				  <!--one-->
				  <div class="rightcon_title">基本信息</div>
				  <div class="rightcon_mid">
				      <p class="pheight1"><label class="label5"><em class="em">*</em>脚本名称：</label><input type="text" class="style5" readonly="true" name="scriptname" id="scriptname" value="<?php echo $scriptinfo[0]['oScriptName'];?>"/><span class="tips"></span></p>
					  <p class="light pheight">输入脚本名字，支持英文与数字组合，例如:backup_mysql。</p>
				      <p class="pheight1"><label class="label5">脚本标签：</label><input type="text" class="style5" name="scriptlabel" id="scriptlabel" value="<?php echo $scriptinfo[0]['oScriptLabel'];?>"/><span class="tips"></span></p>
					  <p class="light pheight">使用标签可以更好管理脚本，多个标签请用“,”隔开。</p>
				      <p class="pheight1"><label class="label5"><em class="em">*</em>脚本保存路径：</label><input type="text" class="style5" style="width:480px;" readonly="true" value="<?php echo $scriptinfo[0]['oScriptPath'];?>" name="scriptpath" id="scriptpath"/><span class="tips"></span></p>
					  <p class="light pheight">输入脚本保存在服务器的位置。</p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--one-->
				  <!--two-->
				  <div class="rightcon_title">脚本编辑器：</div>
				  <div class="rightcon_mid">
					   <div style="margin-left:0px;"><textarea style="width:600px;height:400px;" class="textarea1" cols="5" id="scriptcontent"><?php echo stripslashes($scriptcontent);?></textarea></div>
					   <p class="pheight light" style="margin-top:10px; margin-left:0px;"><input type="button" class="delete" value="测试脚本" disabled="disabled"/><span >您所在的版本,该功能不可用</span></p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <p><input type="button" class="enter specibut" id="scriptedit" value="确认编辑" /><input type="button" class="enter specibut" value="取消返回" onclick="window.location='index.php?c=maintain&a=onlinescript';"/></p>
				  <!--two-->
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
<!--		  <script type="text/javascript" src="script/edit_area/edit_area_full.js"></script>-->
		  <link rel="stylesheet" href="script/CodeMirror/lib/codemirror.css">
	    <script src="script/CodeMirror/lib/codemirror.js"></script>
	    <script src="script/CodeMirror/mode/shell/shell.js"></script>
	    <link rel="stylesheet" href="script/CodeMirror/theme/ambiance.css">
		<script language="Javascript" type="text/javascript">
		  var editor = CodeMirror.fromTextArea(document.getElementById("scriptcontent"), {
		        lineNumbers: true,
//		        theme: "ambiance",
		      });
		  $(document).ready(function(){
			  var editurl = 'index.php?c=maintain&a=editscript&id=<?php echo $scriptinfo[0]['id'];?>';
			  $("#scriptedit").click(function(){
		
					var oScriptName = $.trim($('#scriptname').val());
					var oScriptLabel = $.trim($("#scriptlabel").val());
					var oScriptPath = $.trim($("#scriptpath").val());
					var oScriptContent = editor.getValue('');
					if(oScriptContent ==''){
						alert('脚本内容不能为空');
						return false;
					}
					var url = editurl;
					$.post(url,{
						'oScriptName':oScriptName,
						'oScriptLabel':oScriptLabel,
						'oScriptPath':oScriptPath,
						'oScriptContent':oScriptContent
					},function(msg){
						if(msg.indexOf('failure')!=-1){
							alert('脚本编辑失败');
							return ;
						}else if(msg.indexOf('success')!=-1){
							alert('脚本编辑成功');
							window.location = 'index.php?c=maintain&a=onlinescript';				
						}
					});
				});
			});
		  </script>
		  <?php include 'views/footer.php';?>
		  