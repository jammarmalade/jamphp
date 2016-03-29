<?php

namespace Common\Model;

use \Framework\Model;

class articleLikeModel extends Model {

    public $tablename = 'article_like';

    public function __construct() {
        parent::__construct($this->tablename);
    }
    
}
