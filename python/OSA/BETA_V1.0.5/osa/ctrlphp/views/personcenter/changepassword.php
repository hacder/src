	<?php include 'views/header.php';?>
		  <!--content开始-->
		  <link href="css/ui-lightness/jquery-ui-1.8.20.custom.css" rel="stylesheet" type="text/css"/>
		  <script src="script/jquery-ui-1.8.20.custom.min.js"></script>
		  <div class="content">
		      <!--左边开始-->
			  	<?php include 'views/personcenter/left.php';?>
			  <!--左边结束-->
			  <!--右边开始-->
                <form method="post" action="index.php?c=personcenter&a=changepassword">
			  <div class="main_right">
			      <div class="linkmlist">
				      <div class="home_icom">
					      <span>当前位置：</span>
						  <span><a href="#">个人中心</a></span>
						  <span class="font1">-修改密码</span>
					  </div>
				  </div>
				  <div class="clear"></div>
                
				  <div class="news_con">
				      <p class="pheight">
					      <label class="label5" ><em class="em">*</em>旧密码：</label>
						  <input type="text" class="style5" name="oldpwd"/>
					  </p>
				      <p class="pheight">
					      <label class="label5"><em class="em">*</em>新密码：</label>
						  <input type="text" class="style5" name="newpwd"/>
					  </p>
				      <p class="pheight">
					      <label class="label5"><em class="em">*</em>确认密码：</label>
						  <input type="text" class="style5" name="re_newpwd"/>
					  </p>
					  <div class="pheight light" style="_margin-left:47px;"><input type="submit" class="enter" value="确定保存" /></div>
				  </div>
			  </div>
              </form>
			  <!--右边结束-->
		  </div><!--content结束-->
		  <script type="text/javascript" src="script/common/comlist.js"></script>
<?php include 'views/footer.php';?>
