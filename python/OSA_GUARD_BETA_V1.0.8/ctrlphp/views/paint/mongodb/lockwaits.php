<?php include 'views/header.php'?>
<link rel="stylesheet" href="css/extend.css" type="text/css" />
<!--内容开始-->
<div class="main">
<!--左侧菜单开始-->
	<?php include 'views/paint/mongodb/menu.php'?>
<!--左侧菜单结束-->
<!--右侧内容开始-->
	<div class="mainright" id="mainright">
	<!--右侧title开始-->	
		<div class="currtitle">
			<div class="placing"><span>当前位置：</span><span>图形报表</span> <span>&gt;</span> <span>MongoDB当前锁等待数</span></div>
		</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
		<div class="edit_list">
			
			<?php include 'views/paint/timepop.php';?>
			<?php include 'views/paint/iteminfo.php';?>
			<!--one-->
			<div class="rightcon_title">
				<span>可用率统计</span>
			</div>
			<div class="rightcon_mid"  id="rightcon_mid" >
				<div class="wids_container" >
					
					<div id="lockwaits" class="wids_cols ui-sortable" style="width:70%;height:280px;padding:0;">
						<script type="text/javascript">
						 	var chart;
						    $(document).ready(function() {
						        chart = new Highcharts.Chart({
						            chart: {
						                renderTo: 'lockwaits',
						                type: 'area'
						            },
						            credits : {
										enabled:false
									},
						            title: {
						                text: 'MongoDB当前锁等待数'
						            },
						            xAxis: {
						            	type: 'datetime',
										//lineColor : '#990000',//自定义刻度颜色
										labels: {formatter:function() {
											var vDate = new Date(this.value);	
											return vDate.getHours()+":"+vDate.getMinutes()+":"+vDate.getSeconds();
										}}
						            },
						            yAxis: {
						                min: 0,
						                title: {
						                    text: '单位:个'
						                }
						            },
						            tooltip: {
						                formatter: function() {
						                	var mydate = new Date(this.x);
						                	var year = mydate.getFullYear();
						                	var month = parseInt(mydate.getMonth())+1;
						                	var day = mydate.getDate();
						                	var date = mydate.getHours()+":"+mydate.getMinutes()+":"+mydate.getSeconds();
						                	date = year+"-"+month+"-"+day+" "+date;
						            		return ''+'时间:'+date+'<br >'+this.series.name+': '+ this.y +'个';					                  
						                }
						            },
						            plotOptions: {
						                area: {
							            	allowPointSelect: true,
							    		    lineWidth: 0,			
							                states: {
							                   hover: {			      	   	  
							                      lineWidth: 1
							                   }
							                },
							            	marker: {
								                enabled: false,
								 			   radius: 0,
								                states: {
								                   hover: {
								                      enabled: true,
								                      symbol: 'circle',
								                      radius: 2,
								                      lineWidth: 0
								                   }
								                }   
								             }
						                }
						            },
					                series: [ <?php echo $lockwaits;?>]
						        });
						    });
						</script>
					</div>
				</div>	  					
			</div>
			<div class="rightcon_bottom"></div>
			<!--one-->
		</div>

		<!--右侧content结束-->
	</div>
<!--右侧内容结束-->
</div>
<!--内容结束-->
<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.8.20.custom.css" type="text/css" />
<script src="script/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>
<script src="script/paint/paintlist.js" type="text/javascript"> </script>
<script type="text/javascript" src="script/common/base.js"> </script>
<script src="script/highcharts/highcharts.js" type="text/javascript"> </script>
<script src="script/highcharts/gray.js" type="text/javascript"> </script>
<?php include 'views/footer.php' ;?>