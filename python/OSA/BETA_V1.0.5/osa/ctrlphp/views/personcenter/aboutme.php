<?php include 'views/header.php';?>
		  <!--content开始-->
		  <div class="content">
		      <!--左边开始-->
			  <?php include 'views/personcenter/left.php';?>
			  <!--左边结束-->
			  <!--右边开始-->
               <form method="post" action="index.php?c=personcenter&a=aboutme">
			  <div class="main_right">
			      <div class="linkmlist">
				      <div class="home_icom">
					      <span>当前位置：</span>
						  <span><a href="#">个人中心</a></span>
						  <span class="font1">-个人资料</span>
					  </div>
				  </div>
                 
				  <div class="clear"></div>
				  <div class="news_con">
				      <p class="pheight"><label class="label5">用户名：</label><span><?php echo $_SESSION['username'];?></span></p>
				      <p class="pheight"><label class="label5">角色：</label><span>管理员</span></p>
					  <p class="pheight">
					      <label class="label5"><em class="em">*</em>姓名：</label><input type="text" name="username" class="style5" value="<?php echo $personinfo['oRealName'];?>"/>
					  </p>
					  <!--<p class="pheight">
					      <label class="label5">性别：</label>
					      <span class="style8"><input class="style11"  type="radio" checked="checked"/>男</span>
					      <span class="style8"><input type="radio" class="style11" />女</span>
					  </p>-->
					 <!-- <p class="pheight"><label class="label5">部门职位：</label><input type="text" class="style5" /></p>
					  <p class="pheight"><label class="label5">电话：</label><input type="text" class="style5" /></p>-->
					  <p><label class="label5"><em class="em">*</em>手机号码：</label><input type="text" class="style5" name="tel" value="<?php echo $personinfo['oPhone']?>"/></p>
					  <p class="pheight light">用于接收报警短信，方便同事之间进行联系。</p>
					  <!--<p class="pheight"><label class="label5">传真：</label><input type="text" class="style5" /></p>-->
					  <p><label class="label5"><em class="em">*</em>邮箱：</label><input type="text" class="style5" name="email" value="<?php echo $personinfo['oEmail']; ?>"/></p>
					  <p class="pheight light">用于接收报警的重要途径。</p>
				  </div>
				  <p class="light"><input type="submit" class="enter" value="确定保存" /></p>
			  </div>
              </form>
			  <!--右边结束-->
		  </div><!--content结束-->
		   <script type="text/javascript" src="script/common/comlist.js"></script>
<?php include 'views/footer.php';?>
