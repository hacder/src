$(document).ready(function(){
	$.ajaxSetup({
		  async: false
	}); 
	var flag = true ;
	$('#groupname').click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入分组名');
	});
	$('#groupname').blur(function(){
		var groupname = $.trim($(this).val());
		var url = 'index.php?c=device&a=checkgname';
		if(groupname == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入分组名');
			flag = false;
			//return;
		}else if(groupname!=groupname.match(/^[a-zA-Z0-9\u4e00-\u9fa5][a-zA-Z0-9\u4e00-\u9fa5\.\_\@]+$/)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入正确格式的分组名');
			flag = false;
			//return ;
		}else{
			$.post(url,{'groupname':groupname},function(msg){
				if(msg.indexOf('success')!=-1){
					$('#groupname').parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('分组名可用');
				}else{
					$('#groupname').parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('分组名已被使用');
					flag = false;
				}		
			});	
		}
	});
	//添加提交
	$("#group_add").click(function(){
		flag = true ;
		$('#groupname').blur();
		if(flag == false){
			return false;
		}
		var groupname = $.trim($("#groupname").val());
		var descript = $("#descript").val();
		var serverlist = $.trim($("#serverip").val());
		if(serverlist == ''){
			alert("IP不能为空");
			return false ;
		}
		var url = "index.php?c=device&a=groupadd" ;
		$.post(url ,{
			'groupname':groupname,
			'descript':descript,
			'serverlist':serverlist
		},function(msg){
			if(msg.indexOf('success')!=-1){
				window.location = 'index.php?c=device&a=devgrouplist'
			}else{
				alert('分组添加失败');
			}
		});
	});
	
	//编辑提交
	$("#group_edit").click(function(){
		var groupname = $.trim($("#groupname").val());
		var descript = $("#descript").val();
		var serverlist = $.trim($("#serverip").val());
		if(serverlist == ''){
			alert("IP不能为空");
			return false ;
		}
		var url = $("#hideurl").val();
		$.post(url ,{
			'groupname':groupname,
			'descript':descript,
			'serverlist':serverlist
		},function(msg){
			if(msg.indexOf('success')!=-1){
				window.location = 'index.php?c=device&a=devgrouplist'
			}else{
				alert('分组编辑失败');
			}
		});	
	});
	//同步ip
	function synchIp(){
		var serverip =new Array();
		var iparr = $.trim($("#serverip").val());
		if(iparr!=''){
			serverip = iparr.split('|');
			var iphtml = '';
			for(i in serverip){
				//alert(iparr[i]);
				iphtml += '<span class="left mr10"><label class="ip_tips">'+serverip[i]+'</label><img src="images/erase.png" class="delselectip ml5 pointer"/></span>';
			}
			$("#showselectip").html('').append(iphtml);
		}
	}
	
	synchIp();
	
	
});