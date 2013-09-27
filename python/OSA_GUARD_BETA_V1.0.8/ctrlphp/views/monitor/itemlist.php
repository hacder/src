<?php include 'views/header.php';?>
<link rel="stylesheet" href="css/extend.css" type="text/css" />

<!--内容开始-->
<div class="main">
<!--左侧菜单开始-->
	<?php include "views/monitor/menu.php";?>
<!--左侧菜单结束-->
<!--右侧内容开始-->
		<div class="mainright" id="mainright">
		<!--右侧title开始-->	
			<div class="currtitle">
				<div class="placing"><span>当前位置：</span><span>日常监控</span> <span>&gt;</span> <span>创建监控项目</span></span></div>
			</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
			<div class="edit_list">
			<div class="time_pro">
				<p>
					<span class="time_pro_img"></span>
					<span>提示:您可以在这里创建各类监控项目，请选择以下监控类型。</span>
				</p>
			</div>
			<!--one-->
				<div class="rightcon_title">日常运维监控</div>
				<div class="rightcon_mid">
					   <ul>
						<li class="li_title"><a href="index.php?c=monitor&a=websiteindex" >网址存活(HTTP/HTTPs)</a></li>
						<li class="li_content">网页存活监控可以很方便的帮助您监控网页状态，可以通过关键字检测，指定你认为正确的HTTP状态码等监控网页的存活状态，进一步可以检测到WEB服务器是服务是否运行正常。<a target="_blank" href="http://wiki.osapub.com/%E7%BD%91%E5%9D%80%E5%AD%98%E6%B4%BB(HTTP/HTTPs)#.E7.BD.91.E5.9D.80.E5.AD.98.E6.B4.BB.E9.AB.98.E7.BA.A7.E9.80.89.E9.A1.B9.E8.AF.B4.E6.98.8E">参考配置</a>
						</li>
						<li class="li_title"><a href="index.php?c=monitor&a=pingindex" >域名PING检测(PING)</a></li>
						<li class="li_content">通过发送ICMP 报文,检测主机存活,被监控主机需要允许接收ICMP报文。可以批量检测多个主机。
						</li>
						<li class="li_title"><a href="index.php?c=monitor&a=tcpindex" >TCP端口检测(TPORT)</a></li>
						<li class="li_content">通过socket往指定的TCP端口发送报文,检测主机端口是否存活,可以批量检测多个端口。
						</li>
						<li class="li_title"><a href="index.php?c=monitor&a=udpindex" >UDP端口检测(UPORT)</a></li>
						<li class="li_content">通过socket往指定的UDP端口发送报文,检测主机端口是否存活,可以批量检测多个端口。
						</li>
						<li class="li_title"><a href="index.php?c=monitor&a=dnsindex" >域名服务检测(DNS)</a></li>
						<li class="li_content">监控DNS服务器的可用率和响应时间，并获得各种DNS记录列表，支持DNS轮询(RR)。</li>
						<li class="li_title"><a href="index.php?c=monitor&a=ftpindex" >文件服务器(FTP)</a></li>
						<li class="li_content">监控FTP服务器的可用率和响应时间！
						</li>
					  </ul>
				</div>
				<div class="rightcon_bottom"></div>
			<!--one-->
			<!--two-->
				<div class="rightcon_title">常用服务监控</div>
				<div class="rightcon_mid">
					   <ul>
						<li class="li_title"><a href="index.php?c=monitor&a=mysqlindex" >MySQL</a></li>
						<li class="li_content">监控MySQL运行时的各项性能数据，包括查询吞吐率、查询缓存、索引缓存、并发连接、流量以及表锁定等性能报表和分析报告。<a target="_blank" href="http://wiki.osapub.com/MySQL">更多详细介绍!</a>
						</li>
						<li class="li_title"><a href="index.php?c=monitor&a=mongodbindex" >MongoDB</a></li>
						<li class="li_content">监控MongoDB运行时的各项性能数据,帮助您进一步分析MongoDB压力。<a target="_blank" href="http://wiki.osapub.com/MongoDB">更多详细介绍!</a>
						</li>
						<li class="li_title"><a href="index.php?c=monitor&a=nginxindex" >Nginx</a></li>
						<li class="li_content">监控Nginx运行时并发连接数、吞吐率（请求数/秒）、持久连接利用率，以及更多的详细性能报表和分析报告。<a target="_blank" href="http://wiki.osapub.com/Nginx">更多详细介绍!</a>
						</li>
						<li class="li_title"><a href="index.php?c=monitor&a=apacheindex" >Apache</a></li>
						<li class="li_content">监控Nginx运行时并发连接数、吞吐率（请求数/秒）、持久连接利用率，以及更多的详细性能报表和分析报告。<a target="_blank" href="http://wiki.osapub.com/Apache">更多详细介绍!</a>
						</li>
						<li class="li_title"><a href="index.php?c=monitor&a=lighttpdindex" >Lighttpd</a></li>
						<li class="li_content">通过状态页监控Lighttpd的各项指标,获得可用率报告以及响应时间详细分析。<a target="_blank" href="http://wiki.osapub.com/Lighttpd">更多详细介绍!</a>
						</li>
						<li class="li_title"><a href="index.php?c=monitor&a=memcacheindex" >Memcache</a></li>
						<li class="li_content">监控Memcache运行时的各项性能数据。<a target="_blank" href="http://wiki.osapub.com/Memcache">更多详细介绍!</a>
						</li>
						<li class="li_title"><a href="index.php?c=monitor&a=redisindex" >Redis</a></li>
						<li class="li_content">监控Redis运行时的各项性能数据。<a target="_blank" href="http://wiki.osapub.com/Redis">更多详细介绍!</a>
						</li>
					  </ul>
				</div>
				<div class="rightcon_bottom"></div>
			<!--two-->
			<!--three-->
				<div class="rightcon_title">服务器指标监控[<a href="index.php?c=snmp&a=snmpset">配置snmp信息]</a></div>
				<div class="rightcon_mid">
					   <ul>
						<li class="li_title"><a href="index.php?c=monitor&a=customindex" >创建自定义服务器监控指标</a></li>
						<li class="li_content">通过SNMP,shell脚本监控服务器的系统性能，包括CPU使用率、内存使用率、磁盘空间使用率、网卡流量、磁盘I/O、进程数等。<a target="_blank" href="http://wiki.osapub.com">更多详细介绍!</a>
						</li>
					  </ul>
				</div>
				<div class="rightcon_bottom"></div>
			<!--three-->
			</div>
			
			<div class="height10"></div>
		<!--右侧content结束-->
		</div>
<!--右侧内容结束-->
</div>
<script type="text/javascript" src="script/common/base.js"> </script>
<!--内容结束-->
<?php include 'views/footer.php';?>
