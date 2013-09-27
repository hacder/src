$(document).ready(function(){
	
	
	$("#more-options").click(function(){
		$("#more-class").toggle();
	});
	
	//获取系统恢复值
	function getRemind(){
		if($(".remind").is(":checked")){
			return 1;
		}
		return 0;
	}
	
	//获取通知对象的值
	function getNotiObject(){
		var type = $(".notitype:checked").attr('value');
		if(type == '0'){
			return 'ALL';
		}else if(type == '1'){
			var users = $.trim($("#users").val());//可能为空值
			return users;
		}
	}
	
	
	
	//通知类型
	$(".notitype").bind('change',function(){
		var type = $(".notitype:checked").attr('value');
		if(type == '0'){
			$("#seleuser").hide();
		}else if(type == '1'){
			$("#seleuser").show();
		}
	});
	
	var maxOptions = 2;
	//添加新条目
	$("#add-options").click(function(){
		
		var cloneHtml = $("#more-class").find(".col_4_con:first").clone(true);
		$("#more-class").append(cloneHtml);
	
	});
	
	//删除一个条目
	$(".del-options").live("click",function(){
		if($("#more-class").find(".col_4_con").length == 1){
			tipsAlert("自定义报警指标不能少于1");
		}else{
			$(this).parents(".col_4_con").remove();
		}
	});
	
	var custom_name = "loadstat";
		
	var getCustomConfig = function(){	
	
		var itemConfig = {};
		//itemConfig.name = custom_name;
		//itemConfig.indicators = {};
		$(".col_4_con:visible").each(function(){
			if($(this).find('.custom_norm').val() == "最近1分钟平均负载"){
				itemConfig.one={};
				itemConfig.one.condition = "大于";
				itemConfig.one.value = $.trim($(this).find(".threshold").val());
			}else if($(this).find('.custom_norm').val() == "最近5分钟平均负载"){
				itemConfig.five={};
				itemConfig.five.condition = "大于";
				itemConfig.five.value = $.trim($(this).find(".threshold").val());
			}else if($(this).find('.custom_norm').val() == "最近15分钟平均负载"){
				itemConfig.fifteen={};
				itemConfig.fifteen.condition = "大于";
				itemConfig.fifteen.value = $.trim($(this).find(".threshold").val());
			}else if($(this).find('.custom_norm').val() == "网卡流入速率"){
				itemConfig.inbond={};
				itemConfig.inbond.condition = "大于";
				itemConfig.inbond.value = $.trim($(this).find(".threshold").val());
			}else if($(this).find('.custom_norm').val() == "网卡流出速率"){
				itemConfig.outbond={};
				itemConfig.outbond.condition = "大于";
				itemConfig.outbond.value = $.trim($(this).find(".threshold").val());
			}else if($(this).find('.custom_norm').val() == "内存使用率"){
				itemConfig.real={};
				itemConfig.real.condition = "大于";
				itemConfig.real.value = $.trim($(this).find(".threshold").val());
			}else if($(this).find('.custom_norm').val() == "SWAP内存使用率"){
				itemConfig.swap={};
				itemConfig.swap.condition = "大于";
				itemConfig.swap.value = $.trim($(this).find(".threshold").val());
			}else if($(this).find('.custom_norm').val() == "磁盘空间使用率"){
				itemConfig.used={};
				itemConfig.used.condition = "大于";
				itemConfig.used.value = $.trim($(this).find(".threshold").val());
			}else if($(this).find('.custom_norm').val() == "当前CPU使用率"){
				itemConfig.use={};
				itemConfig.use.condition = "大于";
				itemConfig.use.value = $.trim($(this).find(".threshold").val());
			}else if($(this).find('.custom_norm').val() == "当前登录用户数"){
				itemConfig.logins={};
				itemConfig.logins.condition = "大于";
				itemConfig.logins.value = $.trim($(this).find(".threshold").val());
			}else if($(this).find('.custom_norm').val() == "磁盘写入速率"){
				itemConfig.write={};
				itemConfig.write.condition = "大于";
				itemConfig.write.value = $.trim($(this).find(".threshold").val());
			}else if($(this).find('.custom_norm').val() == "磁盘读取速率"){
				itemConfig.read={};
				itemConfig.read.condition = "大于";
				itemConfig.read.value = $.trim($(this).find(".threshold").val());
			}
		});
		return itemConfig;	
	};
	
	
	$(".custom-li").click(function(){
		var name = custom_name = $(this).attr('name');
		$(".custom-li").parent().removeClass("btn_green1");
		$(".custom-li").parent().addClass("btn_gray1");
		$(".custom-li").removeClass("selected");
		$(this).parent().removeClass("btn_gray1");
		$(this).parent().addClass("btn_green1");
		$(this).addClass("selected");
		$("#more-class").find(".col_4_con").remove();
		$("#more-class").append($("."+name+"-con").clone(true).show());
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
	
	
	$("#user-select").click(function(){
		var userStr = $.trim($("#users").val());
		boxShowUser(userStr);	
	});
	
	$(".user_close").live("click",function(){	
		var value = $(this).parent().find(".li_users").html();
		var oldvalue = $("#users").val();
		newvalue = boxShowDel(oldvalue,value);
		$("#users").attr('value',newvalue);
		$(this).parent().remove();
	});
	
	/****************************** apache 验证 *************************************/
	
	$.ajaxSetup({
		  async: false
	}); 
	//proname urlname  prokey
	var flag = true;
	//项目名称验证
	$("#itemname").click(function(){
		$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
	});
	$("#itemname").blur(function(){		
		var itemname = $.trim($(this).val());
		if(itemname == ''){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入监控项目名称');
			flag = false;
			//return;
		}else if(itemname!=itemname.match(/^[a-zA-Z0-9\u4e00-\u9fa5][a-zA-Z0-9\u4e00-\u9fa5\.\_\@]+$/)){
			$(this).parent().find(".tips").css({'color':'rgb(263,24,13)'}).html('请输入正确格式的项目名称');
			flag = false;
		}else{
			$(this).parent().find(".tips").css({'color':'rgb(23,124,226)'}).html('');
		}
		
	});

	
	$("#custom-save").click(function(){
		
		flag = true ;
		$("#itemname").blur();
		$("#itemurl").blur();
		if(flag == false){
			tipsAlert('基本信息有错误');
			return false;
		}
		var itemip = $.trim($("#itemip").val());
		if(itemip == ''){
			tipsAlert('域名或IP不能为空');
			return false;		
		}
		
		var notiusers = getNotiObject();
		if(notiusers == ''){
			tipsAlert('指定用户不能为空');
			return false;
		}
		var checkrate = $(".checkrate:checked").attr('value');
		var alarmnum = $(".alarmnum:checked").attr('value');
		var repeatnum = $(".repeatnum:checked").attr('value');
		var remind = getRemind();
		
		var itemname = $.trim($("#itemname").val());
		var itemconfig = getCustomConfig();		
		var url = "index.php?c=monitor&a=custom_monitor";
		$.post(url,{
			'itemname':itemname,
			'custom_name':custom_name,
			'itemip':itemip,
			'itemconfig':itemconfig,
			'notiusers':notiusers,
			'checkrate':checkrate,
			'alarmnum':alarmnum,
			'repeatnum':repeatnum,
			'remind':remind
		},function(msg){
			if(msg.indexOf('success')!=-1){
				var callback = function(result){
					if(result == true){					
						window.location = "index.php?c=monitor&a=monitorlist";
					}
				};
				tipsAlert('监控项目创建成功,点击确定进入监控项目列表',callback);
			}else{
				tipsAlert('监控项目创建失败');
			}
		});
	});
	
	
	$("#custom-edit").click(function(){
		
		flag = true ;
		$("#itemname").blur();
		$("#itemurl").blur();
		if(flag == false){
			tipsAlert('基本信息有错误');
			return false;
		}
		var itemip = $.trim($("#itemip").val());
		if(itemip == ''){
			tipsAlert('域名或IP不能为空');
			return false;		
		}
		
		var notiusers = getNotiObject();
		if(notiusers == ''){
			tipsAlert('指定用户不能为空');
			return false;
		}
		var checkrate = $(".checkrate:checked").attr('value');
		var alarmnum = $(".alarmnum:checked").attr('value');
		var repeatnum = $(".repeatnum:checked").attr('value');
		var remind = getRemind();
		
		var itemname = $.trim($("#itemname").val());
		var itemconfig = getCustomConfig();
		var itemid = $.trim($("#edit-itemid").val());
		var url = "index.php?c=monitor&a=monitor_custom_edit";
		$.post(url,{
			'itemid':itemid,
			'itemname':itemname,
			'custom_name':custom_name,
			'itemip':itemip,
			'itemconfig':itemconfig,
			'notiusers':notiusers,
			'checkrate':checkrate,
			'alarmnum':alarmnum,
			'repeatnum':repeatnum,
			'remind':remind
		},function(msg){
			if(msg.indexOf('success')!=-1){
				var callback = function(result){
					if(result == true){					
						window.location = "index.php?c=monitor&a=monitorlist";
					}
				};
				tipsAlert('监控项目编辑成功,点击确定进入监控项目列表',callback);
			}else{
				tipsAlert('监控项目编辑失败');
			}
		});
	});
});