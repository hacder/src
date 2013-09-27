$(document).ready(function(){
	//添加
	$("#addconfigbackup").click(function(){
		
		var iparr = $.trim($('#serverip').val());
		if(iparr == ''){
			alert("选择的服务器不能为空");
			return false;
		}
		var filearr = $.trim($("#sourcefile").val());
		if(filearr == ''){
			alert("源文件不能为空");
			return false;
		}
		filearr = filearr.split('\n');
		var sourcefile = '';
		for(i in filearr){
			sourcefile += filearr[i]+'|' ;
		}
		sourcefile = sourcefile.replace(/(^\|*)|(\|*$)/g, "");
		var backupdir = $.trim($("#backupdir").val());
		if(backupdir == ''){
			alert('备份目录不能为空');
			return false;
		}
		var backuprule = $('#backuprule option:selected').attr('value');
		if(backuprule == '0'){
			alert('请选择备份规则');
			return false ;
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
		var url = 'index.php?c=maintain&a=addconfigbackup';
		$.post(url ,{
			'iparr':iparr,
			'plantime':plantime,
			'sourcefile':sourcefile,
			'backupdir':backupdir,
			'backuprule':backuprule
		},function(msg){
			if(msg.indexOf('failure')!=-1){
				alert('配置备份任务添加失败');
				return ;
			}else if(msg.indexOf('success')!=-1){
				if(plantime == ''){
					alert('提交成功');
					window.location = 'index.php?c=maintain&a=bresultlist&belong=1';
				}else{
					alert('配置备份任务添加成功');
					window.location = 'index.php?c=maintain&a=configbackuplist';
				}
			}else if(msg.indexOf('now_error')!=-1){
				alert('配置备份任务添加失败,指令发送失败');
				return ;
			}
		});
	});
	
});
