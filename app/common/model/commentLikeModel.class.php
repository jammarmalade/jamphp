<?php

namespace Common\Model;

use \Framework\Model;

class commentLikeModel extends Model {

    public $tablename = 'comment_like';

    public function __construct() {
        parent::__construct($this->tablename);
    }
    
}
