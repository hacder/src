<div class="edit_submit2">
	<div class="btn_gray2 right">
		<a id="paint-time"><span class="spanL">自定义搜索</span><span class="spanR"></span></a>
	</div>
	<div class="<?php echo $_SESSION['period']=='last15days'?'btn_green1':'btn_gray2';?> right">
		<a href="<?php echo $url."&period=last15days";?>"><span class="spanL">最近15天</span><span class="spanR"></span></a>
	</div>
	<div class="<?php echo $_SESSION['period']=='last7days'?'btn_green1':'btn_gray2';?> right">
		<a href="<?php echo $url."&period=last7days";?>"><span class="spanL">最近7天</span><span class="spanR"></span></a>
	</div>
	<div class="<?php echo $_SESSION['period']=='yesterday'?'btn_green1':'btn_gray2';?> right">
		<a href="<?php echo $url."&period=yesterday";?>"><span class="spanL">昨日</span><span class="spanR"></span></a>
	</div>
	<div class="<?php echo $_SESSION['period']=='today'?'btn_green1':'btn_gray2';?> right">
		<a href="<?php echo $url."&period=today";?>"><span class="spanL">今日</span><span class="spanR"></span></a>
	</div>
	<div class="left"><span><?php echo $time ;?></span></div>				
</div>
<input type="hidden" value="<?php echo $url;?>" id="hideUrl" />
<div class="morecond_div" id="timepop" style="display:none;">
	<div class="time_pro">
 		<p><img src="images/icon2.gif" />注：请在以下日历中分别点选开始日期和结束日期。</p>
	</div>
	<div class="timecontent" style="width:456px;">
		<p>
	      	<div class="date1"><a href="">&lt;&lt;上个月</a></div>
		  	<div class="date2"><a href="">今天</a></div>
		  	<div class="date3"><a href="">下个月&gt;&gt;</a></div>
	  	</p>
	  	<div id="datepicker"></div>
	</div>
	<div class="timeFrame" >
		<p>
			<label class="label7" style="font-weight: bold;">选择时间：</label>
			<input id="date1" class="style15" type="text" readonly="readonly" value="">
			-
			<input id="date2" class="style15" type="text" readonly="readonly" value="">
		</p>
		<div class="btn_green1 left10">
			<a id="paint-search">
				<span class="spanL">应用</span>
				<span class="spanR"></span>
			</a>
		</div>
	</div>
	<div class="clear height10"></div>
</div>