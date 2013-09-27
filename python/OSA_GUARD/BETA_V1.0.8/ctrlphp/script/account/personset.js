$(document).ready(function(){
	
	
	//获取权限列表
	function getInfoType(){
		var infotype = '';
		$(".infotype:checked").each(function(){
			infotype +=$(this).attr('value')+',';
		});
		return infotype.replace(/(^\,*)|(\,*$)/g ,"");
	}
	
	//获取报表类型
	function getReportType(){
		
		var reporttype = '';
		$(".reportset:checked").each(function(){
			reporttype +=$(this).attr('value')+',';
		});
		return reporttype.replace(/(^\,*)|(\,*$)/g ,"");
	}
	
	//获取关闭的通知类型
	function getSwitchType(){
		
		var switchtype = '';
		$(".switchset:checked").each(function(){
			switchtype +=$(this).attr('value')+',';
		});
		return switchtype.replace(/(^\,*)|(\,*$)/g ,"");
	}
	
	/**
	 *  save shortcut
	 */
	$("#personset-save").click(function(){
		
		var emailset = $(".receive_set:checked").attr('value');
		var infotype = getInfoType();
		var reporttype = getReportType();
		var closetype = getSwitchType();
		var acceptip = $.trim($("#itemip").val()); 
		var url = "index.php?c=account&a=personset_save";
		$.post(url,{
				'emailset':emailset,
				'infotype':infotype,
				'reporttype':reporttype,
				'closetype':closetype,
				'acceptip':acceptip
			},function(msg){
//			$(".time_pro").show("slow");
//			setTimeout(function(){
//				$(".time_pro").hide("slow");
//			}, 5000);
			if(msg.indexOf('success')!=-1){
				var callback = function(result){
					if(result == true){					
						window.location = "index.php?c=account&a=userlists";
					}
				};
				tipsAlert('个性化设置保存成功',callback);
			}else{
				tipsAlert("个性化设置保存失败");
			}
		});
	});
	
	/**************************** osa box event *****************************/
	$("#server-search").click(function(){
		var ipStr = $.trim($("#itemip").val());
		boxShowIp(ipStr);
	});
	
	var boxShowDel = function(oldvalue,value){
		
		if(oldvalue == ''){
			return value ;
		}
		var arr = oldvalue.split(',');
		for(i in arr){
			if(value == arr[i]){
				delete arr[i];
			}		
		}
		var newvalue ='';
		for(n in arr){
			newvalue +=arr[n]+',';
		}
		newvalue = newvalue.replace(',,',',');
		return newvalue.replace(/(^\,*)|(\,*$)/g, "");
	};
	
	$(".server_close").live("click",function(){	
		var value = $(this).parent().find(".li_server").html();
		var oldvalue = $("#itemip").val();
		newvalue = boxShowDel(oldvalue,value);
		$("#itemip").attr('value',newvalue);
		$(this).parent().remove();
	});
	
});