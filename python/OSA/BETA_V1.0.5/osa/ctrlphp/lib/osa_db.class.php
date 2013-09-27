<?php
/*
 * Description:封装数据库的增删查改操作
 * Author: ows开源团队
 * Date: 2011-05-05
 */


class osa_db
{
	private $dbh;				// 保存类实例
	private $db;				// 保存类连接对象
	private $dsn;				// 存储PDO需要的连接参数
	private $db_user;			// 存储数据库用户
	private $db_pwd;			// 存储数据库用户密码
	private $db_charset	;		// 存储数据库校验码
	private $prefix;			//前缀
	/**
	 * 构造方法 : 初始化PDO连接 , 其中的参数可以写一个文件里面然后再引入进来
	 * @param $dsn PDO连接所需要的参数地址，包换：主机，数据库名
	 * @param $db_user 数据库用户名
	 * @param $db_pwd 数据库用户密码
	 * @param $db_charset 数据库校验码,默认值utf8
	 * @return 调用成员方法connect()返回一个连接对象
	 */
	public function __construct($dsn, $db_user, $db_pwd, $db_charset = 'uft8',$prefix)
	{
		$this->dsn 			= 	$dsn;
		$this->db_user		= 	$db_user;
		$this->db_pwd		= 	$db_pwd;
		$this->db_charset 	= 	$db_charset;
		$this->prefix		= 	$prefix;
		$this->connect();
	}

	/**
	 * 连接数据库
	 * @return 返回一个连接数据库的对象
	 */
	public function connect()
	{
		try {
		    $this->db = new PDO($this->dsn, $this->db_user, $this->db_pwd);
		    $this->db->setAttribute(PDO :: ATTR_ERRMODE, PDO :: ERRMODE_EXCEPTION);
		    $this->db->query("SET NAMES '$this->db_charset'");
		} catch (PDOException $e) {
		    echo 'Connection failed: ' . $e->getMessage();
		}
	}

	// 开启事务功能
	public function beginTransaction() {
		return $this->db->beginTransaction();
	}

	// 提交事务
	public function commit() {
		return $this->db->commit();
	}

	// 回滚事务
	public function rollBack() {
		return $this->db->rollBack();
	}

	// 打印错误代码
	public function errorCode() {
		return $this->db->errorCode();
	}

	// 打印错误信息
	public function erroInfo() {
		return $this->db->errorInfo();
	}

	/**
	 * 保存数据库出错信息到本地文件\
	 * @param $table 表名或SQL语句
	 * @param $sql 查询语句.此语句用来查看执行的SQL语句到底是哪儿出错了.
	 * @param $message PDO的错误提示信息
	 */
	public function saveDBError($table = null, $sql = null, $message = null) {
		try {
				$ip 		= $this->getip();				// 出错IP
				$time 		= date("Y-m-d H:i:s");			// 将当前时间格式化输出
				$message 	= "错误的表名或SQL语句:\r\n$table" . "\r\n错误的查询语句:\r\n$sql" .
							  "\r\n数据库错误提示信息:\r\n$message" . "\r\n客户IP:$ip" . "\r\n时间:$time" . "\r\n\r\n";

				$server_date 	= date("Y-m-d");			// 当前日期
				$filename 		= $server_date . ".txt";	// 以格式化日期作为错误文件的文件名
				$file_path 		= OWS_PHPLOG_PATH."./error/" . $filename;		// 错误文件保存的路径
				$error_content 	= $message;					// 错误的内容
				$file 			= OWS_PHPLOG_PATH."./error/"; 					// 设置文件保存目录

				// 如果不存在这个文件夹,就新建文件夹
				if (!file_exists($file))
				{
					@mkdir($file, 0777);
				}

				// 建立txt日期文件
				if (!file_exists($file_path))
				{
					@fopen($file_path, "w+");

					// 首先要确定文件存在并且可写
					if (is_writable($file_path))
					{
						$handle = fopen($file_path, 'a');
						fwrite($handle, $error_content);
						fclose($handle);
					}
				}
				else
				{
					// 首先要确定文件存在并且可写
					if (is_writable($file_path))
					{
						$handle = fopen($file_path, 'a');
						fwrite($handle, $error_content);
						fclose($handle);
					}
				}
		} catch(PDOException $e) {

		}
	}

