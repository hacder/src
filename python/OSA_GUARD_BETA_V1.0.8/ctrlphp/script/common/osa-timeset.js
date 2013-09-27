
/**
 * osa-timeset.js
 * date:2012-12-6
 * author:jiangfeng
 */
function boxTimeSet(object,value,itemid){
	
	topWindow = $(window.document);
	var _timeset_create = function(){
		
		topWindow.find('#tanbox').remove();
		//创建显示ip的弹出层
		topWindow.find('body').append("<div id='tanbox' style='display:none;z-index:9999' class='tanbox'></div>");
		topWindow.find("#tanbox").append("<div class='tanbox-con' id='tanbox-con'></div>");
		topWindow.find("#tanbox").append("<div class='tan_z1' id='tanboc-footer'></div>");
		
		topWindow.find("#tanboc-footer").append("<input type='button' value='应用'  class='tanbt' id='confirm-timeset'/>");
		topWindow.find("#tanboc-footer").append("<p class='tan_tx'>监控频率越小，发现问题越早！</p>");
		
		topWindow.find("#tanbox-con").append("<h1 class='tan_h1'><span>修改监控频率</span></h1>");
		topWindow.find("#tanbox-con").append("<ul style='float:left' id='ul-left-con'></ul>");
		topWindow.find("#tanbox-con").append("<ul style='float:left' id='ul-right-con'></ul>");
		
		topWindow.find("#ul-left-con").append("<li><dl><dt><input name='timeset-rate' type='radio' value='30' /></dt><dd>30秒</dd></dl></li>");
		topWindow.find("#ul-left-con").append("<li><dl><dt><input name='timeset-rate' type='radio' value='60' /></dt><dd>1分钟</dd></dl></li>");
		topWindow.find("#ul-left-con").append("<li><dl><dt><input name='timeset-rate' type='radio' value='120' /></dt><dd>2分钟</dd></dl></li>");
		topWindow.find("#ul-left-con").append("<li><dl><dt><input name='timeset-rate' type='radio' value='180' /></dt><dd>3分钟</dd></dl></li>");
		topWindow.find("#ul-left-con").append("<li><dl><dt><input name='timeset-rate' type='radio' value='300' /></dt><dd>5分钟</dd></dl></li>");
		
		topWindow.find("#ul-left-con").append("<li><dl><dt><input name='timeset-rate' type='radio' value='600' /></dt><dd>10分钟</dd></dl></li>");
		topWindow.find("#ul-left-con").append("<li><dl><dt><input name='timeset-rate' type='radio' value='900' /></dt><dd>15分钟</dd></dl></li>");
		topWindow.find("#ul-right-con").append("<li><dl><dt><input name='timeset-rate' type='radio' value='1800' /></dt><dd>30分钟</dd></dl></li>");
		topWindow.find("#ul-right-con").append("<li><dl><dt><input name='timeset-rate' type='radio' value='3600' /></dt><dd>1小时</dd></dl></li>");
		topWindow.find("#ul-right-con").append("<li><dl><dt><input name='timeset-rate' type='radio' value='86400' /></dt><dd>1天</dd></dl></li>");
		//初始化
	};
	
	/** 初始化值 **/
	var _timeset_value_init = function(value){
		
		$("input[name='timeset-rate']").each(function(){
			
			var timevalue = $(this).attr('value');
			if(timevalue == value){
				$(this).attr('checked','checked');
				return ;
			}
		});
		
	};
	//初始化
	var _timeset_init = function(){
		
		_timeset_create();
		_timeset_value_init(value);
	};
	_timeset_init();

	// position 计算
	var tips_height = topWindow.find('#tanbox').height();
	var tips_width = topWindow.find('#tanbox').width();
	var left = 0;
	var top = 0;
	
	var screen_height	= $(window).height();
	var screen_width	= $(window).width();
	
	var ttop = object.offset().top;    //TT控件的定位点高  
    var tleft = object.offset().left;    //TT控件的定位点宽   
	left = (tleft - tips_width);
	top = ttop - tips_height/2;
			
	topWindow.find('#tanbox').css('left',left+'px');
	topWindow.find('#tanbox').css('top',top+'px');
	topWindow.find('#tanbox').css('display','block');
	
	// 修改频率后显示
	var _timeset_show = function(timerate){	
		
		var showtime = '';
		switch(timerate){
			case '30':
					showtime = '30秒';
					break;
			case '60':
					showtime ='1分钟';
					break;
			case '120':
					showtime = '2分钟';
					break;
			case '180':
					showtime = '3分钟';
					break;
			case '300':
					showtime = '5分钟';
					break;
			case '600':
					showtime ='10分钟';
					break;
			case '900':
					showtime ='15分钟';
					break;
			case '1800':
					showtime = '30分钟';
					break;
			case '3600':
					showtime = '1小时';
					break;
			case '86400':
					showtime = '1天';
					break;	
		}	
		return showtime ;
	};
	
	$("#confirm-timeset").click(function(){
		
		var url = 'index.php?c=monitor&a=monitor_timeset';
		var checkrate = $("input[name='timeset-rate']:checked").attr('value');
		var showrate = _timeset_show(checkrate);
		$.post(url,{'itemid':itemid,'checkrate':checkrate},function(msg){
			object.attr('rate',checkrate);
			object.html('').html(showrate);
		});
		topWindow.find('#tanbox').remove();
	});
	
	$("#mainright").click(function(){

		topWindow.find('#tanbox').remove();
	});
	
	
	
	/**************************  draggable  *********************/
	var isMouseDown = false;
	var isMouseMove = false;
	var downX = 0;
	var downY = 0;
	topWindow.find('#tanbox').mousedown(function(e){
		
		isMouseDown = true;
		e=e||evnet;
		downX = parseInt(e.clientX);
		downY = parseInt(e.clientY);
		topWindow.find('body').mousemove(function(e){
			if( !isMouseDown ) return;
			var oleft = parseInt(e.clientX)-downX;
			var otop = parseInt(e.clientY)-downY;
			var left = parseInt( topWindow.find('#tanbox').css('left') ) + oleft;
			var top = parseInt( topWindow.find('#tanbox').css('top') ) + otop;
			
			var screen_height	= $(window.parent).height();
			var screen_width	= $(window.parent).width();
			//计算滚动条偏差
		var sleft = $(window.parent.document).scrollLeft(); 
		var stop = $(window.parent.document).scrollTop(); 
			left = left < 0 ? '0' : left;
			top = top < 0 ? '0' : top;
			left = left >(screen_width + sleft - tips_width) ? screen_width + sleft - tips_width : left;
			top = top >(screen_height + stop - tips_height) ? screen_height + stop - tips_height : top;
			topWindow.find('#tanbox').css('left',left+'px').css('top',top+'px');
			downX = e.clientX;
			downY = e.clientY;
		});
		topWindow.find('body').mouseup(function(e){
			$(this).unbind('mousemove');
			isMouseDown = false;
			downX = 0;
			downY = 0;
		});
	
	});
}
