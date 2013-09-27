
$(document).ready(function(){
	$("#username,#password").focus(function(){
		$("#msg_show").css({'color':'rgb(23,124,226)'}).html('*欢迎来到OSA管理后台！');
	});
	//提交
	$("#login_submit").click(function(){
		var remember = isRemember();
		var name = $.trim($("#username").val());
		if(name == ''){
			$("#msg_show").css({'color':'rgb(263,24,13)'}).html('用户名不能为空! ');
			return false;
		}
		var password = $.trim($("#password").val());
		if(password == ''){
			$("#msg_show").css({'color':'rgb(263,24,13)'}).html('密码不能为空 !');
			return false;
		}
		var url = "index.php?c=login&a=checklogin";
		$.post(url,{'username':name,'password':password,'remember':remember},function(msg){
			if(msg.indexOf('success')!=-1){
				window.location='index.php?c=home&a=index';
			}else if(msg.indexOf('login_error')!=-1){
				$("#msg_show").css({'color':'rgb(263,24,13)'}).html('用户名或密码错误、或者用户被禁用，登录失败');
			}else if(msg.indexOf('ip_bloack')!=-1){
				$("#msg_show").css({'color':'rgb(263,24,13)'}).html('IP被锁定，登录失败');
			}else{
				$("#msg_show").css({'color':'rgb(263,24,13)'}).html('登录失败已次数超过'+msg+'，登录失败');
			}
		});
	});
	
	//是否记住用户名
	function isRemember(){
		if($("#remember").is(":checked")){
			return $("#remember:checked").attr('value');
		}
		return 0;			
	}
	//绑定enter
	$(document).bind('keydown', function (e) {
		var key = e.which;
        if (key == 13) {
        	$('#login_submit').click();
        }
    });
	
	//设为首页处理
	$("#sethome").click(function(){
		var sURL = window.location.href ;
		if(!!(document.all && navigator.userAgent.indexOf('Opera') === -1)){
			document.body.style.behavior = 'url(#default#homepage)';
			document.body.setHomePage(sURL);
		} else {
			alert("非 IE 浏览器请手动将本站设为首页");
		}
	});
	//收藏处理
	$("#collect").click(function(){
		var title = $("title").html();
		var href = window.location.href ;
		AddFavorite(title,href);
	});
	//收藏
	function AddFavorite(sTitle,sURL)
	{
	    try
	    {
	        window.external.addFavorite(sURL, sTitle);
	    }
	    catch (e)
	    {
	        try
	        {
	            window.sidebar.addPanel(sTitle, sURL, "");
	        }
	        catch (e)
	        {
	            //return ture;
	        	alert("请按 Ctrl+D 键添加到收藏夹");
	        }
	    }
	}
	
	
	var _cache ={};
	function checkInputVal(){
		var account = $("#username"), pwd = $("#password");
        _cache.inputs = [account, pwd];
		if(_cache.inputs){
            for(var i = 0, len = _cache.inputs.length; i < len; i++){
                var input = _cache.inputs[i];
                var label = input.prev();
                if(input.val() != ""){   
                    label.css("display") != "none" && label.hide();
                }
                else{
                    label.css("display") == "none" && label.show();
                }
            }
        }
	}
	window.setInterval(checkInputVal, 100);
	
	//焦点处理
	var _focusTimer;
	$("#nameprev,#username").mouseover(function(){
		if(_focusTimer) window.clearTimeout(_focusTimer);
        _focusTimer = window.setTimeout(function(){
            $("#username").focus();
        }, 200);
	}).mouseout(function(){
		if(_focusTimer) window.clearTimeout(_focusTimer);
	});
	$("#pwdprev,#password").mouseover(function(){
		if(_focusTimer) window.clearTimeout(_focusTimer);
        _focusTimer = window.setTimeout(function(){
            $("#password").focus();
        }, 200);
	}).mouseout(function(){
		if(_focusTimer) window.clearTimeout(_focusTimer);
	});
});