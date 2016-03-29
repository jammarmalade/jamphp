<?php

namespace Common\Model;

use \Framework\Model;

class loginFailedModel extends Model {

    public $tablename = 'loginfailed';

    public function __construct() {
        parent::__construct($this->tablename);
    }



}
