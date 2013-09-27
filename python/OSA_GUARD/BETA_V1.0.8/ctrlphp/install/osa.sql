SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `osa_authkey`
-- ----------------------------
DROP TABLE IF EXISTS `osa_authkey`;
CREATE TABLE `osa_authkey` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oAuthKey` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_authkey
-- ----------------------------

-- ----------------------------
-- Table structure for `osa_authkey_record`
-- ----------------------------
DROP TABLE IF EXISTS `osa_authkey_record`;
CREATE TABLE `osa_authkey_record` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oKeyid` int(20) NOT NULL,
  `oClientIp` varchar(100) NOT NULL,
  `oStatus` varchar(50) DEFAULT NULL,
  `oErrorMsg` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_authkey_record
-- ----------------------------

-- ----------------------------
-- Table structure for `osa_collect_alarm`
-- ----------------------------
DROP TABLE IF EXISTS `osa_collect_alarm`;
CREATE TABLE `osa_collect_alarm` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oIpid` int(20) NOT NULL,
  `oAlarmText` text,
  `oAlarmTime` timestamp NULL DEFAULT NULL,
  `oAlarmLevel` tinyint(4) DEFAULT NULL,
  `oAlarmType` varchar(50) DEFAULT NULL,
  `oSnapShot` varchar(200) DEFAULT NULL,
  `oIsNotice` tinyint(4) DEFAULT '0',
  `oIsRead` tinyint(4) DEFAULT '0',
  `oFaultTime` timestamp NULL DEFAULT NULL,
  `oIsNoticeNext` tinyint(4) DEFAULT '0',
  `oNoticeNextTime` timestamp NULL DEFAULT NULL,
  `oNoticeNextUsers` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8373 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_collect_alarm
-- ----------------------------

-- ----------------------------
-- Table structure for `osa_collect_data`
-- ----------------------------
DROP TABLE IF EXISTS `osa_collect_data`;
CREATE TABLE `osa_collect_data` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oIpid` int(20) NOT NULL,
  `oCollectTime` timestamp NULL DEFAULT NULL,
  `oCollectData` text,
  `oReplayTime` double DEFAULT NULL,
  `oStatus` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=60446 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_collect_data
-- ----------------------------

-- ----------------------------
-- Table structure for `osa_device`
-- ----------------------------
DROP TABLE IF EXISTS `osa_device`;
CREATE TABLE `osa_device` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oDevName` varchar(50) NOT NULL,
  `oIp` varchar(100) NOT NULL,
  `oIpid` int(20) NOT NULL,
  `oTypeName` varchar(100) DEFAULT NULL,
  `oTypeid` int(20) DEFAULT NULL,
  `oWorkDes` varchar(500) DEFAULT NULL,
  `oEngineRoom` varchar(100) DEFAULT NULL,
  `oRoomid` int(20) DEFAULT NULL,
  `oShelveTime` timestamp NULL DEFAULT NULL,
  `oDevLabel` varchar(500) DEFAULT NULL,
  `oLabelid` int(20) DEFAULT NULL,
  `oDevPrice` double DEFAULT '0',
  `oDevTgPrice` double DEFAULT '0',
  `oDevDetail` text,
  `oCreateTime` timestamp NULL DEFAULT NULL,
  `oIsStop` tinyint(2) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_device
-- ----------------------------
INSERT INTO `osa_device` VALUES ('86', '本机监控', '127.0.0.1', '89', '服务器', '8', null, '内网机房', '7', null, null, null, '0', '0', null, null, '0');

-- ----------------------------
-- Table structure for `osa_devlabel`
-- ----------------------------
DROP TABLE IF EXISTS `osa_devlabel`;
CREATE TABLE `osa_devlabel` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oLabelName` varchar(100) NOT NULL,
  `oLabelRate` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_devlabel
-- ----------------------------

-- ----------------------------
-- Table structure for `osa_devroom`
-- ----------------------------
DROP TABLE IF EXISTS `osa_devroom`;
CREATE TABLE `osa_devroom` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oRoomName` varchar(100) NOT NULL,
  `oRoomDes` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_devroom
-- ----------------------------
INSERT INTO `osa_devroom` VALUES ('7', '内网机房', null);

-- ----------------------------
-- Table structure for `osa_devtype`
-- ----------------------------
DROP TABLE IF EXISTS `osa_devtype`;
CREATE TABLE `osa_devtype` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oTypeName` varchar(100) NOT NULL,
  `oTypeDes` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_devtype
-- ----------------------------
INSERT INTO `osa_devtype` VALUES ('8', '服务器', '');

