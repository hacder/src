var osaFileUpload = function(session_id){
	
	/*****************************   创建遮罩  ************************************/
	topWindow = $(window.document);
	$(document.body).append("<div id='coverlayer'></div>");
	$('#coverlayer').css({
		'position':'absolute',
		'left':'0px',
		'top':'0px',
		'width':'100%',
		'min-width':'1024px',
		'height':'100%',
		'background':'#444444',
		'opacity':'0.25',
		'z-index':'9998'
	});
	
	/******************************  osa_upload 窗体生成  **************************/
	topWindow.find('body').append("<div id='osa_upload' style='display:none;z-index:9999' class='osa_upload'></div>");
	topWindow.find("#osa_upload").append("<div class='osa_upload_title' id='osa_upload_title'></div>");
	topWindow.find("#osa_upload_title").append("<span class='osa_upload_text'>OSA文件上传</span>");
	topWindow.find("#osa_upload_title").append("<input type='button' class='osa_upload_close' id='osa_upload_close' />");
	topWindow.find("#osa_upload").append("<div class='osa_upload_body' id='osa_upload_body'>");
	// osa_upload_body
	topWindow.find("#osa_upload_body").append("<form id='form1' enctype='multipart/form-data' method='post' action=''></form>");
	topWindow.find("#form1").append("<div class='body_header' id='body_header'></div>");
	topWindow.find("#body_header").append("<div class='upload_left'><div class='upload_left'><span id='spanButtonPlaceHolder'></span></div><div class='upload_left'><input type='button' id='btnCancel'/></div></div>");
	topWindow.find("#body_header").append("<div style='clear:both;'></div>");
	topWindow.find("#form1").append("<div id='fsUploadProgress' class='upload_flash'></div><div id='divStatus'>0 个文件已经上传成功</div>");
	
	topWindow.find("#osa_upload_body").append("<div id='divLoadingContent' class='content' > 正在加载相关组件，请稍等... </div>");
	topWindow.find("#osa_upload_body").append("<div id='divLongLoading' class='content'>加载失败，请确定您的浏览器安装了Flash Player9.0.28 或更新的版本。 访问<a target='top' href='http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash'>Adobe website</a>获取Flash Player。</div>");
	topWindow.find("#osa_upload_body").append("<div id='divAlternateContent' class='content'>对不起，您需要安装或升级您的Flash Player。 访问<a href='http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash'>Adobe website</a>获取Flash Player。</div>");
	
	topWindow.find("#osa_upload_body").append("<div class='body_footer' id='body_footer'></div>");
	topWindow.find("#body_footer").append("<div class='upload_line'>&nbsp;</div>");
	topWindow.find("#body_footer").append("<div class='upload_right'><input type='button' class='confirm upload_button' id='upload-confirm' value='确定'/><input type='button' class='cancel upload_button' id='upload-cancel' value='取消' /></div>");
	
						
	var initUploadView = function(){
		var url = "index.php?c=serverajax&a=upload_file_view";
		$.post(url,function(data){
			$("#osa_upload_body").append(data);
		});
	};
	//initUploadView();
	
	/**************************  计算position   ********************************/
	var tips_height = topWindow.find('#osa_upload').height();
	var tips_width = topWindow.find('#osa_upload').width();
	var left = 0;
	var top = 0;
	var scrollTop = $(window.parent.document).scrollTop();
	var screen_height	= $(window).height();
	var screen_width	= $(window).width();
	left = (screen_width - tips_width)/2;
	top = (screen_height - tips_height)/2+scrollTop;
	
	
	topWindow.find('#osa_upload').css('left',left+'px');
	topWindow.find('#osa_upload').css('top',top+'px');
	topWindow.find('#osa_upload').css('display','block');
	
	$("#osa_upload_close").live("click",function(){
		topWindow.find("#coverlayer").remove();
		topWindow.find("#osa_upload").remove();
	});
	
	/**************************  draggable  *********************/
	var isMouseDown = false;
	var isMouseMove = false;
	var downX = 0;
	var downY = 0;
	topWindow.find('#osa_upload').mousedown(function(e){
		
		isMouseDown = true;
		e=e||evnet;
		downX = parseInt(e.clientX);
		downY = parseInt(e.clientY);
		topWindow.find('body').mousemove(function(e){
			if( !isMouseDown ) return;
			var oleft = parseInt(e.clientX)-downX;
			var otop = parseInt(e.clientY)-downY;
			var left = parseInt( topWindow.find('#osa_upload').css('left') ) + oleft;
			var top = parseInt( topWindow.find('#osa_upload').css('top') ) + otop;
			
			var screen_height	= $(window.parent).height();
			var screen_width	= $(window.parent).width();
			//计算滚动条偏差
		var sleft = $(window.parent.document).scrollLeft(); 
		var stop = $(window.parent.document).scrollTop(); 
			left = left < 0 ? '0' : left;
			top = top < 0 ? '0' : top;
			left = left >(screen_width + sleft - tips_width) ? screen_width + sleft - tips_width : left;
			top = top >(screen_height + stop - tips_height) ? screen_height + stop - tips_height : top;
			topWindow.find('#osa_upload').css('left',left+'px').css('top',top+'px');
			downX = e.clientX;
			downY = e.clientY;
		});
		topWindow.find('body').mouseup(function(e){
			$(this).unbind('mousemove');
			isMouseDown = false;
			downX = 0;
			downY = 0;
		});
	
	});
	
	/******************* 上传处理函数 ************************/
	var swfu;
	var fileinfo = '';//存储上传文件信息对象
	var settings = {
		flash_url : "script/swfupload/swfupload.swf",
		upload_url: "index.php?c=serverajax&a=osafile_upload",
		post_params:{"PHPSESSID" : session_id},
		file_size_limit : "1048576",  
		file_types : "*.*",
		file_types_description : "All Files",
		file_upload_limit : 5,
		file_queue_limit : 2,
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

				if(serverData.indexOf("exists")!=-1){
					progress.setError();
					progress.toggleCancel(false);
					progress.setStatus("存在同名文件,请重新命名");
					this.debug("Error Code: File with the same name already exists");
				}else{
					progress.setError();
					progress.toggleCancel(false);
					progress.setStatus("没有权限上传,请修改权限.");
					this.debug("Error Code: Failed to execute move_uploaded_file(),No permission to upload" );
				}
			}
		} catch (ex) {
			this.debug(ex);
		}
	}
	//上传完成的确定按钮
	$('#upload-confirm').click(function(){
		var url = $("#hideAjaxUrl").val();
		var search = $("#mon-search").val();
    	url = url+"&search="+search ;
    	url = encodeURI(url);
    	$.get(url,function(info){
			$("#list_ajax").html('').html(info);
		});
		$("#coverlayer").remove();
		$("#osa_upload").remove();
	});
	//上传完成的取消按钮
	$('#upload-cancel').click(function(){
		$("#coverlayer").remove();
		$("#osa_upload").remove();
	});

};