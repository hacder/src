$(document).ready(function(){
	function cmdpost(cmd){
		var url = 'index.php?c=maintain&a=cmdshellajax&id='+ajaxid;
		$.post(url,{'cmd':cmd},function(msg){
			$('#cmd').attr('value','');
			$('#shellpath').html('shell当前路径:'+msg.shellpath);
			$('#returninfo').html(msg.returninfo);
			var height = document.getElementById('returninfo').scrollHeight;
			$('#returninfo').scrollTop(height);
		},'json');
	}
	$("#cmd").bind('keydown', function (e) {
		var key = e.which;
		var cmdvalue = $('#cmd').val();
        if (key == 13 ) {
        	cmdpost(cmdvalue);
        }
    });
	
});
