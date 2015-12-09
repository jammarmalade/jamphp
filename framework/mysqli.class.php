<?php

namespace Framework;
class mysqli{
	//数据库配置
	private static $config=[];
	//数据库链接信息
	private static $dbinfo = [];
	//当前链接
	private static $curlink;
	private static $version='';
	//连接池
	private static $links = [];
	//sql 安全检查关键词
	private static $querysafe = [
		'function'=>['load_file','hex','substring','if','ord','char'],
		'other'=>['@','intooutfile','intodumpfile','unionselect','(select','unionall','uniondistinct','/*','*/','#','--','"']
	];

	public static function getInstance(){
		static $obj;
		if(empty($obj)) {
			$obj = new self();
		}
		if(!self::$config){
			self::$config = config('db');
		}
		return $obj;
	}

	public static function getConfig(){
		return self::$config;
	}
	//链接
	public static function connect($type = 'master'){
		$config = self::$config;
		if($type == 'master'){
			$dbinfo = $config[0];//第一个数组为主库
		}else{
			//若有更多db配置
			if(isset($config[0])){
				$tmpconfig = $config;
				array_shift($tmpconfig);
				$dbinfo = $tmpconfig[array_rand($tmpconfig)];
			}else{
				$dbinfo = $config[0];//还是用主库
			}
		}
		//连接池key值
		$linkkey = md5(serialize($dbinfo));
		if(isset(self::$links[$linkkey])){
			self::$curlink = self::$links[$linkkey];
			return true;
		}
		self::$dbinfo = $dbinfo;
		$curlink = new \mysqli($dbinfo['host'],$dbinfo['user'],$dbinfo['pwd'],$dbinfo['dbname']);

		if($curlink->connect_error) {
			self::halt($curlink->connect_error, $curlink->connect_errno,'connect error');
		} else {
			self::$curlink = self::$links[$linkkey] = $curlink;
			$sql = 'SET character_set_connection=utf8, character_set_results=utf8, character_set_client=binary';
			$curlink->query($sql);
		}

	}
	//抛出 db 错误
	public static function halt($message = '', $code = 0, $sql = '') {
		throw new DbException($message, $code, $sql);
	}
	//版本
	public static function version() {
		if(empty(self::$version)) {
			self::$version = self::$curlink->server_version;
		}
		return self::$version;
	}
	//释放资源
	public static function free_result($result) {
		return $result->free();
	}
	//受影响的行数
	public static function affected_rows() {
		return self::$curlink->affected_rows;
	}
	//插入的id
	public static function insert_id() {
		return self::$curlink->insert_id;
	}

	//query 查询
	public function query($sql,$first = false,$keyfield = '') {
		if(DEBUG){
			global $_debug;
			$_debug['sql'][] = $sql;
		}
		
		/*
		测试注入 关闭 checkquery, LOAD_FILE函数
		C:/Windows/win.ini
		C:/Windows/System32/drivers/etc/hosts
		*/
		//$sql = "select LOAD_FILE('C:/Windows/win.ini')";
		//检查 sql 语句的安全性
		self::checkquery($sql);

		//哪种操作, dml 语句就请求主库
		$cmd = trim(strtoupper(substr($sql, 0, strpos($sql, ' '))));
		$type = $cmd === 'SELECT' ? 'slave' : 'master';
		
		//强制使用 master
		self::connect('master');
		$result = self::$curlink->query($sql);
		if ($result) {
			if($cmd === 'SELECT'){
				if($first){
					$returnData = $result->fetch_array(MYSQLI_ASSOC);
				}else{
					//MYSQLI_ASSOC 关联类型
					while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
						if ($keyfield && isset($row[$keyfield])) {
							$returnData[$row[$keyfield]] = $row;
						} else {
							$returnData[] = $row;
						}
					}
				}
				
			}elseif ($cmd === 'UPDATE' || $cmd === 'DELETE') {
				//返回受影响的行数
				$returnData = self::affected_rows();
			} elseif ($cmd === 'INSERT') {
				//插入的id
				$returnData = self::insert_id();
			}
		}else{
			//抛出错误
			self::halt(self::$curlink->error, self::$curlink->errno,$sql);
		}
		//释放资源
		if(is_object($result)){
			self::free_result($result);
		}
		
		return $returnData;
	}
	//sql 安全检查
	private static function checkquery($sql){

		//清除所有转义符
		$cleansql = str_replace(array('\\\\', '\\\'', '\\"', '\'\''), '', $sql);
		$cleansql = preg_replace("/[^a-z0-9_\-\(\)#\*\/\"]+/is", "", strtolower($cleansql));
		//检查方法
		$halt = false;
		foreach (self::$querysafe['function'] as $fun) {
			if (strpos($cleansql, $fun . '(') !== false){
				$halt = true;
			}
		}
		//检查其它关键字符串
		foreach (self::$querysafe['other'] as $str) {
			if (strpos($cleansql, $str) !== false){
				$halt = true;
			}
		}
		if($halt){
			self::halt('this query is not safe',0,$sql);
		}
	}
}
?>