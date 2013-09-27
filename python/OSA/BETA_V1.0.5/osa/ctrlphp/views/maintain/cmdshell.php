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
						  <span class="font1">-控制中心</span>
					  </div>
					  <div class="back">
					      <a href="#" onclick="javascript:history.go(-1);"><b>返回上页</b></a>
					  </div>
				  </div>
				  <div class="clear"></div>
				  <!--two-->
				 
					  <div class="table_setup2">
						  <table cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<td style="background:#E5F5FD; height:30px; padding:0 5px;">
								    <span class="left" id="shellpath">shell当前路径:<?php echo $shellpath;?></span>
								</td>
							</tr>
							<?php if(empty($msg)){?> 
			
							<tr>
								<td style="text-align:left" width="98%">
								<textarea name="returninfo" id="returninfo" rows="25" readonly="readonly" style="font-size:15px; border:none; margin:0px;padding:0px; width:98%;">
   									<?php echo $returninfo;?>
								</textarea>
								  <p style="font-size:15px; border:none; margin:0px;width:98%; text-align:left;">
								  [root@ows_<?php echo $ip;?>]#					
								  <input name="cmd" id="cmd" type="text" style=" font-size:15px; border:none; margin:0px" size="78" value="" >
								  </p>
								</td>
							</tr> 
						
							<?php } else{?>
								<tr>
									<td>
										<?php echo $msg;?>
									</td>
								</tr>
							
							<?php }?>
						  </table>
				     </div>
				  
				  
				  <!--two-->
			  </div>
			  <!--右边结束-->
			  <script language="javascript">
			  	var ajaxid = '<?php echo $id;?>';
				document.getElementById('returninfo').scrollTop=document.getElementById('returninfo').scrollHeight;	    
				document.getElementById('cmd').focus();	
				
				</script> 
				<script type="text/javascript" src="script/maintain/shellajax.js"></script>
				
		  </div><!--content结束-->
		  <?php include 'views/footer.php';?>
		  