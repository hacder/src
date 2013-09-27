$(document).ready(function(){
	
	/**
	 * 按每页显示数量查询
	 */
	$(".page_li").click(function(){
		var pagenum = $.trim($(this).html());
		var url = $("#hide_url").val();
		window.location = url+"&pagenum="+pagenum;	
	});
	
	
	$("#time-select").click(function(){
		
		$("#time-toggle").toggle();
	});
	
	
	$("#alarm-search").click(function(){
		
		var time1 = $("#datetime1").val();
		var time2 = $("#datetime2").val();
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
	
	$("#datetime1").datepicker({
		dateFormat: 'yy-mm-dd'
	});
	
	$("#datetime2").datepicker({
		dateFormat: 'yy-mm-dd'
	});
	
	//跳页处理
	 $('.dojump').click(function(){
		var page = $('#to_page').val();
		var pagenum = $('#hide_pagenums').val();
		if(page<1){
			page = 1;
		}
		if(parseInt(page)>parseInt(pagenum)){
			page = pagenum;
		}
		var url = $('#hide_url').val();
		var per_page = $('#hide_perpage').val();
		var offset = (page-1)*parseInt(per_page);
		window.location = url+'&offset='+offset;
	});
});