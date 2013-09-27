
    var str = "";  
    document.writeln("<div id=\"_contents\" style=\"padding:4px; background-color:rgb(246,175,58); font-size: 12px; border: 1px solid #777777;  position:absolute; left:0px; top:0px; width:200px; height:34px; z-index:1; display:none;\">");  
    str += "\u65f6<select name=\"_hour\" class=\"_hour\" id=\"hour\">";  
    for (h = 0; h <= 9; h++) {  
        str += "<option value=\"0" + h + "\">0" + h + "</option>";  
    }  
    for (h = 10; h <= 23; h++) {  
        str += "<option value=\"" + h + "\">" + h + "</option>";  
    }  
    str += "</select> \u5206<select name=\"_minute\" class='_minute'>";  
    for (m = 0; m <= 9; m++) {  
        str += "<option value=\"0" + m + "\">0" + m + "</option>";  
    }  
    for (m = 10; m <= 59; m++) {  
        str += "<option value=\"" + m + "\">" + m + "</option>";  
    }  
    str += "</select> \u79d2<select name=\"_second\" class='_second'>";  
    for (s = 0; s <= 9; s++) {  
        str += "<option value=\"0" + s + "\">0" + s + "</option>";  
    }  
    for (s = 10; s <= 59; s++) {  
        str += "<option value=\"" + s + "\">" + s + "</option>";  
    }  
    str += "</select> <input name=\"queding\" type=\"button\" onclick=\"_select(this)\" value=\"\u786e\u5b9a\" style=\"font-size:12px;background-color:rgb(246,175,58);\" /></div>";  
    document.writeln(str);  
    var _fieldname;  
    function _SetTime(tt) {  
        _fieldname = tt;  
        var ttop = $(tt).offset().top;    //TT控件的定位点高  
        var tleft = $(tt).offset().left;    //TT控件的定位点宽       
        $("#_contents").css({top:ttop+25,left:tleft}); 
        $("#_contents").show();  
    }  
    function _select(object) {  
    	var hour = $(object).parent().find("._hour option:selected").attr('value');
    	var minute = $(object).parent().find("._minute option:selected").attr('value');
    	var second = $(object).parent().find("._second option:selected").attr('value');
        _fieldname.value = hour + ":" + minute + ":" + second;  
        $("#_contents").hide();  
    }  
    
    var monthstr = '';
    for(i = 1;i<=31; i++){
    	if(i<10){
    		monthstr += "<option value=\"0" + i + "\">0" + i + "</option>";
    	}else{
    		monthstr += "<option value=\"" + i + "\">" + i + "</option>";
    	}
    }
   