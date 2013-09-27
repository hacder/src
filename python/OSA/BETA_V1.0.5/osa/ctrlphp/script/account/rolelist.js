$(document).ready(function(){
	
	//处理全选/反选
	$("#checkall").click(function(){
		$(".checkbox").attr('checked',this.checked);
	});
	//启用，禁用
	$(".updatestatus").click(function(){
		var status = $(this).attr('status');
		var id = $(this).attr('rid');
		if(status==0){
			var url = "index.php?c=account&a=openRoles&id="+id; 
		}else if(status == 1){
			var url = "index.php?c=account&a=stopRoles&id="+id;
		}
		var targeturl = $("#hide_url").attr('value');
		var offset = $("#hide_offset").attr('value');		
		$.post(url,function(msg){
			window.location = targeturl+'&offset='+offset;
		});	
	});
	
	//删除
	$("#del_role").click(function(){
		var delarr = new Array();
		$("input:checked").not("#checkall").each(function(){
			var id = $(this).attr('value');
			delarr.push(id);
		});
		if(delarr.length<=0){
			alert('请选择要删除的角色');
			return false;
		}
		if(confirm("确定要删除选中的项?") == true){
			var callbackurl = $("#hide_url").attr('value');
			var url = "index.php?c=account&a=delRoles";
			$.post(url ,{'arr':delarr},function(msg){
				if(msg.indexOf('no_permissions')!=-1){
					window.location = "index.php?c=account&a=permiterror";
				}else if(msg.indexOf('success')!=-1){
					window.location = callbackurl;
				}else{
					alert('删除失败');
				}
			});
		}
				
	});
	
	//控制跳页
    $('.dojump').click(function(){
		var page = $('#to_page').val();
		var pagenum = $('#hide_pagenums').val();
		if(page<1){
			page = 1;
		}else if(page > pagenum){
			page = pagenum;
		}
		var url = $('#hide_url').val();
		var per_page = $('#hide_perpage').val();
		var offset = (page-1)*parseInt(per_page);
		window.location = url+'&offset='+offset;
	});
});