	<?php include 'views/header.php';?>
	<link href="css/ui-lightness/jquery-ui-1.8.20.custom.css" rel="stylesheet" type="text/css"/>
    <script src="script/jquery-ui-1.8.20.custom.min.js"></script>
	<script type="text/javascript" src="script/device/region.js"></script>
		  <!--content开始-->
		  <div class="content">
		      <!--左边开始-->
			  <?php include 'views/device/left.php';?>
			  <!--左边结束-->
			  <!--右边开始-->
			  <div class="main_right">
			      <div class="linkmlist">
				      <div class="home_icom">
					      <span>当前位置：</span>
						  <span><a href="#">设备管理</a></span>
						  <span class="font1">-创建设备</span>
					  </div>
					  <div class="link" style="float:right;"><a href="http://bbs.osapub.com/forum.php" target="_blank">设备可以包含哪些类型？</a></div>
				  </div>
				  <!--one-->
				  <div class="rightcon_title">基本信息</div>
				  <div class="rightcon_mid">
				      <p class="pheight1"><label class="label5"><em class="em">*</em>设备名称：</label><input type="text"  class="style5" maxlength="50" id="devname" value="<?php echo $devinfo[0]['oDevName'];?>"/><span class="tips" style="margin-left:10px;"></span></p>
					  <p class="light pheight">描述设备信息，比如：shanghai_192.168.1.5_openweb.org</p>
		
				      <p class="pheight1"><label class="label5"><em class="em">*</em>IP：</label><input type="text" class="style5" id="ipname" maxlength="50" value="<?php echo $devinfo[0]['oIp'];?>" /><span class="tips" style="margin-left:10px;"></span></p>
					  <p class="light pheight">IP地址是用来管理的重要标识！</p>
					  <p class="pheight"><label class="label5">托管地区：</label>
						  <select class="select1" onChange="province1(this.value)" id="country" size="1">
						  	   <option value="中国" selected="">中国大陆</option>
				               <option value="中国香港">中国香港</option>
				               <option value="中国台湾">中国台湾</option>
				               <option value="中国澳门">中国澳门</option>
				               <option value="国外">国外</option>
						  </select>
						  <select class="select1" onChange="city1(this.value)" name="province" id="province" size="1">
						  	   <option>省份</option>
				               <option value="安徽省">安徽省</option>
				               <option value="北京">北京</option>
				               <option value="福建省">福建省</option>
				               <option value="甘肃省">甘肃省</option>
				               <option value="广东省" selected="">广东省</option>
				               <option value="广西省">广西省</option>
				               <option value="贵州省">贵州省</option>
				               <option value="海南省">海南省</option>
				               <option value="河北省">河北省</option>
				               <option value="河南省">河南省</option>
				               <option value="黑龙江省">黑龙江省</option>
				               <option value="湖北省">湖北省</option>
				               <option value="湖南省">湖南省</option>
				               <option value="吉林省">吉林省</option>
				               <option value="江苏省">江苏省</option>
				               <option value="江西省">江西省</option>
				               <option value="辽宁省">辽宁省</option>
				               <option value="内蒙古自治区">内蒙古自治区</option>
				               <option value="宁夏回族自治区">宁夏回族自治区</option>
				               <option value="青海省">青海省</option>
				               <option value="山东省">山东省</option>
				               <option value="山西省">山西省</option>
				               <option value="陕西省">陕西省</option>
				               <option value="上海">上海</option>
				               <option value="四川省">四川省</option>
				               <option value="天津">天津</option>
				               <option value="西藏自治区">西藏自治区</option>
				               <option value="新疆维吾尔自治区">新疆维吾尔自治区</option>
				               <option value="云南省">云南省</option>
				               <option value="浙江省">浙江省</option>
				               <option value="重庆">重庆</option>
						  </select>
						  <select class="select1" id="city" name="city" size="1">
						  	   <option>地级市</option>
				               <option value="潮州市">潮州市</option>
				               <option value="潮阳市">潮阳市</option>
				               <option value="东莞市">东莞市</option>
				               <option value="佛山市">佛山市</option>
				               <option value="广州市">广州市</option>
				               <option value="河源市">河源市</option>
				               <option value="惠州市">惠州市</option>
				               <option value="江门市">江门市</option>
				               <option value="揭阳">揭阳</option>
				               <option value="茂名市">茂名市</option>
				               <option value="梅州市">梅州市</option>
				               <option value="清远市">清远市</option>
				               <option value="汕头市">汕头市</option>
				               <option value="汕尾市">汕尾市</option>
				               <option value="韶关市">韶关市</option>
				               <option value="深圳市" selected="">深圳市</option>
				               <option value="顺德">顺德</option>
				               <option value="阳江市">阳江市</option>
				               <option value="云浮">云浮</option>
				               <option value="湛江市">湛江市</option>
				               <option value="肇庆市">肇庆市</option>
				               <option value="中山市">中山市</option>
				               <option value="珠海">珠海</option>
				               <option value="恩平市">恩平市</option>
						  </select>
					  </p>
					  <div class="clear"></div>
					  <p class="pheight"><label class="label5">详细地址：</label><input type="text" class="style5" id="address" maxlength="100" value="<?php echo $devinfo[0]['oAddress'];?>"/></p>
					  <p class="pheight"><label class="label5">类型：</label>
						  <select class="select1" id="selecttype">
						  		<option value='0'>请选择类型</option>
						  		<?php foreach ($type as $key) {?>
					      			<option value='<?php echo $key['id'];?>' <?php echo $key['id']==$devinfo[0]['oTypeid']?"selected='selected'":""?>><?php echo $key['oTypeName'];?></option>
					      		<?php }?>
						  </select>
						  <span id="typeadd" class="link"><a href="#">创建新类型</a></span>
					  </p>
					  <p class="light pheight">类型是方便您查询服务器的重要途径！</p>
					  <p class="pheight">
					  		<label class="label5">服务上架时间：</label>
					  		<input type="text" class="style5 hui" id="add_time" maxlength="100" readonly="true" value="<?php echo $devinfo[0]['oShelveTime'];?>"/>
					  </p>
					  <p class="pheight">
					  		<label class="label5">业务描述：</label>
					  		<input type="text" class="style5" id="business_des" maxlength="500" value="<?php echo $devinfo[0]['oBusinessDes'];?>"/>
					  </p>
					  <p class="light pheight">描述所在服务器的主要业务，可以有多个业务关键字</p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--one-->
				  <!--two-->
				  <div class="rightcon_title">其它信息</div>
				  <div class="rightcon_mid">
				      <p class="pheight"><label class="label5">设备价格：</label><input type="text" class="style5" id="devprice" value="<?php echo $devinfo[0]['oDevPrice'];?>"/></p>
				      <p class="light pheight">设备价格是指在采购设备时所花费的资金，单位：元</p>
				      <p class="pheight"><label class="label5">托管价格：</label><input type="text" class="style5" id="tgprice" value="<?php echo $devinfo[0]['oDevTgPrice'];?>"/></p>
					  <p class="light pheight">托管价格是指在每月设备托管产生的费用，单位：元，例如：300元/月</p>
					  <p><label class="label5">设备详情：</label><span class="light" style="margin-left:0px;">可用于记录设备详细情况，比如CPU、内存、采购联系人等。</span></p>
					  <p class="light"><textarea class="textarea1" id="detail" name="textarea1"><?php  echo htmlspecialchars_decode($devinfo[0]['oDevDetail']);?></textarea></p>	
				  
				  </div>
				  <div class="rightcon_bottom"></div>
				  <p><input type="button" class="enter specibut" id="submit" value="确认添加" /><input type="button" class="enter specibut" value="取消返回" onclick="window.location='index.php?c=device&a=index'" /></p>
				  <!--two-->
			  </div>
			  <!--右边结束-->
		  </div><!--content结束-->
  
  <!-- 创建新分组 -->
  <div id="shadow"></div>
  <div class="window " id="addgroup" style="display:none;">
	    <div class="window_title">
		    <span class="window_text">创建新分组</span>
			<input type="button" class="windbutton" />
		</div>
		<div class="window_con">
				 <p><label class="label5"><em class="em">*</em>分组名称：</label><input type="text" class="style5" id="groupname" maxlength="50"/></p>
				 <div class="clear"></div>
				 <br />
				 <p><label class="label5">分组描述：</label><textarea class="textarea1" id="description"></textarea></p>
				 <p class="light">把有相同特性的服务器，比如某一个集群，某种跑了相同应用的服务器，比如：安装了MYSQL的服务器 放到一个分组，方便筛选和管理!。</p>
				 <div class="center"><input type="button" class="updatebut specibut" value="确定" id="devgroup" /><input type="button" class="updatebut specibut cancel" value="取消" /></div>
		</div>
		</div>
	</div>
	<!--创建新分组结束-->
	<!--创建新类型-->
	<div class="window" id="addtype" style="display:none;">
	    <div class="window_title">
		    <span class="window_text">创建新类型</span>
			<input type="button" class="windbutton" />
		</div>
		<div class="window_con">
				 <p><label class="label5"><em class="em">*</em>类型名称：</label><input type="text" class="style5" id="typename" /></p>
				 <div class="clear"></div>
				 <br />
				 <div class="center"><input type="button" class="updatebut specibut" value="确定" id="devtype" /><input type="button" class="updatebut specibut cancel" value="取消" /></div>
		</div>
	</div>
	<!--创建新类型结束-->
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="ckeditor/ckfinder/ckfinder.js"></script>
	<script type="text/javascript" src="script/common/comlayer.js"></script>
	<script type="text/javascript" src="script/device/add.js"></script>
	<script type="text/javascript">
		var region = '<?php echo $country;?>';
		var pro = '<?php echo $province;?>';
		var ci = '<?php echo $city;?>';
		var editurl = 'index.php?c=device&a=edit&id=<?php echo $devinfo[0]['id'];?>';
		//初始化国家-省-市 三级联动
		province1(region);
		city1(pro);
		document.getElementById("country").value=region;
		document.getElementById("province").value=pro;
		document.getElementById("city").value=ci;
		var editor = CKEDITOR.replace( 'textarea1',
		{
			filebrowserBrowseUrl : 'ckeditor/ckfinder/ckfinder.html',
			filebrowserImageBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Images',
			filebrowserFlashBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Flash',
			filebrowserUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
			filebrowserImageUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
			filebrowserFlashUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'	
		});
	</script>		
<?php include 'views/footer.php';?>
