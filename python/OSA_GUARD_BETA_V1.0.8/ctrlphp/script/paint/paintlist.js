$(document).ready(function(){
	

	
	$("#paint-time").click(function(){	
		$("#timepop").toggle();
	});
	
	
	$("#paint-search").click(function(){
		
		var time1 = $("#date1").val();
		var time2 = $("#date2").val();
		if(time1 == ''){
			$("#time-toggle").toggle();
		}else if(time2 == ''){		
			$("#time-toggle").toggle();
		}else if(time2<time1){
			$("#time-toggle").toggle();
		}else{
			var url = $("#hideUrl").val();
			window.location = url+"&stime="+time1+"&etime="+time2;
		}
	});
	
	/***************************  时间控件   ***********************/
	var clicktimes = 0;
    $("#datepicker").datepicker({
    	//rangeSelect: true,
    	dateFormat: 'yy-mm-dd',
    	numberOfMonths: 2,
    	maxDate:'+1d',
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