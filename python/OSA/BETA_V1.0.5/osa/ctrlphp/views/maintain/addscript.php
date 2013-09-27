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
						  <span class="font1">-添加新脚本</span>
					  </div>
				  </div>
				  <!--one-->
				  <div class="rightcon_title">基本信息</div>
				  <div class="rightcon_mid">
				      <p class="pheight1"><label class="label5"><em class="em">*</em>脚本名称：</label><input type="text" class="style5" name="scriptname" id="scriptname"/><span class="tips"></span></p>
					  <p class="light pheight">输入脚本名字，支持英文与数字组合，例如:backup_mysql。</p>
				      <p class="pheight1"><label class="label5">脚本标签：</label><input type="text" class="style5" name="scriptlabel" id="scriptlabel"/><span class="tips"></span></p>
					  <p class="light pheight">使用标签可以更好管理脚本，多个标签请用“,”隔开。</p>
				      <p class="pheight1">
				      	<label class="label5"><em class="em">*</em>脚本保存路径：</label><span class="left"><?php echo osa_datapath('script');?></span>
			      		<input type="text" class="style5" style="width:200px;"  name="scriptpath" id="scriptpath"/><span class="tips"></span>	      		
				      </p>
					  <p class="light pheight">输入脚本保存在服务器的位置,脚本保存为相对osa的路径,请输入相对路经，比如："/test/test.py"。</p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--one-->
				  <!--two-->
				  <div class="rightcon_title">脚本编辑器：</div>
				  <div class="rightcon_mid">
					   <div style="margin-left:0px;"><textarea style="width:600px;height:400px;" class="textarea1" cols="5" id="scriptcontent"></textarea></div>
					   <p class="pheight light" style="margin-top:10px; margin-left:0px;"><input type="button" class="delete" value="测试脚本" disabled="disabled"/><span >您所在的版本,该功能不可用</span></p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <p><input type="button" class="enter specibut" id="scriptconfirm" value="确认添加" /><input type="button" class="enter specibut" value="取消返回" onclick="window.location='index.php?c=maintain&a=addscript';"/></p>
				  <!--two-->
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
		  <script type="text/javascript" src="script/maintain/addscript.js"></script>
	  <link rel="stylesheet" href="script/CodeMirror/lib/codemirror.css">
	    <script src="script/CodeMirror/lib/codemirror.js"></script>
	    <script src="script/CodeMirror/mode/shell/shell.js"></script>
	    <link rel="stylesheet" href="script/CodeMirror/theme/ambiance.css">
		<script language="Javascript" type="text/javascript">
		  var editor = CodeMirror.fromTextArea(document.getElementById("scriptcontent"), {
		        lineNumbers: true,
//		        theme: "ambiance",
		      });
			</script>		
		  <?php include 'views/footer.php';?>
		  