-- ----------------------------
-- Table structure for `osa_email_config`
-- ----------------------------
DROP TABLE IF EXISTS `osa_email_config`;
CREATE TABLE `osa_email_config` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oServerName` varchar(50) NOT NULL,
  `oServerHost` varchar(50) NOT NULL,
  `oServerPort` int(10) NOT NULL,
  `oServerPass` varchar(50) NOT NULL,
  `oSendAddress` varchar(100) NOT NULL,
  `oSendName` varchar(50) NOT NULL,
  `oReceiveAddress` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_email_config
-- ----------------------------
INSERT INTO `osa_email_config` VALUES ('1', 'osamail3@osapub.com', 'smtp.osapub.com', '25', 'osapub1688', 'osamail3@osapub.com', 'OsaRobot', 'osapub@163.com');

-- ----------------------------
-- Table structure for `osa_global_config`
-- ----------------------------
DROP TABLE IF EXISTS `osa_global_config`;
CREATE TABLE `osa_global_config` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oEmailSet` varchar(50) NOT NULL,
  `oInfoType` varchar(50) NOT NULL,
  `oAcceptIp` text,
  `oReportType` varchar(50) NOT NULL,
  `oCloseType` varchar(50) NOT NULL,
  `oUserid` int(20) NOT NULL,
  `oUserName` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_global_config
-- ----------------------------

-- ----------------------------
-- Table structure for `osa_gtalk_config`
-- ----------------------------
DROP TABLE IF EXISTS `osa_gtalk_config`;
CREATE TABLE `osa_gtalk_config` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oNoticeGtalk` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_gtalk_config
-- ----------------------------

-- ----------------------------
-- Table structure for `osa_ipinfo`
-- ----------------------------
DROP TABLE IF EXISTS `osa_ipinfo`;
CREATE TABLE `osa_ipinfo` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oIp` varchar(100) NOT NULL,
  `oIsAlive` varchar(10) DEFAULT '1',
  `oStatus` varchar(10) DEFAULT '正常',
  `oIsStop` tinyint(2) DEFAULT '0',
  `oFaultTime` timestamp NULL DEFAULT NULL,
  `oNotifiedNum` int(2) DEFAULT '0',
  `oNotiNum` int(2) DEFAULT '0',
  `oIsEmail` varchar(5) DEFAULT '0',
  `oOsType` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=92 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_ipinfo
-- ----------------------------
INSERT INTO `osa_ipinfo` VALUES ('89', '127.0.0.1', '1', '正常', '0', '0000-00-00 00:00:00', '0', '0', '0', 'Linux');


-- ----------------------------
-- Table structure for `osa_monitor_alarm`
-- ----------------------------
DROP TABLE IF EXISTS `osa_monitor_alarm`;
CREATE TABLE `osa_monitor_alarm` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oItemid` int(20) NOT NULL,
  `oMonName` varchar(100) DEFAULT NULL,
  `oAlarmText` text,
  `oAlarmTime` timestamp NULL DEFAULT NULL,
  `oAlarmLevel` tinyint(4) NOT NULL,
  `oAlarmType` varchar(20) DEFAULT NULL,
  `oSnapShot` varchar(200) DEFAULT NULL,
  `oIsNotice` tinyint(4) DEFAULT '0',
  `oIsRead` tinyint(4) DEFAULT '0',
  `oFaultTime` double DEFAULT NULL,
  `oIsNoticeNext` tinyint(4) DEFAULT '0',
  `oNoticeNextTime` timestamp NULL DEFAULT NULL,
  `oNoticeNextUsers` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=85384 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_monitor_alarm
-- ----------------------------
-- ----------------------------
-- Table structure for `osa_monitor_record`
-- ----------------------------
DROP TABLE IF EXISTS `osa_monitor_record`;
CREATE TABLE `osa_monitor_record` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oItemid` int(20) NOT NULL,
  `oMonTime` timestamp NULL DEFAULT NULL,
  `oMonResult` text,
  `oReplayTime` double DEFAULT NULL,
  `oStatus` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=468778 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `osa_monitors`
-- ----------------------------
DROP TABLE IF EXISTS `osa_monitors`;
CREATE TABLE `osa_monitors` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oItemName` varchar(100) NOT NULL,
  `oItemObject` varchar(200) NOT NULL,
  `oItemType` varchar(50) NOT NULL,
  `oCheckRate` int(10) NOT NULL,
  `oAlarmNum` int(10) NOT NULL,
  `oRepeatNum` int(10) NOT NULL,
  `oIsRemind` tinyint(4) NOT NULL,
  `oItemConfig` varchar(1000) NOT NULL,
  `oNotiUsers` varchar(1000) NOT NULL,
  `oAddTime` timestamp NULL DEFAULT NULL COMMENT '项目添加时间，可用来计算下次监控时间',
  `oUpdateTime` timestamp NULL DEFAULT NULL,
  `oNextCheckTime` timestamp NULL DEFAULT NULL,
  `oStartTime` timestamp NULL DEFAULT NULL COMMENT '开始时间，可用来计算下次监控时间',
  `oStopTime` timestamp NULL DEFAULT NULL COMMENT '项目暂停时间',
  `oIsStop` tinyint(4) DEFAULT '0',
  `oTriggerAction` varchar(500) DEFAULT NULL COMMENT '关联操作',
  `oStatus` tinyint(2) DEFAULT '1' COMMENT '状态',
  `oFaultTime` timestamp NULL DEFAULT NULL COMMENT '错误开始时间，用来计算故障时间',
  `oNotifiedNum` int(2) DEFAULT '0',
  `oNotiNum` int(2) DEFAULT '0',
  `oRepeatedNum` int(2) DEFAULT '0',
  `oIsEmail` varchar(5) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `osa_msn_config`
-- ----------------------------
DROP TABLE IF EXISTS `osa_msn_config`;
CREATE TABLE `osa_msn_config` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oNoticeMsn` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_msn_config
-- ----------------------------

