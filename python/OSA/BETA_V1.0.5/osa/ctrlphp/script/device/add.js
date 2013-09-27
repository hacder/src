//处理添加设备
$(document).ready(function(){
	$.ajaxSetup({
		  async: false
	}); 
	var flag = true ;
	$('#devname').click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('设备名由字母数字或中文组成');
	});
	$('#devname').blur(function(){
		var devname = $.trim($(this).val());
		var url = 'index.php?c=device&a=checkname';
		if(devname == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入设备名');
			flag = false;
			//return;
		}else if(devname!=devname.match(/^[a-zA-Z0-9\u4e00-\u9fa5][a-zA-Z0-9\u4e00-\u9fa5\.\_\@]+$/)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入正确格式的设备名');
			flag = false;
			//return ;
		}else{
			$.post(url,{'devname':devname},function(msg){
				if(msg.indexOf('success')!=-1){
					$('#devname').parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('设备名可用');
				}else{
					$('#devname').parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('设备名已被使用');
					flag = false;
					//alert(flag);
					//return ;
				}		
			});	
		}
	});
	$('#ipname').click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('ip格式为192.168.234.56');
	});
	
	function isip(ip){		
		var re=/^(\d+)\.(\d+)\.(\d+)\.(\d+)$/;//正则表达式   
		if(re.test(ip))   
		{   
			if( RegExp.$1<256 && RegExp.$2<256 && RegExp.$3<256 && RegExp.$4<256) 
			return true;   
		}
		return false ;
	}
	$('#ipname').blur(function(){
		var ip = $.trim($(this).val());
		var url = 'index.php?c=device&a=checkip';
		if(ip == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入ip');
			flag = false;
			//return;
		}else if(!isip(ip)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入正确格式的ip');
			flag = false;
			//return ;
		}else{
			$.post(url,{'ip':ip},function(msg){
				if(msg.indexOf('success')!=-1){
					$('#ipname').parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('ip可用');
				}else{
					$('#ipname').parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('ip已存在');
					flag = false;
					//return ;
				}		
			});	
		}
	});
	
	
	//添加设备
	$('#submit').click(function(){	
		flag = true ;
		$('#devname').blur();
		$('#ipname').blur();
		if(flag == false){
			return false;
		}
		var oDevName = $.trim($('#devname').val());
		//var oGroupid = $("#selectgroup option:selected").attr('value');
		var oIp = $.trim($("#ipname").val());
		var oPlace = $("#country option:selected").attr('value')+'|'+$("#province option:selected").attr('value')+'|'+$("#city option:selected").attr('value');
		var oAddress = $.trim($("#address").val());
		var oTypeid = $("#selecttype option:selected").attr('value');
		var oDevPrice = $.trim($("#devprice").val());
		var oDevTgPrice = $.trim($("#tgprice").val());
		var oShelveTime = $("#add_time").val();
		var oBusinessDes = $.trim($("#business_des").val());
		if(oDevPrice == ''){
			oDevPrice = 0;
		}
		if(oDevTgPrice == ''){
			oDevTgPrice = 0;
		}
		var oDevDetail = editor.getData();
		var url = 'index.php?c=device&a=add';
		$.post(url,{
			'oDevName':oDevName,
			//'oGroupid':oGroupid,
			'oIp':oIp,
			'oPlace':oPlace,
			'oAddress':oAddress,
			'oTypeid':oTypeid,
			'oDevPrice':oDevPrice,
			'oDevTgPrice':oDevTgPrice,
			'oDevDetail':oDevDetail,
			'oShelveTime':oShelveTime,
			'oBusinessDes':oBusinessDes
		},function(msg){
			if(msg.indexOf('failure')!=-1){
				alert('设备添加失败');
				return ;
			}else if(msg.indexOf('success')!=-1){
				window.location = 'index.php?c=device&a=index';				
			}
		});
	});
	
	//详细信息自动填写
	$("#city ,#province ,#country").bind('change',function(){
		var place = $("#country option:selected").attr('value')+$("#province option:selected").attr('value')+$("#city option:selected").attr('value');
		$("#address").attr('value',place);
	});
	$("#country").change(function(){
		var place = $("#country option:selected").attr('value');
		if(place !== '中国'){
			$("#city,#province").hide();
		}else{
			$("#city,#province").show();
		}
	});
	
	$("#add_time").datepicker({
		dateFormat: 'yy-mm-dd',
		nextText: 'Next',
    	prevText: 'Pre'
	});
});