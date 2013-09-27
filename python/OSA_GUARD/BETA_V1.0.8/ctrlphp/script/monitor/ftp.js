$(document).ready(function(){
	
	
	
	//判断输入是否为所需的端口串
	function isPortstr(portStr){
		if(/^[0-9]*[1-9][0-9]*(\,[0-9]*[1-9][0-9]*)?$/.test(portStr)){
			return true;
		}
		return false;
	}
	
	function isip(ip){		
		var re=/^(\d+)\.(\d+)\.(\d+)\.(\d+)$/;//正则表达式   
		if(re.test(ip))   
		{   
			if( RegExp.$1<256 && RegExp.$2<256 && RegExp.$3<256 && RegExp.$4<256) 
			return true;   
		}
		return false ;
	}
	
	
	function isDomain(domain){
		
		var re=/^([\w-]+\.)+((com)|(net)|(org)|(gov\.cn)|(info)|(cc)|(com\.cn)|(net\.cn)|(org\.cn)|(name)|(biz)|(tv)|(cn)|(mobi)|(name)|(sh)|(ac)|   (io)|(tw)|(com\.tw)|(hk)|(com\.hk)|(ws)|(travel)|(us)|(tm)|(la)|(me\.uk)|(org\.uk)|(ltd\.uk)|(plc\.uk)|(in)|(eu)|(it)|(jp))$/ ;
		if(re.test(domain)){
			return true;
		}else{
			return false ;
		}
	}
	
	//获取系统恢复值
	function getRemind(){
		if($(".remind").is(":checked")){
			return 1;
		}
		return 0;
	}
	
	//获取通知对象的值
	function getNotiObject(){
		var type = $(".notitype:checked").attr('value');
		if(type == '0'){
			return 'ALL';
		}else if(type == '1'){
			var users = $.trim($("#users").val());//可能为空值
			return users;
		}
	}
	
	
	//通知类型
	$(".notitype").bind('change',function(){
		var type = $(".notitype:checked").attr('value');
		if(type == '0'){
			$("#seleuser").hide();
		}else if(type == '1'){
			$("#seleuser").show();
		}
	});
	
	
	var getFtpConfig = function(){	
		var itemConfig = {};
		if($("#ftp-identity").is(":visible")){
			itemConfig.defaults = 0;
			var ftpuser = $.trim($("#ftpuser").val());
			var ftppass = $.trim($("#ftppass").val());
			if(ftpuser != ''&&ftppass != ''){
				itemConfig.ftpuser=ftpuser;
				itemConfig.ftppass=ftppass;
			}
		}else{
			itemConfig.defaults = 1;
		}
		return itemConfig;
	};
	
	$(".ftp-radio").bind("change",function(){
		
		if($(".ftp-radio:checked").attr('value')==0){
			$("#ftp-identity").show();
		}else{
			$("#ftp-identity").hide();
		}
	});
	

	
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
	
	/****************************** apache 验证 *************************************/
	
	$.ajaxSetup({
		  async: false
	}); 
	//proname urlname  prokey
	var flag = true;
	//项目名称验证
	$("#itemname").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
	});
	$("#itemname").blur(function(){		
		var itemname = $.trim($(this).val());
		if(itemname == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入监控项目名称');
			flag = false;
			//return;
		}else if(itemname!=itemname.match(/^[a-zA-Z0-9\u4e00-\u9fa5][a-zA-Z0-9\u4e00-\u9fa5\.\_\@]+$/)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入正确格式的项目名称');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
		
	});
	//url 验证
	$("#itemurl").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
	});
	$("#itemurl").blur(function(){
		var urlname = $.trim($(this).val());
		if(urlname==''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入FTP服务的主机域名或者IP');
			flag = false;
			//return;
		}else if(!isip(urlname ) && !isDomain(urlname)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入FTP服务的主机域名或者IP');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
	});
	
	//mysql usernmae 验证
	$("#ftpuser").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
	});
	$("#ftpuser").blur(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		var ftpuser = $.trim($(this).val());
		if(ftpuser == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入FTP用户名');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
	});
	//mysql password 验证
	$("#ftppass").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
	});
	$("#ftppass").blur(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		var ftppass = $.trim($(this).val());
		if(ftppass == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入FTP密码');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
	});
	//mysql password 验证
	$("#ftpport").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
	});
	$("#ftpport").blur(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		var ftpport = $.trim($(this).val());
		if(ftpport == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入FTP端口');
			flag = false;
		}else if(!isPortstr(ftpport)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入FTP端口');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
	});
	
	$("#ftp-save").click(function(){
		
		flag = true ;
		$("#itemname").blur();
		$("#itemurl").blur();
		$("#ftpport").blur();
		if($("#ftp-identity").is(":visible")){
			$("#ftpuser").blur();
			$("#ftppass").blur();
		}
		if(flag == false){
			tipsAlert('基本信息有错误');
			return false;
		}
			
		var notiusers = getNotiObject();
		if(notiusers == ''){
			tipsAlert('指定用户不能为空');
			return false;
		}
		var checkrate = $(".checkrate:checked").attr('value');
		var alarmnum = $(".alarmnum:checked").attr('value');
		var repeatnum = $(".repeatnum:checked").attr('value');
		var remind = getRemind();
		
		var itemname = $.trim($("#itemname").val());
		var urlname = $.trim($("#itemurl").val());
		var ftpport = $.trim($("#ftpport").val());
		var itemconfig = getFtpConfig();
		var url = "index.php?c=monitor&a=ftp_monitor";
		$.post(url,{
			'itemname':itemname,
			'urlname':urlname,
			'ftpport':ftpport,
			'itemconfig':itemconfig,
			'notiusers':notiusers,
			'checkrate':checkrate,
			'alarmnum':alarmnum,
			'repeatnum':repeatnum,
			'remind':remind
		},function(msg){
			if(msg.indexOf('success')!=-1){
				var callback = function(result){
					if(result == true){					
						window.location = "index.php?c=monitor&a=monitorlist";
					}
				};
				tipsAlert('监控项目创建成功,点击确定进入监控项目列表',callback);
			}else{
				tipsAlert('监控项目创建失败');
			}
		});
	});
	
	
	$("#ftp-edit").click(function(){
		
		flag = true ;
		$("#itemname").blur();
		$("#itemurl").blur();
		$("#ftpport").blur();
		if($("#ftp-identity").is(":visible")){
			$("#ftpuser").blur();
			$("#ftppass").blur();
		}
		if(flag == false){
			tipsAlert('基本信息有错误');
			return false;
		}
			
		var notiusers = getNotiObject();
		if(notiusers == ''){
			tipsAlert('指定用户不能为空');
			return false;
		}
		var checkrate = $(".checkrate:checked").attr('value');
		var alarmnum = $(".alarmnum:checked").attr('value');
		var repeatnum = $(".repeatnum:checked").attr('value');
		var remind = getRemind();
		
		var itemname = $.trim($("#itemname").val());
		var urlname = $.trim($("#itemurl").val());
		var ftpport = $.trim($("#ftpport").val());
		var itemconfig = getFtpConfig();
		var itemid = $.trim($("#edit-itemid").val());
		var url = "index.php?c=monitor&a=monitor_ftp_edit";
		$.post(url,{
			'itemid':itemid,
			'itemname':itemname,
			'urlname':urlname,
			'ftpport':ftpport,
			'itemconfig':itemconfig,
			'notiusers':notiusers,
			'checkrate':checkrate,
			'alarmnum':alarmnum,
			'repeatnum':repeatnum,
			'remind':remind
		},function(msg){
			if(msg.indexOf('success')!=-1){
				var callback = function(result){
					if(result == true){					
						window.location = "index.php?c=monitor&a=monitorlist";
					}
				};
				tipsAlert('监控项目编辑成功,点击确定进入监控项目列表',callback);
			}else{
				tipsAlert('监控项目编辑失败');
			}
		});
	});
	
});