$(document).ready(function(){
	
	/**
	 * 更多高级选项
	 */
	$(".more-set").click(function(){
		$(".more_class").toggle();
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
	
	/**
	 * 判断是否为正整数串
	 */
	function isNumber(strInt){
		if(/^[0-9]*[1-9][0-9]*(\,[0-9]*[1-9][0-9]*)?$/.test(strInt)){
			return true;
		}else{
			return false;
		}
	}
	
	
	/**
	 * 获取http状态码
	 */
	function getHttpstatus(){
		var status = '';
		$(".http_status:checked").each(function(){
			status +=$(this).attr('value')+',';
		});
		var value = $.trim($("#httpstatus").val());
		if(value !=''&&!isNumber(value)){
			return 'error';
		}
		status +=value;
		status = status.replace(/(^\,*)|(\,*$)/g, "");
		return status;
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
	/*************************** website 验证 *************************/
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
	$("#urlname").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
	});
	$("#urlname").blur(function(){
		var urlname = $.trim($(this).val());
		if(urlname == 'http://'){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入网页url');
			flag = false;
			//return;
		}else if(!isUrl(urlname)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入正确格式的网页url');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
	});
	
	$("#website-save").click(function(){
		
		flag = true ;
		$("#itemname").blur();
		$("#urlname").blur();
		if(flag == false){
			tipsAlert('基本信息有错误');
			return false;
		}
		
		var httpstatus = getHttpstatus();
		if(httpstatus == 'error'){
			tipsAlert('http状态码中其他项填写错误');
			return false;
		}else if(httpstatus ==''){
			tipsAlert('http状态码不能为空');
			return false;
		}
		
		var notiusers = getNotiObject();
		if(notiusers == ''){
			tipsAlert('指定用户不能为空');
			return false;
		}
		var checkrate = $(".checkrate:checked").attr('value');
		var alarmnum = $(".alarmnum:checked").attr('value');
		var itemname = $.trim($("#itemname").val());
		var urlname = $.trim($("#urlname").val());
		var keyword = $.trim($("#keyword").val());
		var itemip = $.trim($("#itemip").val());
		var repeatnum = $(".repeatnum:checked").attr('value');
		var remind = getRemind();
		
		var url = "index.php?c=monitor&a=website_monitor";
		$.post(url,{
			'itemname':itemname,
			'urlname':urlname,
			'keyword':keyword,
			'itemip':itemip,
			'httpstatus':httpstatus,
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
	
	$("#website-edit").click(function(){
		flag = true ;
		$("#itemname").blur();
		$("#urlname").blur();
		if(flag == false){
			tipsAlert('基本信息有错误');
			return false;
		}
		
		var httpstatus = getHttpstatus();
		if(httpstatus == 'error'){
			tipsAlert('http状态码中其他项填写错误');
			return false;
		}else if(httpstatus ==''){
			tipsAlert('http状态码不能为空');
			return false;
		}
		
		var notiusers = getNotiObject();
		if(notiusers == ''){
			tipsAlert('指定用户不能为空');
			return false;
		}
		var checkrate = $(".checkrate:checked").attr('value');
		var alarmnum = $(".alarmnum:checked").attr('value');
		var itemname = $.trim($("#itemname").val());
		var urlname = $.trim($("#urlname").val());
		var keyword = $.trim($("#keyword").val());
		var itemip = $.trim($("#itemip").val());
		var repeatnum = $(".repeatnum:checked").attr('value');
		var remind = getRemind();
		
		var itemid = $.trim($("#edit-itemid").val());
		var url = "index.php?c=monitor&a=monitor_http_edit";
		$.post(url,{
			'itemid':itemid,
			'itemname':itemname,
			'urlname':urlname,
			'keyword':keyword,
			'itemip':itemip,
			'httpstatus':httpstatus,
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
});