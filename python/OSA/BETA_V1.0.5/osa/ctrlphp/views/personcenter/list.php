	<?php include 'views/header.php';?>
		  <!--content开始-->
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
		  <div class="content">
		      <!--左边开始-->
			  	<?php include 'views/personcenter/left.php';?>
			  <!--左边结束-->
			  <!--右边开始-->
			  <div class="main_right">
			      <div class="linkmlist">
				      <div class="home_icom">
					      <span>当前位置：</span>
						  <span><a href="#">个人中心</a></span>
						  <span class="font1">-告警通知</span>
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
				  <div class="timepop" id="timepop" style="display:none;">
				      <div class="time_pro">
					      <p><img src="images/icon2.gif" />注：请在以下日历中分别点选开始日期和结束日期。</p>
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
						    <th width="5%">状态</th>
							<th width="15%">报警时间</th>
							<th width="15%">监控项目名称</th>
							<th width="20%">所在服务器</th>
							<th width="35%">消息内容</th>
						</tr>
						<?php foreach ($msginfo as $key){?>
						<tr class="hui">
							<td><input type="checkbox" value="<?php echo $key['id'];?>"  class="checkbox"/></td>
							<td><span class="alarm_<?php echo $key['oType'];?>" title="<?php osa_showstatus($key['oType']);?>">&nbsp;</span></td>
							<td><?php echo $key['oAddTime'];?></td>
							<td><?php echo $key['oItemName'];?></td>
							<td><?php echo osa_show_info($key['oServerip'],20);?></td>
							<td><?php echo osa_show_alarms($key['oAlarmInfo']);?></td>
						</tr>
						<?php }?>
						<tr>
						   <td class="td_chect" colspan="7"><span class="checkall"><input type="checkbox" class="style11" id="checkall" />全选</span><span class="del"><input type="button" value="删除" class="delete" id="del_alarm" /></span>
						   </td>  
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
  		<div class="window " id="look_filename" style="display:none;">
		    <div class="window_title">
			    <span class="window_text">异常查看</span>
				<input type="button" class="windbutton" />
			</div>
			<div class="window_con" id="content_filename">
				 
			</div>
		</div>
	<script type="text/javascript" src="script/common/comlist.js"></script>
	<script type="text/javascript" src="script/common/comlayer.js"></script>
<?php include 'views/footer.php';?>
