$(document).ready(function(){
	
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
	
	
	var getDnsConfig = function(){	
		var itemConfig = {};
		itemConfig.qtype = $(".dns-type:checked").attr('value');
		if($("#dns-ip").is(":visible")){
			itemConfig.iplist = getDnsIplist();
		}
		if($("#dns-server").is(":visible")){
			itemConfig.server = $.trim($("#dns-server-value").val());
		}
		return itemConfig;
	};
	
	var getDnsIplist = function(){
		var iplist = '';
		$(".dns-ipli").each(function(){
			var one = $.trim($(this).find(".ip-one").val());
			var two = $.trim($(this).find(".ip-two").val());
			var thr = $.trim($(this).find(".ip-thr").val());
			var four = $.trim($(this).find(".ip-four").val());
			var ip = one+"."+two+"."+thr+"."+four ;
			iplist +=ip+",";
		});
		iplist = iplist.replace(',,',',');
		iplist = iplist.replace(/(^\,*)|(\,*$)/g, "");
		return iplist ;
	};
	
	
	
	$(".dns-ip").bind("change",function(){
		
		if($(this).is(":checked")){
			$("#dns-ip").show();
		}else{
			$("#dns-ip").hide();
		}
	});
	
	$(".dns-server").bind("change",function(){
		
		if($(this).is(":checked")){
			$("#dns-server").show();
		}else{
			$("#dns-server").hide();
		}
	});
	
	$(".dns-type").bind("change",function(){
		
		value = $(".dns-type:checked").attr('value');
		if(value == 'A'){
			$("#dns-A").show();
		}else{
			$("#dns-A").hide();
		}
	});
	
	
	$("#dns-ip-add").click(function(){
		
		var cloneHtml = $(".dns-ipli:first").clone(true);
		$(".append").before(cloneHtml);
	});
	

	$(".dns-ip-del").live("click",function(){
		if($(".dns-ipli").length == 1){
			$("#dns-ip").hide();
			$(".dns-ip").attr("checked",false);
		}else{
			$(this).parents(".dns-ipli").remove();
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
	
	/****************************** dns 验证 *************************************/
	
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
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入顶级域名或者二级域名，不需要”http://”前缀');
			flag = false;
			//return;
		}else if(!isip(urlname ) && !isDomain(urlname)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入顶级域名或者二级域名，不需要”http://”前缀');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
	});
	
	
	$(".ip-one,.ip-two,.ip-thr,.ip-four").click(function(){
		$("#dns-ip-msg").html('');
		$("#dns-ip-msg").parent().hide();
	});
	
	$(".ip-one,.ip-two,.ip-thr,.ip-four").blur(function(){
		var value = $(this).val();
		if(value == ''){
			flag = false ;
		}else if(isNaN(value)){
			$("#dns-ip-msg").html('您填的ip地址'+value+'有错误！');
			$("#dns-ip-msg").parent().show();
			flag = false ;
		}else {
			if(value <=0 || value>255){
				$("#dns-ip-msg").html("您填的ip地址"+value+"有错误！");
				$("#dns-ip-msg").parent().show();
				flag = false ;
			}else{
				$("#dns-ip-msg").html("");
				$("#dns-ip-msg").parent().hide();
			}
		}	
	});
	
	$("#dns-server-value").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
	});
	$("#dns-server-value").blur(function(){
		
		var servername = $.trim($(this).val());
		if(servername==''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入DNS服务器');
			flag = false;
			//return;
		}else if(!isip(servername) && !isDomain(servername)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入DNS服务器');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
	});
	
	$("#dns-save").click(function(){
		
		flag = true ;
		$("#itemname").blur();
		$("#itemurl").blur();
		if($("#dns-server").is(":visible")){
			$("#dns-server-value").blur();
		}
		if(flag == false){
			tipsAlert('基本信息有错误');
			return false;
		}
		
		if($("#dns-ip").is(":visible")){		
			$(".ip-one,.ip-two,.ip-thr,.ip-four").blur();
			if(flag == false){
				$("#dns-ip-msg").html("您填的ip地址有错误！");
				$("#dns-ip-msg").parent().show();
				tipsAlert('基本信息有错误');
				return false ;
			}
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
		var itemconfig = getDnsConfig();
		var url = "index.php?c=monitor&a=dns_monitor";
		$.post(url,{
			'itemname':itemname,
			'urlname':urlname,
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
	
	$("#dns-edit").click(function(){
		
		flag = true ;
		$("#itemname").blur();
		$("#itemurl").blur();
		if($("#dns-server").is(":visible")){
			$("#dns-server-value").blur();
		}
		if(flag == false){
			tipsAlert('基本信息有错误');
			return false;
		}
		
		if($("#dns-ip").is(":visible")){		
			$(".ip-one,.ip-two,.ip-thr,.ip-four").blur();
			if(flag == false){
				$("#dns-ip-msg").html("您填的ip地址有错误！");
				$("#dns-ip-msg").parent().show();
				tipsAlert('基本信息有错误');
				return false ;
			}
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
		var itemconfig = getDnsConfig();
		var itemid = $.trim($("#edit-itemid").val());
		var url = "index.php?c=monitor&a=monitor_dns_edit";
		$.post(url,{
			'itemid':itemid,
			'itemname':itemname,
			'urlname':urlname,
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