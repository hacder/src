$(document).ready(function(){
	//处理全选
	$("#checkall").click(function(){
		$(".checkbox").attr('checked',this.checked);
	});
	
	//处理删除
	$("#delete").click(function(){		
		var delarr = new Array();
		$("input:checked").not("#checkall").each(function(){
			var id = $(this).attr('value');
			delarr.push(id);
		});
		if(delarr.length<=0){
			alert('请选择要删除的设备');
			return false;
		}
		var url = 'index.php?c=device&a=del';
		$.post(url ,{'arr':delarr},function(msg){
			if(msg.indexOf('no_permissions')!=-1){
				window.location = 'index.php?c=device&a=permiterror&left=devlist';
			}else if(msg.indexOf('success')!=-1){
				window.location = 'index.php?c=device&a=index';
			}else{
				alert('设备删除失败');
			}
		});
	});
	
	//自定义搜索下拉
	$("#showsearch").click(function(){
		$("#timepop").show();		
	});
	$("#cancelsearch").click(function(){
		$(".timeFrame").find(":text").attr('value','');
		$("#timepop").hide();
	});
	
	/*********************时间控件控制******************************/
	var clicktimes = 0;
    $("#datepicker").datepicker({
    	//rangeSelect: true,
    	dateFormat: 'yy-mm-dd',
    	numberOfMonths: 2,
    	maxDate:'+0d',
    	nextText: 'Next',
    	prevText: 'Pre',
    	onSelect:function(dataText,inst){
    		if(clicktimes ==0){
    			$("#date1").attr('value',dataText);
    			$("#date2").attr('value',dataText);
    			clicktimes = 1;
    			$('#datepicker').datepicker('option', 'minDate',dataText);
        	}else if(clicktimes == 1){
        		//$("#date1").attr('value',dataText);
    			$("#date2").attr('value',dataText);
    			clicktimes = 0;
    			$('#datepicker').datepicker('option', 'minDate','2010-12-30');
            }
    	}
    
    });
});