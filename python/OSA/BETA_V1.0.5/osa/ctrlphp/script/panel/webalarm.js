$(document).ready(function(){
	$.ajaxSetup({
		  async: false
	}); 
	//proname urlname  prokey
	var flag = true;
	//项目名称验证
	$("#proname").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入监控项目名称');
	});
	$("#proname").blur(function(){		
		var proname = $.trim($(this).val());
		if(proname == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入监控项目名称');
			flag = false;
			//return;
		}else if(proname!=proname.match(/^[a-zA-Z0-9\u4e00-\u9fa5][a-zA-Z0-9\u4e00-\u9fa5\.\_\@]+$/)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入正确格式的项目名称');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
		
	});
	//url 验证
	$("#urlname").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入网页url');
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
	// 验证是否为url
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
	
	//验证对比关键字
	$("#prokey").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入对比关键字');
	});
	$("#prokey").blur(function(){
		var prokey = $.trim($(this).val());
		if(prokey == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入对比关键字');
			flag = false;
			//return;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
		
	});
	
	//通知类型
	$(".notitype").bind('change',function(){
		var type = $(".notitype:checked").attr('value');
		if(type == '0'){
			$("#seleuser").hide();
		}else if(type == '1'){
			$("#seleuser").show();
		}
	});
	
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
	
	//获取http状态码
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
	
	//判断是否为正整数串
	function isNumber(strInt){
		if(/^[0-9]*[1-9][0-9]*(\,[0-9]*[1-9][0-9]*)?$/.test(strInt)){
			return true;
		}else{
			return false;
		}
	}
	
	//获取系统恢复值
	function getRemind(){
		if($(".remind").is(":checked")){
			return 1;
		}
		return 0;
	}
	
	//type ='add'|'edit'
	function webSubmit(type){
		flag = true ;
		$("#proname").blur();
		$("#urlname").blur();
		$("#prokey").blur();
		if(flag == false){
			alert('基本信息有错误');
			return false;
		}
		var httpstatus = getHttpstatus();
		if(httpstatus == 'error'){
			alert('http状态码中其他项填写错误');
			return false;
		}else if(httpstatus ==''){
			alert('http状态码不能为空');
			return false;
		}
		var notiobject = getNotiObject();
		if(notiobject == ''){
			alert('指定用户不能为空');
			return false;
		}
		var checkrate = $(".checkrate:checked").attr('value');
		var checknum = $(".checknum:checked").attr('value');
		var proname = $.trim($("#proname").val());
		var urlname = $.trim($("#urlname").val());
		var prokey = $.trim($("#prokey").val());
		var remind = getRemind();
		var url = "index.php?c=panel&a=savewebalarm";
		if(type == 'edit'){
			var alarmid = $("#hide_alarmid").attr('value');
			url +="&id="+alarmid;
		}
		$.post(url,{
			'proname':proname,
			'urlname':urlname,
			'prokey':prokey,
			'httpstatus':httpstatus,
			'notiobject':notiobject,
			'checkrate':checkrate,
			'checknum':checknum,
			'remind':remind
		},function(msg){
			if(msg.indexOf('success')!=-1){
				alert('保存成功');
				window.location = "index.php?c=panel&a=monitorlist";
			}else{
				alert('保存失败');
			}
		});
	}
	
	//添加提交
	$("#webconfirm").click(function(){
		var type = 'add';
		webSubmit(type);
	});
	
	//编辑提交 webalarmedit
	$("#webalarmedit").click(function(){
		var type = 'edit';
		webSubmit(type);
	});
});