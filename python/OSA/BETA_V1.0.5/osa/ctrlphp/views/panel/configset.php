	<?php include 'views/header.php';?>
		  <!--content开始-->
		  <div class="content">
		      <!--左边开始-->
			  <?php include 'views/panel/left.php';?>
			  <!--左边结束-->
			  <!--右边开始-->
			  <div class="main_right">
			      <div class="linkmlist">
				      <div class="home_icom">
					      <span>当前位置：</span>
						  <span><a href="#">监控配置</a></span>
						  <span class="font1">-配置设置</span>
					  </div>
				  </div>
				  <p class="pheight" style="margin-top:10px;"><input type="radio" class="style11" />只开放部分配置，其余使用OSA推荐的配置。</p>
				  <p class="pheight"><input type="radio" class="style11" />开放全部配置，根据自己的需求定制配置文件，推荐有一定基础的朋友使用。</p>
				  <div class="time_pro">
				      <p><img src="images/icon2.gif" />注：新手强烈推荐使用OSA的默认配置选项。</p>
				  </div>
				  <p class="pheight"><input type="button" class="enter" value="确定保存" /></p>
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
<?php include 'views/footer.php';?>
