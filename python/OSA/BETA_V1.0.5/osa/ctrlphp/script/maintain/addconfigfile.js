//处理添加脚本
$(document).ready(function(){
	$.ajaxSetup({
		  async: false
	}); 
	var flag = true ;
	$('#filename').click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入文件名字');
	});
	$('#filename').blur(function(){
		var filename = $.trim($(this).val());
		var url = 'index.php?c=maintain&a=checkfilename';
		if(filename == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入文件名字');
			flag = false;
		}else{
			$.post(url,{'filename':filename},function(msg){
				if(msg.indexOf('success')!=-1){
					$('#filename').parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('文件名可用');
				}else{
					$('#filename').parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('文件名已被使用');
					flag = false;
				}		
			});	
		}
	});
	$('#filepath').click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入文件存储路径');
	});

	$('#filepath').blur(function(){
		var filepath = $.trim($(this).val());
		if(filepath == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入文件存储路径');
			flag = false;
			//return;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('');
		}
	});
	
	
	//添加设备
	$('#configconfirm').click(function(){		
		flag = true ;
		$('#filename').blur();
		$('#filepath').blur();
		if(flag == false){
			alert("您填写的基本信息有误");
			return false;
		}
		var oFileName = $.trim($('#filename').val());
		var oFileLabel = $.trim($("#filelabel").val());
		var oFileSign = $.trim($("#filesign").val());
		var oSavePath = $.trim($("#filepath").val());
		var oTypeid = $("#filetype option:selected").attr('value');
		if(oTypeid == ''){
			alert('文件类型不能为空');
			return false;
		}
		var oConfigContent = editor.getValue('');
		if(oConfigContent ==''){
			alert('文件内容不能为空');
			return false;
		}
		var url = 'index.php?c=maintain&a=addconfigfile';
		$.post(url,{
			'oFileName':oFileName,
			'oFileLabel':oFileLabel,
			'oFileSign':oFileSign,
			'oSavePath':oSavePath,
			'oTypeid':oTypeid,
			'oConfigContent':oConfigContent
		},function(msg){
			if(msg.indexOf('failure')!=-1){
				alert('文件添加失败');
				return ;
			}else if(msg.indexOf('success')!=-1){
				alert('文件添加成功');
				window.location = 'index.php?c=maintain&a=configfilelist';				
			}else if(msg.indexOf('writable_error')!=-1){
				alert('目录没有写权限，添加失败');
				return ;
			}
		});
	});
	
});