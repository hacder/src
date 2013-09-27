	
	var _ipcache = '';//缓存ip信息
	var _scriptcache = '';//缓存脚本信息
	var _scriptcache_dis = '';//缓存脚本信息 文件分发特列
	var _filecache = '';//缓存配置文件信息
	var _usercache = '' ;//缓存用户信息
	var _page = 1; //默认当前页为第一页
	var _perpage = 10; //默认每页显示10个
	
	var initpage = function(){
		_page = 1; //默认当前页为第一页
	};
	//选择是计划任务还是立即执行任务
	$(".taskplan").bind('change',function(){
		var checkvalue = $(".taskplan:checked").attr('value');
		if(checkvalue == '0'){
			$('#plan_block').hide();
		}else if(checkvalue == '1'){
			$('#plan_block').show();
		}
		
	});
	//执行周期变化
	$('.runcycle').bind('change',function(){
		var checkvalue = $('.runcycle:checked').attr('value');
		$('.plantime').hide();
		if(checkvalue == 'Monthly'){
			 $("#Monthly").find("select").html('').append(monthstr);
		}
		$("#"+checkvalue).show();
	});
	
	/************************************** 关于ip弹出层搜索 相关处理************************************************/
	//按关键字查询
	$('#search_ip').click(function(){
		var keyword = $.trim($('#keyword').val());
		var url = 'index.php?c=maintain&a=searchIp';
		$.post(url,{'keyword':keyword},function(msg){
			var _newipcache = msg;
			_ipcache ='';
			_ipcache = dealipcache(_ipcache,_newipcache);
			showpage(_ipcache,_page,_perpage);
			synchip();
		});	
	});
	
	//按类型查看ip
	$('.servertype').click(function(){
		var typeid = $(this).attr('type');
		var url = 'index.php?c=maintain&a=searchIp';
		$.post(url,{'typeid':typeid},function(msg){
			var _newipcache = msg;
			_ipcache ='';
			_ipcache = dealipcache(_ipcache,_newipcache);
			showpage(_ipcache,_page,_perpage);
			synchip();
		});
	});
	//搜索去重复
	var dealipcache = function(ipcache ,_ipnewcache){
		if(ipcache ==''){
			return _ipnewcache;
		}
		var iparr = ipcache.split('|');
		var ipnewarr = _ipnewcache.split('|');
		for(i in iparr){//不能使用replace
			for(n in ipnewarr){
				if(iparr[i] == ipnewarr[n]){				
					delete ipnewarr[n];
					break ;
				}
			}
		}
		for(n in ipnewarr){
			ipcache += '|'+ipnewarr[n];
		}
		ipcache = ipcache.replace('||','|');
		return ipcache.replace(/(^\|*)|(\|*$)/g, "");
	};
	
	//显示|删除 去重复
	function dealipshow(ipvalue ,value){
		if(ipvalue == ''){
			return value ;
		}
		var iparr = ipvalue.split('|');
		for(i in iparr){
			if(value == iparr[i]){
				delete iparr[i];
			}		
		}
		var newipvalue ='';
		for(n in iparr){
			newipvalue +=iparr[n]+'|';
		}
		newipvalue = newipvalue.replace('||','|');
		return newipvalue.replace(/(^\|*)|(\|*$)/g, "");
	}
	
	//ip 同步
	var synchip = function(){
		var ipstr = $("#serverip").val();
		if(ipstr != ''){
			var iparr = ipstr.split('|');
			$(".checkip").each(function(){
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
	//分页显示
	var showpage = function(_ipcache ,page ,perpage){
		var _iparr = _ipcache.split('|');
		var pagenum = Math.ceil(_iparr.length/perpage );//获取总页数
		if(pagenum >1){
			var content='' ;
			var offset = (page-1)*perpage;
			var lastset = perpage*page>_iparr.length?_iparr.length:perpage*page;
			for(var i=offset;i<lastset;i++){
				content += "<span class='style8'><input type='checkbox' class='style11 checkip'  value='"+_iparr[i]+"'/>"+_iparr[i]+"</span>";
			}
			$("#result_ip").html('').html(content);
			$("#ip_page").show();
		}else{
			var content='' ;
			for(i in _iparr){
				content += "<span class='style8'><input type='checkbox' class='style11 checkip'  value='"+_iparr[i]+"'/>"+_iparr[i]+"</span>";
			}
			$("#result_ip").html('').html(content);
			$("#ip_page").hide();
		}
	};
	
	//上一页,下一页
	$("#lastpage").click(function(){
		_page = _page-1;
		if(_page<1){
			_page = 1;
		}
		showpage(_ipcache ,_page ,_perpage);
		synchip();
	});
	$("#nextpage").click(function(){
		_page = _page + 1;
		var _iparr = _ipcache.split('|');
		var pagenum = Math.ceil(_iparr.length/_perpage );
		if(_page > pagenum){
			_page = pagenum ;
		}
		showpage(_ipcache ,_page ,_perpage);
		synchip();
	});
	//处理全选,全不选
	$("#ipall").click(function(){
		$(".checkip").attr('checked','checked');
	});
	$("#ipcancel").click(function(){
		$(".checkip").attr('checked',false);
	});
	
	//按查看所有ip
	$('.serverall').click(function(){
		//var groupid = $(this).attr('type');
		var url = 'index.php?c=maintain&a=searchIp';
		$.post(url,function(msg){
			var _newipcache = msg;
			_ipcache ='';
			_ipcache = dealipcache(_ipcache,_newipcache);
			showpage(_ipcache,_page,_perpage);
			synchip();
		});	
	});
	
	//删除已经选中的ip 
	$(".delselectip").die().live('click',function(){
		//alert($(this).parent().html());
		var value = $(this).parent().find(".ip_tips").html();
		var ipvalue = $("#serverip").val();
		ipvalue = dealipshow(ipvalue,value);
		$("#serverip").attr('value',ipvalue);
		$(this).parent().remove();
		$(".checkip:checked").each(function(){
			if(value == $(this).attr('value')){
				$(this).attr('checked',false);
			}
		});
	});
	
	//确认选中的ip
	$("#selectipconfirm").click(function(){
		var value = '';
		var iparr = new Array();
		var oldvalue = $("#serverip").val();
		$(".checkip:checked").each(function(){
			value += $(this).val()+'|';
		});
		value = value.replace(/(^\|*)|(\|*$)/g, "");
		value = dealipcache(oldvalue,value);//去重复
		var iphtml = '';
		iparr = value.split('|');
		for(i in iparr){
			iphtml += '<span class="left mr10"><label class="ip_tips">'+iparr[i]+'</label><img src="images/erase.png" class="delselectip ml5 pointer"/></span>';
		}
		$("#showselectip").html('').append(iphtml);
		$("#serverip").attr('value',value);
		//$(".delselectip").bind('click');
		$("#shadow ,#searchip").hide();
	});
	
	/*********************************************配置文件 弹出层 相关处理*********************************/
	
	//按文件类型查看配置文件
	$(".filetype").click(function(){
		var fileid = $(this).attr('type');
		var url = 'index.php?c=maintain&a=searchConfigFile';
		$.post(url,{'fileid':fileid},function(msg){
			_filecache = eval(msg) ;
			showpage_config(_filecache ,_page,_perpage);
			synch_config();
		});
	});
	
	//按关键字查询配置文件
	$("#search_file").click(function(){
		var keyword = $.trim($('#keyword_file').val());
		var url = 'index.php?c=maintain&a=searchConfigFile';
		$.post(url,{'keyword':keyword},function(msg){
			_filecache = eval(msg) ;
			showpage_config(_filecache ,_page,_perpage);
			synch_config();
		});	
	});
	
	//确认选中的配置文件
	$("#selectfileconfirm").click(function(){
		var value = $(".filename:checked").val();
		//alert(value);return ;
		if(value == undefined || value==''){

		}else{
			$("#sourcefile").attr('value',value);
		}
		$("#shadow ,#searchfiletype").hide();
	});	
	
	$("#config_last").click(function(){
		_page = _page-1;
		if(_page<1){
			_page = 1;
		}
		showpage_config(_filecache ,_page ,_perpage);
		synch_config();
	});
	$("#config_next").click(function(){
		_page = _page + 1;
		//var _iparr = _ipcache.split('|');
		var pagenum = Math.ceil(_filecache.length/_perpage );
		if(_page > pagenum){
			_page = pagenum ;
		}
		showpage_config(_filecache ,_page ,_perpage);
		synch_config();
	});
	
	//分页显示文件
	var showpage_config = function(_filecache ,page ,perpage){
		//var _iparr = _ipcache.split('|');
		var pagenum = Math.ceil(_filecache.length/perpage );//获取总页数
		if(pagenum >1){
			var content='' ;
			var offset = (page-1)*perpage;
			var lastset = perpage*page>_filecache.length?_filecache.length:perpage*page;
			for(var i=offset;i<lastset;i++){
				content += "<span class='style8'><input type='radio' class='style11 filename'  name='filename' value='"+_filecache[i].filepath+"'/>"+_filecache[i].filename+"</span>";
			}
			$("#result_file").html('').html(content);
			$("#config_page").show();
		}else{
			var content='' ;
			for(i in _filecache){
				content += "<span class='style8'><input type='radio' class='style11 filename'  name='filename' value='"+_filecache[i].filepath+"'/>"+_filecache[i].filename+"</span>";			
			}
			$("#result_file").html('').html(content);
			$("#config_page").hide();
		}
	};
	
	//config 同步
	var synch_config = function(){
		var configpath = $("#sourcefile").val();
		if(configpath != ''){
			$(".filename").each(function(){
				var value = $(this).attr('value');
				if(value == configpath){
					$(this).attr('checked','checked');
				}
			});
		}
	};
	
	/*****************************************执行脚本 弹出层 相关处理*****************************************/
	
	//按关键字查询执行脚本
	$("#search_script").click(function(){
		var keyword = $.trim($('#keyword_script').val());
		var url = 'index.php?c=maintain&a=searchScript';
		$.post(url,{'keyword':keyword},function(msg){
			//$("#result_script").html('').html(msg);
			_scriptcache = eval(msg) ;
			showpage_script(_scriptcache ,_page,_perpage);
			synch_script();
		});	
	});
	
	
	//确认选中的脚本
	$("#scriptconfirm").click(function(){
		var value = $(".scriptname:checked").val();
		//alert(value);return ;
		if(value == undefined || value==''){

		}else{
			$("#scriptfile").attr('value',value);
		}
		$("#shadow ,#searchscript").hide();
	});	
	
	$("#script_last").click(function(){
		_page = _page-1;
		if(_page<1){
			_page = 1;
		}
		showpage_script(_scriptcache ,_page ,_perpage);
		synch_script();
	});
	$("#script_next").click(function(){
		_page = _page + 1;
		//var _iparr = _ipcache.split('|');
		var pagenum = Math.ceil(_scriptcache.length/_perpage );
		if(_page > pagenum){
			_page = pagenum ;
		}
		showpage_script(_scriptcache ,_page ,_perpage);
		synch_script();
	});
	
	//分页显示脚本
	var showpage_script = function(_scriptcache ,page ,perpage){
		//var _iparr = _ipcache.split('|');
		var pagenum = Math.ceil(_scriptcache.length/perpage );//获取总页数
		if(pagenum >1){
			var content='' ;
			var offset = (page-1)*perpage;
			var lastset = perpage*page>_scriptcache.length?_scriptcache.length:perpage*page;
			for(var i=offset;i<lastset;i++){
				content += "<span class='style8'><input type='radio' class='style11 scriptname'  name='scriptname' value='"+_scriptcache[i].scriptpath+"'/>"+_scriptcache[i].scriptname+"</span>";
			}
			$("#result_script").html('').html(content);
			$("#script_page").show();
		}else{
			var content='' ;
			for(i in _scriptcache){
				content += "<span class='style8'><input type='radio' class='style11 scriptname'  name='scriptname' value='"+_scriptcache[i].scriptpath+"'/>"+_scriptcache[i].scriptname+"</span>";			
			}
			$("#result_script").html('').html(content);
			$("#script_page").hide();
		}
	};
	
	//script 同步
	var synch_script = function(){
		var scriptpath = $("#scriptfile").val();
		if(scriptpath != ''){
			$(".scriptname").each(function(){
				var value = $(this).attr('value');
				if(value == scriptpath){
					$(this).attr('checked','checked');
				}
			});
		}
	};
	/****************************************文件分发 特列 源文件选择脚本************************************/
	//按关键字查询执行脚本
	$("#search_script_dis").click(function(){
		var keyword = $.trim($('#keyword_script_dis').val());
		var url = 'index.php?c=maintain&a=searchScript';
		$.post(url,{'keyword':keyword},function(msg){
			//$("#result_script").html('').html(msg);
			_scriptcache_dis = eval(msg) ;
			showpage_script_dis(_scriptcache_dis ,_page,_perpage);
			synch_script_dis();
		});	
	});
	
	
	//确认选中的脚本
	$("#scriptconfirm_dis").click(function(){
		var value = $(".scriptname_dis:checked").val();
		//alert(value);return ;
		if(value == undefined || value==''){

		}else{
			$(".sourcefile").attr('value',value);
		}
		$("#shadow ,#searchscript_dis").hide();
	});	
	
	//script 同步
	var synch_script_dis = function(){
		var scriptpath = $(".sourcefile").val();
		if(scriptpath != ''){
			$(".scriptname_dis").each(function(){
				var value = $(this).attr('value');
				if(value == scriptpath){
					$(this).attr('checked','checked');
				}
			});
		}
	};
	
	//分页显示脚本
	var showpage_script_dis = function(_scriptcache_dis ,page ,perpage){
		//var _iparr = _ipcache.split('|');
		var pagenum = Math.ceil(_scriptcache_dis.length/perpage );//获取总页数
		if(pagenum >1){
			var content='' ;
			var offset = (page-1)*perpage;
			var lastset = perpage*page>_scriptcache_dis.length?_scriptcache_dis.length:perpage*page;
			for(var i=offset;i<lastset;i++){
				content += "<span class='style8'><input type='radio' class='style11 scriptname_dis'  name='scriptname' value='"+_scriptcache_dis[i].scriptpath+"'/>"+_scriptcache_dis[i].scriptname+"</span>";
			}
			$("#result_script_dis").html('').html(content);
			$("#script_page_dis").show();
		}else{
			var content='' ;
			for(i in _scriptcache_dis){
				content += "<span class='style8'><input type='radio' class='style11 scriptname_dis'  name='scriptname' value='"+_scriptcache_dis[i].scriptpath+"'/>"+_scriptcache_dis[i].scriptname+"</span>";			
			}
			$("#result_script_dis").html('').html(content);
			$("#script_page_dis").hide();
		}
	};
	$("#script_last_dis").click(function(){
		_page = _page-1;
		if(_page<1){
			_page = 1;
		}
		showpage_script_dis(_scriptcache_dis ,_page ,_perpage);
		synch_script_dis();
	});
	$("#script_next_dis").click(function(){
		_page = _page + 1;
		//var _iparr = _ipcache.split('|');
		var pagenum = Math.ceil(_scriptcache_dis.length/_perpage );
		if(_page > pagenum){
			_page = pagenum ;
		}
		showpage_script_dis(_scriptcache_dis ,_page ,_perpage);
		synch_script_dis();
	});
	/*******************************************选择用户 弹出层 相关处理**************************************/
	//查询选择用户
	$("#search_user").click(function(){
		var keyword = $.trim($("#keyword_user").val());
		var url = "index.php?c=panel&a=searchUser";
		$.post(url,{'keyword':keyword},function(msg){
			_usercache = msg.split('|') ;
			showpage_user(_usercache ,_page,_perpage);
			synch_user();
		});
	});
	
	//确定选择的用户
	$("#userconfirm").click(function(){
		var value = '';
		var oldvalue = $("#users").val();
		$(".username:checked").each(function(){
			value += $(this).val()+',';
		});
		value = value.replace(/(^\,*)|(\,*$)/g, "");
	
		value = dealusers(oldvalue,value);//去重复
		var userhtml = '';
		userarr = value.split(',');
		for(i in userarr){
			userhtml += '<span class="left mr10"><label class="user_tips">'+userarr[i]+'</label><img src="images/erase.png" class="delselectuser ml5 pointer"/></span>';
		}
		$("#showselectuser").html('').append(userhtml);
		$("#users").attr('value',value);
		$("#shadow ,#searchusers").hide();
	});
	
	var dealusers = function(oldvalue,value){
		if(oldvalue ==''){
			return value;
		}
		var userarr = oldvalue.split(',');
		var usernewarr = value.split(',');
		for(i in userarr){//不能使用replace
			for(n in usernewarr){
				if(userarr[i] == usernewarr[n]){				
					delete usernewarr[n];
					break ;
				}
			}
		}
		for(n in usernewarr){
			oldvalue += ','+usernewarr[n];
		}
		oldvalue = oldvalue.replace(',,',',');
		return oldvalue.replace(/(^\,*)|(\,*$)/g, "");
	};
	
	//显示|删除 去重复
	function dealusershow(uservalue ,value){
		if(uservalue == ''){
			return value ;
		}
		var userarr = uservalue.split(',');
		for(i in userarr){
			if(value == userarr[i]){
				delete userarr[i];
			}		
		}
		var newuservalue ='';
		for(n in userarr){
			newuservalue +=userarr[n]+',';
		}
		newuservalue = newuservalue.replace(',,',',');
		return newuservalue.replace(/(^\,*)|(\,*$)/g, "");
	}
	
	//删除已经选中的ip 
	$(".delselectuser").die().live('click',function(){
		//alert($(this).parent().html());
		var value = $(this).parent().find(".user_tips").html();
		var uservalue = $("#users").val();
		uservalue = dealusershow(uservalue,value);
		$("#users").attr('value',uservalue);
		$(this).parent().remove();
		$(".username:checked").each(function(){
			if(value == $(this).attr('value')){
				$(this).attr('checked',false);
			}
		});
	});
	
	$("#user_last").click(function(){
		_page = _page-1;
		if(_page<1){
			_page = 1;
		}
		showpage_user(_usercache ,_page ,_perpage);
		synch_user();
	});
	$("#user_next").click(function(){
		_page = _page + 1;
		//var _iparr = _ipcache.split('|');
		var pagenum = Math.ceil(_usercache.length/_perpage );
		if(_page > pagenum){
			_page = pagenum ;
		}
		showpage_user(_usercache ,_page ,_perpage);
		synch_user();
	});
	
	//分页显示用户
	var showpage_user = function(_usercache ,page ,perpage){
		//var _iparr = _ipcache.split('|');
		var pagenum = Math.ceil(_usercache.length/perpage );//获取总页数
		if(pagenum >1){
			var content='' ;
			var offset = (page-1)*perpage;
			var lastset = perpage*page>_usercache.length?_usercache.length:perpage*page;
			for(var i=offset;i<lastset;i++){
				content += "<span class='style8'><input type='checkbox' class='style11 username'  name='username' value='"+_usercache[i]+"'/>"+_usercache[i]+"</span>";
			}
			$("#result_user").html('').html(content);
			$("#user_page").show();
		}else{
			var content='' ;
			for(i in _usercache){
				content += "<span class='style8'><input type='checkbox' class='style11 username'  name='username' value='"+_usercache[i]+"'/>"+_usercache[i]+"</span>";			
			}
			$("#result_user").html('').html(content);
			$("#user_page").hide();
		}
	};
	
	//user 同步
	var synch_user = function(){
		var userlist = $("#users").val();
		if(userlist != ''){
			var userarr = userlist.split(',');
			$(".username").each(function(){
				var value = $(this).attr('value');
				for(i in userarr){
					if(value == userarr[i]){
						$(this).attr('checked','checked');
						break ;
					}
				}
			});
		}
	};
	//处理全选,全不选
	$("#userall").click(function(){
		$(".username").attr('checked','checked');
	});
	$("#usercancel").click(function(){
		$(".username").attr('checked',false);
	});
	/************************************************其他********************************************/
	//获取计划任务时间通用函数
	function getPlanTime(){
		var plantime = new Array();
		var runcycle = $('.runcycle:checked').attr('value');
		var rundate = runtime = '';
		if(runcycle == 'Every-day'){
			runtime = $.trim($("#Every-day").find(":text").val());
			if(runtime == ''){
				return false ;
			}
		}else if(runcycle == 'Weekly'){
			$(".weekly-check:checked").each(function(){
				rundate += $(this).attr('value')+'|';
			});
			rundate = rundate.replace(/(^\|*)|(\|*$)/g, "");
			runtime = $.trim($("#Weekly").find(":text").val());
			if(rundate == '' || runtime ==''){
				return false ;
			}
		}else if(runcycle == 'Monthly'){
			rundate = $("#Monthly").find("select option:selected").attr('value');
			runtime = $.trim($("#Monthly").find(":text").val());
			if(rundate == undefined || runtime ==''){
				return false ;
			}
		}else{
			runtime = $("#One-time").find(":text").val();
			if(runtime == ''){
				return false ;
			}
		}
		plantime.push(runcycle);
		plantime.push(rundate);
		plantime.push(runtime);
		return plantime;
	}
	
	$("#oncedate").datetimepicker({
		showSecond: true,
		dateFormat: 'yy-mm-dd',
		timeFormat: 'hh:mm:ss',
		stepHour: 1//设置步长
	});
