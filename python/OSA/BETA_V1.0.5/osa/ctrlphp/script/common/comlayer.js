var GLOBAL={};
GLOBAL.WIN={
	minWidth:1000,
	hHeight:76,
	fHeight:32
};
GLOBAL.DISPLAY={};
GLOBAL.DISPLAY.ALT={secondAlt:false};
GLOBAL.DISPLAY.altWinPosition=function(){
	var winH=$(window).height();
	var scrollTop=document.documentElement.scrollTop>document.body.scrollTop?document.documentElement.scrollTop:document.body.scrollTop;
	$('.window').each(function(){
		var h=this.h;
		$(this).css('top',(winH-h-100)>0?(winH-h-100)/2+scrollTop:100+scrollTop);
	});
};
//添加设备分组弹出层
$('#groupadd').click(function(){
	GLOBAL.DISPLAY.altWinPosition();
	$("#shadow").height(1200);
	$("#shadow ,#addgroup").show();
});
//添加设备类型弹出层
$('#typeadd').click(function(){
	GLOBAL.DISPLAY.altWinPosition();
	$("#shadow").height(1200);
	$("#shadow ,#addtype").show();
});
//添加日志类型弹出层
$('#logtypeadd').click(function(){
	GLOBAL.DISPLAY.altWinPosition();
	$("#shadow").height(1200);
	$("#shadow ,#addlogtype").show();
});
//添加知识类型弹出层
$('#knowtypeadd').click(function(){
	GLOBAL.DISPLAY.altWinPosition();
	$("#shadow").height(1200);
	$("#shadow ,#addknowtype").show();
});
//添加文件类型弹出层
$('#filetypeadd').click(function(){
	GLOBAL.DISPLAY.altWinPosition();
	$("#shadow").height(1200);
	$("#shadow ,#addfiletype").show();
});
//添加搜索服务器弹出层
$("#showip").click(function(){
	GLOBAL.DISPLAY.altWinPosition();
	initpage();
	$('.serverall').click();
	$("#shadow").height(800);
	$("#shadow ,#searchip").show();	
});
//添加搜索执行脚本弹出层
$("#showscript").click(function(){
	GLOBAL.DISPLAY.altWinPosition();
	initpage();
	$("#search_script").click();
	$("#shadow").height(800);
	$("#shadow ,#searchscript").show();	
});
//添加搜索配置文件弹出层
$("#showconfigfile").click(function(){
	GLOBAL.DISPLAY.altWinPosition();
	initpage();
	$("#search_file").click();
	$("#shadow").height(1200);
	$("#shadow ,#searchfiletype").show();	
});
//添加批量处理选择脚本弹出层
$("#batchscript").die().live('click',function(){
	GLOBAL.DISPLAY.altWinPosition();
	initpage();
	$("#search_script").click();
	$("#shadow").height(800);
	$("#shadow ,#searchscript").show();
});

//特列-- 文件分发 源文件 --选择脚本弹出框
$("#script_dis").click(function(){
	GLOBAL.DISPLAY.altWinPosition();
	initpage();
	$("#search_script_dis").click();
	$("#shadow").height(800);
	$("#shadow ,#searchscript_dis").show();
});

//添加选择用户弹出层
$("#showusers").click(function(){
	GLOBAL.DISPLAY.altWinPosition();
	initpage();
	$("#search_user").click();
	$("#shadow").height(1200);
	$("#shadow ,#searchusers").show();
});

//查看数据库ip弹出层
$(".looks").click(function(){
	var iparr = $(this).attr('ip');
	iparr = iparr.split('|');
	var str = '';
	for(i in iparr){
		str += '<span class="left mr10">'+iparr[i]+'</span>';
	}
	$("#content_ip").html(str);
	var top=$(this).offset().top;
	$("#shadow").height(1200);
	$(".window").css('top',top+10);
	$("#shadow ,#lookIp").show();
});

//alarms查看文件名
$(".alarm_copy").click(function(){
	var filename = $(this).attr('id');
	var index = filename.lastIndexOf('/');
	var first = filename.slice(0,index);
	var last = filename.slice(index,filename.length);
	var span = "<span class='left mr10'>"+first+"<br />"+last+"</span>";
	$("#content_filename").html(span);
	var top=$(this).offset().top;
	$("#shadow").height(800);
	$(".window").css('top',top+10);
	$("#shadow ,#look_filename").show();
});

//查看日志详情
$(".logtext").click(function(){
	var logtext = $(this).attr('ctext');
	$("#log_text").html(logtext);
	var top=$(this).offset().top;
	$("#shadow").height(800);
	$(".window").css('top',top+10);
	$("#shadow ,#looklog").show();
});

