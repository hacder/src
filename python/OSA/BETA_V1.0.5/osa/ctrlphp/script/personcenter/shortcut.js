$(document).ready(function(){
	
	$("#shortcut_confirm").click(function(){
		var shortcut = getShortCut();
		if(shortcut == ''){
			alert('快捷菜单设置不能为空');
			return false ;
		}
		var url = "index.php?c=personcenter&a=setShortCut";
		$.post(url,{'shortcut':shortcut},function(msg){
			if(msg.indexOf('success')!=-1){
				alert('快捷菜单设置成功');
				window.location = "index.php?c=home&a=index";
			}else{
				alert('快捷菜单设置失败');
			}		
		});
		
	});
	
	function getShortCut(){
		var shortcut = '';
		$(":checkbox:checked").each(function(){
			shortcut +=$(this).attr('value')+',';
		});
		return shortcut.replace(/(^\,*)|(\,*$)/g, "");
	}
});