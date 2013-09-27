$(document).ready(function(){
	//开启
	$(".start_alarm").click(function(){
		var url = $(this).attr('url');
		var targeturl = $("#hide_url").attr('value');
		var offset = $("#hide_offset").attr('value');		
		$.post(url,function(msg){
			window.location = targeturl+'&offset='+offset;
		});		
	});
	//停止
	$(".stop_alarm").click(function(){
		var url = $(this).attr('url');
		var targeturl = $("#hide_url").attr('value');
		var offset = $("#hide_offset").attr('value');		
		$.post(url,function(msg){
			window.location = targeturl+'&offset='+offset;
		});		
	});
	//删除
	$("#del_alarm").click(function(){
		var delarr = new Array();
		$("input:checked").not("#checkall").each(function(){
			var id = $(this).attr('value');
			delarr.push(id);
		});
		if(delarr.length<=0){
			alert('请选择要删除的报警项目');
			return false;
		}
		if(confirm("确定要删除选中的项？")==true){
			var callbackurl = $("#hide_url").attr('value');
			var url = "index.php?c=panel&a=delAlarms";
			$.post(url ,{'arr':delarr},function(msg){
				if(msg.indexOf('no_permissions')!=-1){
					window.location = "index.php?c=panel&a=permiterror&left=monitorlist";
				}else if(msg.indexOf('success')!=-1){
					window.location = callbackurl;
				}else{
					alert('删除失败');
				}
			});
		}	
	});
});