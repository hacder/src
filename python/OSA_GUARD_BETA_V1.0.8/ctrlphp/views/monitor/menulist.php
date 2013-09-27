<div class="menu2">
	<p class="height10"></p>
	<p>
		<a class="menu2_title"><span>站点监控</span></a>
		<a href="index.php?c=monitor&a=monitorlist&type=http" class="menu2_title_sub curr_sub"><span>HTTP/HTTPS(<?php echo isset($numarr['http'])?$numarr['http']:0;?>)</span></a>
		<a href="index.php?c=monitor&a=monitorlist&type=ping" class="menu2_title_sub"><span>PING(<?php echo isset($numarr['ping'])?$numarr['ping']:0;?>)</span></a>
		<a href="index.php?c=monitor&a=monitorlist&type=tcp" class="menu2_title_sub"><span>TCP(<?php echo isset($numarr['tcp'])?$numarr['tcp']:0;?>)</span></a>
		<a href="index.php?c=monitor&a=monitorlist&type=udp" class="menu2_title_sub"><span>UDP(<?php echo isset($numarr['udp'])?$numarr['udp']:0;?>)</span></a>
		<a href="index.php?c=monitor&a=monitorlist&type=dns" class="menu2_title_sub"><span>DNS(<?php echo isset($numarr['dns'])?$numarr['dns']:0;?>)</span></a>
		<a href="index.php?c=monitor&a=monitorlist&type=ftp" class="menu2_title_sub"><span>FTP(<?php echo isset($numarr['ftp'])?$numarr['ftp']:0;?>)</span></a>
	</p>
	<p class="height10"></p>
	<p>
		<a class="menu2_title"><span>服务性能监控</span></a>
		<a href="index.php?c=monitor&a=monitorlist&type=apache" class="menu2_title_sub curr_sub"><span>Apache(<?php echo isset($numarr['apache'])?$numarr['apache']:0;?>)</span></a>
		<a href="index.php?c=monitor&a=monitorlist&type=nginx" class="menu2_title_sub"><span>Nginx(<?php echo isset($numarr['nginx'])?$numarr['nginx']:0;?>)</span></a>
		<a href="index.php?c=monitor&a=monitorlist&type=lighttpd" class="menu2_title_sub"><span>Lighttpd(<?php echo isset($numarr['lighttpd'])?$numarr['lighttpd']:0;?>)</span></a>
		<a href="index.php?c=monitor&a=monitorlist&type=mysql" class="menu2_title_sub"><span>Mysql(<?php echo isset($numarr['mysql'])?$numarr['mysql']:0;?>)</span></a>
		<a href="index.php?c=monitor&a=monitorlist&type=mongodb" class="menu2_title_sub"><span>MongoDB(<?php echo isset($numarr['mongodb'])?$numarr['mongodb']:0;?>)</span></a>	
		<a href="index.php?c=monitor&a=monitorlist&type=redis" class="menu2_title_sub"><span>Redis(<?php echo isset($numarr['redis'])?$numarr['redis']:0;?>)</span></a>
		<a href="index.php?c=monitor&a=monitorlist&type=memcache" class="menu2_title_sub"><span>Memcache(<?php echo isset($numarr['memcache'])?$numarr['memcache']:0;?>)</span></a>			
	</p>
	<p class="height10"></p>
	<p>
		<a class="menu2_title"><span>服务器性能监控</span></a>
		<a href="index.php?c=monitor&a=monitorlist&type=custom" class="menu2_title_sub curr_sub"><span>自定义服务器指标(<?php echo isset($numarr['custom'])?$numarr['custom']:0;?>)</span></a>		
	</p>
	<p class="height10"></p>
</div>