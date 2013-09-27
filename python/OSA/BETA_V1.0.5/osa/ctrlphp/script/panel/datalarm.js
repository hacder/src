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
	
	//通知类型转换
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
		
	//获取系统恢复值
	function getRemind(){
		if($(".remind").is(":checked")){
			return 1;
		}
		return 0;
	}
	
	//获取连接数
	function getLinknum(){
		var value = $.trim($("#link_num").val());
		if(value !=''&&!isNum(value)){
			return 'error';
		}
		return value;
	}
	
	//获取线程数量
	function getThreadnum(){
		var value = $.trim($("#thread_num").val());
		if(value !=''&&!isNum(value)){
			return 'error';
		}
		return value;
	}
	
	//判断输入是否为所需的峰值
	function isNum(numStr){
		if(/^[1-9][0-9]*$/.test(numStr)){
			return true;
		}
		return false;
	}
	
	function dataSubmit(type){
		flag = true ;
		$("#proname").blur();
		if(flag == false){
			alert('基本信息有错误');
			return false;
		}
		var user = $.trim($("#mysql_user").val());
		if(user == ''){
			alert("MYSQL用户不能为空");
			return ;
		}
		
		var password = $.trim($("#mysql_pass").val());
		if(password == ''){
			alert("MYSQL密码不能为空");
			return ;
		}
		
//		var host = $.trim($("#mysql_host").val());
//		if(host == ''){
//			alert("MYSQL主机不能为空");
//			return ;
//		}
		
		var port = $.trim($("#mysql_port").val());
		if(port == ''){
			alert("MYSQL端口不能为空");
			return ;
		}
		
		var linknum = getLinknum();
		if(linknum == ''){
			alert('连接数不能为空');
			return ;
		}else if(linknum =='error'){
			alert('连接数输入不合法');
			return ;
		}
		
		var threadnum = getThreadnum();
		if(threadnum == ''){
			alert('线程数不能为空');
			return ;
		}else if(threadnum =='error'){
			alert('线程数输入不合法');
			return ;
		}
		//
		var serverlist = $.trim($('#serverip').val());
		if(serverlist == ''){
			alert("服务器ip不能为空");
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
		var remind = getRemind();
		var url = "index.php?c=panel&a=savadatalarm";
		if(type == 'edit'){
			var alarmid = $("#hide_alarmid").attr('value');
			url +="&id="+alarmid;
		}
		$.post(url,{
			'proname':proname,
			'user':user,
			'password':password,
			//'host':host,
			'port':port,
			'linknum':linknum,
			'threadnum':threadnum,
			'serverlist':serverlist,
			'notiobject':notiobject,
			'checkrate':checkrate,
			'checknum':checknum,
			'remind':remind
		},function(msg){
			if(msg.indexOf('success')!=-1){
				window.location = "index.php?c=panel&a=monitorlist";
			}else{
				alert('保存失败');
			}
		});		
	}
	//提交
	$("#dataconfirm").click(function(){
		var type="add";
		dataSubmit(type);
	});
	$("#datalarmedit").click(function(){
		var type="edit";
		dataSubmit(type);
	});
});