	<?php include 'views/header.php';?>
		  <!--content开始-->
		  <script src="script/highcharts.js" type="text/javascript"> </script>
		  <script src="script/exporting.js" type="text/javascript"> </script>
		  <div class="content">
		      <!--左边开始-->
			  <?php include 'views/operate/left.php';?>
			  <!--左边结束-->
			  <!--右边开始-->
			  <div class="main_right">
			      <div class="linkmlist">
				      <div class="home_icom">
					      <span>当前位置：</span>
						  <span><a href="#">运营分析</a></span>
						  <span class="font1">-图形详细分析</span>
					  </div>
				  </div>
				  <div class="statistics">				  	
				      <div class="time_left">
					      <label class="label5" >年份:<?php echo $year;?></label>
				      </div>
				      <div class="time_right">
				      	 <form method="post" action="<?php echo $url;?>" >
				      		年份选择：
					      <select name="selectyear" id="selectyear" style="width:150px;" onchange="submit()">
						      <option value="2012" <?php echo $year==2012?"selected='selected'":'';?> >2012</option>
						      <option value="2013" <?php echo $year==2013?"selected='selected'":'';?>>2013</option>
						      <option value="2014" <?php echo $year==2014?"selected='selected'":'';?>>2014</option>
						      <option value="2015" <?php echo $year==2015?"selected='selected'":'';?>>2015</option>
						      <option value="2016" <?php echo $year==2016?"selected='selected'":'';?>>2016</option>
						      <option value="2017" <?php echo $year==2017?"selected='selected'":'';?>>2017</option>
						      <option value="2018" <?php echo $year==2018?"selected='selected'":'';?>>2018</option>
						      <option value="2019" <?php echo $year==2019?"selected='selected'":'';?>>2019</option>
						      <option value="2020" <?php echo $year==2020?"selected='selected'":'';?>>2020</option>
					      </select>  
					      </form>   
					  </div>
				  </div>
				  <div class="clear"></div>
				  <div class="LogMinerimg" style="margin:20px 0px 20px 30px;" id="containerid">		
				 	<script type="text/javascript">
				 	var chart;
				    $(document).ready(function() {
				        chart = new Highcharts.Chart({
				            chart: {
				                renderTo: 'containerid',
				                type: 'column'
				            },
				            title: {
				                text: '设备资费趋势图表'
				            },
				            xAxis: {
				                categories: [
				                   	'1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'
				                ]
				            },
				            yAxis: {
				                min: 0,
				                title: {
				                    text: '单位：元'
				                }
				            },
				            legend: {
				            	layout: 'vertical',
				    			align: 'right',
				    			verticalAlign: 'top',
				    			x: -10,
				    			y: 100,
				    			borderWidth: 0
				            },
				            tooltip: {
				                formatter: function() {
				                    return '<b>'+ this.series.name +'</b><br/>'+
									this.x +': '+ this.y +'元';
				                }
				            },
				            plotOptions: {
				                column: {
				                    pointPadding: 0.2,
				                    borderWidth: 0
				                }
				            },
			                series: [
				                {
						            name:'设备费用',
					                data: [<?php echo $seris1 ;;?>]		    
				            	},
				            	{
						            name:'设备托管费用',
					                data: [<?php echo $seris2;?>]		    
				            	},
				            ]
				        });
				    });
			</script>
				  </div>
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
		  <script type="text/javascript" src="script/common/comlist.js"></script>
<?php include 'views/footer.php';?>
