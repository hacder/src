<?php

/**
 * 根据$status 来显示图片
 * @param unknown_type $status
 */
function osa_show_graph($status){
	
	if($status == '正常'){
		echo '<img src="images/2.gif" />';
	}else if($status == '失去响应'){
		echo '<img src="images/1.gif" />';
	}else if($status =='服务器异常'){
		echo '<img src="images/3.gif" />';
	}else{
		echo '<img src="images/4.gif" />';
	}
}


/**
 * 根据报警信息级别来显示图片
 */
function osa_alarm_show_graph($level){
	
	if($level == 1){
		echo '<img class="left" src="images/msg_fault.gif" title="故障消息"><img class="left" src="images/led_nostate.png" title="连接失败">';
	}else if($level == 2){
		echo '<img class="left" src="images/msg_sys.gif" title="系统消息"><img class="left" src="images/led_nostate.png" title="snmp无法获取数据">';
	}else if($level == 3){
		echo '<img class="left" src="images/msg_notice.gif" title="提示消息"><img class="left" src="images/led_nostate.png" title="项目指标异常">';
	}else{
		echo '<img class="left" src="images/msg_notice.gif" title="提示消息"><img class="left" src="images/led_green.png" title="恢复正常">';
	}
}


/**
 * 监控项目列表 监控频率显示
 */
function osa_monitor_timeset($timerate){

	switch($timerate){
	
		case 30:
				echo '30秒';
				break;
		case 60:
				echo '1分钟';
				break;
		case 120:
				echo '2分钟';
				break;
		case 180:
				echo '3分钟';
				break;
		case 300:
				echo '5分钟';
				break;
		case 600:
				echo '10分钟';
				break;
		case 900:
				echo '15分钟';
				break;
		case 1800:
				echo '30分钟';
				break;
		case 3600:
				echo '1小时';
				break;
		case 86400:
				echo '1天';
				break;
	}
}



