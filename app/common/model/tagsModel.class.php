<?php

namespace Common\Model;

use \Framework\Model;

class tagsModel extends Model {

    public $tablename = 'tag';

    public function __construct() {
        parent::__construct($this->tablename);
    }
    //获取文章的标签
    public function getArticleTags($aid){
        $res = $this->query("SELECT t.tagid,t.tagname FROM %t t INNER JOIN %t ta ON t.tagid=ta.tagid WHERE ta.aid=%d AND t.status=1",array('tag','tagid_aid',$aid));
        return $res;
    }
    
    /**
     * 搜索标签
     */
    public function searchTagByName($txt){
        $tmptxt = '%' . strtr($txt, array('%' => '\%', '_' => '\_', '\\' => '\\\\',"'"=>"\'")) . '%';
        $wherestr = 'WHERE tagname LIKE \'%i\'';
        $sql = 'SELECT tagid as id,tagname FROM %t '.$wherestr.' ORDER BY CHAR_LENGTH(tagname) LIMIT 10';
        $return = $this->query($sql,array('tag',$tmptxt));

        return $return;
    }
}
