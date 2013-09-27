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
						  <span class="font1">-添加新配置文件</span>
					  </div>
				  </div>
				  <!--one-->
				 <div class="rightcon_title">基本信息</div>
				  <div class="rightcon_mid">
				      <p class="pheight1"><label class="label5"><em class="em">*</em>文件名称：</label><input type="text" class="style5" id="filename"/><span class="tips"></span></p>
					  <p class="light pheight">输入脚本名字，支持英文与数字组合，例如:backup_mysql。</p>
				      <p class="pheight"><label class="label5"><em class="em">*</em>文件类型：</label>
					      <select class="select1" style="width:120px;" id="filetype">
					      	<option value="">请选择</option>
					      	<?php foreach ($filetype as $key) {?>
					      	<option value="<?php echo $key['id'];?>"><?php echo $key['oTypeName'];?></option>
					      	<?php }?>
					      </select><span class="link"><a href="#" id="filetypeadd">创建新类型</a></span>
				      </p>
					  <p class="pheight1"><label class="label5">文件标签：</label><input type="text" class="style5" id="filelabel"/><span class="tips"></span></p>
					  <p class="light pheight">使用标签可以更好管理脚本，多个标签请用“,”隔开。</p>
				      <p class="pheight1"><label class="label5">文件签名：</label><input type="text" class="style5" id="filesign" /><span class="tips"></span></p>
					  <p class="pheight light">写上你的签名，让大家更好的认识你，脚本签名是宣传自己的一个有效途径。</p>
				      <p class="pheight1"><label class="label5"><em class="em">*</em>保存路径：</label><span class="left"><?php echo osa_datapath('config');?></span>
				      <input type="text" class="style5" style="width:200px;" id="filepath"/><span class="tips"></span></p>
					  <p class="light pheight">配置文件保存在服务器的位置。</p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--one-->
				  <!--two-->
				  <div class="rightcon_title">配置文件编辑器：</div>
				  <div class="rightcon_mid">
				  		<div style="margin-left:0px;"><textarea style="width:600px;height:400px;" class="textarea1" cols="5" id="configcontent"></textarea></div>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <p><input type="button" class="enter specibut" id="configconfirm" value="确认添加" /><input type="button" class="enter specibut" value="取消返回" onclick="window.location='index.php?c=maintain&a=configfilelist';"/></p>
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
		  <script type="text/javascript" src="script/maintain/addconfigfile.js"></script>
		   <link rel="stylesheet" href="script/CodeMirror/lib/codemirror.css">
	    <script src="script/CodeMirror/lib/codemirror.js"></script>
	    <script src="script/CodeMirror/mode/shell/shell.js"></script>
	    <link rel="stylesheet" href="script/CodeMirror/theme/ambiance.css">
		<script language="Javascript" type="text/javascript">
		  var editor = CodeMirror.fromTextArea(document.getElementById("configcontent"), {
		        lineNumbers: true,
//		        theme: "ambiance",
		      });
			</script>		
		  <?php include 'views/footer.php';?>
		  