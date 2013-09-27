$(document).ready(function(){
	//处理全选
	$("#checkall").click(function(){
		$(".checkbox").attr('checked',this.checked);
	});
	
	//处理删除
	function delcommon(url ,callbackurl,perurl){
		var delarr = new Array();
		$("input:checked").not("#checkall").each(function(){
			var id = $(this).attr('value');
			delarr.push(id);
		});
		if(delarr.length<=0){
			alert('请选择要删除的项');
			return false;
		}
		if(confirm("确定要删除选中的项？")==true){
			$.post(url ,{'arr':delarr},function(msg){
				if(msg.indexOf('no_permissions')!=-1){
					window.location = perurl;
				}else if(msg.indexOf('success')!=-1){
					window.location = callbackurl;
				}else{
					alert('删除失败');
				}
			});
		}
	}
	
	//处理设备删除
	$("#delete").click(function(){				
		var url = 'index.php?c=device&a=del';
		var callbackurl = 'index.php?c=device&a=index';
		var perurl = 'index.php?c=device&a=permiterror&left=devlist';
		delcommon(url,callbackurl,perurl);
	});
	
	//处理脚本删除
	$("#scriptdel").click(function(){		
		var url = 'index.php?c=maintain&a=delscript';
		var callbackurl = 'index.php?c=maintain&a=onlinescript';
		var perurl = 'index.php?c=maintain&a=permiterror&left=onlinescript';
		delcommon(url,callbackurl,perurl);
	});
	
	//处理删除日志
	$("#logdel").click(function(){		
		var url = 'index.php?c=maintain&a=delsyslog';
		var callbackurl = 'index.php?c=maintain&a=loglist';
		var perurl = 'index.php?c=maintain&a=permiterror&left=loglist';
		delcommon(url,callbackurl,perurl);
	});
	
	//处理删除知识
	$("#knowdel").click(function(){
		var url = 'index.php?c=maintain&a=delknow';
		var callbackurl = 'index.php?c=maintain&a=knowlist';
		var perurl = 'index.php?c=maintain&a=permiterror&left=knowlist';
		delcommon(url,callbackurl,perurl);
	});
	
	//处理删除配置文件
	$("#configfiledel").click(function(){
		var url = 'index.php?c=maintain&a=delconfigfile';
		var callbackurl = 'index.php?c=maintain&a=configfilelist';
		var perurl = 'index.php?c=maintain&a=permiterror&left=configfilelist';
		delcommon(url,callbackurl,perurl);
	});
	
	//处理删除告警信息
	$("#del_alarm").click(function(){
		var url = 'index.php?c=personcenter&a=delalarm';
		var callbackurl = 'index.php?c=personcenter&a=index';
		var perurl = 'index.php?c=personcenter&a=permiterror&left=list';
		delcommon(url,callbackurl,perurl);
	});
	
	//处理删除批量操作结果
	$("#bresult_del").click(function(){
		var tasktype = $("#tasktype").val();
		var url = 'index.php?c=maintain&a=delbresult';
		var callbackurl = $("#hide_url").val();
		var perurl = 'index.php?c=maintain&a=permiterror&left=bresultlist';
		var delarr = new Array();
		$("input:checked").not("#checkall").each(function(){
			var id = $(this).attr('value');
			delarr.push(id);
		});
		if(delarr.length<=0){
			alert('请选择要删除的项');
			return false;
		}
		if(confirm("确定要删除选中的项？")==true){
			$.post(url ,{'arr':delarr,'tasktype':tasktype},function(msg){
				if(msg.indexOf('no_permissions')!=-1){
					window.location = perurl;
				}else if(msg.indexOf('success')!=-1){
					window.location = callbackurl;
				}else{
					alert('删除失败');
				}
			});
		}
	});
	
	
	//处理批量终止任务操作结果
	$("#task_stop").click(function(){
		var tasktype = $("#tasktype").val();
		var url = '';
		if(tasktype == 0){
			url = 'index.php?c=maintain&a=stoptaskplan';
		}else{
			url = 'index.php?c=maintain&a=stoptasknow';
		}
		var callbackurl = $("#hide_url").val();
		//var perurl = 'index.php?c=maintain&a=permiterror&left=bresultlist';
		var delarr = new Array();
		$("input:checked").not("#checkall").each(function(){
			var id = $(this).attr('value');
			delarr.push(id);
		});
		if(delarr.length<=0){
			alert('请选择要终止的任务');
			return false;
		}
		if(confirm("确定要终止选中的任务？")==true){
			$.post(url ,{'arr':delarr},function(msg){
				if(msg.indexOf('success')!=-1){
					window.location = callbackurl;
				}else{
					alert('终止失败');
				}
			});
		}
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
    
    //控制跳页
    $('.dojump').click(function(){
		var page = $('#to_page').val();
		var pagenum = $('#hide_pagenums').val();
		if(page<1){
			page = 1;
		}else if(page > pagenum){
			page = pagenum;
		}
		var url = $('#hide_url').val();
		var per_page = $('#hide_perpage').val();
		var offset = (page-1)*parseInt(per_page);
		window.location = url+'&offset='+offset;
	});
});