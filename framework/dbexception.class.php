<?php

namespace Framework;
class DbException extends \Exception{

	public $sql;

	public function __construct($message, $code = 0, $sql = '') {
		$this->sql = $sql;
		parent::__construct($message, $code);
	}

	public function getSql() {
		//do something
		return $this->sql;
	}
}
?>