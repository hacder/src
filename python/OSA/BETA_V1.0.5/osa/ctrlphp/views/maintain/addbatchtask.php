	<?php include 'views/header.php';?>
		  <!--content开始-->
		  <script type="text/javascript" src="script/common/setTime.js"></script>
		  <link href="css/ui-lightness/jquery-ui-1.8.20.custom.css" rel="stylesheet" type="text/css"/>
		  <script src="script/jquery-ui-1.8.20.custom.min.js"></script>
		  <script src="script/jquery-ui-timepicker.js"></script>
		  <script src="script/swfupload/swfupload.js"></script>
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
						  <span class="font1">-批量操作</span>
					  </div>
				  </div>
				  <!--  show view -->
				  <div id="showfirst">
					  <div class="rightcon_title">基本信息</div>
					  <div class="rightcon_mid">
					      <p>
						      <label class="label5"><em class="em">*</em>操作类型：</label>
							  <span class="style8"><input type="radio" value="distribution" class="style11 batchtype" name="batchtype" checked="checked"/>批量文件分发</span>
							  <span class="style8"><input type="radio" value="cleaner" class="style11 batchtype" name="batchtype" />批量文件清理</span>
							  <span class="style8"><input type="radio" value="restart" class="style11 batchtype" name="batchtype" />批量服务操作</span>
							  <span class="style8"><input type="radio" value="command" class="style11 batchtype" name="batchtype" />批量指令执行</span>
							  <span class="style8"><input type="radio" value="installation" class="style11 batchtype" name="batchtype" />批量安装程序</span>
							  <span class="style8"><input type="radio" value="diskspace" class="style11 batchtype" name="batchtype" />批量磁盘空间</span>
							  <span class="style8"><input type="radio" value="loadstate" class="style11 batchtype" name="batchtype" />批量负载状态</span>
						  </p>
					  </div>
					  <div class="rightcon_bottom"></div>
					  <div class="rightcon_title">选择服务器</div>
					  <div class="rightcon_mid">
					      <p class="pheight" style="display:none;">
						      <label class="label6">服务器ip：</label><input type="text" class="style7 hui" readonly="true" id="serverip"/>
						  </p>
						  <p class="pheight">
						  	  <label class="label5">请点击按钮：</label>
						  	  <span class="link">
						  	  	<input type="button"  class="delete" id="showip" value="选择服务器" />
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
					  <p><input type="button" class="enter specibut" value="下一步" id="nextstep" /></p>
				  </div>			  
				  <!--  hide view -->
				    <div style="display:none;" id="showsecond">
					  <div class="rightcon_title">配置操作选项</div>
				  	  <div class="rightcon_mid">
				  	  	   <div id="distribution" style="display:none;" class="batch_operate">
						      <p class="pheight" id="upload_file">
							      <label class="label6"><em class="em">*</em>源文件：</label>
								  <input type="text" class="style5 sourcefile" id="sourcefile" />
								  <span class="updatebut left pointer" ><span id="uploadfile"></span></span>
			<!--						  <input type="button" class="updatebut left" value="上传文件" id="upload_btn"/>-->
								  <span class="link"><a href="#" id="showconfigfile">从配置库选择</a></span>
								  <span class="link"><a href="#" id="script_dis">从脚本库选择</a></span>
							  </p>
							  <p class="pheight light1">输入源文件保存在服务器的位置，或者上传源文件!</p>
							  <p class="pheight">
							      <label class="label6">目标路径：</label>
								  <input type="text" class="style5" id="targetdir"/>
							  </p>
							  <p class="pheight light1">输入保存在目标服务器的完整路径，需要包含文件名，比如:/etc/sysconfig/iptables</p>
							   <p class="pheight"><label class="label6">高级选项：</label><span class="style8"><input type="radio" class="style11 dis_radio" name="dis_radio" value="cut" checked="checked"/>备份并覆盖同名文件</span><span class="style8"><input type="radio" class="style11 dis_radio" name="dis_radio" value="copy"/>跳过同名文件</span></p>
							  <p class="pheight"><label class="label6">&nbsp;</label><input type="checkbox" class="style11 dis_check" name="dis_check" value="document_integrity"/>验证文件完整性</p>
						      <p class="pheight script_file">

							  </p>
						  </div>
						  <div id="cleaner" style="display:none;" class="batch_operate">
						  	<p class="pheight"><label class="label6"><em class="em">*</em>清理目录：</label><input type="text" class="style5" id="cleaner_path" /></p>
						  	<p class="pheight light1">需要清理的文件目录，比如:/var/log</p>
						    <p class="pheight"><label class="label6"><em class="em">*</em>目标路径：</label><input type="text" class="style5" id="cleaner_address" value="/dev/null"/></p>
						    <p class="pheight light1">将文件清理到的目标位置，可以为空，如果需要删除请填：/dev/null</p>
							<p class="pheight"><label class="label6">高级选项：</label><span class="style8"><input type="checkbox" class="style11 bak_check" value=".bak" checked="checked" />*.bak</span><span class="style8"><input type="checkbox" class="style11 log_check" value=".log"/>*.log</span></p>
							<p class="pheight">
							    <label class="label6"></label>
								<span class="style8"><input type="checkbox" class="style11 ex_check" value="1"/>指定扩展名：</span><input type="text" class="style9" id="ex_text" />
								<span class="light1 ml5" >多个扩展名以“,”分隔!</span>
							</p>
							<p class="pheight"><label class="label6"></label><span class="style8"><input type="checkbox" class="style11 rm_check" value="rm_dir"/>整目录删除</span></p>										  
						  </div>
						  <div id="restart" style="display:none;" class="batch_operate">
						  	  <p class="pheight">
						  	  	  <label class="label5">&nbsp;</label>
								  <span class="style8"><input type="radio" class="style11 server_radio" name="server_radio" value="server" checked="checked" />选择服务</span>
								  <span class="style8"><input type="radio" class="style11 server_radio" name="server_radio" value="script"/>选择脚本</span>
							  </p>
							  <p class="pheight" id="select_server">
							  	  <label class="label6">选择要操作的服务：</label>
							  	  	<select class="select1" id="server_type">
							  	  		<option value="">请选择</option>
							  	  		<option value="iptables">iptables</option>
							  	  		<option value="sshd">sshd</option>
							  	  		<option value="mysqld">mysqld</option>
							  	  		<option value="vsftpd">vsftpd</option>
							  	  		<option value="snmpd">snmpd</option>
							  	  		<option value="syslog">syslog</option>
							  	  		<option value="httpd">httpd</option>
							  	  		<option value="ntpd">ntpd</option>
							  	  		<option value="sendmail">sendmail</option>
							  	  	</select>
								  <label class="label5">或者输入服务：</label><input type="text" class="style4" id="server_intype"/>
								  <label class="label7">操作类型：</label>
								  	<select class="select1" id="operater_type">
								  		<option value="start">start</option>
								  		<option value="restart">restart</option>
								  		<option value="stop">stop</option>
								  	</select>
							  </p>
							  <p class="pheight script_file" id="select_script" style="display:none;">

							  </p>
						  </div>
						  <div id="command" style="display:none;" class="batch_operate">				
							  <p class="pheight script_file">

							  </p>
						  </div>
						  <div id="installation" style="display:none;" class="batch_operate">
					  		  <p class="pheight script_file">
			
							  </p>
						  </div>
						  <div id="diskspace" style="display:none;" class="batch_operate">
					  		  <p class="pheight">
							      <label><input type="radio" class="style11 space_radio" value="%" name="space_radio" checked="checked"/>当分区使用率超过：</label><input type="text" class="style3" id="space_p"/>%时，返回信息。
							  </p>
							  <p class="pheight">
							      <label><input type="radio" class="style11 space_radio" value="MB" name="space_radio"/>当分区使用超过：</label><input type="text" class="style3" id="space_m"/>MB时，返回信息。
							  </p>
						  </div>
						  <div id="loadstate" style="display:none" class="batch_operate">
					  		  <p class="pheight">
					  		  	 <label class="label5">&nbsp;</label>
							     <span class="style8"><input type="radio" class="style11 loadstate_radio" name="loadstate_radio" value="default" checked="checked"/>使用默认的OSA脚本判断方法返回结果</span>
							     <span class="style8"><input type="radio" class="style11 loadstate_radio" name="loadstate_radio" value="input_script"/>调用脚本</span>
							  </p>
							  <p class="pheight script_file" id="load_script" style="display:none;">
							      <label class="label1">执行命令或脚本：</label><input type="text" class="style5" />
								  <span class="link">或者<a href="#">搜索脚本库</a></span>
							  </p>
						  </div>
	 		      	  </div>
					  <div class="rightcon_bottom"></div>	
					  <div class="rightcon_title">选择时间</div>
					  <div class="rightcon_mid">
					     <p class="pheight">
					     	 <label class="label5">&nbsp;</label>
						     <span class="style8"><input type="radio" class="style11 taskplan" name="taskplan" value='0' checked="checked" />马上执行</span>
						     <span class="style8"><input type="radio" class="style11 taskplan" name="taskplan" value='1' />计划任务</span>
						 </p>
						 <div id="plan_block" style="display:none;"> 
							 <p class="pheight" id="execplan">
							 	  <label class="label5">执行周期：</label>
								  <span class="style8"><input type="radio" name="runcycle" class="style11 runcycle" checked="checked" value="Every-day"/>每天</span>
								  <span class="style8"><input type="radio" name="runcycle" class="style11 runcycle" value="Weekly"/>每周</span>
								  <span class="style8"><input type="radio" name="runcycle" class="style11 runcycle" value="Monthly"/>每月</span>
								  <span class="style8"><input type="radio" name="runcycle" class="style11 runcycle" value="One-time"/>一次性</span>
							  </p>
							  <p class="pheight plantime" id="Weekly" style="display:none;">
							      <label class="label5">执行时间：</label>
								  <span class="style8"><input type="checkbox" class="style11 weekly-check" value="Mon"  />星期一</span>
								  <span class="style8"><input type="checkbox" class="style11 weekly-check" value="Tue"  />星期二</span>
								  <span class="style8"><input type="checkbox" class="style11 weekly-check" value="Wed"  />星期三</span>
								  <span class="style8"><input type="checkbox" class="style11 weekly-check" value="Thu"  />星期四</span>
								  <span class="style8"><input type="checkbox" class="style11 weekly-check" value="Fri"  />星期五</span>
								  <span class="style8"><input type="checkbox" class="style11 weekly-check" value="Sat"  />星期六</span>
								  <span class="style8"><input type="checkbox" class="style11 weekly-check" value="Sun"  />星期日</span>
							      <input type="text" class="style9" onclick="_SetTime(this)" readonly="true"/>
							  </p>
							  <p class="pheight plantime" id="Monthly" style="display:none;">
								  <label class="label5">执行时间：</label>
								  <span style="float:left;">每月</span><select class="select2" style="width:60px;"><option>20</option></select>
								  <input type="text" class="style9" onclick="_SetTime(this)" readonly="true"/>
							  </p>
						      <p class="pheight plantime" id="Every-day">
							      <label class="label5">执行时间：</label>
							      <input type="text" class="style4" onclick="_SetTime(this)" readonly="true"/>
						      </p>
						      <p class="pheight plantime" id="One-time" style="display:none;">
							      <label class="label5">执行时间：</label>
							      <input type="text" class="style4" readonly="true" id="oncedate"/>
						      </p>
					      </div>
						 </div>
				 	 	<div class="rightcon_bottom"></div>
				 	 	<p>
				 	 		<input type="button" class="enter specibut" value="确认添加" id="batchconfirm"/>
				 	 		<input type="button" class="enter specibut" value="上一步" id="laststep"/>
				 	 	</p>
				  </div>
				  
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
		  
		<!-------------------弹出层------------------------->
		 <div id="shadow"></div>
		  <!--搜索服务器弹出层-->
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
					 	<?php foreach ($servertype as $key) {?>
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
			<!-- 搜索配置文件弹出层 -->
			<div class="window" id="searchfiletype" style="display:none;">
			    <div class="window_title">
				    <span class="window_text">选择配置文件</span>
					<input type="button" class="windbutton" />
				</div>
				<div class="window_con">
					 <p style="margin-left:80px;margin-top:-6px;">注：关键词可通过文件名称,标签来搜索</p>
					 <p>
					 	<label class="label5 font1">文件类型：</label>
					 	<?php foreach ($filetype as $key) {?>
					 		<a class="filetype pointer ml5" type="<?php echo $key['id'];?>"><?php echo $key['oTypeName'];?></a>
					 	<?php }?>
				 	 </p>
					 <p><label class="label5 font1">关键词：</label><input type="text" class="style7" id="keyword_file"/><input type="button" class="updatebut " id="search_file" value="查询" /></p>
					 <br />
					 <p>查询结果:</p>
					 <div id="result_file">
	
					 </div>
					 <div class="clear" id="config_page"><span class="pointer" id="config_last">上一页</span><span style="float:right;" class="pointer" id="config_next">下一页</span></div>
					 <div class="clear center" ><input type="button" class="updatebut specibut" id="selectfileconfirm" value="确定" id="searchlayer" /><input type="button" class="updatebut specibut cancel" value="取消" /></div>
				</div>
			</div>
			<!--搜索备份脚本弹出层-->
			<div class="window" id="searchscript" style="display:none;">
			    <div class="window_title">
				    <span class="window_text">选择脚本</span>
					<input type="button" class="windbutton" />
				</div>
				<div class="window_con">
					 <p style="margin-left:80px;margin-top:-6px;">注：关键词可通过脚本名成,标签来搜索</p>
					 <p><label class="label5 font1">关键词：</label><input type="text" class="style7" id="keyword_script"/><input type="button" class="updatebut " id="search_script" value="查询" /></p>
					 <div class="clear"></div>
					 <br />
					 <p>查询结果:</p>
					 <div id="result_script">
					
					 </div>
					 <div class="clear" id="script_page" style="display:;"><span class="pointer" id="script_last">上一页</span><span style="float:right;" class="pointer" id="script_next">下一页</span></div>
					 <div class="clear center"><input type="button" class="updatebut specibut" value="确定" id="scriptconfirm" /><input type="button" class="updatebut specibut cancel" value="取消" /></div>
				</div>
			</div>
			<!--特列--文件分发中源文件--从脚本库中选择 -->
			<div class="window" id="searchscript_dis" style="display:none;">
			    <div class="window_title">
				    <span class="window_text">选择脚本</span>
					<input type="button" class="windbutton" />
				</div>
				<div class="window_con">
					 <p style="margin-left:80px;margin-top:-6px;">注：关键词可通过脚本名成,标签来搜索</p>
					 <p><label class="label5 font1">关键词：</label><input type="text" class="style7" id="keyword_script_dis"/><input type="button" class="updatebut " id="search_script_dis" value="查询" /></p>
					 <div class="clear"></div>
					 <br />
					 <p>查询结果:</p>
					 <div id="result_script_dis">
					
					 </div>
					 <div class="clear" id="script_page_dis" style="display:;"><span class="pointer" id="script_last_dis">上一页</span><span style="float:right;" class="pointer" id="script_next_dis">下一页</span></div>
					 <div class="clear center"><input type="button" class="updatebut specibut" value="确定" id="scriptconfirm_dis" /><input type="button" class="updatebut specibut cancel" value="取消" /></div>
				</div>
			</div>
			<script type="text/javascript" src="script/common/comlayer.js"></script>
			<script type="text/javascript" src="script/common/combitch.js"></script>
			<script type="text/javascript" src="script/maintain/addbatchtask.js"></script>
			<script type="text/javascript">
				var swfu ;
				var settings = {
					flash_url : 'script/swfupload/swfupload.swf',
					upload_url : 'index.php?c=maintain&a=uploadfile',
					file_size_limit : "10 MB",
					file_types : "*.*",
					file_types_description : "All Files",
					file_upload_limit : 0,
					post_params: {"PHPSESSID": "<?php echo session_id();?>","user":"<?php echo $_SESSION['username'];?>"},
					debug : false,

					// Button settings
					button_image_url : "images/swfupload_upload_85x24.png",
					button_width : "85",
					button_height : "22",
					button_placeholder_id : "uploadfile",

					file_dialog_complete_handler : fileDialogComplete,
					upload_progress_handler : uploadProgress,
					upload_error_handler : uploadError,
					upload_success_handler : uploadSuccess
				};
				swfu = new SWFUpload(settings);
				// 文件窗口结束
				function fileDialogComplete(numFilesSelected, numFilesQueued) {
					try {
						/* 开始上传 */
						this.startUpload();
					} catch (ex) {
						this.debug(ex);
					}
				}

				// 上传成功
				function uploadSuccess(file, serverData) {
					if(serverData.indexOf('writable_error')!=-1){
						alert('目录没有写权限,上传失败');
						return ;
					}else{
						$("#sourcefile").val(serverData);
						//alert(serverData);
					}
				}

				function uploadProgress(file, bytesLoaded) {
					//什么都不做
				}

				function uploadError(file, errorCode, message) {
					alert(message);
				}
			</script>
	<?php include 'views/footer.php';?>

