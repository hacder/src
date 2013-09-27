<div class="rightcon_title">
	<span>服务器信息</span>
</div>
<div class="rightcon_mid"  style="overflow-y:hidden;">
	<p style="padding:5px 10px;">
		<span class="left" style="font-weight:bold;">服务器IP：</span><span class="left"><?php echo $ip['oIp'];?></span>
		<select onChange="location.replace(this.value)">
		<option value="#" selected>快速切换服务器
		<?php
		foreach($iplist as $key => $id_ip){
		
			echo "<option value=\"index.php?c=".$_GET['c']."&a=".$_GET['a']."&ipid=".$id_ip['id']."\">".$id_ip['oIp'];
		
		}
		
		?>		
		</select>
		
	</p>
	<p style="padding:5px 10px;">
		<span class="left" style="font-weight:bold;">服务器名称：</span><span class="left"><?php echo $ip['oDevName'];?></span>
	</p>
	<p style="padding:5px 10px;">
		<span class="left" style="font-weight:bold;">业务描述：</span><span class="left"><?php echo empty($ip['oWorkDes'])?"无":$ip['oWorkDes'];?></span>
	</p>
</div>
<div class="rightcon_bottom"></div>