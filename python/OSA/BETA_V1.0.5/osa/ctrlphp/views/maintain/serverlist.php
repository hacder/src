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
						  <span class="font1">-服务器列表</span>
					  </div>
				  </div>
				  <div class="clear"></div>
				  <p class="pheight" style="margin-top:10px;">
				  	  <form method="post" action="<?php echo $url;?>" >
				      <label class="label7">设备名称：</label><input type="text" class="style4" name="devname" value="<?php echo $_SESSION['devname'];?>"/>
				      <label class="label7">IP地址：</label><input type="text" class="style4" name="ip" value="<?php echo $_SESSION['dev_ip'];?>"/>
					  <label class="label7">设备状态：</label>
					  <select class="select1" name="status">
					  		<option value="" >请选择</option>
					  		<option value="正常" <?php echo $_SESSION['dev_status']=='正常'?'selected="selected"':'';?>>正常</option>
					  		<option value="服务器不可达" <?php echo $_SESSION['dev_status']=='服务器不可达'?'selected="selected"':'';?>>服务器不可达</option>
					  		<option value="服务异常" <?php echo $_SESSION['dev_status']=='服务异常'?'selected="selected"':'';?>>服务异常</option>
					  		<option value="未知异常" <?php echo $_SESSION['dev_status']=='未知异常'?'selected="selected"':'';?>>未知异常</option>
					  </select>
					  <input type="submit" class="updatebut" value="查询" />
					  <a class="timea" href="<?php echo $url.'&clean=1'?>">[清空条件]</a>
					  </form>
				  </p>
				  <p class="legend">图例：</p>
				  <div class="cutline">
				      <dl>
					      <dt><img src="images/2.gif" /></dt>
						  <dd><b>正常</b></dd>
					  </dl>
				      <dl>
					      <dt><img src="images/1.gif" /></dt>
						  <dd><b>服务器不可达</b></dd>
					  </dl>
				      <dl>
					      <dt><img src="images/3.gif" /></dt>
						  <dd><b>服务异常</b></dd>
					  </dl>
				      <dl>
					      <dt><img src="images/4.gif" /></dt>
						  <dd><b>未知异常</b></dd>
					  </dl>
				  </div>
				  <div class="clear"></div>
				  <div class="line"></div>
				  <div class="Illustrations ">
				  		<?php foreach($devinfo as $key){
				  			if($key['oStatus'] =="正常"){
				  				$imgnum = 2;
				  			}else if($key['oStatus'] =="服务器不可达"){
				  				$imgnum = 1;
				  			}else if($key['oStatus'] =="服务异常"){
				  				$imgnum = 3;
				  			}else{
				  				$imgnum = 4;
				  			}
				  			?>
				      <dl>
					      <dt><img src="images/<?php echo $imgnum;?>.gif" /></dt>
						  <dd><?php echo $key['oIp']?></dd>
						  <dd><a href="index.php?c=maintain&a=devinfo&id=<?php echo $key['id']?>">即时信息</a>|<a href="index.php?c=maintain&a=controlcenter&id=<?php echo $key['id']?>">控制中心</a></dd>
					  </dl>
					  <?php }?>
				  </div>
	
			  </div>
			  <?php echo $page;?>
			  <!--右边结束-->
		  </div><!--content结束-->
<?php include 'views/footer.php';?>
