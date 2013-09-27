$(document).ready(function(){
	
	var flag = true;
	$('#snmpname').click(function(){
		$(this).parent().find(".snmp-tips").html('');	
	});
	$('#snmpname').blur(function(){
		var snmpname = $.trim($(this).val());
		if(snmpname == ''){
			$(this).parent().find(".snmp-tips").html('请输入SNMP团体名称');
			flag = false;
			//return;
		}else{
			$(this).parent().find(".snmp-tips").html('');	
		}
	});
	
	$('#snmpport').click(function(){
		$(this).parent().find(".snmp-tips").html('');	
	});
	$('#snmpport').blur(function(){
		var snmpport = $.trim($(this).val());
		if(snmpport == ''){
			$(this).parent().find(".snmp-tips").html('请输入SNMP端口');
			flag = false;
			//return;
		}else{
			$(this).parent().find(".snmp-tips").html('');	
		}
	});
	
	$('#snmpkey').click(function(){
		$(this).parent().find(".snmp-tips").html('');	
	});
	$('#snmpkey').blur(function(){
		var snmptime = $.trim($(this).val());
		if(snmptime == ''){
			$(this).parent().find(".snmp-tips").html('请输入SNMP超时时间');
			flag = false;
			//return;
		}else{
			$(this).parent().find(".snmp-tips").html('');	
		}
	});
	
	$("#snmp-submit").click(function(){
		flag = true;
		$('#snmpname').blur();
		$('#snmpport').blur();
		$('#snmpkey').blur();
		if(flag == false){
			return false;
		}
		var snmpname = $.trim($("#snmpname").val());
		var snmpport = $.trim($("#snmpport").val());
		var snmpkey = $.trim($("#snmpkey").val());
		
		var url = "index.php?c=snmp&a=snmp_edit";
		$.post(url,{'snmpname':snmpname,'snmpport':snmpport,'snmpkey':snmpkey},function(msg){
			tipsAlert('SNMP信息设置成功！');
		});
	});
});