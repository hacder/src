//处理添加知识
$(document).ready(function(){
	$.ajaxSetup({
		  async: false
	}); 
	var flag = true ;
	$('#knowtitle').click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入日志标题');
	});
	$('#knowtitle').blur(function(){
		var knowtitle = $.trim($(this).val());
		if(knowtitle == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入日志标题');
			flag = false;
		}else if(knowtitle!=knowtitle.match(/^[a-zA-Z0-9\u4e00-\u9fa5][a-zA-Z0-9\u4e00-\u9fa5\:]+$/)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入正确格式的日志标题');
			flag = false;
			//return ;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('');
		}
	});	
	
	//添加日志
	$('#knowconfirm').click(function(){		
		flag = true ;
		$('#knowtitle').blur();
		if(flag == false){
			alert("您填写的基本信息有误");
			return false;
		}
		var oTypeid = $('#knowtype option:selected').attr('value');
		if(oTypeid == ''){
			alert('知识类型不能为空');
			return false;
		}
		var oRepositoryText = editor.getData();
		if(oRepositoryText ==''){
			alert('知识内容不能为空');
			return false;
		}
		var oRepositoryTitle = $.trim($('#knowtitle').val());
		var oRepositoryLabel = $.trim($('#knowlabel').val());
		var url = 'index.php?c=maintain&a=addknow';
		$.post(url,{
			'oRepositoryTitle':oRepositoryTitle,
			'oRepositoryLabel':oRepositoryLabel,
			'oTypeid':oTypeid,
			'oRepositoryText':oRepositoryText
		},function(msg){
			if(msg.indexOf('failure')!=-1){
				alert('添加失败');
				return ;
			}else if(msg.indexOf('success')!=-1){
				alert('添加成功');
				window.location = 'index.php?c=maintain&a=knowlist';				
			}
		});
	});
	
});