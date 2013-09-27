$(document).ready(function(){
	$.ajaxSetup({
		  async: false
	}); 
	var flag = true ;
	$('#username').click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入用户名');
	});
	$('#username').blur(function(){
		var username = $.trim($(this).val());
		var url = 'index.php?c=account&a=checkname';
		if(username == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入用户名');
			flag = false;
			//return;
		}else if(username!=username.match(/^[a-zA-Z0-9\u4e00-\u9fa5][a-zA-Z0-9\u4e00-\u9fa5\.\_\@]+$/)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入正确格式的用户名');
			flag = false;
		}else{
			$.post(url,{'username':username},function(msg){
				if(msg.indexOf('success')!=-1){
					$('#username').parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('用户名可用');
				}else{
					$('#username').parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('用户名已被使用');
					flag = false;
				}		
			});	
		}
	});
	
	//密码验证
	$("#passwd").blur(function(){
		var passwd = $.trim($(this).val());
		var username = $.trim($("#username").val());
		var score = checkStrong(passwd,username);
		if(score == 0){//无字符
			$(this).parent().find(".notice").css({'background':'url(images/pwbg.png) no-repeat 0 0'});
			flag = false;
		}else if(score <50){//弱
			$(this).parent().find(".notice").css({'background':'url(images/pwbg.png) no-repeat 0 -25px'});
			flag = false;
		}else if(score <60){//一般
			$(this).parent().find(".notice").css({'background':'url(images/pwbg.png) no-repeat 0 -50px'});
		}else{//强
			$(this).parent().find(".notice").css({'background':'url(images/pwbg.png) no-repeat 0 -75px'});
		}	
	});
	
	//密码强度验证
	//	>= 60: 强（Strong）
	//	>= 50: 一般（Average）
	//	>= 25: 弱（Weak）
	//	>= 0: 非常弱 
	function checkStrong(passwd,username){
		var score = 0;
		if(passwd.length ==0) return 0; //为空
		if(passwd.toLowerCase()==username.toLowerCase()){
			score = 10 ;//跟名字一样 表示弱
			return score;		
		}
		//5 分: 小于等于 4 个字符 ;10 分: 5 到 7 字符; 25 分: 大于等于 8 个字符 
		score += passwd.length<=4?5:(passwd.length>=8?25:10);
		//0 分: 没有字母;10 分: 全都是小（大）写字母;20 分: 大小写混合字母 
		score += !passwd.match(/[a-z]/i)?0:(passwd.match(/[a-z]/) && passwd.match(/[A-Z]/)?20:10);
		//0 分: 没有数字;10 分: 1 个数字;20 分: 大于等于 3 个数字 
		score += !passwd.match(/[0-9]/)?0:(passwd.match(/[0-9]/g).length >= 3?20:10); 
		//0 分: 没有符号;10 分: 1 个符号;25 分: 大于 1 个符号 
		score += !passwd.match(/\W/)?0:(passwd.match(/\W/g).length > 1?25:10);
		//奖励: 2 分: 字母和数字;3 分: 字母、数字和符号;5 分: 大小写字母、数字和符号 
		score += !passwd.match(/[0-9]/) || !passwd.match(/[a-z]/i)?0:(!passwd.match(/\W/)?2:(!passwd.match(/[a-z]/) || !passwd.match(/[A-Z]/)?3:5));
		return score;
	}
	
	$("#confirmpasswd").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入确认密码');
	});
	$("#confirmpasswd").blur(function(){
		var confirmpasswd = $.trim($(this).val());
		var passwd = $.trim($("#passwd").val());
		if(confirmpasswd == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入确认密码');
			flag = false;
		}else if(confirmpasswd!=passwd){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('确认密码跟密码不一致');
			flag = false;
		}else{
			var username = $.trim($("#username").val());
			var score = checkStrong(passwd,username);
			if(score < 50){//无字符
				$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('密码太弱');
				flag = false;
			}else{
				$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('');
			}
		}
	});
	//邮箱验证
	function isEmail(strEmail){	
		if (strEmail.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1)
			return true;
		else
			return false;
	}
	//邮箱验证
	$("#email").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入邮箱');
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
			var hidemail = $("#hidemail").val();
			if(hidemail!=''&&hidemail!=email){
				var url ="index.php?c=account&a=checkemail";
				$.post(url,{'email':email},function(msg){
					if(msg.indexOf('success')!=-1){
						$('#email').parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('邮箱可用');
					}else{
						$('#email').parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('邮箱已被使用');
						flag = false;
					}		
				});
			}else{
				$('#email').parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
			}
		}	
	});
	//手机号码验证
	$("#iphone").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入手机号码');
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
	
	//获取工作日期
	function getWorkDay(){
		var workday = '';
		$(".week:checked").each(function(){
			workday += $(this).attr('value')+'|';
		});
		return workday.replace(/(^\|*)|(\|*$)/g ,"");	
	}
	//获取工作日期的工作时间
	function getWorkTime(){
		var stime = $.trim($("#stime").val());
		var etime = $.trim($("#etime").val());
		if(stime == ''||etime == ''){
			return '';
		}
		return stime +'-'+ etime ;
	}
	
	//添加提交
	$("#user_add").click(function(){
		flag = true ;
		$('#username').blur();
		$("#passwd").blur();
		$("#confirmpasswd").blur();
		$("#email").blur();
		$("#iphone").blur();
		if(flag == false){
			return false;
		}
		var rid = $("#role_select option:selected").attr('value');
		if(rid == ''){
			alert('请选择角色名称');
			return false;
		}
		var workdate = getWorkDay();
		if(workdate == ''){
			alert('请选择工作日期');
			return false;
		}
		var worktime = getWorkTime();
		if(worktime == ''){
			alert("请填写工作时间");
			return false ;
		}
		var nickname = $.trim($("#nickname").val());
		var sign = $.trim($("#signature").val());
		var username = $.trim($("#username").val());
		var passwd = $.trim($("#passwd").val());
		var email = $.trim($("#email").val());
		var phone = $.trim($("#iphone").val());
		var url = "index.php?c=account&a=useradd";
		$.post(url,{
			'username':username,
			'passwd':passwd,
			'rid':rid,
			'email':email,
			'phone':phone,
			'workdate':workdate,
			'worktime':worktime,
			'nickname':nickname,
			'sign':sign
		},function(msg){
			if(msg.indexOf('success')!=-1){
				window.location="index.php?c=account&a=userlist";
			}else{
				alert("用户添加失败");
			}
		});
	});
	
	//编辑提交
	$("#user_edit").click(function(){
		
		flag = true ;
		$("#iphone").blur();
		$("#email").blur();
		if(flag == false){
			return false;
		}
		var rid = $("#role_select option:selected").attr('value');
		if(rid == ''){
			alert('请选择角色名称');
			return false;
		}
		var workdate = getWorkDay();
		if(workdate == ''){
			alert('请选择工作日期');
			return false;
		}
		var worktime = getWorkTime();
		if(worktime == ''){
			alert("请填写工作时间");
			return false ;
		}
		var nickname = $.trim($("#nickname").val());
		var sign = $.trim($("#signature").val());
		var username = $.trim($("#username").val());
		var email = $.trim($("#email").val());
		var phone = $.trim($("#iphone").val());
		var url = $("#hideurl").attr('value');
		$.post(url,{
			'username':username,
			'rid':rid,
			'email':email,
			'phone':phone,
			'workdate':workdate,
			'worktime':worktime,
			'nickname':nickname,
			'sign':sign
		},function(msg){
			if(msg.indexOf('success')!=-1){
				window.location="index.php?c=account&a=userlist";
			}else{
				alert("编辑失败");
			}
		});
	});
});