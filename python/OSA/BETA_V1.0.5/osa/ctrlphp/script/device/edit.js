//处理编辑设备
$(document).ready(function(){
	
	//编辑设备
	$('#submit').click(function(){			
		var oDevName = $.trim($('#devname').val());
		//var oGroupid = $("#selectgroup option:selected").attr('value');
		var oPlace = $("#country option:selected").attr('value')+'|'+$("#province option:selected").attr('value')+'|'+$("#city ").attr('value');
		var oAddress = $.trim($("#address").val());
		var oTypeid = $("#selecttype option:selected").attr('value');
		var oDevPrice = $.trim($("#devprice").val());
		var oDevTgPrice = $.trim($("#tgprice").val());
		if(oDevPrice == ''){
			oDevPrice = 0;
		}
		if(oDevTgPrice == ''){
			oDevTgPrice = 0;
		}
		var oIp = $.trim($("#ipname").val());
		var oDevDetail = editor.getData();
		var oShelveTime = $("#add_time").val();
		var oBusinessDes = $.trim($("#business_des").val());
		var url = editurl;
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
				alert('设备编辑失败');
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