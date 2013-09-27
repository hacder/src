$(document).ready(function(){
	
	$.ajaxSetup({
		  async: false
	}); 
	var flag = true ;

	//邮箱验证
	function isEmail(strEmail){	
		if (strEmail.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1)
			return true;
		else
			return false;
	}
	//邮箱验证
	$("#email").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
	});
	$("#email").blur(function(){
		var email = $.trim($(this).val());
		if(email == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入邮箱');
			flag = false;
			//return;
		}else if(!isEmail(email)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入合法的邮箱');
			flag = false;
		}else{//做唯一性判断
			var url ="index.php?c=account&a=email_isexist";
			var hideEmail = $.trim($('#hide-email').val());
			if(email == hideEmail){
				$('#email').parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
			}else{
				$.post(url,{'email':email},function(msg){
					if(msg.indexOf('success')!=-1){
						$('#email').parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
					}else{
						$('#email').parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('邮箱已被使用');
						flag = false;
					}		
				});
			}
		}	
	});
	//手机号码验证
	$("#iphone").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
	});
	$("#iphone").blur(function(){
		var phone = $.trim($(this).val());
		if(phone == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入手机号码');
			flag = false;
		}else if(!isPhone(phone)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入合法的手机号码');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('');
		}
	});
	//是否手机号码验证
	function isPhone(phonestr){
		if(phonestr.length != 11){
			return false ;
		}
		var reg = /^((13[0-9]{1}|15[0-9]{1}|18[0-9]{1}){1}\d{8})$/;
		if(!reg.test(phonestr)){
			return false;
		}
		return true ;
	}
	
	
	//添加提交
	$("#user-edit").click(function(){
		flag = true ;
		$("#email").blur();
		$("#iphone").blur();
		if(flag == false){
			return false;
		}
		var rolename = $.trim($("#rolename").val());
		if(rolename == '请选择角色'){
			tipsAlert('请选择角色名称');
			return false;
		}
		var id = $("#hide-id").val();
		var realname = $.trim($("#nickname").val());
		var username = $.trim($("#username").val());
		var email = $.trim($("#email").val());
		var phone = $.trim($("#iphone").val());
		var url = "index.php?c=account&a=user_edit";
		$.post(url,{
			'id':id,
			'username':username,
			'rolename':rolename,
			'email':email,
			'phone':phone,
			'realname':realname
		},function(msg){
			if(msg.indexOf('success')!=-1){
				var callback = function(result){
					if(result == true){					
						window.location = "index.php?c=account&a=userlists";
					}
				};
				tipsAlert('用户编辑成功',callback);
			}else{
				tipsAlert("用户编辑失败");
			}
		});
	});
});