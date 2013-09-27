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
						  <span class="font1">-编辑用户</span>
					  </div>
				  </div>
				  <!--one-->
				  <div class="rightcon_title">基本信息</div>
				  <div class="rightcon_mid">
				      <p class="pheight1"><label class="label5"><em class="em">*</em>用户名：</label><input type="text" readonly="true" class="style5 hui" id="username" value="<?php echo $user[0]['oUserName'];?>"/><span class="tips"></span></p>
					  <p class="pheight light">用户名用于登录系统和社区，请谨慎填写！</p>
				      <p class="pheight">
				      	  <label class="label5"><em class="em">*</em>角色：</label>
				      	  <select id="role_select" class="style5">
				      	  	  <option value="">请选择角色</option>
				      	  	  <?php foreach ($roles as $key){?>
				      	  	  <option value="<?php echo $key['id'];?>" <?php echo $key['id']==$user[0]['oRoleid']?"selected='selected'":"";?> ><?php echo $key['oRoleName'];?></option>
				      	  	  <?php }?>
				      	  </select>
				      	  <span class="link"><a href="index.php?c=account&a=roleadd">添加新角色</a></span>
				      </p>
				      <p class="pheight1">
					      <label class="label5"><em class="em">*</em>邮箱：</label>
					      <input type="text" class="style5" id="email" value="<?php echo $user[0]['oEmail'];?>" />
					      <input type="hidden" class="style5" id="hidemail" value="<?php echo $user[0]['oEmail'];?>" />
					      <span class="tips"></span>
				      </p>
					  <p class="pheight light">用于接收报警的重要途径。</p>
				      <p class="pheight1">
					      <label class="label5"><em class="em">*</em>手机号码：</label>
					      <input type="text" class="style5" id="iphone" value="<?php echo $user[0]['oPhone'];?>"/>
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
						  <span class="style8"><input type="checkbox" class="style11 week" value="Mon" <?php echo strpos($user[0]['oDutyDate'],'Mon')!==false?"checked='checked'":"";?>/>星期一</span>
						  <span class="style8"><input type="checkbox" class="style11 week" value="Tue" <?php echo strpos($user[0]['oDutyDate'],'Tue')!==false?"checked='checked'":"";?>/>星期二</span>
						  <span class="style8"><input type="checkbox" class="style11 week" value="Wed" <?php echo strpos($user[0]['oDutyDate'],'Wed')!==false?"checked='checked'":"";?>/>星期三</span>
						  <span class="style8"><input type="checkbox" class="style11 week" value="Thu" <?php echo strpos($user[0]['oDutyDate'],'Thu')!==false?"checked='checked'":"";?>/>星期四</span>
						  <span class="style8"><input type="checkbox" class="style11 week" value="Fri" <?php echo strpos($user[0]['oDutyDate'],'Fri')!==false?"checked='checked'":"";?>/>星期五</span>
						  <span class="style8"><input type="checkbox" class="style11 week" value="Sat" <?php echo strpos($user[0]['oDutyDate'],'Sat')!==false?"checked='checked'":"";?>/>星期六</span>
						  <span class="style8"><input type="checkbox" class="style11 week" value="Sun" <?php echo strpos($user[0]['oDutyDate'],'Sun')!==false?"checked='checked'":"";?>/>星期日</span>
					  </p>
					  <p class="pheight">
					      <label class="label5">每天工作时间：</label>
					      <input type="text" class="style7" onclick="_SetTime(this)" readonly="true" id="stime" value="<?php echo substr($user[0]['oDutyTime'],0,stripos($user[0]['oDutyTime'],'-'));?>"/>-<input type="text" value="<?php echo substr($user[0]['oDutyTime'],stripos($user[0]['oDutyTime'],'-')+1);?>" id="etime" readonly="true" onclick="_SetTime(this)" style="width:150px;height:23px;border:1px solid #9BABBA;"/>
					  </p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--two-->
				  <!--three-->
				  <div class="rightcon_title">社区</div>
				  <div class="rightcon_mid">
					  <p class="pheight">
					      <label class="label5">社区昵称：</label><input type="text" class="style7" id="nickname" value="<?php echo $user[0]['oNickName'];?>"/>
					  </p>
					  <p>
					      <label class="label5">签名：</label><textarea class="textarea1" id="signature"><?php echo $user[0]['oSignature'];?></textarea>
					  </p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <p>
					  <input type="button" class="enter specibut" value="确认编辑" id="user_edit" />
					  <input type="button" class="enter specibut" value="取消返回" onclick="window.location='index.php?c=account&a=userlist';" />
				  	  <input type="hidden" value="<?php echo $hideurl;?>" id="hideurl" />
				  </p>
				  <!--three-->
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
		  <script type="text/javascript" src="script/account/useradd.js"></script>
		  <script type="text/javascript" src="script/common/setTime.js"></script>
<?php include 'views/footer.php';?>
