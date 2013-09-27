	<?php include 'views/header.php';?>
		  <!--content开始-->
		  <div class="content">
		      <!--左边开始-->
			  <?php include 'views/home/left.php';?>
			  <!--左边结束-->
			  <!--右边开始-->
			  <div class="main_right">
			      <div class="linkmlist">
				      <div class="home_icom">
					      <span>当前位置：</span>
						  <span><a href="#">系统首页</a></span>
						  <span class="font1">-系统首页</span>
					  </div>
				  </div>
				  <div class="prompt">
					  <p><img src="images/icon2.gif" />您已经连续使用<b><?php echo $_SESSION['users_num'];?></b>次！最近登录时间为:<?php echo $_SESSION['login_time'];?>！最近的操作记录为：<b><?php echo $operate;?></b>！登录IP地址为：<?php echo $loginip ;?></p>
				  </div>
				  <!--one-->
				  <div class="rightcon_title">安全提示</div>
				  <div class="rightcon_mid">
				      <p>尊敬的用户，您当前使用的版本为<?php echo OSA_VERSION;?></p>
				      <?php if(OSA_VERSION == $info['version']){?>
					  <p>您使用的是最新版本,无需版本更新</p>
					  <?php }else{?>
					  <p>最新版本为：<a href="http://bbs.osapub.com" style="color:#55A2D7;" target="blank"><?php echo $info['version'];?></a></p>
					  <p>为了您的系统安全, 建议您升级到最新版本</p>
					  <?php } ?>
					  <?php if($info['patch']!= ''){ $patch =$info['patch'] ; $num = count($patch);?>
					  		<p>您的系统存在bug,以下补丁可以更新</p>
					  	<?php for($i=0;$i<$num;$i++){ 
					  			$key = (array)$patch[$i];
					  		?>
					  		<p><?php echo $key['patchname'];?>：<a href="<?php echo $key['url'];?>" style="color:#55A2D7;" target="blank"><?php echo $key['url'];?></a></p>
					  	<?php }
					  }?>		  
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--one-->
				  <!--two-->
				  <div class="rightcon_title">在线成员</div>
				  <div class="rightcon_mid online">
				      <ul>
				      	<?php foreach ($loginuser as $key) {?>
					      <li><?php echo $key['oUserName'];?></li>
					    <?php }?>
					  </ul>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--two-->
				  <!--three-->
				  <div class="rightcon_title">系统信息</div>
				  <div class="rightcon_mid">
				      <table cellspacing="0" cellspading="0" width="100%">
					    <tr>
						    <td width="40%">OSA 程序版本</td>
							<td width="60%">当前版本为：<?php echo OSA_VERSION;?><span class="news"><a href="http://www.osapub.com" target="blank">查看最新版本 </a><a href="http://bbs.osapub.com" target="blank">支持与服务</a></span></td>
						</tr>
						<tr>
						    <td width="40%">服务器系统及php版本</td>
							<td width="60%"><?php echo PHP_OS;?> / PHP <?php echo 'V'.phpversion();?></td>
						</tr>
						<tr>
						    <td width="40%">服务器软件</td>
							<td width="60%"><?php echo getServerSoft();?></td>
						</tr>
						<tr>
						    <td width="40%">服务器MYSQL版本</td>
							<td width="60%"><?php echo $mysql_version;?></td>
						</tr>
						<tr>
						    <td width="40%">PYTHON版本</td>
							<td width="60%">2.7.2</td>
						</tr>
					  </table>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--three-->
				  <div class="themassage">
				      <div class="left">
					      <div class="massage_title">团队信息</div>
						  <div class="massage_con">
						      <p class="line"><b>版权所有：</b>OSA开源团队</p>
						      <?php echo $info['team'];?>
						  </div>
						  <div class="massage_bottom"></div>
					  </div>
					  <div class="right">
					      <div class="massage_title">给OSA团队留言</div>
						  <div class="massage_con">
						      <span class="leave">帮助OSA改进新产品，提交新功能需求，产品BUG提交</span>
							  <textarea class="style1" id="content" name="content"></textarea>
							  <p class="center"><input type="button"  class="enter" value="提交" id="submit"/></p>
						  </div>
						  <div class="massage_bottom"></div>
					  </div>
				  </div>
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
	      <script type="text/javascript">
	      var web_domain = "<?php echo OSA_WEBSERVER_DOMAIN;?>";
		  $(document).ready(function(){
				$("#submit").click(function(){
					var content = $("#content").val();
					if(content == ''){
						return false;
					}
					var url =web_domain+"/proposal.php?callback=?";
					$.getJSON(url,{'content':content},function(json){
						var msg = json.msg;
						if(msg.indexOf('success')!=-1){
							alert('感谢你的留言！');
							$("#content").val('');
						}else{
							$("#content").val('');
						}
					});
				});
		  });
		  </script>
<?php include 'views/footer.php';?>
