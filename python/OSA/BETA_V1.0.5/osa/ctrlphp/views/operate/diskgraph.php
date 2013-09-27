	<?php include 'views/header.php';?>
		  <!--content开始-->
		  <div class="content">
		      <!--左边开始-->
			  <?php include 'views/operate/left.php';?>
			  <!--左边结束-->
			  <!--右边开始-->
			  <div class="main_right">
			      <div class="linkmlist">
				      <div class="home_icom">
					      <span>当前位置：</span>
						  <span><a href="#">运营分析</a></span>
						  <span class="font1">-图形详细分析</span>
					  </div>
				  </div>
				  <div class="statistics">
				      <div class="time_left"><label class="label5">根据IP查看：</label><select class="select1"><option>请选择</option></select></div>
					  <div class="time_right">
					      <span><a href="#">今日</a></span>   
					      <span><a href="#">昨日</a></span>   
					      <span><a href="#" class="optfor">最近7天</a></span>   
					      <span><a href="#">最近15天</a></span>   
					      <span><a href="#">自定义搜索</a></span>   
					  </div>
				  </div>
				  <div class="clear"></div>
				  <div class="LogMinerimg"></div>
				  <div class="LogMinerimg"></div>
				  <div class="LogMinerimg"></div>
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
<?php include 'views/footer.php';?>
