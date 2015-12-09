<?php

namespace Home\Model;
use \Framework\Model;
class homeModel extends Model{
	
	public $tablename = 'home';
	public function __construct(){
		parent::__construct($this->tablename);
	}
	public function homeFetch(){
		echo 'model homeModel action homeFetch!<br>';
	}

	public function getById($id){
		$where['label_id'] = $id;
		return $this->where($where)->fetch();
	}

	
}