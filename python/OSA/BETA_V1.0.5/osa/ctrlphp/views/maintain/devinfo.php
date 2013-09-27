	<?php include 'views/header.php';?>
		  <!--content开始-->
		  <div class="content">
		      <!--左边开始-->
			   <?php include 'views/maintain/left.php';?>
			  <!--左边结束-->
			  <!--右边开始-->
			  <div class="main_right">
			      <div class="linkmlist">
				      <div class="home_icom">
					      <span>当前位置：</span>
						  <span><a href="#">日常运维</a></span>
						  <span class="font1">-即时信息</span>
					  </div>
					  <div class="back">
					      <a href="" onclick="javascript:history.go(0);"><b>刷新状态</b></a>
						  <a href="index.php?c=maintain&a=serverlist" ><b>返回上页</b></a>
					  </div>
				  </div>
				  <div class="clear"></div>
				  <!--one-->
				  <div class="rightcon_title">基本信息</div>
				  <div class="rightcon_mid">
					  <div class="table_setup2">
						  <table cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<th>状态</th>
								<th>IP地址</th>
								<th>所在地区</th>
								<th>服务器类型</th>
							</tr> 
							<tr>
								<td><?php echo $mon_detail[0]['oStatus'];?></td>
								<td><?php echo $mon_detail[0]['oIp'];?></td>
								<td><?php echo $mon_detail[0]['oPlace'];?></td>
								<td><?php echo $mon_detail[0]['oTypeName'];?></td>
							</tr> 
						  </table>
				     </div>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--one-->
				 <?php if(!empty($msg)) {?>
				  	<div class="rightcon_mid" ><?php echo $msg;?></div>
				 <?php } else{?>
				  <!--two-->
				  <div class="rightcon_title">详细信息</div>
				  <div class="rightcon_mid">
					  <div class="table_setup2">
						  <table cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<th width="20%">CPU信息：</th>
								<th>型号：<?php echo $detail_list['cpuinfo']['cpu_type'];?>主频：<?php echo $detail_list['cpuinfo']['cpu_mhz'];?>物理个数：<?php echo $detail_list['cpuinfo']['cpu_number'];?></th>
							</tr> 
							<tr>
							    <td>内存状态：</td>
								<td>
								    <table cellpadding="0" cellspacing="0" width="100%">
									    <tr>
										    <td width="20%">物理内存信息</td>
											<td width="80%">总大小:<?php echo $detail_list['meminfo']['mem_total'];?>,剩余大小:<?php echo $detail_list['meminfo']['mem_free'];?>,Buffers:<?php echo $detail_list['meminfo']['mem_buffer'];?>,Cached:<?php echo $detail_list['meminfo']['mem_cache'];?></td>
										</tr>
										<tr>
										    <td>SWAP内存信息</td>
											<td>总大小:<?php echo $detail_list['meminfo']['mem_swap_total'];?>,剩余大小:<?php echo $detail_list['meminfo']['mem_swap_free'];?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr class="hui">
							    <td>硬盘状态：</td>
								<td>
								    <div class="table_setup4">
								    <table cellpadding="0" cellspacing="0" width="100%">
									    <tr>
										    <td width="25%">Filestem</td>
										    <td width="15%">use</td>
										    <td width="15%">use</td>
										    <td width="15%">use</td>
										    <td width="15%">use</td>
										    <td width="15%">use</td>
										</tr>
									    <tr>
										    <td>Filestem</td>
										    <td>Size</td>
										    <td>Used</td>
										    <td>Avail</td>
										    <td>Use%</td>
										    <td>Mounted</td>
										</tr>
									   <?php foreach($detail_list['disklist'] as $d_key=>$d_value){ ?>
											<tr>
											<?php foreach ($d_value as $dd_value){ ?>
												<td><?php echo $dd_value;?></td>
											<?php }?>
											</tr>
										<?php }	?>									   									 
									</table>
									</div>
								</td>
							</tr>
							<tr>
							    <td>网站状态：</td>
								<td>
								    <div class="table_setup4">
								    <table cellpadding="0" cellspacing="0" width="100%">
									    <tr>
										    <td width="10%">device</td>
										    <td width="45%">in</td>
										    <td width="45%">out</td>
										</tr>
										<?php foreach($detail_list['netlist'] as $n_key=>$n_value){ ?>
											
											<tr>
											<?php foreach($n_value as $nn_key=>$nn_value){
											
												if(is_numeric($nn_value)){ ?>
													<td><?php echo $nn_value;?> bytes(<?php echo round(($nn_value/1024/1024/1024),2);?> GiB)</td>
												<?php }else{ ?>
													<td><?php echo $nn_value;?> </td>
											<?php 	}       
											 } ?>
											</tr>
									<?php } ?>
									</table>
									</div>
								</td>
							</tr>
							<tr class="hui">
							    <td>正在运行的服务器：</td>
								<td>
								    <div class="table_setup4">
								    <table cellpadding="0" cellspacing="0" width="100%">
								    	<tr>
								    	<?php $ii=0;							
										foreach($detail_list['seviceinfo'] as $sev_key=>$sev_value){
											if( ($ii%6)==0 ){ ?>
												</tr><tr>
											<?php }?>
											<td><?php echo $sev_key;?></td>
										<?php 	$ii++;
										}?>
										</tr>
									</table>
									</div>
								</td>
							</tr>
							<tr>
							    <td>负载状态：</td>
								<td><?php echo "1min: ".$detail_list['topinfo']['1min']." , 5min:".$detail_list['topinfo']['5min']." ,15min:".$detail_list['topinfo']['15min'];?></td>
							</tr>
							<tr class="hui">
							    <td>运行时间：</td>
								<td><?php echo $detail_list['uptimeinfo']['onlinetime']?></td>
							</tr>
							<tr>
							    <td>登录人数：</td>
								<td><?php echo $detail_list['logininfo']['onlineuse'];?></td>
							</tr>
						  </table>
				     </div>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--two-->
				  <!--three-->
				  <div class="rightcon_title">进程信息</div>
				  <div class="rightcon_mid">
					  <div class="table_setup3">
						  <table cellspacing="0" cellpadding="0" width="100%">
						    <tr>
								<th colspan="4">占用CPU最多的十个进程</th>
							</tr>
							<tr>
								<th >No.</th>
								<th >PID</th>
								<th >%cpu</th>
								<th >command</th>
							</tr> 
							<?php foreach($detail_list['cpulist'] as $cpu_key=>$cpu_value){ ?>
								
								<tr><td><?php echo $cpu_key;?></td>									
								<?php foreach($cpu_value as $ccpu_value){?>								
									<td><?php echo $ccpu_value;?></td>									
								<?php }?>		
								</tr>
							<?php }?> 
						  </table>
				     </div>
					  <div class="table_setup3" style="margin-right:0;">
						  <table cellspacing="0" cellpadding="0" width="100%">
						    <tr>
								<th colspan="4">占用内存最多的十个进程</th>
							</tr>
							<tr>
								<th >No.</th>
								<th >PID</th>
								<th >%mem</th>
								<th >command</th>
							</tr> 
							<?php foreach($detail_list['memlist'] as $mem_key=>$mem_value){ ?>
								
								<tr><td><?php echo $mem_key;?></td>									
								<?php foreach($mem_value as $mmem_value){?>								
									<td><?php echo $mmem_value;?></td>									
								<?php }?>		
								</tr>
							<?php }?> 							
						  </table>
				     </div>
				  </div>
				  <?php }?>
				  <div class="rightcon_bottom"></div>
				  <!--three-->
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
<?php include 'views/footer.php';?>
