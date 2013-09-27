
/*Table structure for table `osa_alarmconfig` */

DROP TABLE IF EXISTS `osa_alarmconfig`;

CREATE TABLE `osa_alarmconfig` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oGroupTypeId` int(20) DEFAULT NULL,
  `oConfigName` varchar(80) NOT NULL,
  `oConfigPriority` int(10) NOT NULL,
  `oConfigFileName` varchar(200) NOT NULL,
  `oRemark` varchar(500) NOT NULL,
  `oConfigTime` datetime NOT NULL,
  `oStatus` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `osa_alarmconfig` */

LOCK TABLES `osa_alarmconfig` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_alarmmsg` */

DROP TABLE IF EXISTS `osa_alarmmsg`;

CREATE TABLE `osa_alarmmsg` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oAddTime` timestamp NULL DEFAULT NULL,
  `oItemName` varchar(50) DEFAULT NULL,
  `oItemid` int(20) NOT NULL,
  `oServerip` varchar(200) NOT NULL,
  `oAlarmInfo` varchar(1000) NOT NULL,
  `oType` int(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Data for the table `osa_alarmmsg` */

LOCK TABLES `osa_alarmmsg` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_alarms` */

DROP TABLE IF EXISTS `osa_alarms`;

CREATE TABLE `osa_alarms` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oItemName` varchar(50) NOT NULL,
  `oItemClass` varchar(100) NOT NULL,
  `oItemType` varchar(100) NOT NULL,
  `oItemConfig` varchar(1000) NOT NULL,
  `oServerList` text,
  `oCheckRate` int(10) NOT NULL,
  `oAlarmNum` int(10) NOT NULL,
  `oIsRemind` varchar(10) NOT NULL,
  `oNotiObject` text NOT NULL,
  `oNotiNum` int(10) DEFAULT '0',
  `oLastStatus` varchar(20) DEFAULT '正常',
  `oAddTime` timestamp NULL DEFAULT NULL,
  `oUpdateTime` timestamp NULL DEFAULT NULL,
  `oNextCheckTime` timestamp NULL DEFAULT NULL,
  `oStartTime` timestamp NULL DEFAULT NULL,
  `oStopTime` timestamp NULL DEFAULT NULL,
  `oIsAllow` varchar(10) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Data for the table `osa_alarms` */

LOCK TABLES `osa_alarms` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_alarmstatus` */

DROP TABLE IF EXISTS `osa_alarmstatus`;

CREATE TABLE `osa_alarmstatus` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oIpid` int(20) NOT NULL,
  `oItemName` varchar(20) NOT NULL,
  `oFieldName` varchar(20) NOT NULL,
  `oCurrentStatus` varchar(20) NOT NULL,
  `oLastStatus` varchar(10) NOT NULL,
  `oNotifyNum` int(10) NOT NULL,
  `oLastUpTime` timestamp NULL DEFAULT NULL,
  `oCurrentTime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `osa_alarmstatus` */

LOCK TABLES `osa_alarmstatus` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_command` */

DROP TABLE IF EXISTS `osa_command`;

CREATE TABLE `osa_command` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `oCmdaddTime` timestamp NULL DEFAULT NULL,
  `oLastUpdateTime` datetime DEFAULT NULL,
  `oDeleteTime` datetime DEFAULT NULL,
  `oCmdTitle` varchar(200) NOT NULL,
  `oCmdText` varchar(500) NOT NULL,
  `oCmdType` tinyint(4) NOT NULL,
  `oCmdStatus` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `osa_command` */

LOCK TABLES `osa_command` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_comnowtask` */

DROP TABLE IF EXISTS `osa_comnowtask`;

CREATE TABLE `osa_comnowtask` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oRunTime` varchar(20) DEFAULT NULL,
  `oCmdType` varchar(50) NOT NULL,
  `oTasknowid` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Data for the table `osa_comnowtask` */

LOCK TABLES `osa_comnowtask` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_complantask` */

DROP TABLE IF EXISTS `osa_complantask`;

CREATE TABLE `osa_complantask` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oRunCycle` varchar(10) NOT NULL,
  `oRunDate` varchar(300) DEFAULT NULL,
  `oRunTime` varchar(20) DEFAULT NULL,
  `oCmdType` varchar(50) NOT NULL,
  `oTaskplanid` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Data for the table `osa_complantask` */

LOCK TABLES `osa_complantask` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_configbackup` */

