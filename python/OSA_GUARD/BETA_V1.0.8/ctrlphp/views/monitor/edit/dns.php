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
				<div class="placing"><span>当前位置：</span><span>日常监控</span> <span>&gt;</span> <span>创建监控项目</span></div>
			</div>
		<!--右侧title结束-->
		<!--右侧content开始-->
		<div class="edit_list">
				<?php $itemconfig = jsonDecode($itemdata[0]['oItemConfig']);?>
				<div class="rightcon_title">报警项目信息</div>
				<div class="rightcon_mid">
					<label class="label_more"><span class="red">*</span>监控项目名称：</label>
					<div class="left"><input type="text" class="style5" placeholder="请输入监控项目名称" id="itemname" value="<?php echo $itemdata[0]['oItemName'];?>"/><span class="tips" style="margin-left:6px;"></span></div>
					<div class="height10"></div>
					<label class="label_more"><span class="red">*</span>域名：</label>
					<div class="left"><input type="text" class="style5" placeholder="请输入域名" id="itemurl" value="<?php echo $itemdata[0]['oItemObject'];?>" readonly="readonly" /><span class="tips" style="margin-left:6px;"></span></div>
					<div class="clear"></div> 
					<label class="label_more">DNS查询类型：</label>
					<div class="left">
						<input type="radio" class="radio1 dns-type" value="A" name="dns-type" <?php echo $itemconfig['qtype']=='A'?"checked='checked'":"";?>/>
						<label class="label_c2">A</label>
						<input type="radio" class="radio1 dns-type" value="MX" name="dns-type" <?php echo $itemconfig['qtype']=='MX'?"checked='checked'":"";?>/>
						<label class="label_c2">MX</label>
						<input type="radio" class="radio1 dns-type" value="NS" name="dns-type" <?php echo $itemconfig['qtype']=='NS'?"checked='checked'":"";?>/>
						<label class="label_c2">NS</label>
						<input type="radio" class="radio1 dns-type" value="CNAME" name="dns-type" <?php echo $itemconfig['qtype']=='CNAME'?"checked='checked'":"";?>/>
						<label class="label_c2">CNAME</label>
						<input type="radio" class="radio1 dns-type" value="TXT" name="dns-type" <?php echo $itemconfig['qtype']=='TXT'?"checked='checked'":"";?>/>
						<label class="label_c2">TXT</label>
						<input type="radio" class="radio1 dns-type" value="ANY" name="dns-type" <?php echo $itemconfig['qtype']=='ANY'?"checked='checked'":"";?>/>
						<label class="label_c2">ANY</label>
					</div>
					<div class="clear"></div> 
					<label class="label_more">&nbsp;</label>
				
					<div class="light0">指定DNS查询类型。</div>
					<div class="clear"></div>
					<div id="dns-A" style="display:<?php echo $itemconfig['qtype']=='A'?'block':'none';?>">
						<label class="label_more">查询IP地址：</label>
						<div class="left">
							<input type="checkbox" class="radio1 dns-ip" name="dns-ip" <?php echo !empty($itemconfig['iplist'])?'checked="chedked"':'';?>/>
						</div>
						<div class="clear"></div> 
						<label class="label_more">&nbsp;</label>
						<div class="light0">你可以通过指定多个IP地址来检查相应内容中是否包含，为空则不做匹配检查。</div>
						<div class="clear"></div>
						<div class="inner_table" id="dns-ip" style="display:<?php echo empty($itemconfig['iplist'])?'none':'block';?>;">
							<div class="col2ip" style="">
								<div class="col2ip_1 head">IP地址输入</div><div class="col2ip_2 head">操作</div>
							</div>
							<?php if(empty($itemconfig['iplist'])){?>
							<div class="col2ip dns-ipli">
								<div class="col2ip_1 " >
									<span class="left"><input type="text" class="inputip ip-one"></span>
									<span class="left">&nbsp;-&nbsp;</span>
									<span class="left"><input type="text" class="inputip ip-two"></span>
									<span class="left">&nbsp;-&nbsp;</span>
									<span class="left"><input type="text" class="inputip ip-thr"></span>
									<span class="left">&nbsp;-&nbsp;</span>
									<span class="left"><input type="text" class="inputip ip-four"></span>
								</div>
								<div class="col2ip_2 "><a class="dns-ip-del">删除</a></div>
							</div>
							<?php }else{ $iplist = explode(',',$itemconfig['iplist']);
								foreach ($iplist as $key) {
									$ip = explode('.',$key);
							?>
							<div class="col2ip dns-ipli">
								<div class="col2ip_1 " >
									<span class="left"><input type="text" class="inputip ip-one" value="<?php echo $ip[0];?>" /></span>
									<span class="left">&nbsp;-&nbsp;</span>
									<span class="left"><input type="text" class="inputip ip-two" value="<?php echo $ip[1];?>" /></span>
									<span class="left">&nbsp;-&nbsp;</span>
									<span class="left"><input type="text" class="inputip ip-thr" value="<?php echo $ip[2];?>" /></span>
									<span class="left">&nbsp;-&nbsp;</span>
									<span class="left"><input type="text" class="inputip ip-four" value="<?php echo $ip[3];?>" /></span>
								</div>
								<div class="col2ip_2 "><a class="dns-ip-del">删除</a></div>
							</div>
							<?php } }?>
							<div class="append col2ip" style="display:none;">
								<div class="dns-msg" id="dns-ip-msg"></div>
							</div>
							<div class="col2ip">
								<div class="btn_gray2 left">
									<a id="dns-ip-add"><span class="spanL">增加一行</span><span class="spanR"></span></a>
								</div>
							</div>
							<div class="clear"></div>
						</div>
					</div>	
					<div class="clear"></div>
					<label class="label_more">指定DNS服务器：</label>
					<div class="left">
						<input type="checkbox" class="radio1 dns-server" name="dns-server" <?php echo !empty($itemconfig['server'])?'checked="chedked"':'';?>/>
					</div>
					<label class="label_more">&nbsp;</label>
					<div class="light0">您可以使用特定的DNS服务器来解析以上域名。如不指定，OSA监控精灵会使用个分布式检测点的本地DNS服务器</div>
					<div class="clear"></div>
					<div id="dns-server" style="display:<?php echo !empty($itemconfig['server'])?'block':'none';?>;">
						<label class="label_more">DNS服务器：</label>
						<div class="left">
							<input type="text" class="style5" id="dns-server-value" value="<?php echo $itemconfig['server'];?>"><span class="tips" style="margin-left:6px;"></span>
						</div>
						<div class="clear"></div> 
						<label class="label_more">&nbsp;</label>
						<div class="light0">请填写DNS服务器地址，可以是域名服务器提供的DNS服务器或您自己搭建的DNS服务器。<br>比如：ns.xinnetdns.com或ns1.sina.com.cn</div>
					</div>
				</div>
				<input type="hidden" value="<?php echo $itemid;?>" id="edit-itemid" />
				<div class="rightcon_bottom"></div>
			<!--one-->
				<?php include 'views/monitor/edit/common.php';?>
		</div>
		<div class="height10"></div>
		<div class="edit_submit">
			<div class="btn_green" style=""><a id="dns-edit"><span class="spanL">编辑监控项目</span><span class="spanR"></span></a></div>
			<div style="" class="btn_cancel"><a href="javascript:history.go(-1)"><span class="spanL">取消返回</span><span class="spanR"></span></a></div>
		</div>
		<!--右侧content结束-->
		</div>
<!--右侧内容结束-->
</div>
<!--内容结束-->
<script type="text/javascript" src="script/common/base.js"> </script>
<script type="text/javascript" src="script/device/common.js"></script>
<link rel="stylesheet" href="css/osa-tips.css" type="text/css" />
<link rel="stylesheet" href="css/osa-box.css" type="text/css" />
<script type="text/javascript" src="script/common/tips-base.js"></script>
<script type="text/javascript" src="script/common/osa-box.js"></script>
<script type="text/javascript" src="script/monitor/dns.js"> </script>

<?php include 'views/footer.php';?>
