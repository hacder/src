	<?php include 'views/header.php';?>
		  <!--content开始-->
		  <div class="content">
		      <!--左边开始-->
			  <?php include 'views/account/left.php';?>
			  <!--右边开始-->
			  <div class="main_right">
			      <div class="linkmlist">
				      <div class="home_icom">
					      <span>当前位置：</span>
						  <span><a href="#">账户管理</a></span>
						  <span class="font1">-添加新用户</span>
					  </div>
				  </div>
				  <!--one-->
				  <div class="rightcon_title">基本信息</div>
				  <div class="rightcon_mid">
				      <p class="pheight1"><label class="label5"><em class="em">*</em>用户名：</label><input type="text" class="style5" id="username"/><span class="tips"></span></p>
					  <p class="pheight light">用户名用于登录系统和社区，请谨慎填写！</p>
				      <p class="pheight">
					      <label class="label5"><em class="em">*</em>密码：</label><input type="password" class="style5" id="passwd"/>
						  <span class="notice"></span>
					  </p>
				      <p class="pheight">
					      <label class="label5"><em class="em">*</em>确认密码：</label><input type="password" class="style5" id="confirmpasswd" />
					      <span class="tips"></span>
					  </p>
				      <p class="pheight">
				      	  <label class="label5"><em class="em">*</em>角色：</label>
				      	  <select id="role_select" class="style5">
				      	  	  <option value="">请选择角色</option>
				      	  	  <?php foreach ($roles as $key){?>
				      	  	  <option value="<?php echo $key['id'];?>"><?php echo $key['oRoleName'];?></option>
				      	  	  <?php }?>
				      	  </select>
				      	  <span class="link"><a href="index.php?c=account&a=roleadd">添加新角色</a></span>
				      </p>
				      <p class="pheight1">
					      <label class="label5"><em class="em">*</em>邮箱：</label>
					      <input type="text" class="style5" id="email"/>
					      <input type="hidden" class="style5" id="hidemail" value="" />
					      <span class="tips"></span>
				      </p>
					  <p class="pheight light">用于接收报警的重要途径。</p>
				      <p class="pheight1">
					      <label class="label5"><em class="em">*</em>手机号码：</label>
					      <input type="text" class="style5" id="iphone"/>
					      <span class="tips"></span>
				      </p>
					  <p class="pheight light">用于接收报警短信，方便同事之间进行联系。</p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--one-->
				  <!--two-->
				  <div class="rightcon_title">工作时间</div>
				  <div class="rightcon_mid">
					  <p class="pheight">
					      <label class="label5">每周工作日：</label>
						  <span class="style8"><input type="checkbox" class="style11 week" value="Mon" />星期一</span>
						  <span class="style8"><input type="checkbox" class="style11 week" value="Tue" />星期二</span>
						  <span class="style8"><input type="checkbox" class="style11 week" value="Wed" />星期三</span>
						  <span class="style8"><input type="checkbox" class="style11 week" value="Thu" />星期四</span>
						  <span class="style8"><input type="checkbox" class="style11 week" value="Fri" />星期五</span>
						  <span class="style8"><input type="checkbox" class="style11 week" value="Sat" />星期六</span>
						  <span class="style8"><input type="checkbox" class="style11 week" value="Sun" />星期日</span>
					  </p>
					  <p class="pheight">
					      <label class="label5">每天工作时间：</label>
					      <input type="text" class="style7" onclick="_SetTime(this)" readonly="true" id="stime"/>-<input type="text" id="etime" readonly="true" onclick="_SetTime(this)" style="width:150px;height:23px;border:1px solid #9BABBA;"/>
					  </p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--two-->
				  <!--three-->
				  <div class="rightcon_title">社区</div>
				  <div class="rightcon_mid">
					  <p class="pheight">
					      <label class="label5">社区昵称：</label><input type="text" class="style7" id="nickname"/>
					  </p>
					  <p>
					      <label class="label5">签名：</label><textarea class="textarea1" id="signature"></textarea>
					  </p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <p><input type="button" class="enter specibut" value="确认添加" id="user_add" /><input type="button" class="enter specibut" value="取消返回" onclick="window.location='index.php?c=account&a=userlist';" /></p>
				  <!--three-->
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
		  <script type="text/javascript" src="script/account/useradd.js"></script>
		  <script type="text/javascript" src="script/common/setTime.js"></script>
<?php include 'views/footer.php';?>
