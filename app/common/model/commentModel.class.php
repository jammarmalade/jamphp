<?php

namespace Common\Model;

use \Framework\Model;

class commentModel extends Model {

    public $tablename = 'comment';

    public function __construct() {
        parent::__construct($this->tablename);
    }
    /**
     * 获取评论列表
     * @param int 文章id
     * @param int 当前页数
     * @return array   评论数据
     */
    public function getCommentList($aid,$pageNow=1){
        
        $limit = 30;
        $startLimit = ($pageNow-1) * $limit;
        $where['aid'] = $aid;
        $where['classify'] = 'article';
        $where['status'] = 1;
        $comlist = $this->where($where)->limit($startLimit,$limit)->fetchAll();
        
        $next = 0;
        if ($comlist) {
            foreach ($comlist as $k => $v) {
                $comlist[$k]['formattime'] = formatTime($v['dateline'], 1);
                $comlist[$k]['time'] = formatTime($v['dateline']);
                $comlist[$k]['avatar'] = IMG_DIR . '/jam.png';
                $comlist[$k]['content'] = Model('article')->ubb2html($v['content']);
            }
            if (count($comlist) >= $limit) {
                $next = 1;
            }
        }
        return ['list'=>$comlist,'next'=>$next];
    }
    /**
     * 最新评论
     * @param int 默认取前四条
     */
    public function latest($limit = 4){
        $res = $this->field('cid,ruid,username,aid,authorid,author,content')->where(['status'=>1])->order('dateline DESC')->limit($limit)->fetchAll();
        if($res){
            foreach($res as $k=>$v){
                $res[$k]['content'] = cutstr($v['content'], 100);
            }
        }
        return $res;
    }
}
