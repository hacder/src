<div class="menu2">
	<p class="height10"></p>
	<p>
		<a class="menu2_title"><span>项目监控</span></a>
		<a href="index.php?c=alarm&a=itemalarmed" class="menu2_title_sub curr_sub"><span>已发送告警信息<?php if($itemalarmd_num > 0){?><img src="images/new.gif" alt="新的未
读告警消息！"/><?php }?></span></a>
		<a href="index.php?c=alarm&a=itemalarm" class="menu2_title_sub"><span>未发送告警信息<?php if($itemalarm_num > 0){?><img src="images/new.gif" alt="新的未
读告警消息！"/><?php }?></span></a>
	</p>
	<p class="height10"></p>
	<p>
		<a class="menu2_title"><span>服务器监控</span></a>
		<a href="index.php?c=alarm&a=serveralarmed" class="menu2_title_sub curr_sub"><span>已发送告警信息<?php if($serveralarmd_num > 0){?><img src="images/new.gif" alt="新的未
读告警消息！"/><?php }?></span></a>
		<a href="index.php?c=alarm&a=serveralarm" class="menu2_title_sub"><span>未发送告警信息<?php if($serveralarm_num > 0){?><img src="images/new.gif" alt="新的未
读告警消息！"/><?php }?></span></a>
	</p>
	<p class="height10"></p>
	<p>
		<a class="menu2_title"><span>告警设定</span></a>
		<a href="index.php?c=alarm&a=notiset" class="menu2_title_sub curr_sub"><span>告警通知设定</span></a>
	</p>
	<p class="height10"></p>
</div>