DROP TABLE IF EXISTS `osa_configbackup`;

CREATE TABLE `osa_configbackup` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oIpArr` varchar(1000) NOT NULL,
  `oSourceFile` varchar(200) NOT NULL,
  `oBackupDir` varchar(200) NOT NULL,
  `oBackupRule` varchar(200) NOT NULL,
  `oCreateTime` timestamp NULL DEFAULT NULL,
  `oCombinCmd` varchar(2000) NOT NULL,
  `oTaskplanid` int(20) DEFAULT NULL,
  `oTasknowid` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Data for the table `osa_configbackup` */

LOCK TABLES `osa_configbackup` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_configfile` */

DROP TABLE IF EXISTS `osa_configfile`;

CREATE TABLE `osa_configfile` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oFileName` varchar(200) NOT NULL,
  `oTypeid` int(20) NOT NULL,
  `oFileLabel` varchar(200) DEFAULT NULL,
  `oFileSign` varchar(200) DEFAULT NULL,
  `oSavePath` varchar(200) NOT NULL,
  `oIsShare` tinyint(4) DEFAULT '0',
  `oIsBelong` tinyint(4) DEFAULT '0',
  `oCreateTime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Data for the table `osa_configfile` */

LOCK TABLES `osa_configfile` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_configupdate` */

DROP TABLE IF EXISTS `osa_configupdate`;

CREATE TABLE `osa_configupdate` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oIpArr` varchar(1000) NOT NULL,
  `oSourceFile` varchar(200) NOT NULL,
  `oTargetPath` varchar(200) NOT NULL,
  `oAdvance` varchar(200) NOT NULL,
  `oScriptFile` varchar(300) NOT NULL,
  `oCreateTime` timestamp NULL DEFAULT NULL,
  `oCombinCmd` varchar(2000) NOT NULL,
  `oTaskplanid` int(20) DEFAULT NULL,
  `oTasknowid` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Data for the table `osa_configupdate` */

LOCK TABLES `osa_configupdate` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_databackup` */

DROP TABLE IF EXISTS `osa_databackup`;

CREATE TABLE `osa_databackup` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oBackupName` varchar(80) NOT NULL,
  `oTaskplanid` int(20) NOT NULL,
  `oBackupIp` varchar(200) NOT NULL,
  `oCreateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `oScriptFile` varchar(300) NOT NULL,
  `oCombinCmd` varchar(2000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Data for the table `osa_databackup` */

LOCK TABLES `osa_databackup` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_devgroup` */

DROP TABLE IF EXISTS `osa_devgroup`;

CREATE TABLE `osa_devgroup` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oGroupName` varchar(50) NOT NULL,
  `oDescription` varchar(500) DEFAULT NULL,
  `oAddTime` timestamp NULL DEFAULT NULL,
  `oServerList` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Data for the table `osa_devgroup` */

LOCK TABLES `osa_devgroup` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_devinfo` */

DROP TABLE IF EXISTS `osa_devinfo`;

CREATE TABLE `osa_devinfo` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oDevName` varchar(50) NOT NULL,
  `oIpid` int(20) NOT NULL,
  `oIp` varchar(50) NOT NULL,
  `oPlace` varchar(100) DEFAULT NULL,
  `oAddress` varchar(100) DEFAULT NULL,
  `oDevPrice` double NOT NULL,
  `oDevTgPrice` double NOT NULL,
  `oTypeid` int(20) NOT NULL,
  `oUserid` int(20) NOT NULL,
  `oDevDetail` varchar(200) DEFAULT NULL,
  `oCreateTime` timestamp NULL DEFAULT NULL,
  `oShelveTime` timestamp NULL DEFAULT NULL,
  `oBusinessDes` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `osa_devinfo` */

LOCK TABLES `osa_devinfo` WRITE;

insert  into `osa_devinfo`(`id`,`oDevName`,`oIpid`,`oIp`,`oPlace`,`oAddress`,`oDevPrice`,`oDevTgPrice`,`oTypeid`,`oUserid`,`oDevDetail`,`oCreateTime`) values (1,'本机',1,'127.0.0.1','中国|福建省|厦门市','软件园',10000,1000,1,1,'','2012-06-06 13:45:45');

UNLOCK TABLES;

/*Table structure for table `osa_devtype` */

DROP TABLE IF EXISTS `osa_devtype`;

CREATE TABLE `osa_devtype` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oTypeName` varchar(50) NOT NULL,
  `oDescription` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Data for the table `osa_devtype` */

