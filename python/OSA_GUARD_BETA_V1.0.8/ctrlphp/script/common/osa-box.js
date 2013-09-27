
/**
 * osa-box.js
 * date:2012-10-12
 * author:jiangfeng
 */
function boxShowIp(ipStr){
	
	var _ipCache = ''; //ip信息缓存
	var _page = 1; //默认显示第一页
	var _perPage = 20 ; //默认每页显示20个ip
	
	//初始化分页展示为第一页
	var initPage = function(){
		_page = 1;
	};
	
	//创建弹出层
	var createLayer = function(){
		//创建遮罩
		topWindow = $(window.document);
		$(document.body).append("<div id='masklayer'></div>");
		$('#masklayer').css({
			'position':'absolute',
			'left':'0px',
			'top':'0px',
			'width':'100%',
			'min-width':'1024px',
			'height':'100%',
			'background':'#444444',
			'opacity':'0.25',
			'z-index':'9998'
		});
		
		//创建显示ip的弹出层
		topWindow.find('body').append("<div id='osa_box' style='display:none;z-index:9999' class='osa_box'></div>");
		topWindow.find("#osa_box").append("<div class='osa_box_title' id='osa_box_title'></div>");
		topWindow.find("#osa_box_title").append("<span class='osa_box_text'>搜索服务器</span>");
		topWindow.find("#osa_box_title").append("<input type='button' class='osa_box_close' id='osa_box_close' />")
		topWindow.find("#osa_box").append("<div class='osa_box_body' id='osa_box_body'></div>");
		topWindow.find("#osa_box_body").append("<div class='body_header' id='body_header'></div>");
		topWindow.find("#osa_box_body").append("<div class='body_options' id='body_options' style='display:none;'></div>");
		topWindow.find("#osa_box_body").append("<div class='body_result'>查询结果:</div>");
		topWindow.find("#osa_box_body").append("<div class='body_content' id='body_content'></div>");
		topWindow.find("#osa_box_body").append("<div class='body_footer' id='body_footer'></div>");
		//body_footer 元素 添加
		topWindow.find("#body_footer").append("<div class='osa_box_page'><div class='li_page' id='li_page'></div></div>");
		topWindow.find("#body_footer").append("<div class='osa_box_line'>&nbsp;</div>");
		topWindow.find("#body_footer").append("<div class='osa_box_right'><input type='button' class='confirm osa_box_button' id='osa_box_confirm' value='确定' /><input type='button' class='cancel osa_box_button' id='osa_box_cancel' value='取消' /></div>");
		//body_header 元素 添加
		topWindow.find("#body_header").append("<div class='osa_box_right'><input class='osa_box_search' type='text' id='osa_box_search'><label class='osa_box_label' id='more_select'>更多高级选项》</label></div>");
		topWindow.find("#body_header").append("<div class='osa_box_left' id='body_header_left'></div>");
		topWindow.find("#body_header_left").append("<div class='osa_box_left'><a class='green_a' id='clear-ip'><span class='body_header_lgreen'>清空条件</span><span class='body_header_rgreen'></span></a></div>");
		topWindow.find("#body_header_left").append("<div class='osa_box_left'><a class='gray_a' id='checkall-ip'><span class='body_header_lgray'>全选</span><span class='body_header_rgray'></span></a></div>");
		topWindow.find("#body_header_left").append("<div class='osa_box_left'><a class='gray_a' id='invert-ip'><span class='body_header_lgray'>反选</span><span class='body_header_rgray'></span></a></div>");
		topWindow.find("#body_header_left").append("<div class='osa_box_left'><a class='gray_a' id='cancelall-ip'><span class='body_header_lgray'>全不选</span><span class='body_header_rgray'></span></a></div>");
		topWindow.find("#body_header").append("<div style='clear:both;'></div>");
		//body_options 元素 添加
		topWindow.find("#body_options").append("<div style='height:10px;'></div>");
		topWindow.find("#body_options").append("<div class='body_types' id='body_types'></div>");
		topWindow.find("#body_types").append("<label class='options_lable'>设备类型：</label>");
		topWindow.find("#body_types").append("<div style='float:left' id='body_types_left'></div>");
		topWindow.find("#body_options").append("<div class='body_lables' id='body_rooms' style='clear:both;'></div>");
		topWindow.find("#body_rooms").append("<label class='options_lable'>托管机房：</label>");
		topWindow.find("#body_rooms").append("<div style='float:left' id='body_rooms_left'></div>");
		topWindow.find("#body_options").append("<div style='clear:both;height:10px;'></div>");
	};
	
	var initDevType = function(){	
		var url = "index.php?c=serverajax&a=devtype_inquiry";
		$.ajax({
			type:"post",
			url:"index.php?c=serverajax&a=devtype_inquiry",
			dataType:"json",
			success:function(data){
				for(i in data){
					var type = "<span class='options_span'><a class='osa_types' key='"+data[i].id+"'>"+data[i].oTypeName+"</a></span>";
					$("#body_types_left").append(type);		
				}
			},
			error:function(){
				
			}
		});
	};
	
	var initDevRoom = function(){
		
		var url = "index.php?c=serverajax&a=devtroom_inquiry";
		$.ajax({
			type:"post",
			url:"index.php?c=serverajax&a=devroom_inquiry",
			dataType:"json",
			success:function(data){
				for(i in data){
					var room = "<span class='options_span'><a class='osa_rooms' key='"+data[i].id+"'>"+data[i].oRoomName+"</a></span>";
					$("#body_rooms_left").append(room);		
				}
			},
			error:function(){
				
			}
		});
		
	};
	
	
	//初始化_ipCache
	var _ipCacheInit = function(ipObject){
	
		_ipCache = '';
		for(n in ipObject){
			_ipCache += ','+ipObject[n].oIp;
		}
		_ipCache = _ipCache.replace(',,',',');
		_ipCache = _ipCache.replace(/(^\,*)|(\,*$)/g, "");
	};
	
	//显示|删除 去重复
	function ipShowDeal(ipcache ,_ipnewcache){
		if(ipcache ==''){
			return _ipnewcache;
		}
		var iparr = ipcache.split(',');
		var ipnewarr = _ipnewcache.split(',');
		for(i in iparr){//不能使用replace
			for(n in ipnewarr){
				if(iparr[i] == ipnewarr[n]){				
					delete ipnewarr[n];
					break ;
				}
			}
		}
		for(n in ipnewarr){
			ipcache += ','+ipnewarr[n];
		}
		ipcache = ipcache.replace(',,',',');
		return ipcache.replace(/(^\,*)|(\,*$)/g, "");
	}
	
	//同步 ipStr
	var ipStrSynch = function(ipStr){
		
		if(ipStr){
			var iparr = ipStr.split(',');
			$(".osa_box_checkbox").each(function(){
				var value = $(this).attr('value');
				for(i in iparr){
					if(value == iparr[i]){
						$(this).attr('checked','checked');
						break ;
					}
				}
			});
		}
	};
	
	var pageShow = function(){
		if(_ipCache == ''){
			$("#body_content").html('');
			$("#li_page").html('');
			return ;
		}
		var _iparr = _ipCache.split(',');
		var pagenum = Math.ceil(_iparr.length/_perPage );//获取总页数
		if(pagenum >1){
			var content='' ;
			var offset = (_page-1)*_perPage;
			var lastset = _perPage*_page>_iparr.length?_iparr.length:_perPage*_page;
			for(var i=offset;i<lastset;i++){
				content += "<span class='osa_box_left20'><input type='checkbox' class='osa_box_checkbox'  value='"+_iparr[i]+"'/><label class='osa_box_data'>"+_iparr[i]+"</label></span>";
			}
			$("#body_content").html('').html(content);
			var pageShow = "<a class='a_page' id='first-page'>首页</a><a class='a_page' id='pre-page'>上一页</a>"; 
			for(n=1;n<=pagenum;n++){
				if(_page == n){
					pageShow +="<a class='a_page num-page page_curr' id='num_"+n+"'>"+n+"</a>";
				}else{
					pageShow +="<a class='a_page num-page' id='num_"+n+"'>"+n+"</a>";
				}
			}
			pageShow +="<a class='a_page' id='next-page'>下一页</a><a class='a_page' id='last-page'>尾页</a>";
			$("#li_page").html('').html(pageShow);
		}else{
			var content='' ;
			for(i in _iparr){
				content += "<span class='osa_box_left20'><input type='checkbox' class='osa_box_checkbox'  value='"+_iparr[i]+"'/><label class='osa_box_data'>"+_iparr[i]+"</label></span>";
			}
			$("#body_content").html('').html(content);;
			$("#li_page").html('');
		}
	};
	
	var initServerInfo = function(){	
		$.ajax({
			type:"post",
			url:"index.php?c=serverajax&a=serverip_inquiry",
			dataType:"json",
			success:function(data){
				_ipCache ='';
				for(n in data){
					_ipCache += ','+data[n].oIp;
				}
				_ipCache = _ipCache.replace(',,',',');
				_ipCache = _ipCache.replace(/(^\,*)|(\,*$)/g, "");
				pageShow();
				ipStrSynch(ipStr);
			},
			error:function(){
				
			}		
		});	
	};
	//osa-box complete init
	var osa_box_complete_init = function(){
		
		createLayer();
		initPage();
		initDevType();
		initDevRoom();
		initServerInfo();
		//计算position
	};
	//初始化
	osa_box_complete_init();
	var tips_height = topWindow.find('#osa_box').height();
	var tips_width = topWindow.find('#osa_box').width();
	var left = 0;
	var top = 0;
	var scrollTop = $(window.parent.document).scrollTop();
	var screen_height	= $(window).height();
	var screen_width	= $(window).width();
	left = (screen_width - tips_width)/2;
	top = (screen_height - tips_height)/2 + scrollTop;
	
	
	topWindow.find('#osa_box').css('left',left+'px');
	topWindow.find('#osa_box').css('top',top+'px');
	topWindow.find('#osa_box').css('display','block');
	
	/***************************** 事件绑定 *************************************/
	$("#osa_box_close,#osa_box_cancel").click(function(){
		topWindow.find('#osa_box').remove();
		topWindow.find('#masklayer').remove();
	});
	
	$("#osa_box_confirm").click(function(){
		var value = '';
		var iparr = new Array();
		$(".osa_box_checkbox:checked").each(function(){
			value += $(this).val()+',';
		});
		var oldvalue = $("#itemip").val();
		value = value.replace(/(^\,*)|(\,*$)/g, "");
		value = ipShowDeal(oldvalue,value);//去重复
		var iphtml = '';
		if(value !=''){
			iparr = value.split(',');
			for(i in iparr){
				iphtml += '<div class="left width150"><label class="label_c1 li_server">'+iparr[i]+'</label><div class="window_ipclose server_close">&nbsp;</div></div>';
			}
		}
		$("#show_resultip").html('').html(iphtml);
		$("#itemip").attr('value',value);
		topWindow.find('#osa_box').remove();
		topWindow.find('#masklayer').remove();	
	});
	
	$("#more_select").click(function(){
		$("#body_options").toggle('slow');
	});
	
	//处理全选,全不选,反选
	$("#checkall-ip").click(function(){
		$(".osa_box_checkbox").attr('checked','checked');
	});
	$("#cancelall-ip").click(function(){
		$(".osa_box_checkbox").attr('checked',false);
	});
	$("#invert-ip").click(function(){
		$(".osa_box_checkbox").each(function(){
			$(this).attr('checked',!this.checked);
		});
	});
	
	//分页 显示
	$("#pre-page").live("click",function(){
		_page = _page-1;
		if(_page<1){
			_page = 1;
		}
		pageShow();
		ipStrSynch(ipStr);
	});
	$("#next-page").live("click",function(){
		_page = _page + 1;
		var _iparr = _ipCache.split(',');
		var pagenum = Math.ceil(_iparr.length/_perPage );
		if(_page > pagenum){
			_page = pagenum ;
		}
		pageShow();
		ipStrSynch(ipStr);
	});
	
	$("#first-page").live("click",function(){	
		_page = 1;
		pageShow();
		ipStrSynch(ipStr);	
	});
	
	$("#last-page").live("click",function(){
		var _iparr = _ipCache.split(',');
		var pagenum = Math.ceil(_iparr.length/_perPage );
		_page = pagenum;
		pageShow();
		ipStrSynch(ipStr);
	});
	
	$(".num-page").live("click",function(){
		_page = $(this).html();
		pageShow();
		ipStrSynch(ipStr);
	});
	
	$("#osa_box_search").live("keyup",function(){
		var ipvalue = $.trim($(this).val());
		$.ajax({
			type:"post",
			url:"index.php?c=serverajax&a=serverip_inquiry",
			data:{'ipvalue':ipvalue},
			dataType:"json",
			success:function(data){
				initPage();
				_ipCache ='';
				for(n in data){
					_ipCache += ','+data[n].oIp;
				}
				_ipCache = _ipCache.replace(',,',',');
				_ipCache = _ipCache.replace(/(^\,*)|(\,*$)/g, "");
				pageShow();
				ipStrSynch(ipStr);
				
			},
			error:function(){
				
			}		
		});		
	});
	
	$(".osa_types").live("click",function(){
		
		var typeid = $.trim($(this).attr('key'));
		$.ajax({
			type:"post",
			url:"index.php?c=serverajax&a=serverip_inquiry_bytypes",
			data:{'typeid':typeid},
			dataType:"json",
			success:function(data){
				initPage();
				_ipCache ='';
				for(n in data){
					_ipCache += ','+data[n].oIp;
				}
				_ipCache = _ipCache.replace(',,',',');
				_ipCache = _ipCache.replace(/(^\,*)|(\,*$)/g, "");
				pageShow();
				ipStrSynch(ipStr);
				
			},
			error:function(){
				
			}		
		});	
	});
	
	$(".osa_rooms").live("click",function(){
		
		var roomid = $.trim($(this).attr('key'));
		$.ajax({
			type:"post",
			url:"index.php?c=serverajax&a=serverip_inquiry_byrooms",
			data:{'roomid':roomid},
			dataType:"json",
			success:function(data){
				initPage();
				_ipCache ='';
				for(n in data){
					_ipCache += ','+data[n].oIp;
				}
				_ipCache = _ipCache.replace(',,',',');
				_ipCache = _ipCache.replace(/(^\,*)|(\,*$)/g, "");
				pageShow();
				ipStrSynch(ipStr);
				
			},
			error:function(){
				
			}		
		});	
		
	});
	
	$("#clear-ip").click(function(){
		
		$("#osa_box_search").attr('value','');
		initServerInfo();
	});
	
	/**************************  draggable  *********************/
	var isMouseDown = false;
	var isMouseMove = false;
	var downX = 0;
	var downY = 0;
	topWindow.find('#osa_box').mousedown(function(e){
		
		isMouseDown = true;
		e=e||evnet;
		downX = parseInt(e.clientX);
		downY = parseInt(e.clientY);
		topWindow.find('body').mousemove(function(e){
			if( !isMouseDown ) return;
			var oleft = parseInt(e.clientX)-downX;
			var otop = parseInt(e.clientY)-downY;
			var left = parseInt( topWindow.find('#osa_box').css('left') ) + oleft;
			var top = parseInt( topWindow.find('#osa_box').css('top') ) + otop;
			
			var screen_height	= $(window.parent).height();
			var screen_width	= $(window.parent).width();
			//计算滚动条偏差
		var sleft = $(window.parent.document).scrollLeft(); 
		var stop = $(window.parent.document).scrollTop(); 
			left = left < 0 ? '0' : left;
			top = top < 0 ? '0' : top;
			left = left >(screen_width + sleft - tips_width) ? screen_width + sleft - tips_width : left;
			top = top >(screen_height + stop - tips_height) ? screen_height + stop - tips_height : top;
			topWindow.find('#osa_box').css('left',left+'px').css('top',top+'px');
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

var  boxShowUser = function(userStr){
	
	var _userCache = ''; //user信息缓存
	var _page = 1; //默认显示第一页
	var _perPage = 20 ; //默认每页显示20个ip
	
	//初始化分页展示为第一页
	var initPage = function(){
		_page = 1;
	};
	
	//创建弹出层
	var createLayer = function(){
		//创建遮罩
		topWindow = $(window.document);
		$(document.body).append("<div id='masklayer'></div>");
		$('#masklayer').css({
			'position':'absolute',
			'left':'0px',
			'top':'0px',
			'width':'100%',
			'min-width':'1024px',
			'height':'100%',
			'background':'#444444',
			'opacity':'0.25',
			'z-index':'9998'
		});
		
		//创建显示ip的弹出层
		topWindow.find('body').append("<div id='osa_box' style='display:none;z-index:9999' class='osa_box'></div>");
		topWindow.find("#osa_box").append("<div class='osa_box_title' id='osa_box_title'></div>");
		topWindow.find("#osa_box_title").append("<span class='osa_box_text'>搜索服务器</span>");
		topWindow.find("#osa_box_title").append("<input type='button' class='osa_box_close' id='osa_box_close' />")
		topWindow.find("#osa_box").append("<div class='osa_box_body' id='osa_box_body'></div>");
		topWindow.find("#osa_box_body").append("<div class='body_header' id='body_header'></div>");
		topWindow.find("#osa_box_body").append("<div class='body_options' id='body_options' style='display:none;'></div>");
		topWindow.find("#osa_box_body").append("<div class='body_result'>查询结果:</div>");
		topWindow.find("#osa_box_body").append("<div class='body_content' id='body_content'></div>");
		topWindow.find("#osa_box_body").append("<div class='body_footer' id='body_footer'></div>");
		//body_footer 元素 添加
		topWindow.find("#body_footer").append("<div class='osa_box_page'><div class='li_page' id='li_page'></div></div>");
		topWindow.find("#body_footer").append("<div class='osa_box_line'>&nbsp;</div>");
		topWindow.find("#body_footer").append("<div class='osa_box_right'><input type='button' class='confirm osa_box_button' id='osa_box_confirm' value='确定' /><input type='button' class='cancel osa_box_button' id='osa_box_cancel' value='取消' /></div>");
		//body_header 元素 添加
		topWindow.find("#body_header").append("<div class='osa_box_right'><input class='osa_box_search' type='text' id='osa_box_search'></div>");
		topWindow.find("#body_header").append("<div class='osa_box_left' id='body_header_left'></div>");
		topWindow.find("#body_header_left").append("<div class='osa_box_left'><a class='green_a' id='clear-user'><span class='body_header_lgreen'>清空条件</span><span class='body_header_rgreen'></span></a></div>");
		topWindow.find("#body_header_left").append("<div class='osa_box_left'><a class='gray_a' id='checkall-user'><span class='body_header_lgray'>全选</span><span class='body_header_rgray'></span></a></div>");
		topWindow.find("#body_header_left").append("<div class='osa_box_left'><a class='gray_a' id='invert-user'><span class='body_header_lgray'>反选</span><span class='body_header_rgray'></span></a></div>");
		topWindow.find("#body_header_left").append("<div class='osa_box_left'><a class='gray_a' id='cancelall-user'><span class='body_header_lgray'>全不选</span><span class='body_header_rgray'></span></a></div>");
		topWindow.find("#body_header").append("<div style='clear:both;'></div>");
	
	};
	
	
	//初始化_ipCache
	var _userCacheInit = function(userObject){
	
		_userCache = '';
		for(n in userObject){
			_userCache += ','+userObject[n].oIp;
		}
		_userCache = _userCache.replace(',,',',');
		_userCache = _userCache.replace(/(^\,*)|(\,*$)/g, "");
	};
	
	//显示|删除 去重复
	function userShowDeal(usercache ,_usernewcache){
		if(usercache ==''){
			return _usernewcache;
		}
		var userarr = usercache.split(',');
		var usernewarr = _usernewcache.split(',');
		for(i in userarr){//不能使用replace
			for(n in usernewarr){
				if(userarr[i] == usernewarr[n]){				
					delete usernewarr[n];
					break ;
				}
			}
		}
		for(n in usernewarr){
			usercache += ','+usernewarr[n];
		}
		usercache = usercache.replace(',,',',');
		return usercache.replace(/(^\,*)|(\,*$)/g, "");
	}
	
	//同步 userStr
	var userStrSynch = function(userStr){
		
		if(userStr){
			var usrarr = userStr.split(',');
			$(".osa_box_checkbox").each(function(){
				var value = $(this).attr('value');
				for(i in usrarr){
					if(value == usrarr[i]){
						$(this).attr('checked','checked');
						break ;
					}
				}
			});
		}
	};
	
	var pageShow = function(){
		if(_userCache == ''){
			$("#body_content").html('');
			$("#li_page").html('');
			return ;
		}
		var _userarr = _userCache.split(',');
		var pagenum = Math.ceil(_userarr.length/_perPage );//获取总页数
		if(pagenum >1){
			var content='' ;
			var offset = (_page-1)*_perPage;
			var lastset = _perPage*_page>_userarr.length?_userarr.length:_perPage*_page;
			for(var i=offset;i<lastset;i++){
				content += "<span class='osa_box_left20'><input type='checkbox' class='osa_box_checkbox'  value='"+_userarr[i]+"'/><label class='osa_box_data'>"+_userarr[i]+"</label></span>";
			}
			$("#body_content").html('').html(content);
			var pageShow = "<a class='a_page' id='first-page'>首页</a><a class='a_page' id='pre-page'>上一页</a>"; 
			for(n=1;n<=pagenum;n++){
				if(_page == n){
					pageShow +="<a class='a_page num-page page_curr' id='num_"+n+"'>"+n+"</a>";
				}else{
					pageShow +="<a class='a_page num-page' id='num_"+n+"'>"+n+"</a>";
				}
			}
			pageShow +="<a class='a_page' id='next-page'>下一页</a><a class='a_page' id='last-page'>尾页</a>";
			$("#li_page").html('').html(pageShow);
		}else{
			var content='' ;
			for(i in _userarr){
				content += "<span class='osa_box_left20'><input type='checkbox' class='osa_box_checkbox'  value='"+_userarr[i]+"'/><label class='osa_box_data'>"+_userarr[i]+"</label></span>";
			}
			$("#body_content").html('').html(content);;
			$("#li_page").html('');
		}
	};
	
	var initUserInfo = function(){	
		$.ajax({
			type:"post",
			url:"index.php?c=serverajax&a=userinfo_inquiry",
			dataType:"json",
			success:function(data){
				_userCache ='';
				for(n in data){
					_userCache += ','+data[n].oUserName;
				}
				_userCache = _userCache.replace(',,',',');
				_userCache = _userCache.replace(/(^\,*)|(\,*$)/g, "");
				pageShow();
				userStrSynch(userStr);
			},
			error:function(){
				
			}		
		});	
	};
	//osa-box complete init
	var osa_box_complete_init = function(){
		
		createLayer();
		initPage();
		initUserInfo();
		//计算position
	};
	//初始化
	osa_box_complete_init();
	var tips_height = topWindow.find('#osa_box').height();
	var tips_width = topWindow.find('#osa_box').width();
	var left = 0;
	var top = 0;
	var scrollTop = $(window.parent.document).scrollTop();
	
	var screen_height	= $(window).height();
	var screen_width	= $(window).width();
	left = (screen_width - tips_width)/2;
	top = (screen_height - tips_height)/2 + scrollTop;
	
	
	topWindow.find('#osa_box').css('left',left+'px');
	topWindow.find('#osa_box').css('top',top+'px');
	topWindow.find('#osa_box').css('display','block');
	
	/***************************** 事件绑定 *************************************/
	$("#osa_box_close,#osa_box_cancel").click(function(){
		topWindow.find('#osa_box').remove();
		topWindow.find('#masklayer').remove();
	});
	
	$("#osa_box_confirm").click(function(){
		var value = '';
		var userarr = new Array();
		$(".osa_box_checkbox:checked").each(function(){
			value += $(this).val()+',';
		});
		var oldvalue = $("#users").val();
		value = value.replace(/(^\,*)|(\,*$)/g, "");
		value = userShowDeal(oldvalue,value);//去重复
		var userhtml = '';
		if(value !=''){
			userarr = value.split(',');
			for(i in userarr){
				userhtml += '<div class="left width150"><label class="label_c1 li_users">'+userarr[i]+'</label><div class="window_ipclose user_close">&nbsp;</div></div>';
			}
		}
		$("#show_resultuser").html('').html(userhtml);
		$("#users").attr('value',value);
		topWindow.find('#osa_box').remove();
		topWindow.find('#masklayer').remove();	
	});
	
	
	//处理全选,全不选,反选
	$("#checkall-user").click(function(){
		$(".osa_box_checkbox").attr('checked','checked');
	});
	$("#cancelall-user").click(function(){
		$(".osa_box_checkbox").attr('checked',false);
	});
	$("#invert-user").click(function(){
		$(".osa_box_checkbox").each(function(){
			$(this).attr('checked',!this.checked);
		});
	});
	
	//分页 显示
	$("#pre-page").live("click",function(){
		_page = _page-1;
		if(_page<1){
			_page = 1;
		}
		pageShow();
		userStrSynch(userStr);
	});
	$("#next-page").live("click",function(){
		_page = _page + 1;
		var _userarr = _userCache.split(',');
		var pagenum = Math.ceil(_userarr.length/_perPage );
		if(_page > pagenum){
			_page = pagenum ;
		}
		pageShow();
		userStrSynch(userStr);
	});
	
	$("#first-page").live("click",function(){	
		_page = 1;
		pageShow();
		userStrSynch(userStr);	
	});
	
	$("#last-page").live("click",function(){
		var _userarr = _userCache.split(',');
		var pagenum = Math.ceil(_userarr.length/_perPage );
		_page = pagenum;
		pageShow();
		userStrSynch(userStr);
	});
	
	$(".num-page").live("click",function(){
		_page = $(this).html();
		pageShow();
		userStrSynch(userStr);
	});
	
	$("#osa_box_search").live("keyup",function(){
		var ipvalue = $.trim($(this).val());
		$.ajax({
			type:"post",
			url:"index.php?c=serverajax&a=serverip_inquiry",
			data:{'ipvalue':ipvalue},
			dataType:"json",
			success:function(data){
				initPage();
				_ipCache ='';
				for(n in data){
					_ipCache += ','+data[n].oIp;
				}
				_ipCache = _ipCache.replace(',,',',');
				_ipCache = _ipCache.replace(/(^\,*)|(\,*$)/g, "");
				pageShow();
				ipStrSynch(ipStr);
				
			},
			error:function(){
				
			}		
		});		
	});
	

	

	$("#clear-user").click(function(){
		
		$("#osa_box_search").attr('value','');
		initServerInfo();
	});
	
	/**************************  draggable  *********************/
	var isMouseDown = false;
	var isMouseMove = false;
	var downX = 0;
	var downY = 0;
	topWindow.find('#osa_box').mousedown(function(e){
		
		isMouseDown = true;
		e=e||evnet;
		downX = parseInt(e.clientX);
		downY = parseInt(e.clientY);
		topWindow.find('body').mousemove(function(e){
			if( !isMouseDown ) return;
			var oleft = parseInt(e.clientX)-downX;
			var otop = parseInt(e.clientY)-downY;
			var left = parseInt( topWindow.find('#osa_box').css('left') ) + oleft;
			var top = parseInt( topWindow.find('#osa_box').css('top') ) + otop;
			
			var screen_height	= $(window.parent).height();
			var screen_width	= $(window.parent).width();
			//计算滚动条偏差
		var sleft = $(window.parent.document).scrollLeft(); 
		var stop = $(window.parent.document).scrollTop(); 
			left = left < 0 ? '0' : left;
			top = top < 0 ? '0' : top;
			left = left >(screen_width + sleft - tips_width) ? screen_width + sleft - tips_width : left;
			top = top >(screen_height + stop - tips_height) ? screen_height + stop - tips_height : top;
			topWindow.find('#osa_box').css('left',left+'px').css('top',top+'px');
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
};