	/**
	 * 对应exec里面的exec方法
	 */
	public function exec($sql) {
		return $this->db->exec($sql);
	}

	/**
	 * 对应PDO里面的query()
	 */
	public function query($sql) {
		try {
			return $this->db->query($sql);
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	/**
	 * 返回最后插入记录时的ID号
	 */
	public function lastInsertId($name = null) {
		return $this->db->lastInsertId($name);
	}

	/**
	 * 获取一个“数据库连接对象”的属性
	 */
	public function getAttribute($attribute) {
		return $this->db->getAttribute($attribute);
	}

	/**
	 * 设置一个“数据库连接对象”的属性
	 */
	public function setAttribute($attribute, $value) {
		return $this->db->setAttribute($attribute, $value);
	}


	/**
	 * 准备语句:适合封装类不够应用的时候用,返回一个PDOStatement对象
	 */
	public function prepare($sql, $driver_options = false) {
		if (!$driver_options) {
			$driver_options = array ();
		}
		return $this->db->prepare($sql, $driver_options);
	}

	/**
	 * 返回一个仅以键为下标的数组
	 */
	public function queryFetchAllAssoc($sql) {
		return $this->db->query($sql)->fetchAll(PDO :: FETCH_ASSOC);
	}

	/**
	 * 返回一个以数字和键为下标的数组:这是PDO默认的动作
	 */
	public function queryFetchAllBoth($sql) {
		return $this->query($sql)->fetchAll(PDO :: FETCH_BOTH);
	}

	/**
	 * 获得客户端真实的IP地址
	 */
	public function getip()
	{
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
		{
			$ip = getenv("HTTP_CLIENT_IP");
		}
		elseif (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
		{
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		}
		elseif (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
		{
			$ip = getenv("REMOTE_ADDR");
		}
		elseif (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		else
		{
			$ip = "unknown";
		}
		return ($ip);
	}
	
	/**
	 * 增加：提供两种方式，一种封装了SQL操作，一个直接传递SQL语句。
	 * 注意：数组已经进行了加引号或点号处理,字符串没有加点号或引号处理。
	 * @param $table 表名或SQL插入语句。表名绝对不能为insert开头。
	 * @param $key_value_arr 键值对数组,键对应字段，值对应字段的值。
	 * @return 插入成功返回插入的最后ID，插入失败返回空。
	 */
	public function insert($table, $key_value_arr = null)
	{
		$id 	= NULL;
		$query 	= null;
		$ret=array();
		try {
			if (strtolower(substr(trim($table), 0, 6)) == 'insert')
			{
				$id = $this->db->exec($table);
			}
			else
			{
				foreach ($key_value_arr as $key => $value)
				{
					$keys 	.= "`$key`,";
					$query 	.= "'$value',";
				}
				$keys 	= substr($keys, 0, -1);
				$query 	= substr($query, 0, -1);
				$query 	= "INSERT INTO `$this->prefix$table` ($keys) VALUES($query)";
				$id 	= $this->db->exec($query);
			}
			$id = $id ? $this->db->lastInsertId() : null;
		} catch(PDOException $e)
		{
			$this->saveDBError($table, $query, $e->getMessage());
		}
		$ret[]=$id;
		$ret[]=$query;		
		return $ret;
	}

	/**
	 * 更新
	 * @param $table 表名或SQL语句。表名不能为update开头。
	 * @param $key_value_arr 字符串或键值对数组。即更新对应的字段或值。
	 * @param $field_value_arr 数组或字符串。where条件值，字符串必须写自己的where关键字。
	 * @return 返回修改的行数,一般为1,再者为0,为0的时候为修改的值与先前记录的值一样,即对记录没有进行修改操作
	 */
	public function update($table, $key_value_arr = null, $field_value_arr = null)
	{
		$rows_affected = null;
		$query		   = null;
		$where		   = null;
		try {
			if (strtolower(substr(trim($table), 0, 6)) == 'update')
			{
				$rows_affected = $this->db->exec($table);
			}
			else
			{
				if (is_array($key_value_arr))
				{
					foreach ($key_value_arr as $key => $val)
					{
						$query .= "`$key` = '$val',";
					}
					$query = substr($query, 0, -1);
					if (is_array($field_value_arr))
					{
						foreach ($field_value_arr as $fkey => $fvalue)
						{
							$where .= "`$fkey` = '$fvalue' AND ";
						}
						$where	=	substr(trim($where), 0, -3);
						$query	= "UPDATE `$this->prefix$table` SET $query WHERE $where";
					} else {
						$query	= "UPDATE `$this->prefix$table` SET $query WHERE $field_value_arr";
					}
				}
				elseif (is_string($key_value_arr))
				{

					if (is_array($field_value_arr))
					{
						foreach ($field_value_arr as $fkey => $fvalue)
						{
							$where .= "`$fkey` = '$fvalue' AND ";
						}
						$where	=	substr(trim($where), 0, -3);
						$query	= "UPDATE `$this->prefix$table` SET $key_value_arr WHERE $where";
					}
					else
					{
						$query	= "UPDATE `$this->prefix$table` SET $key_value_arr WHERE $field_value_arr";
					}
				}
				$rows_affected 	= $this->db->exec($query);
			}
		} catch (PDOException $e)
		{
			$this->saveDBError($table, $query, $e->getMessage());
		}
		return $rows_affected;
	}


	/**
	 * 删除
	 * @param $table 表名或SQL删除语句。表名不能为delete开头。
	 * @param $field where条件，即按照哪个字段进行更新，即此中据说的字段。
	 * @param $value 数组或字符串，where条件中的值，数组和字符串都进行了加引号处理。
	 * @return 返回删除所影响的行数。
	 */
   public function delete($table, $field = null, $value = null, $field_value_arr = null)
   {
    	$rows_affected 	= null;
    	$query 			= null;
    	try {
    		if (strtolower(substr(trim($table), 0, 6)) == 'delete')
			{
				$rows_affected = $this->db->exec($table);
			}
    		else
    		{
    			if (is_array($value))
	    		{

		    		$value1 = implode(",",$value);
		    		$query = "DELETE FROM `$this->prefix$table` WHERE `$field` IN ($value1)";
		    	}
		    	elseif (is_string($value))
		    	{
		    		$query = "DELETE FROM `$this->prefix$table` WHERE `$field` = '$value'";
		    	}
		    	elseif (is_array($field_value_arr))
		    	{
	    			foreach ($field_value_arr as $fkey => $fvalue)
	    			{
	    				$query .= "`$fkey` = '$fvalue' and ";
	    			}

	    			$query	=	substr(trim($query), 0, -3);
		    		$query 	= 	"DELETE FROM `$this->prefix$table` WHERE $query";
		    	}
		    	$rows_affected = $this->db->exec($query);
    		}
    	} catch (PDOException $e)
    	{
		   $this->saveDBError($table, $query, $e->getMessage());
    	}
    	return $rows_affected;
    }

	/**
	 * 查询
	 * @param $table 表名或SQL查询语句。表名不能为select
	 * @param $where 类型为array或字符串，条件语句,即where语句。
	 * @param $order 需要进行降序的字段，数据库默认为升序，所以，这里只需要指名需要进行降序的字段即可，字段只能为一个
	 * @param $offset 结果集中第一条记录的下标，用于分页用,默认值为空
	 * @param $count 结果集记录条数
	 * @param $orand 与或条件，默认值为且，此参数可以用来做并列查询或非并列查询
	 * @return 返回一个查询结果集
	 */
	public function select($table, $where = null, $order = null, $offset = null, $count = null, $orand = 'AND')
	{
		try {
			$sql 	= "SELECT * FROM `$this->prefix$table`";
			$rows	= null;
			if (strtolower(substr(trim($table), 0, 6)) == 'select')
				{
					$rows = $this->db->query($table);
					$rows = $rows->fetchAll(PDO::FETCH_ASSOC);
				}
			else
			{				
				if ($where)
				{
		        	if (is_array($where))
		        	{
			        	$orand = strtolower($orand);

			        	$sql = $sql . ' where';
			        	foreach ($where as $key => $val)
			        	{
							$sql .= " `$key` = '$val' $orand";
			        	}
			        	if ($orand = 'and')
			        	{
			        		$sql = substr($sql, 0, -3);
			        	} elseif($orand = 'or') {
			        		$sql = substr($sql, 0, -2);
			        	}
		        	} elseif (is_string($where))
		        	{
						$sql = $sql . '  ' . $where;						
		        	}
		        }
		        if ($order)
		        {
					$sql .= ' ' . $order ;
		        }
		        if (isset($offset) && isset($count))
		        {
					$sql .= " limit $offset, $count";
		        }
				
		        $result = $this->db->query($sql);
				
				$rows	= $result->fetchAll(PDO::FETCH_ASSOC);
			}
		} catch(PDOException $e) {
			$this->saveDBError($this->prefix.$table, $sql, $e->getMessage());
		}		
		return $rows;
	}

	/**
	 * 根据本表中的外键查找关联表的的键的值。
	 *
	 * @param $rowset 一个查询结果集，此结果集中有一些是外键值，是需要进行查询其他表来填充的,必须是二维数组
	 * @param $field 字段值，是查询结果集中，指定哪个字段是外键，是需要进行查询替换的
	 * @param $table 外键对应的表,此外键是哪个表中的主键,一般都为主键，其他情况极少
	 * @param $primay 外键对应的表的主键，与外键有关联的那个表的键，通过此键能查询到所想要的值
	 * @param $tofiled 查询关联表中哪个字段的值，即通过关联键查询到对应的字段的值,可以是主键
	 * @return 返回被查询替换后的结果集
	 */
	public function dependentKeyName($rowset, $field, $table, $primary, $tofield)
	{
		$rowset_length = count($rowset);
		for ($i = 0; $i < $rowset_length; $i++)
		{
			$row					=	$rowset[$i][$field];
			$result 				= 	$this->select($this->prefix.$table, array($primary => $row));
			$rowset[$i][$field] 	= 	$result[0][$tofield];
		}
		return $rowset;
	}
	
	
	/*
	 *保存不同类型的历史记录
	 *参数$title :标题
	 *参数$contents :内容
	 *参数$logtype :日志类型
	 *参数$user :用户名（可选）
	*/
	
	public function savelog($title,$contents,$logtype,$user=''){
	
		$in_sql = null;
		$lid    = null;
		$sin_sql= null;
		$sid    = null;
		$se_sql = null;
		$result = null;
		$rows   = null;	
		if(empty($user) && empty($_SESSION[username])){$user='robot';}
		if(empty($user) && ! empty($_SESSION[username])){$user=$_SESSION[username];}
		if(empty($title)){exit('日志标题不能为空!');}
		if(empty($contents)){exit('日志内容不能为空!');}
		if(empty($logtype)){exit('日志类型不能为空!');}
		$se_sql="SELECT `id` FROM `osa_syslog_cfg` WHERE `oTypeText` = '".$logtype."';";	
		$result=$this->db->query("$se_sql");
		$rows	= $result->fetchAll(PDO::FETCH_ASSOC);
		if(empty($rows)){	
			$in_sql = "INSERT INTO `osa_syslog_cfg`(`oTypeText`) VALUES ('".$logtype."')";
			$lid = $this->db->exec("$in_sql");
			$lid = $lid ? $this->db->lastInsertId() : null;
			if(empty($lid)){exit('日志分类插入失败！');}	
		}else{
			$lid = $rows[0][id];		
		}
		$sin_sql = "INSERT INTO `osa_syslog`(oTypeid,oUserName,oLogTitle,oLogText,oLogAddTime) VALUES ('".$lid."','".$user."','".$title."','".$contents."',now())";
		$sid = $this->db->exec("$sin_sql");
		$sid = $sid ? $this->db->lastInsertId() : null;
		if($sid){ return 1; }else{ return 0; }
	}

	
	/*
	 *根据oIpId获取IP地址
	 *参数$ipid :IP地址对应的ID
	*/
	public function ows_getIpById($ipid){
	
		$rdata=$this->select("select oIp from osa_ipinfo where id = ".$ipid);

		return $rdata[0][oIp];
	}
	
	/*检查是否登录*/
	public function is_login(){
	
		$r = ! empty($_SESSION['username']) ? 1 : 0 ;
		
		return $r;
		
	}
}