LOCK TABLES `osa_devtype` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_filetype` */

DROP TABLE IF EXISTS `osa_filetype`;

CREATE TABLE `osa_filetype` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oTypeName` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Data for the table `osa_filetype` */

LOCK TABLES `osa_filetype` WRITE;

insert  into `osa_filetype`(`id`,`oTypeName`) values (1,'上传文件');

UNLOCK TABLES;

/*Table structure for table `osa_ipgroup` */

DROP TABLE IF EXISTS `osa_ipgroup`;

CREATE TABLE `osa_ipgroup` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oGroupName` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `osa_ipgroup` */

LOCK TABLES `osa_ipgroup` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_ipinfo` */

DROP TABLE IF EXISTS `osa_ipinfo`;

CREATE TABLE `osa_ipinfo` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oIp` varchar(50) NOT NULL,
  `oGroupTypeId` varchar(50) DEFAULT NULL,
  `oIsAlive` varchar(10) DEFAULT NULL,
  `oStatus` varchar(10) NOT NULL,
  `oIsAliveNum` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `osa_ipinfo` */

LOCK TABLES `osa_ipinfo` WRITE;

insert  into `osa_ipinfo`(`id`,`oIp`,`oGroupTypeId`,`oIsAlive`,`oStatus`,`oIsAliveNum`) values (1,'127.0.0.1',NULL,'0','正常',0);

UNLOCK TABLES;

/*Table structure for table `osa_monitor` */

DROP TABLE IF EXISTS `osa_monitor`;

CREATE TABLE `osa_monitor` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oIpid` int(20) NOT NULL,
  `oCmdid` int(20) NOT NULL,
  `oMonTime` timestamp NULL DEFAULT NULL on update CURRENT_TIMESTAMP,
  `oMonText` varchar(2000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Data for the table `osa_monitor` */

LOCK TABLES `osa_monitor` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_noticonfig` */

DROP TABLE IF EXISTS `osa_noticonfig`;

CREATE TABLE `osa_noticonfig` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oServerName` varchar(50) NOT NULL,
  `oServerHost` varchar(50) NOT NULL,
  `oServerPort` int(10) NOT NULL,
  `oServerPass` varchar(50) NOT NULL,
  `oSendAddress` varchar(50) NOT NULL,
  `oSendName` varchar(50) NOT NULL,
  `oReceiveAddress` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `osa_noticonfig` */

LOCK TABLES `osa_noticonfig` WRITE;

insert  into `osa_noticonfig`(`id`,`oServerName`,`oServerPort`,`oServerPass`,`oSendAddress`,`oSendName`,`oReceiveAddress`,`oServerHost`) values (1,'openwebsa',25,'openwebsa','osa@mail.com','osa','ows@mail.com','smtp.1163.com');

UNLOCK TABLES;

/*Table structure for table `osa_operation_type` */

DROP TABLE IF EXISTS `osa_operation_type`;

CREATE TABLE `osa_operation_type` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oTypeName` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `osa_operation_type` */

LOCK TABLES `osa_operation_type` WRITE;

insert  into `osa_operation_type`(`id`,`oTypeName`) values (1,'批量文件分发'),(2,'批量文件清理'),(3,'批量文件服务器处理'),(4,'批量指令执行'),(5,'批量安装程序'),(6,'批量磁盘空间'),(7,'批量负载状态');

UNLOCK TABLES;

/*Table structure for table `osa_operations` */

DROP TABLE IF EXISTS `osa_operations`;

CREATE TABLE `osa_operations` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oTypeid` int(20) NOT NULL,
  `oTypeName` varchar(50) NOT NULL,
  `oIpArr` varchar(200) NOT NULL,
  `oCombinCmd` varchar(2000) NOT NULL,
  `oCreateTime` timestamp NULL DEFAULT NULL,
  `oTaskplanid` int(20) DEFAULT NULL,
  `oTasknowid` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Data for the table `osa_operations` */

LOCK TABLES `osa_operations` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_patch` */

DROP TABLE IF EXISTS `osa_patch`;

CREATE TABLE `osa_patch` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oPatchName` varchar(50) NOT NULL,
  `oPatchUrl` varchar(200) NOT NULL,
  `oNumkey` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `osa_patch` */

LOCK TABLES `osa_patch` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_permissions` */

DROP TABLE IF EXISTS `osa_permissions`;

CREATE TABLE `osa_permissions` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oPerName` varchar(50) NOT NULL,
  `oGname` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `osa_permissions` */

LOCK TABLES `osa_permissions` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_repository` */

DROP TABLE IF EXISTS `osa_repository`;

CREATE TABLE `osa_repository` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oRepositoryTitle` varchar(80) NOT NULL,
  `oTypeid` int(20) NOT NULL,
  `oRepositoryLabel` varchar(200) DEFAULT NULL,
  `oRepositoryText` text NOT NULL,
  `oCreateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `oIsShare` tinyint(4) DEFAULT '0',
  `oUserName` varchar(50) NOT NULL,
  `oIsPrivate` tinyint(4) default '1',
  `oIsProtect` tinyint(4) default '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Data for the table `osa_repository` */

LOCK TABLES `osa_repository` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_repository_type` */

DROP TABLE IF EXISTS `osa_repository_type`;

CREATE TABLE `osa_repository_type` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oTypeName` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Data for the table `osa_repository_type` */

LOCK TABLES `osa_repository_type` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_roles` */

DROP TABLE IF EXISTS `osa_roles`;

CREATE TABLE `osa_roles` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oRoleName` varchar(50) NOT NULL,
  `oDescription` varchar(200) DEFAULT NULL,
  `oStatus` tinyint(4) DEFAULT '0',
  `oPerArr` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `osa_roles` */

LOCK TABLES `osa_roles` WRITE;

insert  into `osa_roles`(`id`,`oRoleName`,`oDescription`,`oStatus`,`oPerArr`) values (1,'管理员','管理员拥有所有权限',1,'9,1,2,3,4,10,11,12,13,130,131,132,20,21,22,23,24,30,31,32,33,40,41,42,43,44,45,46,47,50,51,52,53,60,61,62,63,120,70,71,72,73,80,81,82,83,90,91,92,93,94,95,96,100,101,102,103');

UNLOCK TABLES;

/*Table structure for table `osa_script` */

DROP TABLE IF EXISTS `osa_script`;

CREATE TABLE `osa_script` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oScriptName` varchar(80) NOT NULL,
  `oScriptLabel` varchar(80) DEFAULT NULL,
  `oScriptPath` varchar(200) NOT NULL,
  `oIsShare` tinyint(4) DEFAULT '0',
  `oCreateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `oUpdateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Data for the table `osa_script` */

LOCK TABLES `osa_script` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_sysconfig` */

DROP TABLE IF EXISTS `osa_sysconfig`;

CREATE TABLE `osa_sysconfig` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oIsOpen` varchar(10) NOT NULL,
  `oIsSnmp` varchar(10) DEFAULT NULL,
  `oSnmpConfig` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `osa_sysconfig` */

LOCK TABLES `osa_sysconfig` WRITE;

insert  into `osa_sysconfig`(`id`,`oIsOpen`,`oIsSnmp`,`oSnmpConfig`) values (1,'1','0','');

UNLOCK TABLES;

/*Table structure for table `osa_syslog` */

DROP TABLE IF EXISTS `osa_syslog`;

CREATE TABLE `osa_syslog` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oTypeid` int(20) NOT NULL,
  `oUserName` varchar(50) NOT NULL,
  `oLogTitle` varchar(80) NOT NULL,
  `oLogText` varchar(500) NOT NULL,
  `oLogAddTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `oIsShare` tinyint(4) DEFAULT '0',
  `oLogLabel` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Data for the table `osa_syslog` */

LOCK TABLES `osa_syslog` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_syslog_cfg` */

DROP TABLE IF EXISTS `osa_syslog_cfg`;

CREATE TABLE `osa_syslog_cfg` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `oTypeText` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `osa_syslog_cfg` */

LOCK TABLES `osa_syslog_cfg` WRITE;

insert  into `osa_syslog_cfg`(`id`,`oTypeText`) values (1,'用户登录'),(2,'设备管理'),(3,'日常运维'),(4,'运营分析'),(5,'账户管理'),(6,'配置面板'),(7,'个人中心'),(8,'控制中心指令');

UNLOCK TABLES;

/*Table structure for table `osa_tasknow` */

DROP TABLE IF EXISTS `osa_tasknow`;

CREATE TABLE `osa_tasknow` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oCmdType` varchar(50) NOT NULL,
  `oStatus` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Data for the table `osa_tasknow` */

LOCK TABLES `osa_tasknow` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_tasknow_result` */

DROP TABLE IF EXISTS `osa_tasknow_result`;

CREATE TABLE `osa_tasknow_result` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oCmdType` varchar(50) NOT NULL,
  `oBatchid` int(20) DEFAULT NULL,
  `oRunTime` varchar(20) DEFAULT NULL,
  `oTaskNowid` int(20) NOT NULL,
  `oResult` text,
  `oClientip` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `osa_tasknow_result` */

LOCK TABLES `osa_tasknow_result` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_taskplan` */

DROP TABLE IF EXISTS `osa_taskplan`;

CREATE TABLE `osa_taskplan` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oRunCycle` varchar(10) NOT NULL,
  `oRunDate` varchar(300) DEFAULT NULL,
  `oRunTime` varchar(20) DEFAULT NULL,
  `oCmdType` varchar(50) NOT NULL,
  `oCreateTime` timestamp NULL DEFAULT NULL,
  `oStatus` varchar(20) NOT NULL,
  `oRunNextTime` timestamp NULL DEFAULT NULL,
  `oRunLastTime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Data for the table `osa_taskplan` */

LOCK TABLES `osa_taskplan` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_taskplan_result` */

DROP TABLE IF EXISTS `osa_taskplan_result`;

CREATE TABLE `osa_taskplan_result` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oCmdType` varchar(50) NOT NULL,
  `oBatchid` int(20) DEFAULT NULL,
  `oRunTime` varchar(20) DEFAULT NULL,
  `oTaskPlanid` int(20) NOT NULL,
  `oResult` text,
  `oClientip` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `osa_taskplan_result` */

LOCK TABLES `osa_taskplan_result` WRITE;

UNLOCK TABLES;

/*Table structure for table `osa_users` */

DROP TABLE IF EXISTS `osa_users`;

CREATE TABLE `osa_users` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oUserName` varchar(50) NOT NULL,
  `oRealName` varchar(50) NOT NULL,
  `oPassword` varchar(200) NOT NULL,
  `oRoleid` int(20) NOT NULL,
  `oEmail` varchar(50) NOT NULL,
  `oPhone` varchar(20) NOT NULL,
  `oDutyDate` varchar(200) DEFAULT NULL,
  `oDutyTime` varchar(200) DEFAULT NULL,
  `oNickName` varchar(50) DEFAULT NULL,
  `oSignature` varchar(200) DEFAULT NULL,
  `oStatus` tinyint(4) DEFAULT '0',
  `oIsLogin` tinyint(4) DEFAULT '0',
  `oShortCut` varchar(500) DEFAULT NULL,
  `oUsersNum` int(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `osa_users` */

LOCK TABLES `osa_users` WRITE;

insert  into `osa_users`(`id`,`oUserName`,`oRealName`,`oPassword`,`oRoleid`,`oEmail`,`oPhone`,`oDutyDate`,`oDutyTime`,`oNickName`,`oSignature`,`oStatus`,`oIsLogin`,`oShortCut`,`oUsersNum`) values (1,'osapub','osapub','YjgwMOnzDIJyu9FN37kYQwizIAnMQnqLwMGzX7',1,'osapub@mail.com','13212345678','Mon|Tue|Wed|Thu|Fri','08:00:00-18:00:00','osapub',NULL,1,0,'0,63,17,39',0);

UNLOCK TABLES;

DROP TABLE IF EXISTS `osa_serverinfo`;

CREATE TABLE `osa_serverinfo` (
  `id` int(10) NOT NULL auto_increment,
  `oMonitorId` int(20) default NULL,
  `oIpOrUrl` varchar(500) default NULL,
  `oStatus` varchar(10) default NULL,
  `oNotiNum` int(10) default NULL,
  `oAddTime` timestamp NULL default NULL on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
