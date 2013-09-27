	<?php include 'views/header.php';?>
		<link href="css/ui-lightness/jquery-ui-1.8.20.custom.css" rel="stylesheet" type="text/css"/>
		  <script src="script/jquery-ui-1.8.20.custom.min.js"></script>
		  <script type="text/javascript" >
		  	var picker = "<?php echo isset($picker)?$picker:''?>";
		  	$(document).ready(function(){
		  		if(picker !=''){
					$("#"+picker).addClass('optfor');
				}
			});
		  </script>
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
						  <span class="font1">-操作记录管理</span>
					  </div>
					  <div class="setup">
<!--					      <span class="setup_icon"><a href="#">逛逛知道库</a></span>-->
					      <span class="setup_icon"><a href="index.php?c=maintain&a=addlog">添加操作记录</a></span>
					  </div>
				  </div>
				  <div class="statistics">
				      <div class="time_left"><?php echo $starttime;?>至<?php echo $endtime;?></div>
					  <div class="time_right">
					      <span><a href="<?php echo $url;?>&date=today" id="today" class="" >今日</a></span>   
					      <span><a href="<?php echo $url;?>&date=yesterday" id="yesterday" class="" >昨日</a></span>   
					      <span><a href="<?php echo $url;?>&date=lastweek" id="lastweek" class="" >最近7天</a></span>   
					      <span><a href="<?php echo $url;?>&date=last2week" id="last2week" class="">最近15天</a></span>   
					      <span><a href="#" id="showsearch">自定义搜索</a></span>     
					  </div>
				  </div>
				  <div class="clear"></div>
				  <!-- 隐藏 自定义搜索 -->
				  <div class="timepop" id="timepop" style="display:none;">
				      <div class="time_pro">
					      <p><img src="../images/icon2.gif" />注：请在以下日历中分别点选开始日期和结束日期，关键词可搜索日志标题、标签。</p>
					  </div>
					  <div class="timecontent">
					      <p>
						      <div class="date1"><a href="#">&lt;&lt;上个月</a></div>
							  <div class="date2"><a href="#">今天</a></div>
							  <div class="date3"><a href="#">下个月&gt;&gt;</a></div>
						  </p>
						  <div id="datepicker"></div>
					  </div>
					  <div class="timeFrame">
					  	  <form method="post" action="<?php echo $url;?>">
					      <p><label class="label7">关键词：</label><input type="text" class="style15" name="keyword" value="<?php echo $_SESSION['logsearch'];?>"/></p>      
					      <p class="style16"><label class="label7">时间范围：</label><input type="text" class="style15" id="date1" name="starttime" value="<?php echo $starttime;?>"/>-<input type="text" name="endtime" class="style15" id="date2" value="<?php echo $endtime;?>"/></p>
						  <p class="center"><input type="submit" value="查询" class="button3" />&nbsp;或&nbsp;&nbsp;<a href="#" id="cancelsearch">取消</a></p>
						  <p class="center"><a href="<?php echo $url.'&clean=1';?>" class="timea"><b>[清除查询条件]</b></a></p>
						  </form>
					  </div>
				  </div>
				  <div class="table_setup">
				      <table cellspacing="0" cellpadding="0" width="100%">
					    <tr>
						    <th width="5%">选择</th>
						    <th>用户</th>
							<th>日志标题</th>
							<th>添加时间</th>
							<th>日志内容</th>
							<th>操作</th>
						</tr>
						<?php foreach ($loginfo as $key) {?>
							<tr>
								<td><input type="checkbox" value="<?php echo $key['id'];?>"  class="checkbox"/></td>
								<td><?php echo $key['oUserName'];?></td>
								<td><span class="types_knowledge">[<?php echo $key['oTypeText'];?>]</span><span class="left"><?php echo $key['oLogTitle'];?></span></td>
								<td><?php echo $key['oLogAddTime'];?></td>
								<td><a href="#" class="logtext" ctext="<?php echo $key['oLogText']?>">详情</a></td>
								<td><a href="index.php?c=maintain&a=editlog&id=<?php echo $key['id'];?>">编辑</a>
								<!--<?php //if($key['oIsShare'] == 0){?>
								<a href="index.php?c=maintain&a=sharelog&id=<?php //echo $key['id'];?>" class="share">分享</a>
								<?php //} else {?>
									已分享
								<?php //}?>
								--></td>
							</tr> 
						<?php }?>
						<tr>
						   <td class="td_chect" colspan="5"><span class="checkall"><input type="checkbox" class="style11" id="checkall"/>全选</span><span class="del"><input type="button" value="删除" class="delete" id="logdel"/></span></td>  
						</tr>
						<tr>
						    <td class="style2" colspan="7">
							     <?php echo $page;?>
							</td>
						</tr>
					  </table>
				  </div>
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
		  <div id="shadow"></div>
	  	  <div class="window " id="looklog" style="display:none;">
		    <div class="window_title">
			    <span class="window_text">日志详细内容</span>
				<input type="button" class="windbutton" />
			</div>
			<div class="window_con" id="log_text">
				 
			</div>
		  </div>
		  <script type="text/javascript" src="script/common/comlayer.js"></script>
		  <script type="text/javascript" src="script/common/comlist.js"></script>
<?php include 'views/footer.php';?>