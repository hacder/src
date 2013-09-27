<script type="text/javascript" src="script/swfupload/swfupload.js"></script>
<script type="text/javascript" src="script/osaUpload/swfupload.swfobject.js"></script>
<script type="text/javascript" src="script/osaUpload/swfupload.queue.js"></script>
<script type="text/javascript" src="script/osaUpload/fileprogress.js"></script>
<script type="text/javascript" src="script/osaUpload/handlers.js"></script>

<!-- - 
		<form id="form1" enctype="multipart/form-data" method="post" action="">
			<div class="body_header">
				<div class="upload_left">
					<div class='upload_left'>
						<span id='spanButtonPlaceHolder'>
						</span>
					</div>
					<div class='upload_left'>
						<input id="btnCancel" type="button" value="" onclick="swfu.cancelQueue();" disabled="disabled" />
					</div>					
				</div>		
				<div style="clear:both;"></div>
			</div>
			<div id="fsUploadProgress" class="upload_flash"></div>
			<div id="divStatus">0 个文件已经上传成功</div>
		</form>
		<div id="divLoadingContent" class="content" style="position:absolute;left:40px;top:100px;width:500px;height:200px;display: none;"> 正在加载相关组件，请稍等... </div>
		<div id="divLongLoading" class="content" style="position:absolute;left:40px;top:100px;width:500px;height:200px;display: none;">
		加载失败，请确定您的浏览器安装了Flash Player9.0.28 或更新的版本。 访问
			<a target="top" href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash">Adobe website</a>
			获取Flash Player。
		</div>
		<div id="divAlternateContent" class="content" style="position:absolute;left:40px;top:100px;width:500px;height:200px;display:block;">
			对不起，您需要安装或升级您的Flash Player。 访问
			<a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash">Adobe website</a>
			获取Flash Player。
		</div>
		<div class="body_footer">
			<div class="upload_line">&nbsp;</div>
			<div class='upload_right'>
				<input type='button' class='confirm upload_button' id='upload-confirm' value="确定" />
				<input type='button' class='cancel upload_button' id='upload-cancel' value="取消" />
			</div>
		</div>		
-->	

<script type="text/javascript">
	var swfu;
	var fileinfo = '';//存储上传文件信息对象
	var settings = {
		flash_url : "script/swfupload/swfupload.swf",
		upload_url: "index.php?c=serverajax&a=osafile_upload",
		post_params:{"PHPSESSID" : "<?php echo session_id(); ?>"},
		file_size_limit : "1024000",  
		file_types : "*.*",
		file_types_description : "All Files",
		file_upload_limit : 0,
		file_queue_limit : 0,
		custom_settings : {
			progressTarget : "fsUploadProgress",
			cancelButtonId : "btnCancel"
		},
		debug: false,

		// Button Settings
		button_image_url: "images/swfupload_upload_85x24.png",
		button_width: "85",
		button_height: "23",
		button_placeholder_id: "spanButtonPlaceHolder",
		button_text: '<span class="theFont"></span>',
		button_text_style: ".theFont { font-size: 16; }",
		button_text_left_padding: 12,
		button_text_top_padding: 3,

		// The event handler functions are defined in handlers.js
		swfupload_loaded_handler : swfUploadLoaded,
		file_queued_handler : fileQueued,
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : my_uploadSuccess,
		upload_complete_handler : uploadComplete,
		queue_complete_handler : queueComplete,	// Queue plugin event
		
		// SWFObject settings
		minimum_flash_version : "9.0.28",
		swfupload_pre_load_handler : swfUploadPreLoad,
		swfupload_load_failed_handler : swfUploadLoadFailed
	};
	swfu = new SWFUpload(settings);

	//上传完成的回调函数
	function my_uploadSuccess(file, serverData) {
		try {
			var progress = new FileProgress(file, this.customSettings.progressTarget);
			//get httpupload callback info
			if(serverData.indexOf("{")!=-1){
				progress.setComplete();
				progress.setStatus("Succeed.");
				progress.toggleCancel(false);
				eval('var uploadfileinfo = '+serverData.slice(serverData.indexOf('{'),serverData.indexOf('}'))+'}');
				fileinfo = {realpath:uploadfileinfo.realpath,filename:uploadfileinfo.filename,filesize:uploadfileinfo.filesize};
			}else{
				progress.setError();
				progress.toggleCancel(false);
				progress.setStatus("No permission to upload.");
				this.debug("Error Code: Failed to execute move_uploaded_file(),No permission to upload" );
				alert(serverData);
			}
		} catch (ex) {
			this.debug(ex);
		}
	}
	//上传完成的确定按钮
	$('#upload-confirm').click(function(){
		if(fileinfo !=''){
			alert(fileinfo.filename) ;
		}
		$("#coverlayer").remove();
		$("#osa_upload").remove();	
	});
	//上传完成的取消按钮
	$('#upload-cancel').click(function(){
		$("#coverlayer").remove();
		$("#osa_upload").remove();
	});
</script>
 

