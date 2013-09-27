//处理添加设备
$(document).ready(function(){
	$.ajaxSetup({
		  async: false
	}); 
	var flag = true ;
	/**************************************devname验证***********************************************/
	$('#devname').click(function(){
		$("#devname_tips").html('');	
	});
	$('#devname').blur(function(){
		var devname = $.trim($(this).val());
		if(devname == ''){
			$("#devname_tips").css({'color':'rgb(263,24,13)'}).html('请输入正确格式的设备名');
			flag = false;
			//return;
		}else if(devname!=devname.match(/^[a-zA-Z0-9\u4e00-\u9fa5][a-zA-Z0-9\u4e00-\u9fa5\.\_\@]+$/)){
			$("#devname_tips").css({'color':'rgb(263,24,13)'}).html('请输入正确格式的设备名');
			flag = false;
			//return ;
		}else{
			$("#devname_tips").html('');	
		}
	});
	
	/**************************************ipname验证***********************************************/
	$('#ipname').click(function(){
		$("#ip_tips").css({'color':'rgb(23,124,226)'}).html('');
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
		var url = 'index.php?c=device&a=ip_check';
		if(ip == ''){
			$("#ip_tips").css({'color':'rgb(263,24,13)'}).html('请输入正确格式的ip');
			flag = false;
			//return;
		}else if(!isip(ip)){
			$("#ip_tips").css({'color':'rgb(263,24,13)'}).html('请输入正确格式的ip');
			flag = false;
			//return ;
		}else{
			$.post(url,{'ip':ip},function(msg){
				if(msg.indexOf('success')!=-1){
					$("#ip_tips").css({'color':'rgb(23,124,226)'}).html('');
				}else if(msg.indexOf('exists')!=-1){
					$("#ip_tips").css({'color':'rgb(263,24,13)'}).html('该ip已经存在');
					flag = false;
				}		
			});	
		}
	});
	
	
	/********************************************设备添加信息*******************************/
	$('#submit').click(function(){	
		flag = true ;
		$('#devname').blur();
		$('#ipname').blur();
		if(flag == false){
			return false;
		}
		var devname = $.trim($('#devname').val());
		var ipname = $.trim($("#ipname").val());
		var devtype = $.trim($("#typename").val());
        if(devtype == '请选择类型'){
        	devtype = '';
        } 
		var workdes = $.trim($("#workdes").val());
		//托管机房
		var engineroom = get_devroom();
		//
		var shelvetime = $.trim($("#shelvetime").val());
		var devlabel = $.trim($("#devlabel").val());
		var devprice = $.trim($("#devprice").val());
		var tgprice = $("#tgprice").val();
		var devdetail = $.trim($("#devdetail").val());
		var url = 'index.php?c=device&a=device_add';
		$.post(url,{
			'devname':devname,
			//'oGroupid':oGroupid,
			'ipname':ipname,
			'devtype':devtype,
			'workdes':workdes,
			'engineroom':engineroom,
			'shelvetime':shelvetime,
			'devlabel':devlabel,
			'devprice':devprice,
			'tgprice':tgprice,
			'devdetail':devdetail
		},function(msg){
			//alert(msg);
			if(msg.indexOf('failure_add')!=-1){
				tipsAlert('设备添加失败！');
				return ;
			}else if(msg.indexOf('sucess_add')!=-1){
				var callback = function(result){
					if(result == true){					
						window.location = "index.php?c=device&a=listindex";
					}
				};
				tipsAlert('设备添加成功！',callback);
				//window.location = 'index.php?c=device&a=listindex';				
			}
		});
	});
	
	function get_devroom(){
		var room = $.trim($("#roomname").val());
		if(room =='请选择机房'){
			room = $.trim($("#input_devroom").val());
		}
		return room;
	}

	/******************************************时间控件****************************************************/
	$("#shelvetime").datepicker({
		dateFormat: 'yy-mm-dd'
	});
	 
});