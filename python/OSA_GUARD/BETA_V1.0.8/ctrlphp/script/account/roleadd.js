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
	var flag = true ;
	$('#rolename').click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
	});
	$('#rolename').blur(function(){
		var rolename = $.trim($(this).val());
		var url = 'index.php?c=account&a=rolename_isexist';
		if(rolename == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入角色名');
			flag = false;
			//return;
		}else if(rolename!=rolename.match(/^[a-zA-Z0-9\u4e00-\u9fa5][a-zA-Z0-9\u4e00-\u9fa5\.\_\@]+$/)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入正确格式的角色名');
			flag = false;
		}else{
			$.post(url,{'rolename':rolename},function(msg){
				if(msg.indexOf('success')!=-1){
					$('#rolename').parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
				}else{
					$('#rolename').parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('角色名已被使用');
					flag = false;
				}		
			});	
		}
	});
	
	//添加提交
	$("#role-add").click(function(){
		flag = true;
		$('#rolename').blur();
		if(flag == false){
			return false;
		}
		var roledes = $.trim($("#roledes").val());
		var rolename = $.trim($('#rolename').val());
		var perstr = getPermList();
		if(perstr == ''){
			tipsAlert('请选择角色对应的权限');
			return false;
		}
		var url = "index.php?c=account&a=role_add";
		$.post(url,{'rolename':rolename,'roledes':roledes,'perstr':perstr},
			function(msg){
				if(msg.indexOf('success')!=-1){
					var callback = function(result){
						if(result == true){					
							window.location = "index.php?c=account&a=rolelists";
						}
					};
					tipsAlert("添加成功，用户在下次登录时候权限生效",callback);
				}else{
					tipsAlert("添加失败");
				}
			});
	});
});