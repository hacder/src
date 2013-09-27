<?php include 'views/header.php';?>
<link rel="stylesheet" href="css/extend.css" type="text/css" />

<!--内容开始-->
<div class="main">
<!--左侧菜单开始-->
		<?php include 'views/home/menu.php';?>
<!--左侧菜单结束-->
<!--右侧内容开始-->
		<div class="mainright" id="mainright">
		<!--右侧title开始-->	
			<div class="currtitle">
				<div class="placing"><span>当前位置：</span><span>系统首页</span></div>
			</div>
	
			<div class="guide" style="display:none;">
				<div class="guide_title">
					<h1>您好，欢迎您回来！</h1>
					<P>您己经连续登录888次！最近登录时间为:2011年01月01日，登录IP地址为：<span class="txt_green">192.168.0.1</span></P>
					<div class="clear"></div>
					<div class="guide_mid1">
						<div class="left10">您的产品唯一标识号为：<span class="txt_green">XXXXXXXXXX</span></div>
						<div class="left10">您的短信剩余条数为：<span class="txt_green">20条</span></div>
						<div class="btn_green1 left10">
						<a href="#"><span class="spanL">点击充值</span><span class="spanR"></span></a>
						</div>
						<div class="clear"></div>
					</div>
					<div class="height10"></div>
				</div>
			</div>

		<!--右侧title结束-->
		<!--右侧content开始-->
			<div class="edit_list">
				<!--one-->
				<div style="display:none;">
				<div class="rightcon_title"><span class="left">告警方式</span><span class="gray left10">(您的告警信息共有[<span class="txt_green">20</span>]条，其中未读告警信息[<span class="txt_green">15</span>]条。)</span></div>
				<div class="rightcon_mid">
				<table border="0" width="100%">
					<tbody>
						<tr>
							<td width="30%">
								<label class="label5 left">手机：</label>
								<span class="left10">19850006954</span>
								<span class="gray left10"><a href="#" class="txt_green">[修改]</a></span>
							</td>
							<td>
								<label class="label5 left">附属手机号：</label>
								<span class="gray left10">12354678910</span>
								<span class="gray left10">12354678910</span>
								<span class="gray left10"><a href="#" class="txt_green">[修改]</a></span>
							</td>
						</tr>
						<tr>
							<td>
								<label class="label5 left">邮箱：</label>
								<span class="gray left10">kangyuelai@booksir.com</span>
								<span class="gray left10"><a href="#" class="txt_green">[修改]</a></span>
							</td>
							<td>
								<label class="label5">相关：</label>
								<span class="gray left10"><a href="#" class="txt_green">[个性化设置]</a></span>
							</td>
						</tr>
					</tbody>
				</table>
				</div>
				<div class="rightcon_bottom"></div>
				</div>
				<!--one-->
				<!--two-->
				<div class="height10"></div>
				<div class="rightcon_mid2">
					<div class="wids_container_l">
						<ul class="wids_cols_left">
							<li class="module_contain1">
								<div class="wid_title">
									<div class="left">
										<span><strong>服务器概况</strong></span>
										<span class="gray">(共有<?php echo $server['total'];?>台服务器)</span>
									</div>
									<div class="right">
										<a class="right10 txt_green" href="index.php?c=device&a=listindex">
											<span class="">进入服务器列表&gt;&gt;</span>
										</a>
									</div>
								</div>
								<div class="clear"></div>
								<?php if($server['total'] == 0){?>
								<div style="text-align:center; padding:20px; color:#666; display:none;" id="wid_body_error_38220">该时间范围无统计数据！</div>
								<?php }else{?>
								<div id="serverpie" style="width:100%;height:300px;">
									<script type = "text/javascript">										
										var charts;
										$(document).ready(function(){
											charts = new Highcharts.Chart({
									            chart: {
									                renderTo: 'serverpie',
									                plotBackgroundColor: null,
									                plotBorderWidth: null,
									                plotShadow: false
									            },
									            title: {
									                text: '服务器状态统计'
									            },    
									            tooltip: {
									            	 formatter: function() {
									            		return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';					                  
									                }
									            },
									            plotOptions: {
									                pie: {
									                    allowPointSelect: true,
									                    cursor: 'pointer',
									                    dataLabels: {
									                        enabled: false
									                    },
									                    showInLegend: true
									                }
									            },
									            series: [{
									                type: 'pie',
									                name: '服务器状态统计',
									                data: [<?php echo $server['serpie'];?>]
									            }]
									        });
								        });								
									</script>
								</div>
								<?php }?>
							</li>
						</ul><div class="clear"></div>
					</div>
					<div class="wids_container_r" >
						<ul class="wids_cols_right">
							<li class="module_contain1">
								<div class="wid_title">
									<div class="left">
										<span><strong>监控项目概况</strong></span>
										<span class="gray">(共有<?php echo $item['total'];?>个监控项目)</span>
									</div>
									<div class="right">
										<a class="right10 txt_green" href="index.php?c=monitor&a=monitorlist">
											<span class="">进入监控项目列表&gt;&gt;</span>
										</a>
									</div>
								</div>
								<div class="clear"></div>
								<?php if($item['total'] == 0){?>
								<div style="text-align:center; padding:20px; color:#666; display:none;" id="wid_body_error_38220">该时间范围无统计数据！</div>
								<?php }else{?>
								<div id="itempie" style="width:100%;height:300px;">
									<script type = "text/javascript">
										var chart;
										$(document).ready(function(){
											chart = new Highcharts.Chart({
									            chart: {
									                renderTo: 'itempie',
									                plotBackgroundColor: null,
									                plotBorderWidth: null,
									                plotShadow: false
									            },
									            title: {
									                text: '监控项目状态统计'
									            },    
									            tooltip: {
									            	 formatter: function() {
									            		return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';					                  
									                }
									            },
									            plotOptions: {
									                pie: {
									                    allowPointSelect: true,
									                    cursor: 'pointer',
									                    dataLabels: {
									                        enabled: false
									                    },
									                    showInLegend: true
									                }
									            },
									            series: [{
									                type: 'pie',
									                name: '监控项目状态统计',
									                data: [<?php echo $item['itempie'];?>]									
									            }]
									        });
										});									
									</script>
								</div>
								<?php }?>
							</li>
						</ul>
						<div class="clear"></div>	
					</div>
				</div>
				<!--two2-->
			<div class="clear"></div>
			</div>
			<div class="height10"></div>
			
		<div class="rightcon_title">
			<span>快捷功能&常见问题帮助文档</span>
		</div>
		<div class="rightcon_mid"  style="overflow-y:hidden;">
		<p style="padding:5px 10px;">
			<span class="left" style="padding-top:5px;"><a href="./index.php?c=snmp&a=snmpset"><b>《全局snmp配置》</b></a></span>
			<span class="left" style="padding:5px 10px;"><a href="./index.php?c=paint&a=serverable&ipid=89"><b>《查看本机图形》</b></a></span>
			<span class="left" style="padding:5px 10px;"><a href="./index.php?c=alarm&a=notiset"><b>《配置发送邮箱账号及告警通知》</b></a></span>
			<span class="left" style="padding:5px 10px;"><a href="./index.php?c=account&a=useredit&id=15"><b>《配置接收邮箱账号》</b></a></span>	
			<span class="left" style="padding:5px 10px;"><a href="./index.php?c=account&a=personset"><b>《告警个性化设置》</b></a></span>
		</p>
		<p style="padding:5px 10px;">
			<span class="left"><a href="http://bbs.osapub.com/forum-127-1.html" target="_blank">最新版本下载及更新指南?</a></span><br />
			<span class="left"><a href="http://wiki.osapub.com/%E6%9C%8D%E5%8A%A1%E5%99%A8%E5%8F%AF%E4%BB%A5ping%E9%80%9A,%E4%B8%BA%E4%BB%80%E4%B9%88%E6%98%BE%E7%A4%BA%E7%A1%AE%E6%98%AF%E6%95%85%E9%9A%9C" target="_blank">为什么能ping通，但服务器图形都显示故障呢？</a></span><br />
			<span class="left"><a href="http://wiki.osapub.com/%E9%A6%96%E9%A1%B5#.E6.97.A5.E5.B8.B8.E8.BF.90.E7.BB.B4.E7.9B.91.E6.8E.A7" target="_blank">各服务性能监控指标及帮助文档？</a></span>
		</p>
		
		</div>
		<div class="height10"></div>
	
		<!--右侧content结束-->
		</div>
<!--右侧内容结束-->
</div>
<script type="text/javascript" src="script/common/base.js"> </script>
<script src="script/highcharts/highcharts.js" type="text/javascript"> </script>
<script src="script/highcharts/gray.js" type="text/javascript"> </script>
<?php include 'views/footer.php';?>