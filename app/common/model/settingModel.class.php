<?php

namespace Common\Model;

use \Framework\Model;

class settingModel extends Model {

    public $tablename = 'setting';

    public function __construct() {
        parent::__construct($this->tablename);
    }
    
}
