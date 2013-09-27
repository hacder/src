<!--two-->

				<div class="rightcon_title">通知对像</div>
				<div class="rightcon_mid">
					<label class="label_more">通知对像：</label>
					<div class="left">
						<input type="radio" class="radio1 notitype" name="notitype" value="0" <?php echo $itemdata[0]['oNotiUsers']=='ALL'?'checked="checked"':'';?>/>
						<label class="label_c1">任何时间都通知所有人</label>
						<input type="radio" class="radio1 notitype" name="notitype" value="1" <?php echo $itemdata[0]['oNotiUsers']!='ALL'?'checked="checked"':'';?>"/>
						<label class="label_c1">任何时间都只通知指定用户</label>
					</div>
					<div class="height10"></div>
					<div style="display:<?php echo $itemdata[0]['oNotiUsers']=='ALL'?'none':'block';?>;" id="seleuser">

						<label class="label_more">用户：<input type="hidden" class="style5" value="<?php echo $itemdata[0]['oNotiUsers']!='ALL'?$itemdata[0]['oNotiUsers']:''?>" id="users"></label>
						<div class="btn_green1 left">
							<a id="user-select"><span class="spanL">选择用户</span><span class="spanR"></span></a>
						</div>
						<label class="label_more">已选择的用户：</label>
						<div class="left125" id="show_resultuser">
							<?php if($itemdata[0]['oNotiUsers']!='ALL'){
								$users = explode(',',$itemdata[0]['oNotiUsers']);
								foreach ($users as $key){
							?>
								<div class="left width150">
									<label class="label_c1 li_users"><?php echo $key;?></label>
									<div class="window_ipclose user_close"> </div>
								</div>						
							<?php }}?>
						</div>
						<p class="clear"></p> 
					</div>					
				</div>
				<div class="rightcon_bottom"></div>
			<!--two-->
			<!--three-->
				<div class="rightcon_title">报警选项</div>
				<div class="rightcon_mid">
					<label class="label_more">检测频率：</label>
					<div class="left">
						<input type="radio" class="radio1 checkrate" value="30" name="checkrate" <?php echo $itemdata[0]['oCheckRate']==30?'checked="checked"':'';?>/>
						<label class="label_c2">30秒</label>
						<input type="radio" class="radio1 checkrate" value="60" name="checkrate" <?php echo $itemdata[0]['oCheckRate']==60?'checked="checked"':'';?>/>
						<label class="label_c2">1分钟</label>
						<input type="radio" class="radio1 checkrate" value="120" name="checkrate" <?php echo $itemdata[0]['oCheckRate']==120?'checked="checked"':'';?>/>
						<label class="label_c2">2分钟</label>
						<input type="radio" class="radio1 checkrate" value="180" name="checkrate" <?php echo $itemdata[0]['oCheckRate']==180?'checked="checked"':'';?>/>
						<label class="label_c2">3分钟</label>
						<input type="radio" class="radio1 checkrate" value="300" name="checkrate" <?php echo $itemdata[0]['oCheckRate']==300?'checked="checked"':'';?>/>
						<label class="label_c2">5分钟</label>
					</div>
					<div class="clear"></div>
					<label class="label_more">&nbsp;</label>
					<div class="left">
						<input type="radio" class="radio1 checkrate" value="600" name="checkrate" <?php echo $itemdata[0]['oCheckRate']==600?'checked="checked"':'';?>/>
						<label class="label_c2">10分钟</label>
						<input type="radio" class="radio1 checkrate" value="900" name="checkrate" <?php echo $itemdata[0]['oCheckRate']==900?'checked="checked"':'';?>/>
						<label class="label_c2">15分钟</label>
						<input type="radio" class="radio1 checkrate" value="1800" name="checkrate" <?php echo $itemdata[0]['oCheckRate']==1800?'checked="checked"':'';?>/>
						<label class="label_c2">30分钟</label>
						<input type="radio" class="radio1 checkrate" value="3600" name="checkrate" <?php echo $itemdata[0]['oCheckRate']==3600?'checked="checked"':'';?>/>
						<label class="label_c2">1小时</label>
						<input type="radio" class="radio1 checkrate" value="86400" name="checkrate" <?php echo $itemdata[0]['oCheckRate']==86400?'checked="checked"':'';?>/>
						<label class="label_c2">1天</label>
					</div>
					<div class="clear"></div>
					<label class="label_more">&nbsp;</label>
					<div class="light0">检测频率是指监控数据获取的时间间隔，间隔越小，越能及时发现故障。</div>
					<div class="clear"></div>					
					<div class="window_hr"></div>
					<label class="label_more">报警次数：</label>
					<div class="left">
						<input type="radio" class="radio1 alarmnum" value="1" name="alarmnum" <?php echo $itemdata[0]['oAlarmNum']==1?'checked="checked"':'';?>/>
						<label class="label_c2">1次</label>
						<input type="radio" class="radio1 alarmnum" value="2" name="alarmnum" <?php echo $itemdata[0]['oAlarmNum']==2?'checked="checked"':'';?>/>
						<label class="label_c2">2次</label>
						<input type="radio" class="radio1 alarmnum" value="3" name="alarmnum" <?php echo $itemdata[0]['oAlarmNum']==3?'checked="checked"':'';?>/>
						<label class="label_c2">3次</label>
						<input type="radio" class="radio1 alarmnum" value="4" name="alarmnum" <?php echo $itemdata[0]['oAlarmNum']==4?'checked="checked"':'';?>/>
						<label class="label_c2">4次</label>
						<input type="radio" class="radio1 alarmnum" value="5" name="alarmnum" <?php echo $itemdata[0]['oAlarmNum']==5?'checked="checked"':'';?>/>
						<label class="label_c2">5次</label>
					</div>
					<div class="clear"></div>
					<label class="label_more">&nbsp;</label>
					<div class="light0">报警次数是指发现故障后发送告警通知的次数，建议发送2次，发送间隔与检测频率一致。</div>	
					<div class="window_hr"></div>
					<div class="clear"></div>					
					<label class="label_more">重试几次后告警：</label>
					<div class="left">
						<input type="radio" class="radio1 repeatnum" value="0" name="repeatnum" <?php echo $itemdata[0]['oRepeatNum']==0?'checked="checked"':'';?>/>
						<label class="label_c2">0次</label>
						<input type="radio" class="radio1 repeatnum" value="1" name="repeatnum" <?php echo $itemdata[0]['oRepeatNum']==1?'checked="checked"':'';?>/>
						<label class="label_c2">1次</label>
						<input type="radio" class="radio1 repeatnum" value="2" name="repeatnum" <?php echo $itemdata[0]['oRepeatNum']==2?'checked="checked"':'';?>/>
						<label class="label_c2">2次</label>
					</div>
					<div class="clear"></div>
					<label class="label_more">&nbsp;</label>
					<div class="light0">发现故障后自动进行以上次数的重试，多次重试都失败后，才会触发告警通知。重试时间间隔与检测频率一致。</div>	
					<div class="window_hr"></div>
					<div class="clear"></div>					
					<label class="label_more">&nbsp;</label>
					<div class="left">
						<input type="checkbox" class="radio1 remind" <?php echo $itemdata[0]['oIsRemind']==1?'checked="checked"':'';?> name="remind"/>
						<label class="label_c2">恢复时提醒</label>
					</div>
					<div class="clear"></div>
					<label class="label_more">&nbsp;</label>
					<div class="light0">恢复时告警可以通知您故障处理的情况,方便不同成员之间的信息共享！</div>	
					<div class="height10"></div>
					<p class="clear"></p> 
					
				</div>
				<div class="rightcon_bottom"></div>