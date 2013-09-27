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
				  <div class="rightcon_title"><?php echo $ctitle;?></div>
				  <div class="rightcon_mid">
					  <div class="table_setup2">
						  <table cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<td style="background:#E5F5FD; height:30px; padding:0 5px;">
								    <span class="left">配置详情:</span>
								</td>
							</tr>
							<?php if(empty($msg)){?> 
<!--							<form name="cmdform" method="post" action="index.php?c=maintain&a=saveconfig&id=<?php echo $id;?>">-->
							<tr>
								<td style="text-align:center" >
								<textarea class="texta2" name="ctext" id="ctext" rows="20" style="border:none; margin:0px;width:98%;"><?php echo $ctext;?>
								</textarea>
								</td>
							</tr> 
							<tr>
								<td>
									<input type="hidden" value="<?php echo $fname;?>" name="cfilename" id="cfilename"/> 
									<input type="button" value="保存" class="button3" id="savefile"/>
									<input type="button" value="重置" class="button3" onClick="javascript:history.go(0);"/>
								</td>
							</tr>
<!--							</form>-->
							<?php } else{?>
								<tr>
									<td>
										<?php echo $msg;?>
									</td>
								</tr>
					
							<?php }?>
						  </table>
				     </div>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--two-->
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
		  <script type="text/javascript">
		  	$(document).ready(function(){
				$("#savefile").click(function(){
					var fname = $("#cfilename").val();
					var ctext = $.trim($('#ctext').val());
					var url = "index.php?c=maintain&a=saveconfig&id=<?php echo $id;?>";
					$.post(url,{'cfilename':fname,'ctext':ctext},function(msg){
						alert(msg);
					});
				});
			});
		  </script>
		  <?php include 'views/footer.php';?>
		  