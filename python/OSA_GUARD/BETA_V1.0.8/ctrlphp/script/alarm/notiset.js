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
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
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
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
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
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
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
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
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
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
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
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
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
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
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
			tipsAlert("你填的SMTP基本信息有误");
			return false;
		}
		var smtphost = $.trim($('#servername').val());
		var smtpport = $.trim($('#serverport').val());
		var smtpuser = $.trim($('#serveruser').val());
		var smtppass = $.trim($('#serverpass').val());
		var sendemail = $.trim($('#sendaddress').val());
		var senduser = $.trim($('#sendname').val());
		var receivemail = $.trim($('#receiveadd').val());
		var url = "index.php?c=monitor&a=smtp_testemail";
		$.post(url,{
			'smtphost':smtphost,
			'smtpport':smtpport,
			'smtpuser':smtpuser,
			'smtppass':smtppass,
			'sendemail':sendemail,
			'senduser':senduser,
			'receivemail':receivemail
		},function(msg){
			tipsAlert(msg);
		});
	});
	
	//保存设置到配置文件
	$("#notiset-save").click(function(){
		flag = true ;
		$('#servername').blur();
		$('#serverport').blur();
		$('#serveruser').blur();
		$('#serverpass').blur();
		$('#sendaddress').blur();
		$('#sendname').blur();
		$('#receiveadd').blur();
		if(flag == false){
			tipsAlert("你填的SMTP基本信息有误");
			return false;
		}
		var smtphost = $.trim($('#servername').val());
		var smtpport = $.trim($('#serverport').val());
		var smtpuser = $.trim($('#serveruser').val());
		var smtppass = $.trim($('#serverpass').val());
		var sendemail = $.trim($('#sendaddress').val());
		var senduser = $.trim($('#sendname').val());
		var receivemail = $.trim($('#receiveadd').val());
		/*******新增参数********/
		var isemail = 1;
		var issms = getIsSms();
		var ismsn = getIsMsn();
		var isgtalk = getIsGtalk();
		
		var notisms = getNotiSms();
		var notimsn = getNotiMsn();
		var notigtalk = getNotiGtalk();
		var mnumitem = $.trim($("#mnumitem").val());
		var mnumip = $.trim($("#mnumip").val());
		
		var url = "index.php?c=monitor&a=notiset_save";
		$.post(url,{
			'isemail':isemail,
			'issms':issms,
			'ismsn':ismsn,
			'isgtalk':isgtalk,
			'notisms':notisms,
			'notimsn':notimsn,
			'notigtalk':notigtalk,
			'mnumitem':mnumitem,
			'mnumip':mnumip,
			'smtphost':smtphost,
			'smtpport':smtpport,
			'smtpuser':smtpuser,
			'smtppass':smtppass,
			'sendemail':sendemail,
			'senduser':senduser,
			'receivemail':receivemail
		},function(msg){
			tipsAlert('告警通知设置成功');
		});
		
	});
	
	
	/*************************************** 其他js效果 ******************************/
	$(".isemail").bind("change",function(){
		if($(this).is(":checked")){
			$("#email_set").show();
		}else{
			$("#email_set").hide();
		}
	});
	
	$(".issms").bind("change",function(){
		if($(this).is(":checked")){
			$("#sms_set").show();
		}else{
			$("#sms_set").hide();
		}
	});
	
	$(".ismsn").bind("change",function(){
		if($(this).is(":checked")){
			$("#msn_set").show();
		}else{
			$("#msn_set").hide();
		}
	});
	
	$(".isgtalk").bind("change",function(){
		if($(this).is(":checked")){
			$("#gtalk_set").show();
		}else{
			$("#gtalk_set").hide();
		}
	});
	
	function getIsSms(){
		if($(".issms").is(":checked")){
			return 1;
		}else{
			return 0;	
		}
	}
	
	function getNotiSms(){
		var notisms = '';
		if($(".issms").is(":checked")){
			if($(".notiset_sms:checked").attr('value') == 0){
				notisms = 'ALL';
			}else{
				notisms = $.trim($("#users").val());
			}
		}
		return notisms;	
	}
	
	function getIsMsn(){
		if($(".ismsn").is(":checked")){
			return 1;
		}else{
			return 0;	
		}
	}
	
	function getNotiMsn(){
		var notimsn = '';
		if($(".ismsn").is(":checked")){
			notimsn = $.trim($("#notimsn").val());
		}
		return notimsn;	
	}
	
	function getIsGtalk(){
		if($(".isgtalk").is(":checked")){
			return 1;
		}else{
			return 0;	
		}
	}
	
	function getNotiGtalk(){
		var notigtalk = '';
		if($(".isgtalk").is(":checked")){
			notigtalk = $.trim($("#notigtalk").val());
		}
		return notigtalk;	
	}
	
	/**************************** osa box event *****************************/
	var boxShowDel = function(oldvalue,value){
		
		if(oldvalue == ''){
			return value ;
		}
		var arr = oldvalue.split(',');
		for(i in arr){
			if(value == arr[i]){
				delete arr[i];
			}		
		}
		var newvalue ='';
		for(n in arr){
			newvalue +=arr[n]+',';
		}
		newvalue = newvalue.replace(',,',',');
		return newvalue.replace(/(^\,*)|(\,*$)/g, "");
	};
	
	$("#user-select").click(function(){
		var userStr = $.trim($("#users").val());
		boxShowUser(userStr);	
	});
	
	$(".user_close").live("click",function(){	
		var value = $(this).parent().find(".li_users").html();
		var oldvalue = $("#users").val();
		newvalue = boxShowDel(oldvalue,value);
		$("#users").attr('value',newvalue);
		$(this).parent().remove();
	});
	
	//通知类型
	$(".notiset_sms").bind('change',function(){
		var type = $(".notiset_sms:checked").attr('value');
		if(type == '0'){
			$("#seleuser").hide();
		}else if(type == '1'){
			$("#seleuser").show();
		}
	});
});