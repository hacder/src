$(document).ready(function(){
	//下一步
	$("#nextstep").click(function(){
		var iparr = $.trim($('#serverip').val());
		if(iparr == ''){
			alert("选择的服务器不能为空");
			return false;
		}
		var batchtype = $(".batchtype:checked").attr('value');
		$("#showfirst").hide();
		$(".script_file").html('');
		var html = '<label class="label6">执行命令或脚本：</label><input type="text" class="style7" id="scriptfile"/><span class="link">或者<a href="#" id="batchscript">搜索脚本库</a></span>';
		$("#"+batchtype).find('.script_file').append(html);
		$("#showsecond").show();
		$("#"+batchtype).show();		
	});
	//上一步
	$("#laststep").click(function(){
		$("#showsecond").hide();
		$(".batch_operate").hide();
		$("#showfirst").show();
	});
	
	//文件分发 获取advance
	function get_advance_dis(){
		var advance = '';
		var radio = $(".dis_radio:checked").attr('value');
		if(radio == undefined || radio =='') radio = 'null';
		var check = $('.dis_check:checked').attr('value');
		if(check == undefined || check =='') check = 'null';
		advance = radio + '|' + check;
		return advance ;
	}
	//文件清理 获取advance
	function get_advance_cleaner(){
		var value = $(".rm_check:checked").attr('value');
		if(value == undefined){
			var advance = new Array();
			if($(".bak_check").is(":checked")){
				advance.push($(".bak_check:checked").attr('value'));
			}
			if($(".log_check").is(":checked")){
				advance.push($(".log_check:checked").attr('value'));
			}
			if($(".ex_check").is(":checked")){
				extext = $.trim($('#ex_text').val());
				if(extext == ''){
					return 'null';
				}
				advance.push(extext);
			}
			if(advance.length == 0) return '';
			var rs = '';
			for(i in advance){
				rs +=advance[i]+',';
			}
			return rs.replace(/(^\,*)|(\,*$)/g ,"");
		}else if(value=="rm_dir"){
			return value;
		}
	}
	//分发中禁用其他input
	$(".rm_check").bind('change',function(){
		var value = $(".rm_check:checked").attr('value');
		if(value == undefined){
			$(".bak_check,.log_check,.ex_check,#ex_text").removeAttr('disabled');
		}else if(value=="rm_dir"){
			$(".bak_check,.log_check,.ex_check,#ex_text").attr('disabled','disabled');
		}
	});
	
	//服务重启 选择服务或脚本
	$(".server_radio").bind('change',function(){
		var value = $(".server_radio:checked").attr('value');
		if(value == 'script'){
			$("#select_script").show();
			$("#select_server").hide();
		}else{
			$("#select_script").hide();
			$("#select_server").show();
		}
	});
	//添加批量处理
	$("#batchconfirm").click(function(){
		var iparr = $.trim($('#serverip').val());
		if(iparr == ''){
			alert("选择的服务器不能为空");
			return false;
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
		var batchtype = $(".batchtype:checked").attr('value');
		//分批量操作类型来处理,方便以后好处理
		if(batchtype == 'distribution'){//文件分发
			var advance = get_advance_dis();
			var sourcefile = $.trim($("#sourcefile").val());
			if(sourcefile == ''){
				alert("源文件不能为空");
				return false;
			}
			var targetdir = $.trim($("#targetdir").val());
			if(targetdir == ''){
				alert("目标地址不能为空");
				return false;
			}
			var scriptfile = $.trim($("#scriptfile").val());
			if(scriptfile == ''){
				scriptfile = 'ls -l '+targetdir;
			}
			var url = 'index.php?c=maintain&a=distribution';
			$.post(url ,{
				'iparr':iparr,
				'plantime':plantime,
				'sourcefile':sourcefile,
				'targetdir':targetdir,
				'advance':advance,
				'scriptfile':scriptfile
			},function(msg){
				if(msg.indexOf('failure')!=-1){
					alert('任务添加失败,源文件不存在');
					return ;
				}else if(msg.indexOf('success')!=-1){
					if(plantime == ''){
						alert('提交成功');
						window.location = 'index.php?c=maintain&a=bresultlist&belong=1';
					}else{
						alert('计划任务提交成功');
						window.location = 'index.php?c=maintain&a=batchlist';
					}
				}else if(msg.indexOf('now_error')!=-1){
					alert('任务添加失败,源文件不存在或指令发送失败');
					return ;
				}
			});
		}else if(batchtype == 'cleaner'){//文件清理
			var sourcefile = $.trim($("#cleaner_path").val());
			if(sourcefile == ''){
				alert("目标文件路径不能为空");
				return false;
			}
			var targetdir = $.trim($("#cleaner_address").val());
			if(targetdir == ''){
				alert("目标地址不能为空");
				return false;
			}
			var advance = get_advance_cleaner();
			if(advance == 'null'){
				alert('扩展名不能为空');
				return false;
			}else if(advance == ''){
				alert('高级选项不能为空');
				return false;
			}
			var url = 'index.php?c=maintain&a=cleaner';
			$.post(url ,{
				'iparr':iparr,
				'plantime':plantime,
				'sourcefile':sourcefile,
				'targetdir':targetdir,
				'advance':advance
			},function(msg){
				if(msg.indexOf('failure')!=-1){
					alert('任务添加失败');
					return ;
				}else if(msg.indexOf('success')!=-1){
					if(plantime == ''){
						alert('提交成功');
						window.location = 'index.php?c=maintain&a=bresultlist&belong=1';
					}else{
						alert('计划任务提交成功');
						window.location = 'index.php?c=maintain&a=batchlist';
					}			
				}else if(msg.indexOf('now_error')!=-1){
					alert('任务添加失败,指令发送失败');
					return ;
				}
			});
		}else if(batchtype == 'restart'){//服务
			var select_type = $(".server_radio:checked").attr('value');
			var url = "index.php?c=maintain&a=serverdeal";
			if(select_type == 'script'){
				var scriptfile = $.trim($("#scriptfile").val());
				if(scriptfile == ''){
					alert("执行脚本不能为空");
					return false;
				}
				//处理不同的指令
				$.post(url,{'iparr':iparr,'plantime':plantime,'scriptfile':scriptfile},function(msg){
					if(msg.indexOf('failure')!=-1){
						alert('任务添加失败');
						return ;
					}else if(msg.indexOf('success')!=-1){
						if(plantime == ''){
							alert('提交成功');
							window.location = 'index.php?c=maintain&a=bresultlist&belong=1';
						}else{
							alert('计划任务提交成功');
							window.location = 'index.php?c=maintain&a=batchlist';
						}				
					}else if(msg.indexOf('now_error')!=-1){
						alert('任务添加失败,指令发送失败');
						return ;
					}
				});
			}else if(select_type == 'server'){
				var server_name = getServerType();
				if(server_name == ''){
					alert('服务项不能为空');
					return false;
				}
				var server_type = $("#operater_type option:selected").attr('value');
				//
				$.post(url,{'iparr':iparr,'plantime':plantime,'name':server_name,'type':server_type},function(msg){
					if(msg.indexOf('failure')!=-1){
						alert('任务添加失败');
						return ;
					}else if(msg.indexOf('success')!=-1){
						if(plantime == ''){
							alert('提交成功');
							window.location = 'index.php?c=maintain&a=bresultlist&belong=1';
						}else{
							alert('计划任务提交成功');
							window.location = 'index.php?c=maintain&a=batchlist';
						}			
					}else if(msg.indexOf('now_error')!=-1){
						alert('任务添加失败,指令发送失败');
						return ;
					}
				});
			}
		}else if(batchtype == 'command'){//指令
			var scriptfile = $.trim($("#scriptfile").val());
			if(scriptfile == ''){
				alert("执行脚本不能为空");
				return false;
			}
			var url="index.php?c=maintain&a=command";
			$.post(url,{'iparr':iparr,'plantime':plantime,'scriptfile':scriptfile},function(msg){
				if(msg.indexOf('failure')!=-1){
					alert('任务添加失败');
					return ;
				}else if(msg.indexOf('success')!=-1){
					if(plantime == ''){
						alert('提交成功');
						window.location = 'index.php?c=maintain&a=bresultlist&belong=1';
					}else{
						alert('计划任务提交成功');
						window.location = 'index.php?c=maintain&a=batchlist';
					}			
				}else if(msg.indexOf('now_error')!=-1){
					alert('任务添加失败,指令发送失败');
					return ;
				}
			});
		}else if(batchtype == 'installation'){//安装
			var scriptfile = $.trim($("#scriptfile").val());
			if(scriptfile == ''){
				alert("执行脚本不能为空");
				return false;
			}
			var url="index.php?c=maintain&a=installation";
			$.post(url,{'iparr':iparr,'plantime':plantime,'scriptfile':scriptfile},function(msg){
				if(msg.indexOf('failure')!=-1){
					alert('任务添加失败');
					return ;
				}else if(msg.indexOf('success')!=-1){
					if(plantime == ''){
						alert('提交成功');
						window.location = 'index.php?c=maintain&a=bresultlist&belong=1';
					}else{
						alert('计划任务提交成功');
						window.location = 'index.php?c=maintain&a=batchlist';
					}				
				}else if(msg.indexOf('now_error')!=-1){
					alert('任务添加失败,指令发送失败');
					return ;
				}
			});
		}else if(batchtype == 'diskspace'){//磁盘空间
			var unit = $(".space_radio:checked").attr('value');
			var threshold = '';
			if(unit == 'MB'){
				threshold = $.trim($("#space_m").val());
				if(threshold == ''){
					alert("阀值不能为空");
					return false;
				}else if(isNaN(threshold)){
					alert("阀值必须为数字");
					return false;
				}else if(threshold < 0){
					alert("阀值不能小于0");
					return false;
				}
				
			}else{
				threshold = $.trim($("#space_p").val());
				if(threshold == ''){
					alert("阀值百分数不能为空");
					return false;
				}else if(isNaN(threshold)){
					alert("阀值百分数必须为数字");
					return false;
				}else if(threshold < 0){
					alert("阀值百分数不能小于0");
					return false;
				}else if(threshold >= 100){
					alert("阀值百分数不能大于100");
					return false;
				}
			}
			var url = "index.php?c=maintain&a=diskspace";
			$.post(url,{'iparr':iparr,'plantime':plantime,'threshold':threshold,'unit':unit},function(msg){
				if(msg.indexOf('failure')!=-1){
					alert('任务添加失败');
					return ;
				}else if(msg.indexOf('success')!=-1){
					if(plantime == ''){
						alert('提交成功');
						window.location = 'index.php?c=maintain&a=bresultlist&belong=1';
					}else{
						alert('计划任务提交成功');
						window.location = 'index.php?c=maintain&a=batchlist';
					}				
				}else if(msg.indexOf('now_error')!=-1){
					alert('任务添加失败,指令发送失败');
					return ;
				}
			});
		}else if(batchtype == 'loadstate'){//负载
			var scriptfile = getloadscript();
			if(scriptfile == ''){
				alert("执行脚本不能为空");
				return false;
			}
			var url="index.php?c=maintain&a=loadstate";
			$.post(url,{'iparr':iparr,'plantime':plantime,'scriptfile':scriptfile},function(msg){
				if(msg.indexOf('failure')!=-1){
					alert('任务添加失败');
					return ;
				}else if(msg.indexOf('success')!=-1){
					if(plantime == ''){
						alert('提交成功');
						window.location = 'index.php?c=maintain&a=bresultlist&belong=1';
					}else{
						alert('计划任务提交成功');
						window.location = 'index.php?c=maintain&a=batchlist';
					}				
				}else if(msg.indexOf('now_error')!=-1){
					alert('任务添加失败,指令发送失败');
					return ;
				}
			});
		}
	});
	
	//获取服务类型
	function getServerType(){
		var servertype = $("#server_type option:selected").attr('value');
		if(servertype != '') {
			return servertype;
		}else{
			servertype = $.trim($("#server_intype").val());
			return servertype ;
		}
	}
	
	//负载 中隐藏-显示功能
	$(".loadstate_radio").bind('change',function(){
		var value = $(".loadstate_radio:checked").attr('value');
		if(value == 'default'){
			$("#load_script").hide();
		}else{
			$("#load_script").show();
		}
	});
	
	//负载中 获取值
	function getloadscript(){
		var value = $(".loadstate_radio").attr('value');
		if(value == 'default'){
			return value ;
		}else{
			return $.trim($("#scriptfile").val());
		}
		
	}
});