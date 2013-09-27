<?php include 'views/header.php'?>
<link rel="stylesheet" href="css/extend.css" type="text/css" />
<!--内容开始-->
<div class="main">
<!--左侧菜单开始-->
	<?php include 'views/paint/memcache/menu.php'?>
<!--左侧菜单结束-->
<!--右侧内容开始-->
	<div class="mainright" id="mainright">
	<!--右侧title开始-->	
		<div class="currtitle">
			<div class="placing"><span>当前位置：</span><span>图形报表</span> <span>&gt;</span> <span>响应时间报告</span></div>
		</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
		<div class="edit_list">
			<div class="edit_submit2">
				<div class="btn_gray2 right">
					<a href="#"><span class="spanL">自定义搜索</span><span class="spanR"></span></a>
				</div>
				<div class="btn_gray2 right">
					<a href="#"><span class="spanL">最近15天</span><span class="spanR"></span></a>
				</div>
				<div class="btn_gray2 right">
					<a href="#"><span class="spanL">最近7天</span><span class="spanR"></span></a>
				</div>
				<div class="btn_gray2 right">
					<a href="#"><span class="spanL">昨日</span><span class="spanR"></span></a>
				</div>
				<div class="btn_green1 right">
					<a href="#"><span class="spanL">今日</span><span class="spanR"></span></a>
				</div>				
			</div>
			<!--one-->
			<div class="rightcon_title">
				<span><?php echo date("Y-m-d H:i:s",time()) ;?></span>
			</div>
			<div class="rightcon_mid"  id="rightcon_mid" >
				<div class="monitorhome">
					
					<div id="container-rate" class="chartcontainer">
						<script type="text/javascript">
						 	var chart;
						    $(document).ready(function() {
						        chart = new Highcharts.Chart({
						            chart: {
						                renderTo: 'container-rate',
						                type: 'area'
						            },
						            credits : {
										enabled:false
									},
						            title: {
						                text: 'Memcache缓存命中率'
						            },
						            xAxis: {
						            	type: 'datetime',	
						            	maxPadding : 0.05,
										minPadding : 0.05,
										//lineColor : '#990000',//自定义刻度颜色
										labels: {formatter:function() {
											var vDate = new Date(this.value);	
											return vDate.getHours()+":"+vDate.getMinutes()+":"+vDate.getSeconds();
										}}
						            },
						            yAxis: {
						                min: 0,
						                max: 1,
						                title: {
						                    text: '单位:%'
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
						            		return ''+'时间:'+date+'<br >缓存命中率: '+ this.y*100 +'%';					                  
						                }
						            },
						            plotOptions: {
						                area: {
						                    marker: {
						                        enabled: false,
						                        symbol: 'circle',
						                        radius: 2,
						                        states: {
						                            hover: {
						                                enabled: true
						                            }
						                        }
						                    }
						                }
						            },
					                series: [ <?php echo $indexrate;?>]
						        });
						    });
						</script>
					</div>	  
					<div class="chartcontainer" id="spacerate" >	
						<script type="text/javascript">
						 	var charts;
						    $(document).ready(function() {
						        charts = new Highcharts.Chart({
						            chart: {
						                renderTo: 'spacerate',
						                type: 'area'
						            },
						            credits : {
										enabled:false
									},
						            title: {
						                text: 'Memcache空间使用率'
						            },
						            xAxis: {
						            	type: 'datetime',	
						            	maxPadding : 0.05,
										minPadding : 0.05,
										//lineColor : '#990000',//自定义刻度颜色
										labels: {formatter:function() {
											var vDate = new Date(this.value);	
											return vDate.getHours()+":"+vDate.getMinutes()+":"+vDate.getSeconds();
										}}
						            },
						            yAxis: {
						                min: 0,
						                max: 1,
						                title: {
						                    text: '单位:%'
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
						            		return ''+'时间:'+date +'<br >空间使用率: '+ this.y*100 +'%';								                  
						                }
						            },
					                series: [ <?php echo $spacerate;?>]
						        });
						    });
						</script>		
					</div> 
					<div id="usedmem" class="chartcontainer">
						<script type="text/javascript">
						var charts;
					    $(document).ready(function() {
					        charts = new Highcharts.Chart({
					            chart: {
					                renderTo: 'usedmem',
					                type: 'area'
					            },
					            credits : {
									enabled:false
								},
					            title: {
					                text: 'Memcache使用内存'
					            },
					            xAxis: {
					            	type: 'datetime',	
					            	maxPadding : 0.05,
									minPadding : 0.05,
									//lineColor : '#990000',//自定义刻度颜色
									labels: {formatter:function() {
										var vDate = new Date(this.value);	
										return vDate.getHours()+":"+vDate.getMinutes()+":"+vDate.getSeconds();
									}}
					            },
					            yAxis: {
					                min: 0,
					                title: {
					                    text: '单位:M'
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
					            		return ''+'时间:'+date +'<br >使用内存: '+ this.y +'M';								                  
					                }
					            },
				                series: [ <?php echo $usedmem;?>]
					        });
					    });
						</script>
					</div>
					<div id="currconnects" class="chartcontainer">
						<script type="text/javascript">
						var charts;
					    $(document).ready(function() {
					        charts = new Highcharts.Chart({
					            chart: {
					                renderTo: 'currconnects',
					                type: 'area'
					            },
					            credits : {
									enabled:false
								},
					            title: {
					                text: 'Memcache当前连接数'
					            },
					            xAxis: {
					            	type: 'datetime',	
					            	maxPadding : 0.05,
									minPadding : 0.05,
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
					            		return ''+'时间:'+date +'<br >当前连接数: '+ this.y +'个';								                  
					                }
					            },
				                series: [ <?php echo $currconnects;?>]
					        });
					    });
						</script>
					</div>
					<div id="curritems" class="chartcontainer">
						<script type="text/javascript">
						var charts;
					    $(document).ready(function() {
					        charts = new Highcharts.Chart({
					            chart: {
					                renderTo: 'curritems',
					                type: 'area'
					            },
					            credits : {
									enabled:false
								},
					            title: {
					                text: 'Memcache当前条目数'
					            },
					            xAxis: {
					            	type: 'datetime',	
					            	maxPadding : 0.05,
									minPadding : 0.05,
									//lineColor : '#990000',//自定义刻度颜色
									labels: {formatter:function() {
										var vDate = new Date(this.value);	
										return vDate.getHours()+":"+vDate.getMinutes()+":"+vDate.getSeconds();
									}}
					            },
					            yAxis: {
					       			min:0,
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
					            		return ''+'时间:'+date +'<br >当前条目数: '+ this.y +'个';
					      							                  
					                }
					            },
				                series: [ <?php echo $curritem;?>]
					        });
					    });
						</script>
					</div>
					<div id="wrsecond" class="chartcontainer">
						<script type="text/javascript">
						var charts;
					    $(document).ready(function() {
					        charts = new Highcharts.Chart({
					            chart: {
					                renderTo: 'wrsecond',
					                type: 'area'
					            },
					            credits : {
									enabled:false
								},
					            title: {
					                text: 'Memcache读写/每秒'
					            },
					            xAxis: {
					            	type: 'datetime',	
					            	maxPadding : 0.05,
									minPadding : 0.05,
									//lineColor : '#990000',//自定义刻度颜色
									labels: {formatter:function() {
										var vDate = new Date(this.value);	
										return vDate.getHours()+":"+vDate.getMinutes()+":"+vDate.getSeconds();
									}}
					            },
					            yAxis: {
					       			min:0,
					                title: {
					                    text: '单位:bytes/sec'
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
					            		return ''+'时间:'+date +'<br >'+this.series.name+': ' + this.y +'b/s';
					      							                  
					                }
					            },
				                series: [ <?php echo $wrsecond;?>]
					        });
					    });
						</script>
					</div>
					<div id="consecond" class="chartcontainer">
						<script type="text/javascript">
						var charts;
					    $(document).ready(function() {
					        charts = new Highcharts.Chart({
					            chart: {
					                renderTo: 'consecond',
					                type: 'area'
					            },
					            credits : {
									enabled:false
								},
					            title: {
					                text: 'Memcache连接数/每秒'
					            },
					            xAxis: {
					            	type: 'datetime',	
					            	maxPadding : 0.05,
									minPadding : 0.05,
									//lineColor : '#990000',//自定义刻度颜色
									labels: {formatter:function() {
										var vDate = new Date(this.value);	
										return vDate.getHours()+":"+vDate.getMinutes()+":"+vDate.getSeconds();
									}}
					            },
					            yAxis: {
					       			min:0,
					                title: {
					                    text: '单位:个/秒'
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
					            		return ''+'时间:'+date +'<br >'+this.series.name+': ' + this.y +'个/秒';
					      							                  
					                }
					            },
				                series: [ <?php echo $consecond;?>]
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
<script type="text/javascript" src="script/common/base.js"> </script>
<script src="script/highcharts/highcharts.js" type="text/javascript"> </script>
<script src="script/highcharts/gray.js" type="text/javascript"> </script>
<?php include 'views/footer.php' ;?>