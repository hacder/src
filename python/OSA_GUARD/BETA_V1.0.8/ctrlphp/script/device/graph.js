$(document).ready(function(){
	
	/**********************启用，删除，暂停**********************************/
	$(".graph-pause").live("click",function(){
		
		var object = $(this);
		var id = $(this).parent().parent().find('.graph-checkbox').attr('value');
		var ipid = $(this).parent().parent().find('.input-hide').val();
		var url = "index.php?c=device&a=device_stop";
		$.post(url,{'id':id,'ipid':ipid},function(msg){
			object.hide();
			object.parent().find('.graph-open').show();
		});
	});
	
	$(".graph-open").live("click",function(){
		var object = $(this);
		var id = $(this).parent().parent().find('.graph-checkbox').attr('value');
		var ipid = $(this).parent().parent().find('.input-hide').val();
		var url = "index.php?c=device&a=device_open";
		$.post(url,{'id':id,'ipid':ipid},function(msg){
			object.hide();
			object.parent().find('.graph-pause').show();
		});
	});
	
	$(".graph-del").live("click",function(){
		var object = $(this);
		var id = $(this).parent().parent().find('.graph-checkbox').attr('value');
		var ipid = $(this).parent().parent().find('.input-hide').val();
		var url = "index.php?c=device&a=device_del";
		var callback = function(result){
			if(result==true){
				$.post(url,{'id':id,'ipid':ipid},function(msg){
					object.parents('.graph-unit').remove();
				});	
			}
		};
		var msg = '删除后记录不可恢复，确认删除吗？';
		tipsConfirm(msg,callback);
	});
	
	
	/**************************批量处理 暂停，启用，删除**********************************/
	
	function graph_record_tranform(type){
		$(".graph-checkbox:visible").each(function(){
			if(type == 'pause'){
				if($(this).is(":checked")){
					object = $(this).parent().parent().parent();
					object.find('.graph-open').show();
					object.find('.graph-pause').hide();
				}
			}else if(type=='open'){
				if($(this).is(":checked")){
					object = $(this).parent().parent().parent();
					object.find('.graph-pause').show();
					object.find('.graph-open').hide();
				}
			}
			else if(type=='del'){
				if($(this).is(":checked")){
					object = $(this).parent().parent().parent();
					object.remove();
				}	
			}
		});	
		$(":checkbox").attr('checked',false);
	}
	
	$("#record-pause").click(function(){
		var idarr = new Array();
		var iparr = new Array();
		$(".graph-checkbox:visible").each(function(){
			if($(this).is(":checked")){
				idarr.push($(this).attr('value'));
				iparr.push($(this).parent().find('.input-hide').val());
			}
		});
		if(idarr.length == 0){
			var msg = '请选择需要暂停的项';
			tipsAlert(msg);
			return false;
		}
		var url = "index.php?c=device&a=device_stop_batch";
		$.post(url,{'idarr':idarr,'iparr':iparr},function(msg){
			graph_record_tranform('pause');
		});	
	});
	
	$("#record-open").click(function(){
		var idarr = new Array();
		var iparr = new Array();
		$(".graph-checkbox:visible").each(function(){
			if($(this).is(":checked")){
				idarr.push($(this).attr('value'));
				iparr.push($(this).parent().find('.input-hide').val());
			}
		});
		if(idarr.length == 0){
			var msg = '请选择需要启用的项';
			tipsAlert(msg);
			return false;
		}
		var url = "index.php?c=device&a=device_open_batch";
		$.post(url,{'idarr':idarr,'iparr':iparr},function(msg){
			graph_record_tranform('open');
		});
	});
	
	
	//批量删除
	$("#record-del").click(function(){
		var idarr = new Array();
		var iparr = new Array();
		$(".graph-checkbox:visible").each(function(){
			if($(this).is(":checked")){
				idarr.push($(this).attr('value'));
				iparr.push($(this).parent().find('.input-hide').val());
			}
		});
		if(idarr.length == 0){
			var msg = '请选择需要删除的项';
			tipsAlert(msg);
			return false;
		}
		var callback = function(result){
			if(result == true){
				var url = "index.php?c=device&a=device_del_batch";
				$.post(url,{'idarr':idarr,'iparr':iparr},function(msg){
					graph_record_tranform('del');
				});
			}
		};
		var msg ='删除后记录不可恢复，确认删除吗？';
		tipsConfirm(msg,callback);
	});
	
	
	/**************************ajax 交互搜索*****************************/
	
	function graph_records_ajaxshow(info){
		$('.graph-unit').hide();
		info = eval(info);
		for(i in info){
			$('.graph-checkbox').each(function(){
				var checkid = $(this).attr('value');
				if(checkid == info[i].id){
					$(this).parent().parent().parent().show();
				}
			});
		}	
	}
	
	$("#record-search").keyup(function(){
		var url = "index.php?c=device&a=list_search";
		var value = $.trim($(this).val());
		$.post(url,{'search':value},function(info){
			graph_records_ajaxshow(info);	
		});
	});
	
	
	/************************** 添加设备 *******************************/
	$("#record-add").click(function(){
		$("#divece-pop").find("#devname-pop").attr('value','');
		$("#divece-pop").find("#ipname-pop").attr('value','');
		$("#divece-pop").find(".tag_input").attr('value','请选择类型');
		devicePop();
	});
});