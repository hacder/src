$(document).ready(function(){
	$.ajaxSetup({
		  async: false
	}); 
	//系统功能设置js
	//控制是否开启采集
	$(".radio_switch").bind('change',function(){
		var method = $(".radio_switch:checked").attr('value');
		if(method == '0'){
			$("#show_method").hide();
		}else if(method == '1'){
			$("#show_method").show();
		}
	});
	//控制是否使用snmp
	$(".radio_method").bind('change',function(){
		var method = $(".radio_method:checked").attr('value');
		if(method == '0'){
			$("input:text").val('');
			$(".tips").html('');
			$("#snmpset").hide();
		}else if(method == '1'){
			$("#snmpset").show();
		}
	});
	var flag = true ;
	//snmp 团体名称判断
	$("#snmp_name").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入SNMP团体名称');
	});
	$("#snmp_name").blur(function(){
		var snmp_name = $.trim($(this).val());
		if(snmp_name == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入SNMP团体名称');
			flag = false;
			//return;
		}else if(snmp_name!=snmp_name.match(/^[a-zA-Z0-9\u4e00-\u9fa5][a-zA-Z0-9\u4e00-\u9fa5\.\_\@]+$/)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入正确格式的SNMP团体名称');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
	});
	
	//判断是否为正整数
	function isNum(strInt){		
		if(/^[1-9][0-9]*$/.test(strInt)){
			return true;
		}else{
			return false;
		}
	}
	//snmp 端口判断
	$("#snmp_port").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入SNMP端口');
	});
	$("#snmp_port").blur(function(){
		var snmp_port = $.trim($(this).val());
		if(snmp_port == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入SNMP端口');
			flag = false;
			//return;
		}else if(!isNum(snmp_port)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入正确的SNMP端口');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
	});
	
	//snmp超时判断
	$("#snmp_timeout").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('请输入SNMP超时时间');
	});
	$("#snmp_timeout").blur(function(){
		var snmp_timeout = $.trim($(this).val());
		if(snmp_timeout == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入SNMP超时时间');
			flag = false;
			//return;
		}else if(!isNum(snmp_timeout)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入正确的SNMP超时时间');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
	});
	
	//提交
	$("#snmpconfirm").click(function(){
		var oIsOpen = $(".radio_switch:checked").attr('value');
		var oIsSnmp = $(".radio_method:checked").attr('value');
		var oSnmpConfig = '';
		flag = true;
		if(oIsSnmp == 1){
			$("#snmp_name").blur();
			$("#snmp_port").blur();
			$("#snmp_timeout").blur();
			if(flag == false){
				alert("SNMP信息填写有误");
				return false;
			}
			oSnmpConfig={};
			oSnmpConfig.snmp_orgname = $.trim($("#snmp_name").val());
			oSnmpConfig.snmp_port = $.trim($("#snmp_port").val());
			oSnmpConfig.snmp_timeout = $.trim($("#snmp_timeout").val());			
		}
		var url = "index.php?c=panel&a=sysfeatureset";
		$.post(url,{'oIsOpen':oIsOpen,'oIsSnmp':oIsSnmp,'oSnmpConfig':oSnmpConfig},function(msg){
			if(msg.indexOf('success')!=-1){
				alert('设置成功');
			}else{
				alert('设置失败');
			}
		});
	});
	
});