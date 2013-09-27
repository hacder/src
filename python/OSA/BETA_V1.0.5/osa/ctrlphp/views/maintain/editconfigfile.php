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
						  <span class="font1">-编辑配置文件</span>
					  </div>
				  </div>
				  <!--one-->
				 <div class="rightcon_title">基本信息</div>
				  <div class="rightcon_mid">
				      <p class="pheight1"><label class="label5"><em class="em">*</em>文件名称：</label><input type="text" class="style5" id="filename" readonly='true' value="<?php echo $configinfo[0]['oFileName'];?>"/><span class="tips"></span></p>
					  <p class="light pheight">输入脚本名字，支持英文与数字组合，例如:backup_mysql。</p>
				      <p class="pheight"><label class="label5"><em class="em">*</em>文件类型：</label>
					      <select class="select1" style="width:120px;" id="filetype">
					      	<option value="">请选择</option>
					      	<?php foreach ($filetype as $key) {?>
					      	<option value="<?php echo $key['id'];?>" <?php echo $key['id']==$configinfo[0]['oTypeid']?"selected='selected'":""?>><?php echo $key['oTypeName'];?></option>
					      	<?php }?>
					      </select><span class="link"><a href="#" id="filetypeadd">创建新类型</a></span>
				      </p>
					  <p class="pheight1"><label class="label5">文件标签：</label><input type="text" class="style5" id="filelabel" value="<?php echo $configinfo[0]['oFileLabel'];?>"/><span class="tips"></span></p>
					  <p class="light pheight">使用标签可以更好管理脚本，多个标签请用“,”隔开。</p>
				      <p class="pheight1"><label class="label5">文件签名：</label><input type="text" class="style5" id="filesign" value="<?php echo $configinfo[0]['oFileSign'];?>" /><span class="tips"></span></p>
					  <p class="pheight light">写上你的签名，让大家更好的认识你，脚本签名是宣传自己的一个有效途径。</p>
				      <p class="pheight1"><label class="label5"><em class="em">*</em>保存路径：</label><input type="text" class="style5" style="width:480px;" id="filepath" value="<?php echo $configinfo[0]['oSavePath'];?>" readonly='true'/><span class="tips"></span></p>
					  <p class="light pheight">配置文件保存在服务器的位置。</p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--one-->
				  <!--two-->
				  <div class="rightcon_title">配置文件编辑器：</div>
				  <div class="rightcon_mid">
				  		<div style="margin-left:0px;"><textarea style="width:600px;height:400px;" class="textarea1" cols="5" id="configcontent"><?php echo $configfilecontent;?></textarea></div>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <p><input type="button" class="enter specibut" id="configedit" value="确认编辑" /><input type="button" class="enter specibut" value="取消返回" onclick="window.location='index.php?c=maintain&a=configfilelist';"/></p>
				  <!--two-->
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
		    <div id="shadow"></div>
		  <!--创建新类型-->
			<div class="window" id="addfiletype" style="display:none;">
			    <div class="window_title">
				    <span class="window_text">创建新类型</span>
					<input type="button" class="windbutton" />
				</div>
				<div class="window_con">
						 <p><label class="label5"><em class="em">*</em>类型名称：</label><input type="text" class="style5" id="typename" /></p>
						 <div class="clear"></div>
						 <br />
						 <div class="center"><input type="button" class="updatebut specibut" value="确定" id="configfilelayer" /><input type="button" class="updatebut specibut cancel" value="取消" /></div>
				</div>
			</div>
		<!--创建新类型结束-->
		  <script type="text/javascript" src="script/common/comlayer.js"></script>
	  	    <link rel="stylesheet" href="script/CodeMirror/lib/codemirror.css">
	    <script src="script/CodeMirror/lib/codemirror.js"></script>
	    <script src="script/CodeMirror/mode/shell/shell.js"></script>
	    <link rel="stylesheet" href="script/CodeMirror/theme/ambiance.css">
		<script language="Javascript" type="text/javascript">
		  var editor = CodeMirror.fromTextArea(document.getElementById("configcontent"), {
		        lineNumbers: true,
//		        theme: "ambiance",
		      });
		  $(document).ready(function(){
			  var editurl = 'index.php?c=maintain&a=editconfigfile&id=<?php echo $configinfo[0]['id'];?>';
				$('#configedit').click(function(){		
					var oFileName = $.trim($('#filename').val());
					var oFileLabel = $.trim($("#filelabel").val());
					var oFileSign = $.trim($("#filesign").val());
					var oSavePath = $.trim($("#filepath").val());
					var oTypeid = $("#filetype").find(":checked").attr('value');
					if(oTypeid == ''){
						alert('文件类型不能为空');
						return false;
					}
					var oConfigContent = editor.getValue('');
					if(oConfigContent ==''){
						alert('文件内容不能为空');
						return false;
					}
					var url = editurl;
					$.post(url,{
						'oFileName':oFileName,
						'oFileLabel':oFileLabel,
						'oFileSign':oFileSign,
						'oSavePath':oSavePath,
						'oTypeid':oTypeid,
						'oConfigContent':oConfigContent
					},function(msg){
						if(msg.indexOf('failure')!=-1){
							alert('编辑失败');
							return ;
						}else if(msg.indexOf('success')!=-1){
							alert('编辑成功');
							window.location = 'index.php?c=maintain&a=configfilelist';				
						}
					});
				});
			});
		  </script>	
		  <?php include 'views/footer.php';?>
		  