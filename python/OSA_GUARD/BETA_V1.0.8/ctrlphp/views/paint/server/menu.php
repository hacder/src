<div class="menu2">
	<p class="height10"></p>
	<p>
		<a class="menu2_title"><span>图形分析</span></a>
		
		<a href="index.php?c=paint&a=serverable&ipid=<?php echo $ipid;?>" class="menu2_title_sub curr_sub"><span>可用率统计</span></a>
		<a href="index.php?c=paint&a=serresponse&ipid=<?php echo $ipid;?>" class="menu2_title_sub"><span>响应时间</span></a>
		<a href="index.php?c=paint&a=sermemory&ipid=<?php echo $ipid;?>" class="menu2_title_sub"><span>使用内存</span></a>
		<a href="index.php?c=paint&a=serlogins&ipid=<?php echo $ipid;?>" class="menu2_title_sub"><span>登录用户数</span></a>		
		<a href="index.php?c=paint&a=serprocess&ipid=<?php echo $ipid;?>" class="menu2_title_sub"><span>进程数量</span></a>
		<a href="index.php?c=paint&a=serdiskstat&ipid=<?php echo $ipid;?>" class="menu2_title_sub"><span>硬盘状态</span></a>
		<a href="index.php?c=paint&a=sernetwork&ipid=<?php echo $ipid;?>" class="menu2_title_sub"><span>网络流量</span></a>
		<a href="index.php?c=paint&a=serusedcpu&ipid=<?php echo $ipid;?>" class="menu2_title_sub"><span>cpu使用率</span></a>
    	<?php if($ostype['oOsType'] === 'Linux'){?>
		<a href="index.php?c=paint&a=serloadstat&ipid=<?php echo $ipid;?>" class="menu2_title_sub"><span>负载状况</span></a>
		<a href="index.php?c=paint&a=serconstat&ipid=<?php echo $ipid;?>" class="menu2_title_sub"><span>连接状况</span></a>			
		<a href="index.php?c=paint&a=serdiskio&ipid=<?php echo $ipid;?>" class="menu2_title_sub"><span>磁盘I/O</span></a>
		<?php }?>
	</p>
	<p class="height10"></p>
</div>