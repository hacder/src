function isEmail(strEmail){	
	if (strEmail.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1)
		return true;
	else
		return false;
}
$(document).ready(function(){
	$.ajaxSetup({
		  async: false
	}); 
	var flag = true ;
	$('#servername').click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入SMTP服务器');
	});
	$('#servername').blur(function(){
		var servername = $.trim($(this).val());
		if(servername == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入SMTP服务器');
			flag = false;
			//return;
		}else if(!isDomain(servername)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('SMTP服务器为域名或ip');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
	});
	
	//验证是否为域名或ip
	function isDomain(strDomain){
		if(/^((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]\d)|\d)(\.((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]\d)|\d)){3}$|^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$/.test(strDomain))
		{
			return true;
		}else{
			return false;
		}
	}
	//判断是否为正整数
	function isNumber(strInt){
		if(/^[0-9]*[1-9][0-9]*$/.test(strInt)){
			return true;
		}else{
			return false;
		}
	}
	
	//端口号验证
	$("#serverport").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入SMTP端口');
	});
	$('#serverport').blur(function(){
		var serverport = $.trim($(this).val());
		if(serverport == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入SMTP端口');
			flag = false;
			//return;
		}else if(!isNumber(serverport)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('SMTP端口为正整数');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
	});
	
	//密码验证
	$("#serverpass").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入SMTP密码');
	});
	$('#serverpass').blur(function(){
		var serverpass = $.trim($(this).val());
		if(serverpass == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入SMTP密码');
			flag = false;
			//return;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
	});
	
	//发件人地址验证
	$("#sendaddress").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入发件人地址');
	});
	$('#sendaddress').blur(function(){
		var sendaddress = $.trim($(this).val());
		if(sendaddress == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入发件人地址');
			flag = false;
			//return;
		}else if(!isEmail(sendaddress)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('发件人地址必须为邮箱');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
	});
	
	//收件人地址验证
	$("#receiveadd").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入收件人地址');
	});
	$('#receiveadd').blur(function(){
		var receiveadd = $.trim($(this).val());
		if(receiveadd == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入收件人地址');
			flag = false;
			//return;
		}else if(!isEmail(receiveadd)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('收件人地址必须为邮箱');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
	});
	
	//发件人姓名验证 sendname
	$("#sendname").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入发件人名称');
	});
	$('#sendname').blur(function(){
		var sendname = $.trim($(this).val());
		if(sendname == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入发件人名称');
			flag = false;
			//return;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
	});
	
	//stmp用户名验证 sendname
	$("#serveruser").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入STMP用户名称');
	});
	$('#serveruser').blur(function(){
		var serveruser = $.trim($(this).val());
		if(serveruser == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入STMP用户名称');
			flag = false;
			//return;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
	});
	
	//测试邮件
	$("#testemail").click(function(){
		flag = true ;
		$('#servername').blur();
		$('#serverport').blur();
		$('#serveruser').blur();
		$('#serverpass').blur();
		$('#sendaddress').blur();
		$('#sendname').blur();
		$('#receiveadd').blur();
		if(flag == false){
			alert("你填的基本信息有误");
			return false;
		}
		var smtphost = $.trim($('#servername').val());
		var smtpport = $.trim($('#serverport').val());
		var smtpuser = $.trim($('#serveruser').val());
		var smtppass = $.trim($('#serverpass').val());
		var sendemail = $.trim($('#sendaddress').val());
		var senduser = $.trim($('#sendname').val());
		var receivemail = $.trim($('#receiveadd').val());
		var url = "index.php?c=panel&a=testemail";
		$.post(url,{
			'smtphost':smtphost,
			'smtpport':smtpport,
			'smtpuser':smtpuser,
			'smtppass':smtppass,
			'sendemail':sendemail,
			'senduser':senduser,
			'receivemail':receivemail
		},function(msg){
			alert(msg);
		});
	});
	
	//保存设置到配置文件
	$("#smtpsave").click(function(){
		flag = true ;
		$('#servername').blur();
		$('#serverport').blur();
		$('#serveruser').blur();
		$('#serverpass').blur();
		$('#sendaddress').blur();
		$('#sendname').blur();
		$('#receiveadd').blur();
		if(flag == false){
			alert("你填的基本信息有误");
			return false;
		}
		var smtphost = $.trim($('#servername').val());
		var smtpport = $.trim($('#serverport').val());
		var smtpuser = $.trim($('#serveruser').val());
		var smtppass = $.trim($('#serverpass').val());
		var sendemail = $.trim($('#sendaddress').val());
		var senduser = $.trim($('#sendname').val());
		var receivemail = $.trim($('#receiveadd').val());
		var url = "index.php?c=panel&a=savesmtpset";
		$.post(url,{
			'smtphost':smtphost,
			'smtpport':smtpport,
			'smtpuser':smtpuser,
			'smtppass':smtppass,
			'sendemail':sendemail,
			'senduser':senduser,
			'receivemail':receivemail
		},function(msg){
			alert('设置成功');
		});
		
	});
});