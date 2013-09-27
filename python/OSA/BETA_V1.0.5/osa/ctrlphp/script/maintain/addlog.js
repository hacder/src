//处理添加日志
$(document).ready(function(){
	$.ajaxSetup({
		  async: false
	}); 
	var flag = true ;
	$('#logtitle').click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入日志标题');
	});
	$('#logtitle').blur(function(){
		var logtitie = $.trim($(this).val());
		if(logtitie == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入日志标题');
			flag = false;
		}else if(logtitie!=logtitie.match(/^[a-zA-Z0-9\u4e00-\u9fa5][a-zA-Z0-9\u4e00-\u9fa5\:]+$/)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入正确格式的日志标题');
			flag = false;
			//return ;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('');
		}
	});	
	
	//添加日志
	$('#logconfirm').click(function(){		
		flag = true ;
		$('#logtitle').blur();
		if(flag == false){
			alert("您填写的基本信息有误");
			return false;
		}
		var oTypeid = $('#logtype option:selected').attr('value');
		if(oTypeid == ''){
			alert('日志类型不能为空');
			return false;
		}
		var oLogText = editor.getData();
		if(oLogText ==''){
			alert('脚本内容不能为空');
			return false;
		}
		var oLogTitle = $.trim($('#logtitle').val());
		var oLogLabel = $.trim($('#loglabel').val());
		var url = 'index.php?c=maintain&a=addlog';
		$.post(url,{
			'oLogTitle':oLogTitle,
			'oLogLabel':oLogLabel,
			'oTypeid':oTypeid,
			'oLogText':oLogText
		},function(msg){
			if(msg.indexOf('failure')!=-1){
				alert('日志添加失败');
				return ;
			}else if(msg.indexOf('success')!=-1){
				alert('日志添加成功');
				window.location = 'index.php?c=maintain&a=loglist';				
			}
		});
	});
	
});