-- ----------------------------
-- Table structure for `osa_notice_method`
-- ----------------------------
DROP TABLE IF EXISTS `osa_notice_method`;
CREATE TABLE `osa_notice_method` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oIsEmail` tinyint(4) DEFAULT NULL,
  `oIsSms` tinyint(4) DEFAULT NULL,
  `oIsMsn` tinyint(4) DEFAULT NULL,
  `oIsGtalk` tinyint(4) DEFAULT NULL,
  `oMnumItem` int(5) DEFAULT NULL,
  `oMnumIp` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_notice_method
-- ----------------------------
INSERT INTO `osa_notice_method` VALUES ('1', '1', '0', '0', '0', '6', '10');

-- ----------------------------
-- Table structure for `osa_roles`
-- ----------------------------
DROP TABLE IF EXISTS `osa_roles`;
CREATE TABLE `osa_roles` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oRoleName` varchar(50) NOT NULL,
  `oStatus` tinyint(4) DEFAULT '0',
  `oPerStr` varchar(500) DEFAULT NULL,
  `oRoleDes` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_roles
-- ----------------------------
INSERT INTO `osa_roles` VALUES ('10', '管理员', '0', '01,02,03,04,11,12,13,14,21,22,23,24,31,32,33,34,41,42,43', '');

-- ----------------------------
-- Table structure for `osa_sms_config`
-- ----------------------------
DROP TABLE IF EXISTS `osa_sms_config`;
CREATE TABLE `osa_sms_config` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oNoticeUsers` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_sms_config
-- ----------------------------

-- ----------------------------
-- Table structure for `osa_snmp`
-- ----------------------------
DROP TABLE IF EXISTS `osa_snmp`;
CREATE TABLE `osa_snmp` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oSnmpName` varchar(200) NOT NULL,
  `oSnmpPort` int(5) NOT NULL,
  `oSnmpKey` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_snmp
-- ----------------------------
INSERT INTO `osa_snmp` VALUES ('1', 'v2c', '161', 'public');

-- ----------------------------
-- Table structure for `osa_table_manage`
-- ----------------------------
DROP TABLE IF EXISTS `osa_table_manage`;
CREATE TABLE `osa_table_manage` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `oTableName` varchar(100) NOT NULL,
  `oCreateTime` timestamp NULL DEFAULT NULL,
  `oTableType` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_table_manage
-- ----------------------------

-- ----------------------------
-- Table structure for `osa_trigger_result`
-- ----------------------------
DROP TABLE IF EXISTS `osa_trigger_result`;
CREATE TABLE `osa_trigger_result` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oItemid` int(20) NOT NULL,
  `oTriggerTime` timestamp NULL DEFAULT NULL,
  `oExecResult` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_trigger_result
-- ----------------------------

-- ----------------------------
-- Table structure for `osa_upload_file`
-- ----------------------------
DROP TABLE IF EXISTS `osa_upload_file`;
CREATE TABLE `osa_upload_file` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oFileName` varchar(200) NOT NULL,
  `oFileSize` varchar(200) DEFAULT NULL,
  `oRealPath` varchar(500) NOT NULL,
  `oUploadUser` varchar(50) DEFAULT NULL,
  `oCreateTime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_upload_file
-- ----------------------------

-- ----------------------------
-- Table structure for `osa_users`
-- ----------------------------
DROP TABLE IF EXISTS `osa_users`;
CREATE TABLE `osa_users` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `oRoleid` int(20) NOT NULL,
  `oUserName` varchar(50) NOT NULL,
  `oRealName` varchar(50) DEFAULT NULL,
  `oPassword` varchar(200) NOT NULL,
  `oEmail` varchar(100) NOT NULL,
  `oPhone` varchar(20) NOT NULL,
  `oStatus` tinyint(4) DEFAULT '0',
  `oShortCut` varchar(500) DEFAULT NULL,
  `oUsersNum` int(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of osa_users
-- ----------------------------
INSERT INTO `osa_users` VALUES ('3', '10', 'osapub', 'osapub', 'YjgwMOnzDIJyu9FN37kYQwizIAnMQnqLwMGzX7', 'osapub@163.com', '18950006955', '0', '01,02,03,04,05,11,12,13,14,15,21,22,23,24,25', '130');
