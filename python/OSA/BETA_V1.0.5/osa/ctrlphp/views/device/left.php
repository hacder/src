
<div class="main_left">
	<div class="left_title">
		<h2>设备管理</h2>
	</div>
	<div class="left_con">
		<ul>
			<li class="active">设备管理</li>
			<li><a href="index.php?c=device&a=index" class="devlist">设备信息列表</a></li>
			<li><a href="index.php?c=device&a=devgrouplist" class="devgrouplist">分组信息列表</a></li>
			<li class="active">按托管地区查看</li>
		    <?php
			$ii = 1;
		    foreach ($region as $key) { ?>
		    <li><a href="index.php?c=device&a=index&region=<?php echo $key['oPlace'];?>&rid=<?php echo $ii;?>" class="rlist<?php echo $ii;?>"><?php echo trim($key['oPlace'],'||');?></a></li>
		    <?php   $ii++;
		     }?><!--
      		<li class="active">按分组查看</li>
		    <?php foreach ($group as $key) { ?>
		    <li><a href="index.php?c=device&a=index&group=<?php echo $key['id'];?>" class="glist<?php echo $key['id'];?>"><?php echo $key['oGroupName'];?></a></li>
		    <?php }?>
			-->
			<li class="active">按类型查看</li>
	  	    <?php foreach ($type as $key) { ?>
	        <li><a href="index.php?c=device&a=index&type=<?php echo $key['id'];?>" class="tlist<?php echo $key['id'];?>"><?php echo $key['oTypeName'];?></a></li>
	        <?php }?>
		</ul>
	</div>
	<div class="left_bottom"></div>
</div>