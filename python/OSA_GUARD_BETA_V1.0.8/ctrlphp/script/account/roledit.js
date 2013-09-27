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
	function getPermList(){
		var permlist = '';
		$(".input_c4:checked").each(function(){
			permlist +=$(this).attr('value')+',';
		});
		return permlist.replace(/(^\,*)|(\,*$)/g ,"");
	}
	
	/***************************  提交处理 *******************************************/
	$.ajaxSetup({
		  async: false
	}); 
	
	//添加提交
	$("#role-edit").click(function(){
		flag = true;
		$('#rolename').blur();
		if(flag == false){
			return false;
		}
		var roledes = $.trim($("#roledes").val());
		var id = $.trim($('#hide-id').val());
		var perstr = getPermList();
		if(perstr == ''){
			tipsAlert('请选择角色对应的权限');
			return false;
		}
		var url = "index.php?c=account&a=role_edit";
		$.post(url,{'id':id,'roledes':roledes,'perstr':perstr},
			function(msg){
				if(msg.indexOf('success')!=-1){
					var callback = function(result){
						if(result == true){					
							window.location = "index.php?c=account&a=rolelists";
						}
					};
					tipsAlert("编辑成功，权限己生效",callback);
				}else{
					tipsAlert("编辑失败");
				}
			});
	});
});