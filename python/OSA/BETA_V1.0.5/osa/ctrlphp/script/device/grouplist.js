$(document).ready(function(){
	
	
	$("#group_del").click(function(){
		
		var delarr = new Array();
		$(".checkbox:checked").each(function(){
			delarr.push($(this).attr('value'));
		});
		if(delarr.length == 0){
			alert('请选择要删除的项!');
			return false ;
		}
		if(confirm('确定要删除选中的项？') == true){
			var url = "index.php?c=device&a=groupdel";
			$.post(url,{'delarr':delarr},function(msg){
				if(msg.indexOf('no_permissions')!=-1){
					window.location = 'index.php?c=device&a=permiterror&left=devgrouplist';
				}else if(msg.indexOf('success')!=-1){
					window.location = 'index.php?c=device&a=devgrouplist';
				}else{
					alert('删除失败');
				}
			});			
		}	
	});
});