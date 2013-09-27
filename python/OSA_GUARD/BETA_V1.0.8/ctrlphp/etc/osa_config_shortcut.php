<?php
//权限列表
$config['permissions'] = array(
	//设备操作
	'01'=>array('name'=>'查看设备','link'=>'index.php?c=device&a=listindex'),
	'02'=>array('name'=>'创建设备','link'=>'index.php?c=device&a=device_add'),
	'03'=>array('name'=>'编辑设备','link'=>'index.php?c=device&a=device_edit'),
	'04'=>array('name'=>'删除设备','link'=>'index.php?c=device&a=device_del'),

	//监控项目
	'11'=>array('name'=>'查看监控项目','link'=>'index.php?c=paint&a=distribution'),
	'12'=>array('name'=>'创建监控项目','link'=>'index.php?c=monitor&a=itemlist'),
	'13'=>array('name'=>'编辑监控项目','link'=>'index.php?c=monitor&a=monitoredit'),
	'14'=>array('name'=>'删除监控项目','link'=>'index.php?c=monitor&a=monitor_del_batch'),

	//账户管理
	'21'=>array('name'=>'查看用户列表','link'=>'index.php?c=account&a=userlists'),
	'22'=>array('name'=>'添加用户信息','link'=>'index.php?c=account&a=useradd'),
	'23'=>array('name'=>'编辑用户信息','link'=>'index.php?c=account&a=useredit'),
	'24'=>array('name'=>'删除用户信息','link'=>'index.php?c=account&a=user_del_batch'),

	//角色管理
	'31'=>array('name'=>'查看角色列表','link'=>'index.php?c=account&a=rolelists'),
	'32'=>array('name'=>'添加角色信息','link'=>'index.php?c=account&a=roleadd'),
	'33'=>array('name'=>'编辑角色信息','link'=>'index.php?c=account&a=useredit'),
	'34'=>array('name'=>'删除角色信息','link'=>'index.php?c=account&a=user_del_batch'),

	//其他相关
	'41'=>array('name'=>'SNMP采集配置','link'=>'index.php?c=snmp&a=snmpset'),
	'42'=>array('name'=>'告警通知设定','link'=>'index.php?c=alarm&a=notiset'),
	'43'=>array('name'=>'个性化设定','link'=>'index.php?c=account&a=personset')
);
