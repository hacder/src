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
//添加分组弹出层
$('#groupadd').click(function(){
	GLOBAL.DISPLAY.altWinPosition();
	$("#shadow").height(1200);
	$("#shadow ,#addgroup").show();
});
//添加类型弹出层
$('#typeadd').click(function(){
	GLOBAL.DISPLAY.altWinPosition();
	$("#shadow").height(1200);
	$("#shadow ,#addtype").show();
});
//弹出层关闭按钮
$('.windbutton').click(function(){
	$("#shadow ,#addgroup ,#addtype").hide();
});
//弹出层取消按钮
$('.cancel').click(function(){
	$('input:text').val('');
	$('textarea').val('');
});
//添加分组
$("#devgroup").click(function(){
	var name = $.trim($("#groupname").val());
	var description = $.trim($("#description").val());
	if(name == ''){
		alert('名称不能为空');	
		return false;
	}else if(name!=name.match(/^[a-zA-Z0-9\u4e00-\u9fa5]+$/)){
		alert('名称由字母数字中文和下划线组成');
		return false;
	}
	var url = 'index.php?c=device&a=addgroup';
	$.post(url,{'oGroupName':name,'oDescription':description},function(msg){
		if(msg.indexOf('failure')!=-1){
			alert('添加分组失败');
		}else{
			var options = "<option value="+msg+">"+name+"</option>";
			$('#selectgroup').append(options);
			alert('添加分组成功');
			$("#shadow ,#addgroup").hide();
		}
	});
	
});
//添加类型
$("#devtype").click(function(){
	var name = $.trim($("#typename").val());
	//var description = $("#description").val();
	if(name == ''){
		alert('名称不能为空');
		return false;
	}else if(name!=name.match(/^[a-zA-Z0-9\u4e00-\u9fa5]+$/)){
		alert('名称由字母数字中文和下划线组成');
		return false;
	}
	var url = 'index.php?c=device&a=addtype';
	$.post(url,{'oTypeName':name},function(msg){
		if(msg.indexOf('failure')!=-1){
			alert('添加类型失败');
		}else{
			var options = "<option value="+msg+">"+name+"</option>";
			$('#selecttype').append(options);
			alert('添加类型成功');
			$("#shadow ,#addtype").hide();
		}
	});
	
});