//弹出层关闭按钮
$('.windbutton').click(function(){
	$("#shadow ,.window").hide();
});
//弹出层取消按钮
$('.cancel').click(function(){
	$("#shadow ,.window").hide();
});
//添加设备分组
$("#devgroup").click(function(){
	$.ajaxSetup({
		  async: false
	}); 
	var name = $.trim($("#groupname").val());
	var description = $.trim($("#description").val());
	if(name == ''){
		alert('名称不能为空');	
		return false;
	}else if(name!=name.match(/^[a-zA-Z0-9\u4e00-\u9fa5][a-zA-Z0-9\u4e00-\u9fa5\.\_\@]+$/)){
		alert('名称由字母数字中文和下划线组成');
		return false;
	}else{
		var flag = true ;
		var url = 'index.php?c=device&a=checkgname';
		$.post(url,{'groupname':name},function(msg){
			if(msg.indexOf('success')!=-1){
				flag =true ;
			}else{
				flag = false;
			}		
		});
		if(flag == false) {
			alert('该分组已存在');
			return false ;
		}
	}
	var url = 'index.php?c=device&a=addgroup';
	$.post(url,{'oGroupName':name,'oDescription':description},function(msg){
		if(msg.indexOf('failure')!=-1){
			alert('添加分组失败');
		}else{
			var options = "<option selected='selected' value="+msg+">"+name+"</option>";
			$('#selectgroup').append(options);
			$("#shadow ,#addgroup").hide();
		}
	});
	
});
//添加设备类型
$("#devtype").click(function(){
	var name = $.trim($("#typename").val());
	//var description = $("#description").val();
	if(name == ''){
		alert('名称不能为空');
		return false;
	}else if(name!=name.match(/^[a-zA-Z0-9\u4e00-\u9fa5][a-zA-Z0-9\u4e00-\u9fa5\.\_\@]+$/)){
		alert('名称由字母数字中文和下划线组成');
		return false;
	}else{
		var flag = true ;
		var url = 'index.php?c=device&a=checktname';
		$.post(url,{'typename':name},function(msg){
			if(msg.indexOf('success')!=-1){
				flag =true ;
			}else{
				flag = false;
			}		
		});
		if(flag == false) {
			alert('该分类已存在');
			return false ;
		}	
	}
	var url = 'index.php?c=device&a=addtype';
	$.post(url,{'oTypeName':name},function(msg){
		if(msg.indexOf('failure')!=-1){
			alert('添加类型失败');
		}else{
			var options = "<option selected='selected' value="+msg+">"+name+"</option>";
			$('#selecttype').append(options);
			$("#shadow ,#addtype").hide();
		}
	});
	
});

//添加日志类型
$("#loglayer").click(function(){
	var name = $.trim($("#typename").val());
	//var description = $("#description").val();
	if(name == ''){
		alert('名称不能为空');
		return false;
	}else if(name!=name.match(/^[a-zA-Z0-9\u4e00-\u9fa5][a-zA-Z0-9\u4e00-\u9fa5\.\_\@]+$/)){
		alert('名称由字母数字中文和下划线组成');
		return false;
	}else{
		var flag = true ;
		var url = 'index.php?c=device&a=checklogtype';
		$.post(url,{'typename':name},function(msg){
			if(msg.indexOf('success')!=-1){
				flag =true ;
			}else{
				flag = false;
			}		
		});
		if(flag == false) {
			alert('该分类已存在');
			return false ;
		}
	}
	var url = 'index.php?c=maintain&a=addlogtype';
	$.post(url,{'oTypeText':name},function(msg){
		if(msg.indexOf('failure')!=-1){
			alert('添加类型失败');
		}else{
			var options = "<option selected='selected' value="+msg+">"+name+"</option>";
			$('#logtype').append(options);
			$("#shadow ,#addlogtype").hide();
		}
	});
	
});

//添加知识类型
$("#knowlayer").click(function(){
	var name = $.trim($("#typename").val());
	//var description = $("#description").val();
	if(name == ''){
		alert('名称不能为空');
		return false;
	}else if(name!=name.match(/^[a-zA-Z0-9\u4e00-\u9fa5][a-zA-Z0-9\u4e00-\u9fa5\.\_\@]+$/)){
		alert('名称由字母数字中文和下划线组成');
		return false;
	}else{
		var flag = true ;
		var url = 'index.php?c=device&a=checkknowtype';
		$.post(url,{'typename':name},function(msg){
			if(msg.indexOf('success')!=-1){
				flag =true ;
			}else{
				flag = false;
			}		
		});
		if(flag == false) {
			alert('该分类已存在');
			return false ;
		}
	}
	var url = 'index.php?c=maintain&a=addknowtype';
	$.post(url,{'oTypeText':name},function(msg){
		if(msg.indexOf('failure')!=-1){
			alert('添加类型失败');
		}else{
			var options = "<option selected='selected' value="+msg+">"+name+"</option>";
			$('#knowtype').append(options);
			$("#shadow ,#addknowtype").hide();
		}
	});
});

//添加文件类型
$("#configfilelayer").click(function(){
	var name = $.trim($("#typename").val());
	//var description = $("#description").val();
	if(name == ''){
		alert('名称不能为空');
		return false;
	}else if(name!=name.match(/^[a-zA-Z0-9\u4e00-\u9fa5][a-zA-Z0-9\u4e00-\u9fa5\.\_\@]+$/)){
		alert('名称由字母数字中文和下划线组成');
		return false;
	}else{
		var flag = true ;
		var url = 'index.php?c=device&a=checkfiletype';
		$.post(url,{'typename':name},function(msg){
			if(msg.indexOf('success')!=-1){
				flag =true ;
			}else{
				flag = false;
			}		
		});
		if(flag == false) {
			alert('该分类已存在');
			return false ;
		}
	}
	var url = 'index.php?c=maintain&a=addfiletype';
	$.post(url,{'oTypeText':name},function(msg){
		if(msg.indexOf('failure')!=-1){
			alert('添加类型失败');
		}else{
			var options = "<option selected='selected' value="+msg+">"+name+"</option>";
			$('#filetype').append(options);
			$("#shadow ,#addfiletype").hide();
		}
	});
});
