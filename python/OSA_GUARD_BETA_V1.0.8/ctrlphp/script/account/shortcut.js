$(document).ready(function(){
	
	
	/*******************************  处理全选/全不选/全不选反选  ***************************************/
	//全选 |全不选|反选
	$("#checkall").click(function(){
		$(".input_c4").attr('checked','checked');
	});
	$("#cancelall").click(function(){
		$(".input_c4").attr('checked',false);
	});
	$("#invert").click(function(){
		$(".input_c4").each(function(){
			$(this).attr('checked',!this.checked);
		});
	});
	
	//获取权限列表
	function getShortCut(){
		var shortcut = '';
		$(".input_c4:checked").each(function(){
			shortcut +=$(this).attr('value')+',';
		});
		return shortcut.replace(/(^\,*)|(\,*$)/g ,"");
	}
	
	
	/**
	 *  save shortcut
	 */
	$("#shortcut-save").click(function(){
		
		var shortcut = getShortCut();
		if(shortcut == ''){
			return false;
		}
		var url = "index.php?c=account&a=shortcut_set";
		$.post(url,{'shortcut':shortcut},function(msg){
			$(".time_pro").show("slow");
			setTimeout(function(){
				$(".time_pro").hide("slow");
			}, 5000);
		});
	});
	
});