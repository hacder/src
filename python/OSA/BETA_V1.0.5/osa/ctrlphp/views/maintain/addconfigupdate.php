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
						  <span class="font1">-批量配置更新</span>
					  </div>
				  </div>
				  <!--one-->
				  <div class="rightcon_title">选择服务器</div>
				  <div class="rightcon_mid">
				      <p class="pheight" style="display:none;">
					      <label class="label6">服务器ip：</label><input type="text" class="style7 hui" readonly="true" id="serverip"/>
					  </p>
					  <p class="pheight">
						  <label class="label5">请点击按钮：</label>
						  <span class="link">
							<input type="button" id="showip" value="选择服务器" />
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
				  <!--one-->
				  <!--two-->
				  <div class="rightcon_title">配置操作选项</div>
				  <div class="rightcon_mid">
				      <p class="pheight" id="upload_file">
					      <label class="label5"><em class="em">*</em>源配置文件：</label>
						  <input type="text" class="style5" id="sourcefile" />
						  <span class="updatebut left pointer" ><span id="uploadfile"></span></span>
<!--						  <input type="button" class="updatebut left" value="上传文件" id="upload_btn"/>-->
						  <span class="link"><a href="#" id="showconfigfile">从配置库选择</a></span>
					  </p>
					  <p class="pheight">
					      <label class="label5">目标路径：</label>
						  <input type="text" class="style5" id="targetdir"/>
					  </p>
					  <p class="light1 pheight" style="margin-left:100px;">目标配置文件的绝对路径，比如：/etc/sysconfig/iptables</p>
					  <p class="pheight">
					      <label class="label5">高级选项：</label>
						  <span class="style8"><input type="checkbox" name="advance" class="advance" value="backup" checked="checked"/>备份原配置文件</span>
						  <span class="style8"><input type="checkbox" name="advance" class="advance" value="document_integrity" checked="checked"/>验证文件完整性</span>
					  </p>
 			     </div>
				  <div class="rightcon_bottom"></div>
				  <!--two-->
				  <div class="rightcon_title">选择脚本</div>
				  <div class="rightcon_mid">
				      <p class="pheight">
					      <label class="label6">执行命令或脚本：</label>
					      <input type="text" class="style7" id="scriptfile"/>
					      <span class="link">或者<a href="#" id="showscript">搜索脚本库</a></span>
					  </p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--three-->
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
				  <!--three-->
				  <p><input type="button" class="enter specibut" value="确认添加" id="addconfigupdate"/></p>
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
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
					 <div class="clear"></div>
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
			<script type="text/javascript" src="script/common/comlayer.js"></script>
			<script type="text/javascript" src="script/common/combitch.js"></script>
			<script type="text/javascript" src="script/maintain/addconfigupdate.js"></script>
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
