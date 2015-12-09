<?php

namespace Framework;

class Model{
	//数据表名称
	public $tablename = '';
	//数据
	public $data = [];
	//数据库实例
	private $_db = '';
	private $_parame = [];

	public function __construct($tablename) {
		$this->table($tablename);
		//mysqli 链接数据库的实例
		$this->_db = \Framework\mysqli::getInstance();
	}
	public function table($tablename){
		$pretable = config('tablePrefix') ? config('tablePrefix') : '';
		$this->tablename = $pretable.$tablename;
		return $this->tablename;
	}
	
	#魔术方法，调用不存在的 属性 时执行
	//设置
	public function __set($name,$value) {
        $this->data[$name] = $value;
    }
	//获取	
	public function __get($name) {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }
	//设置参数
	public function __call($method,$args) {
        if(in_array($method,['where','field','order','limit'])){
			$this->_parame[$method] = [];
			$this->_parame[$method] = count($args)==1 ? $args[0] : $args;
			return $this;
		}
    }
	//查询 多条
	public function fetchAll($keyfield = ''){
		$sql = 'SELECT ';
		if($this->_parame['field']){
			$sql .= $this->_parame['field'].' ';
		}else{
			$sql .= '* ';
		}
		$sql .= 'FROM '.$this->tablename.' ';
		$sql .= $this->_sql();
		$this->_destruct();
		
		return $this->_db->query($sql,false,$keyfield);
	}
	//查询 单条
	public function fetch(){
		$sql = 'SELECT ';
		if($this->_parame['field']){
			$sql .= $this->_parame['field'].' ';
		}else{
			$sql .= '* ';
		}
		$sql .= 'FROM '.$this->tablename.' ';
		$sql .= $this->_sql();
		
		$this->_destruct();
		
		return $this->_db->query($sql,true);
	}
	//增加
	public function add($data = []){
		$sql = 'INSERT INTO '.$this->tablename;
		if(!$data){
			$data = $this->data;
		}
		$fields = array_keys($data);
		$values = array_values($data);
		$sql .= " (".join(",",$fields).")";
		$sql .= " VALUES ('".join("','",$values)."')";
		$this->_destruct();
		return $this->_db->query($sql);
	}
	//修改
	public function update($where,$data = []){
		$this->_parame['where'] = $where;
		if(!$data){
			$data = $this->data;
		}
		$sql = 'UPDATE '.$this->tablename.' SET ';
		$dot = $setstr = '';
		foreach($data as $field=>$value){
			$setstr .= $dot."`".$field."` = '".$value."' ";
			$dot = ',';
		}
		$sql .= $setstr;
		$sql .= $this->_sql();
		return $this->_db->query($sql);
	}
	//删除
	public function delete(){
		$sql = 'DELETE FROM '.$this->tablename.' ';
		$sql .= $this->_sql();
		return $this->_db->query($sql);
	}
	//原生查询
	public function query($sql,$args = []){
		if(is_array($args)){
			$sql = $this->_format($sql,$args);
		}

		return $this->_db->query($sql);
	}
	//格式化 sql 
	private function _format($sql, $arg) {
		$count = substr_count($sql, '%');
		if($count == 0){
			return $sql;
		}
		//若是 %占位符 和参数不相等
		if ($count > count($arg)) {
			$this->error('SQL need '.$count.' vars. - '.$sql);
		}
		//格式化 sql 参数
		$len = strlen($sql);
		$i = $find = 0;
		$tmpsql = '';
		while ($i <= $len && $find < $count) {
			if ($sql[$i] == '%') {
				$next = $sql[$i + 1];
				if ($next == 't') {//表名
					$tmpsql .= $this->table($arg[$find]);
				} elseif ($next == 's') {//addslashes 转义
					$tmpsql .= '\''.addslashes(is_array($arg[$find]) ? serialize($arg[$find]) : strval($arg[$find])).'\'';
				} elseif ($next == 'f') {//前导零
					$tmpsql .= sprintf('%F', $arg[$find]);
				} elseif ($next == 'd') {//数字
					$tmpsql .= intval($arg[$find]);
				} elseif ($next == 'i') {//不处理
					$tmpsql .= $arg[$find];
				} elseif ($next == 'n') {//如 id IN (1,2,3)
					if (!empty($arg[$find])) {
						$tmpsql .= is_array($arg[$find]) ? "'".join("','", $arg[$find])."'" : $arg[$find];
					} else {
						$tmpsql .= '0';
					}
				} else {
					$tmpsql .= '\''.addslashes($arg[$find]).'\'';
				}
				$i++;
				$find++;
			} else {
				$tmpsql .= $sql[$i];
			}
			$i++;
		}
		if ($i < $len) {
			$tmpsql .= substr($sql, $i);
		}
		return $tmpsql;
	}

	// sql 生成(部分)
	private function _sql(){
		$sql = '';
		if($this->_parame['where']){
			$sql .='WHERE ';
			if(is_array($this->_parame['where'])){
				$and = $tmpcondition = '';
				foreach($this->_parame['where'] as $filed=>$condition){
					if(is_array($condition)){
						$tmpcondition .= $and.$filed.' '.$condition[0]." '".$condition[1]."'";
					}else{
						$tmpcondition .= $and.$filed."='".$condition."'";
					}
					$and = ' AND ';
				}
				$sql .= $tmpcondition.' ';
			}else{
				$sql .= $this->_parame['where'].' ';
			}
		}
		if($this->_parame['order']){
			$sql .= 'ORDER BY '.$this->_parame['order'].' ';
		}
		if($this->_parame['limit']){
			if(is_array($this->_parame['limit'])){
				list($startLimit,$limit) = $this->_parame['limit'];
			}else{
				$startLimit = 0;
				$limit = $this->_parame['limit'];
			}
			$sql .= 'LIMIT '.$startLimit.','.$limit.' ';
		}
		return $sql;
	}
	//销毁上次查询的数据
	private function _destruct(){
		$this->_parame = [];
		$this->data = [];
	}
	//自定义异常处理方法
	public function error($msg){
		throw new \Exception($msg);
	}





	// test
	public function getDbConfig(){
		return $this->_db->getConfig();
	}

	public function connect(){
		$this->_db->connect();
	}

	public function getParame(){
		return $this->_parame;
	}

}