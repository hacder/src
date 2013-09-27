//处理添加脚本
$(document).ready(function(){
	$.ajaxSetup({
		  async: false
	}); 
	var flag = true ;
	$('#scriptname').click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入脚本名字');
	});
	$('#scriptname').blur(function(){
		var scriptname = $.trim($(this).val());
		var url = 'index.php?c=maintain&a=checkscriptname';
		if(scriptname == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入脚本名字');
			flag = false;
		}else{
			$.post(url,{'scriptname':scriptname},function(msg){
				if(msg.indexOf('success')!=-1){
					$('#scriptname').parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('脚本名可用');
				}else{
					$('#scriptname').parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('脚本名已被使用');
					flag = false;
				}		
			});	
		}
	});
	$('#scriptpath').click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入脚本存储路径');
	});

	$('#scriptpath').blur(function(){
		var scriptpath = $.trim($(this).val());
		var url = 'index.php?c=maintain&a=checkscriptpath';
		if(scriptpath == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入脚本存储路径');
			flag = false;
			//return;
		}else if(!isFilePath(scriptpath)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('脚本路径不合法');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('');
		}
	});
	
	var isFilePath = function(value){
		// window 环境验证
		//var rFile1 =/^[a-zA-Z]:[\\/]((?! )(?![^\\/]*\s+[\\/])[\w -]+[\\/])*(?! )(?![^.]*\s+\.)[\w -]+\.[\w]+$/;
		// linux 环境验证
		var rFile2 = /^([\/]*[\w-]+)+\.[\w]+$/ ; 
		if(!rFile2.test(value)){
			return false ;
		}
		return true ;
	};
	
	
	//添加设备
	$('#scriptconfirm').click(function(){		
		flag = true ;
		$('#scriptname').blur();
		$('#scriptpath').blur();
		if(flag == false){
			alert("您填写的基本信息有误");
			return false;
		}
		var oScriptName = $.trim($('#scriptname').val());
		var oScriptLabel = $.trim($("#scriptlabel").val());
		var oScriptPath = $.trim($("#scriptpath").val());
		var oScriptContent = editor.getValue('');
		if(oScriptContent ==''){
			alert('脚本内容不能为空');
			return false;
		}
		var url = 'index.php?c=maintain&a=addscript';
		$.post(url,{
			'oScriptName':oScriptName,
			'oScriptLabel':oScriptLabel,
			'oScriptPath':oScriptPath,
			'oScriptContent':oScriptContent
		},function(msg){
			if(msg.indexOf('failure')!=-1){
				alert('脚本添加失败');
				return ;
			}else if(msg.indexOf('success')!=-1){
				alert('脚本添加成功');
				window.location = 'index.php?c=maintain&a=onlinescript';				
			}else if(msg.indexOf('writable_error')!=-1){
				alert('目录没有写权限，添加失败');
				return ;
			}
		});
	});
	
});