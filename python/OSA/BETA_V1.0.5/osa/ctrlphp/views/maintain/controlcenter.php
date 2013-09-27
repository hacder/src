 <?php include 'views/header.php';?>
 		<!--content开始-->
 <script type="text/javascript">
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval("window.location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

var oldValue = "";
function cmdso(cmd){	
	var re= /^[A-Za-z0-9]+$/ ;
	if(cmd == ""){
		document.getElementById('tishi').innerHTML = '常用命令提示，单击可以选择命令到输入框！';
	}
	if(oldValue != cmd){
		if(re.test(cmd)){
		var xmlObj;     //定义XMLHttpRequest对象
	    if(window.ActiveXObject){     //如果是浏览器支持ActiveXObjext则创建ActiveXObject对象
	      xmlObj = new ActiveXObject("Microsoft.XMLHTTP");
	    }else if(window.XMLHttpRequest){     //如果浏览器支持XMLHttpRequest对象则创建XMLHttpRequest对象
		    xmlObj = new XMLHttpRequest();
	    }		
	    xmlObj.onreadystatechange = callBackFun;    //指定回调函数
	    xmlObj.open('GET', 'index.php?c=maintain&a=getCmdinfo&cmdstr='+cmd, true);     //使用GET方法调用test.php并传递username参数的值
	    xmlObj.send(null);     //不发送任何数据，因为数据已经使用请求URL通过GET方法发送
		
	    function callBackFun(){     //回调函数
	        if(xmlObj.readyState == 4 && xmlObj.status == 200){   //如果服务器已经传回信息并没发生错误
			    var gettext=xmlObj.responseText;    //得到返回值
				if(gettext != ""){				
				document.getElementById('tishi').innerHTML=gettext;	
				}			
				  
		    }
	    }
	    }
	}
}
function Change(str){
	var re= /^[A-Za-z0-9]+$/ ;
	if(re.test(str)){
		document.getElementById('cmd').value=str;
	}
}
function enterIn(event){
  var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
  if (keyCode == 13) {    
  	document.getElementById('cmd').value=document.getElementById('cmd').value.toLowerCase();  
	returninfo();	
	document.cmdform.submit();
  }
}

function returninfo(){
	
	document.getElementById('returninfo').scrollTop=document.getElementById('returninfo').scrollHeight;	    
	document.getElementById('cmd').focus();
	}
</script>
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
						  <span class="font1">-控制中心</span>
					  </div>
					  <div class="back">
					      <a href="#" onclick="javascript:history.go(-1);"><b>返回上页</b></a>
					  </div>
				  </div>
				  <div class="clear"></div>
				  <!--one-->
				  <div class="rightcon_title">快捷功能</div>
				  <div class="rightcon_mid">
				  		<?php echo $cmddoinfo; ?>
					  <div class="table_setup2">
						  <table cellspacing="0" cellpadding="0" width="100%">
							<tr>
							     <td>服务器IP地址：</td>
								 <td style="text-align:left; padding:0 3px;"><?php echo $ip;?></td>
							</tr> 
							<tr>							
								<td>服务操作</td>
								<td style="text-align:left; padding:0 3px">
								<form name="sevfomr" method="post" action="<?php echo $url;?>">
								    <label>请你选择要操作的服务：</label>
								    <select style="width:90px;" name="sev" id="sev">
								    	<option>请选择</option>
								    	<?php foreach ($sevinfo as $sev_value) {?>
								    	<option><?php echo $sev_value;?></option>
								    	<?php }?>
								    </select>
								    <label>请你选择要操作的类型：</label>
								    <select style="width:90px;" name="dotype" id="dotype">
								    	<option>请选择</option>
								    	<option>start</option>
								    	<option>stop</option>
								    	<option>restart</option>
								    </select>
									<input type="submit" name="sevsubmit" class="button3" value="确定" />
									</form>
								</td>
							</tr> 
							 <form name="mainform"  method="post" action="<?php echo $url;?>">
							<tr>
								<td>系统功能</td>
								<td style="text-align:left; padding:0 3px">
									<?php foreach ($systeminfo as $sys_key => $sys_value){?>
										<input type="submit" class="button3"  name="<?php echo $sys_key;?>" value="<?php echo $sys_value;?>"/>
									<?php }?>
									<a href="#" title="系统功能"><img src="images/hxselp.png" /></a>
								</td>
							</tr> 
							<tr>
								<td>程序更新</td>
								<td style="text-align:left; padding:0 3px">
									<?php foreach ($updatesinfo as $up_key => $up_value){?>
										<input type="submit" class="button3"  name="<?php echo $up_key;?>" value="<?php echo $up_value;?>"/>
									<?php }?>
 								</td>
							</tr> 
							<tr>
								<td>配置文件</td>
								<td style="text-align:left; padding:0 3px"><label>请选择配置文件进行更改：</label>
									<select style="width:90px;" name="CjumpMenu" id="CjumpMenu" onChange="MM_jumpMenu('parent',this,0)">
										<option>请选择</option>
										<?php foreach ($configinfo as $con_key => $con_value) {?>
								    	<option value="index.php?c=maintain&a=serverconfig&name=<?php echo $con_key;?>&id=<?php echo $id;?>" ><?php echo $con_key;?></option>
								    	<?php }?>
									</select>
									<input type="hidden" value="allmidsubmit" name="midsubmit">
								</td>
							</tr> 
							<tr>
								<td>其它功能</td>
								<td style="text-align:left; padding:0 3px">
									<?php foreach ($otherinfo as $oth_key => $oth_value){?>
										<input type="submit" class="button3"  name="<?php echo $oth_key;?>" value="<?php echo $oth_value;?>"/>
									<?php }?>
								</td>
							</tr> 
							</form>
						  </table>
				     </div>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--one-->
				  <!--two-->
				  <div class="rightcon_title">执行shell命令</div>
				  <div class="rightcon_mid">
					  <div class="table_setup2">
						  <table cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<td style="background:#E5F5FD; height:30px; padding:0 5px;">
								    <span class="left">Shell当前路径:<?php echo $shellpath;?></span>
									<a class="right" href="index.php?c=maintain&a=cmdshell&id=<?php echo $id;?>">[全屏ajax版命令行] </a>
								</td>
							</tr> 
							<form name="cmdform" method="post" action="">
							<tr>
								<td style="text-align:left" width="98%">
								<textarea rows="10" name="returninfo" class="texta2" readonly="readonly" id="returninfo" style="font-size:15px; border:none; margin:0px; width:98%"><?php echo $returninfo;?>
								</textarea>
								<p style="font-size:15px; border:none; margin:0px;width:98%; text-align:left">[root@ows_<?php echo $ip;?>]#																
									<input name="cmd" id="cmd" type="text" style=" font-size:15px; border:none; margin:0px" size="50" onkeyup="this.value.toLowerCase();cmdso(this.value.toLowerCase());">									
								</p>
								</td>
							</tr> 
							</form>
							<tr>
	         					<td style="text-align:left;" id="tishi" name="tishi">        
								</td>
    						</tr>
							<tr>
							    <td style="background:#E5F5FD; height:30px; padding:0 5px;"><span class="left">显示最近执行了的命令：</span><a href="index.php?c=maintain&a=getHistoryText" class="right" target="_blank">[获取命令列表]</a> <a href="http://linux.chinaitlab.com/Special/linuxcom/" target="_blank" class="right" >[LINUX常用命令] </a></td>
							</tr>
							<tr>
							    <td>
									<textarea rows="10"  style="font-size:15px; border:none; margin:0px; width:98%"><?php echo $history;?>
									</textarea>
								</td>
							</tr>
						  </table>
				     </div>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <!--two-->
			  </div>
			  <!--右边结束-->
			  <script language="javascript">
				document.getElementById('returninfo').scrollTop=document.getElementById('returninfo').scrollHeight;							    
				document.getElementById('cmd').focus();
			</script>
		  </div><!--content结束-->
		  <?php include 'views/footer.php';?>
		  