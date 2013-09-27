$(document).ready(function(){
	
	//全选 |全不选|反选
	$("#checkall").click(function(){
		$(".perm").attr('checked','checked');
	});
	$("#cancelall").click(function(){
		$(".perm").attr('checked',false);
	});
	$("#invert").click(function(){
		$(".perm").each(function(){
			$(this).attr('checked',!this.checked);
		});
	});
	
	//获取权限列表
	function getPermList(){
		var permlist = '';
		$(".perm:checked").each(function(){
			permlist +=$(this).attr('value')+',';
		});
		return permlist.replace(/(^\,*)|(\,*$)/g ,"");
	}
	
	$.ajaxSetup({
		  async: false
	}); 
	var flag = true ;
	$('#rolename').click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入角色名');
	});
	$('#rolename').blur(function(){
		var rolename = $.trim($(this).val());
		var url = 'index.php?c=account&a=checkrname';
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
					$('#rolename').parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('角色名可用');
				}else{
					$('#rolename').parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('角色名已被使用');
					flag = false;
				}		
			});	
		}
	});
	
	//添加提交
	$("#role_add").click(function(){
		flag = true;
		$('#rolename').blur();
		if(flag == false){
			return false;
		}
		var descript = $.trim($("#roledescript").val());
		var rolename = $.trim($('#rolename').val());
		var rolestr = getPermList();
		if(rolestr == ''){
			alert('请选择角色对应的权限');
			return false;
		}
		var url = "index.php?c=account&a=roleadd";
		$.post(url,{'rolename':rolename,'descript':descript,'rolestr':rolestr},
				function(msg){
					if(msg.indexOf('success')!=-1){
						alert("添加成功，用户在下次登录时候权限生效");
						window.location="index.php?c=account&a=rolelist";
					}else{
						alert("添加失败");
					}
				});
	});
	//编辑提交
	$("#role_edit").click(function(){
		var descript = $.trim($("#roledescript").val());
		var rolename = $.trim($('#rolename').val());
		var rolestr = getPermList();
		if(rolestr == ''){
			alert('请选择角色对应的权限');
			return false;
		}
		var url = $("#hideurl").attr('value');
		$.post(url,{'rolename':rolename,'descript':descript,'rolestr':rolestr},
			function(msg){
				if(msg.indexOf('success')!=-1){
					alert("编辑成功，用户在下次登录时候权限生效");
					window.location="index.php?c=account&a=rolelist";
				}else{
					alert("编辑失败");
				}
			});
	});
});	