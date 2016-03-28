<?php

namespace Common\Model;

use \Framework\Model;

class loginfailedModel extends Model {

    public $tablename = 'loginfailed';

    public function __construct() {
        parent::__construct($this->tablename);
    }



}
