$(document).ready(function(){
	
	
	$("#more-options").click(function(){
		$("#more-class").toggle();
	});
	
	/**
	 * 验证是否为url
	 */ 
	function isUrl(strUrl){		
		var strRegex = "^((https|http)?://)"  
	        + "(([0-9]{1,3}\.){3}[0-9]{1,3}" // IP形式的URL- 199.194.52.184  
	        + "|" // 允许IP和DOMAIN（域名） 
	        + "([0-9a-z_!~*'()-]+\.)*" // 域名- www.  
	        + "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\." // 二级域名  
	        + "[a-z]{2,6})" // first level domain- .com or .museum  
	        + "(:[0-9]{1,4})?" // 端口- :80  
	        + "((/?)|" // a slash isn't required if there is no file name  
	        + "(/[0-9a-zA-Z_!~*'().;?:@&=+$,%#-]+)+/?)$";  
        var re=new RegExp(strRegex);  
        //re.test() 
        if (re.test(strUrl)){ 
            return (true);  
        }else{  
            return (false);  
        } 
	}
	
	//自定义指标 单位转换
	$(".tag_li").live('click',function(){
		var value = $(this).html();
		if(value == 'Mysql查询缓存利用率'){
			$(this).parents(".col_4_con").find('.threshold-tips').html('%');
			$(this).parents(".col_4_con").find('.tag_mysql_input').val("大于");
                        $(this).parents(".col_4_con").find('.tag_mysql_select').html('大于');
		}else if(value == 'Mysql主从(slave)状态'){
			$(this).parents(".col_4_con").find('.threshold').val("Yes");
			$(this).parents(".col_4_con").find('.threshold').attr("readonly",true);
			$(this).parents(".col_4_con").find('.tag_mysql_input').val("不等于");
			$(this).parents(".col_4_con").find('.tag_mysql_select').html('不等于');
		}else{
			$(this).parents(".col_4_con").find('.threshold-tips').html('');
			$(this).parents(".col_4_con").find('.tag_mysql_input').val("大于");
                        $(this).parents(".col_4_con").find('.tag_mysql_select').html('大于');
		}
	});
	
	
	//判断输入是否为所需的端口串
	function isPortstr(portStr){
		if(/^[0-9]*[1-9][0-9]*(\,[0-9]*[1-9][0-9]*)?$/.test(portStr)){
			return true;
		}
		return false;
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
	
	var maxOptions = 5;
	//添加新条目
	$("#add-options").click(function(){
		var numOptions = $(".col_4_con").length;
		if(numOptions>=maxOptions){
			tipsAlert("Mysql自定义报警指标数量不能超过"+maxOptions);
		}else{
			var cloneHtml = $(".col_4_con:first").clone(true);
			$(".append").before(cloneHtml);
		}
	});
	
	//删除一个条目
	$(".del-options").live("click",function(){
		if($(".col_4_con").length == 1){
			$("#more-class").hide();
		}else{
			$(this).parents(".col_4_con").remove();
		}
	});
	
	
	var getMysqlConfig = function(){	
		if($("#more-class").is(":visible")){
			var itemConfig = {};
			$(".col_4_con").each(function(){
				if($(this).find('.mysql_norm').val() == "Mysql激活的线程数"){
					itemConfig.Threads_running={};
					itemConfig.Threads_running.condition = "大于";
					itemConfig.Threads_running.value = $.trim($(this).find(".threshold").val());
				}else if($(this).find('.mysql_norm').val() == "Mysql当前连接数"){
					itemConfig.Threads_connected={};
					itemConfig.Threads_connected.condition = "大于";
					itemConfig.Threads_connected.value = $.trim($(this).find(".threshold").val());
				}else if($(this).find('.mysql_norm').val() == "Mysql中断连接数"){
					itemConfig.Abored_clients={};
					itemConfig.Abored_clients.condition = "大于";
					itemConfig.Abored_clients.value = $.trim($(this).find(".threshold").val());
				}else if($(this).find('.mysql_norm').val() == "Mysql失败连接数"){
					itemConfig.Abored_connects={};
					itemConfig.Abored_connects.condition = "大于";
					itemConfig.Abored_connects.value = $.trim($(this).find(".threshold").val());					
				}else if($(this).find('.mysql_norm').val() == "Mysql查询缓存利用率"){
					itemConfig.query_cache_rate={};
					itemConfig.query_cache_rate.condition = "大于";
					itemConfig.query_cache_rate.value = $.trim($(this).find(".threshold").val());
				}else if($(this).find('.mysql_norm').val() == "Mysql主从(slave)状态"){
					itemConfig.slave_status={};
					itemConfig.slave_status.condition = "不等于";
					itemConfig.slave_status.value = $.trim($(this).find(".threshold").val());
				}
			});
			return itemConfig;
		}else{
			return '';
		}
	};
	

	
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
	
	//mysql usernmae 验证
	$("#mysqluser").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
	});
	$("#mysqluser").blur(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		var mysqluser = $.trim($(this).val());
		if(mysqluser == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入MYSQL用户名');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
	});
	//mysql password 验证
	$("#mysqlpass").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
	});
	$("#mysqlpass").blur(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		var mysqlpass = $.trim($(this).val());
		if(mysqlpass == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入MYSQL密码');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
	});
	//mysql password 验证
	$("#mysqlport").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
	});
	$("#mysqlport").blur(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		var mysqlport = $.trim($(this).val());
		if(mysqlport == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入MYSQL端口');
			flag = false;
		}else if(!isPortstr(mysqlport)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入MYSQL端口');
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
		if(urlname == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入服务器IP或域名');
			flag = false;
			//return;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
	});
	
	
	
	$("#mysql-save").click(function(){
		
		flag = true ;
		$("#itemname").blur();
		$("#mysqluser").blur();
		$("#mysqlpass").blur();
		$("#mysqlport").blur();
		$("#itemurl").blur();
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
		var mysqluser = $.trim($("#mysqluser").val());
		var mysqlpass = $.trim($("#mysqlpass").val());
		var mysqlport = $.trim($("#mysqlport").val());
		var urlname = $.trim($("#itemurl").val());
		var itemconfig = getMysqlConfig();
		var url = "index.php?c=monitor&a=mysql_monitor";
		$.post(url,{
			'itemname':itemname,
			'urlname':urlname,
			'mysqluser':mysqluser,
			'mysqlpass':mysqlpass,
			'mysqlport':mysqlport,
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
	
	
	$("#mysql-edit").click(function(){
		
		flag = true ;
		$("#itemname").blur();
		$("#mysqluser").blur();
		$("#mysqlpass").blur();
		$("#mysqlport").blur();
		$("#itemurl").blur();
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
		var mysqluser = $.trim($("#mysqluser").val());
		var mysqlpass = $.trim($("#mysqlpass").val());
		var mysqlport = $.trim($("#mysqlport").val());
		var urlname = $.trim($("#itemurl").val());
		var itemconfig = getMysqlConfig();
		var itemid = $.trim($("#edit-itemid").val());
		var url = "index.php?c=monitor&a=monitor_mysql_edit";
		$.post(url,{
			'itemid':itemid,
			'itemname':itemname,
			'urlname':urlname,
			'mysqluser':mysqluser,
			'mysqlpass':mysqlpass,
			'mysqlport':mysqlport,
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
