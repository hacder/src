$(document).ready(function(){
	//添加
	$("#addconfigupdate").click(function(){
		
		var iparr = $.trim($('#serverip').val());
		if(iparr == ''){
			alert("选择的服务器不能为空");
			return false;
		}
		var sourcefile = $.trim($("#sourcefile").val());
		if(sourcefile == ''){
			alert("配置源文件不能为空");
			return false;
		}
		var targetdir = $.trim($("#targetdir").val());
		if(targetdir == ''){
			alert("目标地址不能为空");
			return false;
		}
		var advance = getAdvance();
		var scriptfile = $.trim($('#scriptfile').val());
		if(scriptfile == ''){
			scriptfile = 'ls -l '+targetdir;
		}
		var value = $(".taskplan:checked").attr('value');
		var plantime ;
		if(value == 0){
			plantime = '';
		}else if(value == 1){
			plantime = getPlanTime();
			if(plantime == false){
				alert('执行时间不能为空');
				return false;
			}
		}
		var url = 'index.php?c=maintain&a=addconfigupdate';
		$.post(url ,{
			'iparr':iparr,
			'plantime':plantime,
			'sourcefile':sourcefile,
			'targetdir':targetdir,
			'advance':advance,
			'scriptfile':scriptfile
		},function(msg){
			if(msg.indexOf('failure')!=-1){
				alert('配置更新任务添加失败,源文件不存在');
				return ;
			}else if(msg.indexOf('success')!=-1){
				if(plantime == ''){
					alert('提交成功');
					window.location = 'index.php?c=maintain&a=bresultlist&belong=1';
				}else{
					alert('配置更新任务添加成功');
					window.location = 'index.php?c=maintain&a=configupdatelist';
				}				
			}else if(msg.indexOf('now_error')!=-1){
				alert('配置更新任务添加失败,源文件不存在或指令发送失败');
				return ;
			}
		});
	});
	
	function getAdvance(){
		var advance = '';
		$(".advance:checked").each(function(){
			advance +=$(this).attr('value')+'|';
		});
		advance = advance.replace(/(^\|*)|(\|*$)/g, "");
		if(advance == ''){
			advance = 'null|null';
		}else if(advance == 'backup'){
			advance = 'backup|null';
		}else if(advance == 'document_integrity'){
			advance = 'null|document_integrity';
		}
		return advance;
	}
	
});
