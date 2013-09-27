$(document).ready(function(){

	
	$("#dataconfirm").click(function(){
		var backupname = $.trim($('#backupname').val());
		if(backupname == ''){
			alert("备份名称不能为空");
			return false;
		}
		var plantime = getPlanTime();
		if(plantime == false){
			alert('执行时间不能为空');
			return false;
		}
		var iparr = $.trim($('#serverip').val());
		if(iparr == ''){
			alert("选择的服务器不能为空");
			return false;
		}
		var scriptfile = $.trim($('#scriptfile').val());
		if(scriptfile == ''){
			alert("执行的脚本或指令不能为空");
			return false;
		}
		var url = 'index.php?c=maintain&a=adddatabackup';
		$.post(url ,{
			'oBackupName':backupname,
			'oBackupIp':iparr,
			'plantime':plantime,
			'oScriptFile':scriptfile
		},function(msg){
			if(msg.indexOf('failure')!=-1){
				alert('数据库备份任务添加失败');
				return ;
			}else if(msg.indexOf('success')!=-1){
				alert('数据库备份任务添加成功');
				window.location = 'index.php?c=maintain&a=databackuplist';				
			}
		});
		
	});

});