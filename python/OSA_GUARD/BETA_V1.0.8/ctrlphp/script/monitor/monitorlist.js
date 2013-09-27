$(document).ready(function(){
	
	
	/**
	 * 按每页显示数量查询
	 */
	$(".page_li").live('click',function(){
		var pagenum = $.trim($(this).html());
		var url = $("#hide_url").val();
		window.location = url+"&pagenum="+pagenum;	
	});
	
	/**
	 * 全选/全不选
	 */
	$("#checkall").live('click',function(){
		$(".check_one").attr('checked',this.checked);
	});
	
	
	/**
	 * 暂停、启用、删除
	 */
	$("#mon-pause").click(function(){
		var idarr = new Array();
		$(".check_one").each(function(){
			if($(this).is(":checked")){
				idarr.push($(this).attr('value'));
			}
		});
		if(idarr.length == 0){
			var msg = '请选择需要暂停的项';
			tipsAlert(msg);
			return false;
		}
		var url = "index.php?c=monitor&a=monitor_stop_batch";
		$.post(url,{'idarr':idarr},function(msg){
			var targetUrl = $("#hideUrl").val();
			var search = $("#mon-search").val();
			targetUrl = targetUrl+"&search="+search ;
			window.location = targetUrl;
		});
		
	});
	
	$("#mon-play").click(function(){
		var idarr = new Array();
		$(".check_one").each(function(){
			if($(this).is(":checked")){
				idarr.push($(this).attr('value'));
			}
		});
		if(idarr.length == 0){
			var msg = '请选择需要开启的项';
			tipsAlert(msg);
			return false;
		}
		var url = "index.php?c=monitor&a=monitor_open_batch";
		$.post(url,{'idarr':idarr},function(msg){
			var targetUrl = $("#hideUrl").val();
			var search = $("#mon-search").val();
			targetUrl = targetUrl+"&search="+search ;
			window.location = targetUrl;
		});
		
	});
	
	$("#mon-del").click(function(){
		var idarr = new Array();
		$(".check_one").each(function(){
			if($(this).is(":checked")){
				idarr.push($(this).attr('value'));
			}
		});
		if(idarr.length == 0){
			var msg = '请选择需要删除的项';
			tipsAlert(msg);
			return false;
		}
		var callback = function(result){
			if(result == true){
				var url = "index.php?c=monitor&a=monitor_del_batch";
				$.post(url,{'idarr':idarr},function(msg){
					var targetUrl = $("#hideUrl").val();
					var search = $("#mon-search").val();
					targetUrl = targetUrl+"&search="+search ;
					window.location = targetUrl;
				});
			}
		};
		var msg ='删除后记录不可恢复，确认删除吗？';
		tipsConfirm(msg,callback);	
	});
	
	
	$(".mon-pause").live('click',function(){
		
		var id = $(this).parents(".record-list").find(".check_one").attr('value');
		var url = "index.php?c=monitor&a=monitor_stop";
		$.post(url,{'id':id},function(msg){
			var targetUrl = $("#hideUrl").val();
			var search = $("#mon-search").val();
			targetUrl = targetUrl+"&search="+search ;
			window.location = targetUrl;
		});
	});
	
	$(".mon-play").live('click',function(){
		
		var id = $(this).parents(".record-list").find(".check_one").attr('value');
		var url = "index.php?c=monitor&a=monitor_open";
		$.post(url,{'id':id},function(msg){
			var targetUrl = $("#hideUrl").val();
			var search = $("#mon-search").val();
			targetUrl = targetUrl+"&search="+search ;
			window.location = targetUrl;
		});
	});
	
	$(".mon-del").live('click',function(){
		
		var id = $(this).parents(".record-list").find(".check_one").attr('value');
		var callback = function(result){
			if(result == true){
				var url = "index.php?c=monitor&a=monitor_del";
				$.post(url,{'id':id},function(msg){
					var targetUrl = $("#hideUrl").val();
					var search = $("#mon-search").val();
					targetUrl = targetUrl+"&search="+search ;
					window.location = targetUrl;
				});
			}
		};
		var msg ='删除后记录不可恢复，确认删除吗？';
		tipsConfirm(msg,callback);
	});
	
	
	//搜索
	$("#mon-search").keyup(function(){
		var url = $("#hideAjaxUrl").val();
		var search = $("#mon-search").val();
    	url = url+"&search="+search ;
    	$.get(url,function(info){
			$("#list_ajax").html('').html(info);
		});
	});
	
	//状态跳转(正常|异常|所有)
	$(".tag_li").live('click',function(){

		var status = $(this).html();
		var search = $("#mon-search").val();
		var url = $("#hideAjaxUrl").val();
		url = url+"&search="+search+"&status="+status;
		url = encodeURI(url);
		$.get(url,function(info){
			$("#list_ajax").html('').html(info);
		});
	});
	
	//跳页处理
	 $('.dojump').live('click',function(){
		var page = $('#to_page').val();
		var pagenum = $('#hide_pagenums').val();
		if(page<1){
			page = 1;
		}
		if(parseInt(page)>parseInt(pagenum)){
			page = pagenum;
		}
		var url = $('#hide_url').val();
		var per_page = $('#hide_perpage').val();
		var offset = (page-1)*parseInt(per_page);
		window.location = url+'&offset='+offset;
	});
	 
	 
	$(".rateimg").live('click',function(){
		object = $(this).parent().find('.ratevalue');
		var itemid = $(this).parents('.record-list').find('.check_one').attr('value');
		var value = object.attr('rate');
		boxTimeSet(object,value,itemid);
	}); 
	
	
	
});