				  <div class="rightcon_title">报警选项</div>
				  <div class="rightcon_mid">
			    	<P>
		      			  <label class="label6">检测频率：</label>
						  <span class="style13"><input type="radio" class="style11 checkrate" value="30" name="checkrate" />30秒</span>
						  <span class="style13"><input type="radio" class="style11 checkrate" value="60" name="checkrate" />1分钟</span>
						  <span class="style13"><input type="radio" class="style11 checkrate" value="120" name="checkrate" />2分钟</span>
						  <span class="style13"><input type="radio" class="style11 checkrate" value="180" name="checkrate" />3分钟</span>
						  <span class="style13"><input type="radio" class="style11 checkrate" value="300" name="checkrate" />5分钟</span>
					</p>
					<p>
						  <label class="label6"></label>
						  <span class="style13"><input type="radio" class="style11 checkrate" value="600" name="checkrate"  />10分钟</span>
						  <span class="style13"><input type="radio" class="style11 checkrate" value="900" name="checkrate" />15分钟</span>
						  <span class="style13"><input type="radio" class="style11 checkrate" value="1800" name="checkrate" />30分钟</span>
						  <span class="style13"><input type="radio" class="style11 checkrate" value="3600" name="checkrate" />1小时</span>
						  <span class="style13"><input type="radio" class="style11 checkrate" value="86400" name="checkrate" />1天</span>
					  </P>
					  <p class="pheight1 light1">检测频率是指每隔多久对监控项目检测一次，请根据业务需求选择合适的检测频率。</p>
					  <p class="pheight" style="margin-top:10px;">
					      <label class="label6">告警次数：</label>
						  <span class="style13"><input type="radio" class="style11 checknum" value="1" name="checknum" />1次</span>
						  <span class="style13"><input type="radio" class="style11 checknum" value="2" name="checknum" />2次</span>
						  <span class="style13"><input type="radio" class="style11 checknum" value="3" name="checknum" />3次</span>
						  <span class="style13"><input type="radio" class="style11 checknum" value="4" name="checknum" />4次</span>
						  <span class="style13"><input type="radio" class="style11 checknum" value="5" name="checknum" />5次</span>
					  </p>
					  <p class="pheight1 light1">告警次数是指出现异常时通知的次数，防止告警信息未能及时收取，OSA建议您设置2次为佳！</p>
					  <p class="pheight light1"><input type="checkbox" checked="checked" class="style11 remind" name="remind" value="1" />恢复时提醒</p>
				  </div>
				  <div class="rightcon_bottom"></div>
				  <div class="rightcon_title">通知对象</div>
				  <div class="rightcon_mid">
				      <P class="pheight">
					      <label class="label6">通知对象：</label>
						  <span class="style8"><input type="radio" class="style11 notitype" name="notitype" checked="checked" value="0"/>任何时间都通知所有人</span>
						  <span class="style8"><input type="radio" class="style11 notitype" name="notitype"  value="1"/>任何时间都只通知指定用户</span>
					  </P>
				      <div class="pheight" style="display:none" id="seleuser">
					      <label class="label6"></label>
						  <input type="text" class="style5" id="users" readonly="true" value="" /><input type="button" class="updatebut" value="选择用户" id="showusers"/>
					  	  <div class="selected" style="">
						      <div style="float:left;"><label class="label6">已选择的用户：</label></div>
						      <div style="width:630px;float:left;" id="showselectuser">
							  </div>
						  </div>
					  </div>
					  
				  </div>
				  <div class="rightcon_bottom"></div>