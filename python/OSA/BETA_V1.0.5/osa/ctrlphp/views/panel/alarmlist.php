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
						  <span class="font1">-创建监控项目</span>
					  </div>
				  </div>
				  <div class="prompt">
					  <p><img src="images/icon2.gif">您可以在这里创建各类监控项目，请选择以下监控类型。</p>
				  </div>
				  <div class="rightcon_title">监控类型</div>
				  <div class="rightcon_mid">
					   <ul>
						<li class="li_title"><a href="index.php?c=panel&a=webalarm" >网页存活报警</a></li>
						<li class="li_content">网页存活监控可以很方便的帮助您监控网页状态，可以通过关键字检测，指定你认为正确的HTTP状态码，巧妙的设定监控页面，进一步可以检测到WEB服务器是服务是否运行正常。<a href="http://bbs.osapub.com/forum.php" target="_blank">更多参考配置，请上社区看看别人怎么配置的？</a>
						</li>
						<li class="li_title"><a href="index.php?c=panel&a=diskalarm" >磁盘空间报警</a></li>
						<li class="li_content">通过磁盘空间检查，让您从此不再担心由于磁盘空间满，而出现的一系列服务器问题，可以设定一个合理的阀值，对服务器的磁盘空间使用率进行监控，到了预警值马上告警通知，真正做到提前预防磁盘空间使用100%的问题。
						</li>
						<li class="li_title"><a href="index.php?c=panel&a=loadalarm" >负载状态报警</a></li>
						<li class="li_content">还在为新上线的服务器突然负载增高而担忧吗?只需要简单的设定一个报警阀值，负载增设马上通知到您手机邮箱。
						</li>
						<li class="li_title"><a href="index.php?c=panel&a=portalarm" >端口存活报警</a></li>
						<li class="li_content">通过端口存活报警，让您一目了然的监控到各种服务的运行状态，以及是否开放相关不应该开放的端口，帮助您轻松打造安全的服务器管理。
						</li>
						<li class="li_title"><a href="index.php?c=panel&a=databasealarm" >数据库服务监控</a></li>
						<li class="li_content">通过对数据库的监控，可以轻松的掌握到连接数，线程数是否超标，数据库是否存在异常等状况。</li>
						<li class="li_title"><a href="index.php?c=panel&a=usersalarm" >登录用户数量报警</a></li>
						<li class="li_content">想不用登录服务器就对登录用户了如指掌吗？设定一定的阀值，一旦有新用户登录马上会通知到您手机邮箱，服务器安全尽在掌握之中。
						</li>
						<li class="li_title"><a href="index.php?c=panel&a=networktraffic" >网络流量峰值报警</a></li>
						<li class="li_content">想了解流量的突发情况吗？当你新上线一个业务，突然流量增长十几倍，是不是应该有个自动监控的机制，来帮助分析原因呢？
						</li>
					  </ul>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <div class="clear"></div>			  
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
<?php include 'views/footer.php';?>
