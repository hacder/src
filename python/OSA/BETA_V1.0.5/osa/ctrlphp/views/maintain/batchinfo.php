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
						  <span class="font1">-添加新配置文件</span>
					  </div>
				  </div>
				  <!--one-->
				 <div class="rightcon_title">任务详情</div>
				  <div class="rightcon_mid">
				      <p class="pheight1"><label class="label5">批量操作类型：</label><?php osa_showbatch($baseinfo[0]['oCmdType']);?></p>
					  <p class="pheight1">
						  <label class="label5">创建时间：</label>
						  <?php echo $exinfo[0]['oCreateTime'];?>
					  </p>		  
					  <p class="pheight1">
						  <label class="label5">执行状态：</label>
						  <?php echo $baseinfo[0]['oStatus'];?>
					  </p>
					  <?php if($type == 'taskplan'){?>
					  <p class="pheight1">
						  <label class="label5">执行周期：</label>
						  <?php showTimes($baseinfo[0]['oRunCycle'],$baseinfo[0]['oRunDate'],$baseinfo[0]['oRunTime']);?>
					  </p>
					  <p class="pheight1">
						  <label class="label5">下次执行时间：</label>
						  <?php echo $baseinfo[0]['oRunNextTime'];?>
					  </p>
					  <p class="pheight1">
						  <label class="label5">最近执行时间：</label>
						  <?php echo $baseinfo[0]['oRunLastTime'];?>
					  </p>
					  <?php }?>
					  
					 <?php osa_reverse_command($baseinfo[0]['oCmdType'] ,$exinfo[0]['oCombinCmd']);?>

				  </div>
				  <div class="rightcon_bottom"></div>
				  <div class="rightcon_title">任务结果</div>
				  <div class="rightcon_mid">
				  <?php if(!empty($result)){
					 foreach ($result as $key){?>
					  <div style="width:740px;height:auto;border:1px solid rgb(232,232,232);margin-bottom:2px;">
						  <p class="pheight1">
							  <label class="label5">IP：</label>
							  <?php echo $key['oClientip'];?>
						  </p>
						  <?php echo osa_show_bresult($key['oResult'],1);?>
					  </div>		  
					<?php } }else{?>
						<p>暂时没有结果</p>
				  <?php }?>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--one-->
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
		<!--创建新类型结束-->
<!--		  <script type="text/javascript" src="script/common/comlayer.js"></script>-->
		  <?php include 'views/footer.php';?>
		  