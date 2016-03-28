<?php

/*
 * 标签
 */

namespace Blog\Controller;

use \Common\webController;

class tagController extends webController {
    
    /**
     * 添加标签
     */
    public function add() {
        $tagname = input('tagname');
        if (Model('tag')->where(['tagname' => $tagname])->fetch()) {
            $this->ajaxReturn('已存在该标签', '', false);
        } else {
            $insertData = [
                'tagname' => $tagname,
                'uid' => session('user.uid'),
                'username' => session('user.username'),
                'dateline' => TIMESTAMP
            ];
            if($tagid = Model('tag')->add($insertData)){
                $this->ajaxReturn('添加成功', $tagid, true);
            }else{
                $this->ajaxReturn('添加失败', '', false);
            }
        }
    }
    /**
     * 搜索标签
     */
    public function search(){
        $tagname = input('q');
        $data = Model('tags')->searchTagByName($tagname);
	echo json_encode($data);
	exit();
    }
    /**
     * 添加/删除文章和标签的关系
     */
    public function relation(){
        $tagid = input('tagid');
        $aid = input('aid');
        $dotype = input('dotype');
        if (!$tagid || !$aid) {
            $this->ajaxReturn('缺少参数','' , false);
        }
        if($dotype=='addRelation'){
            //是否已有5个标签
            if (Model('tagid_aid')->count(['aid'=>$aid]) == 5) {
                $this->ajaxReturn('文章已存在 5 个标签','' , false);
            }
            if (Model('tagid_aid')->where(['tagid'=>$tagid,'aid'=>$aid])->fetch()) {
                $this->ajaxReturn('文章已存在该标签','' , false);
            } else {
                $insertId = Model('tagid_aid')->add([
                    'tagid' => $tagid,
                    'aid' => $aid,
                    'uid' => session('user.uid'),
                    'username' => session('user.username'),
                    'dateline' => TIMESTAMP
                ]);
                //标签文章数+1
                Model('tags')->where(['tagid'=>$tagid])->increase('articles');
                $this->ajaxReturn('',$insertId , true);
            }
        }elseif($dotype=='delRelation'){
            if (Model('tagid_aid')->where(['tagid'=>$tagid,'aid'=>$aid])->delete()) {
                //标签文章数-1
                Model('tags')->where(['tagid'=>$tagid])->decrease('articles');
                $this->ajaxReturn('success','' , true);
            } else {
                $this->ajaxReturn('fialed','' , false);
            }
        }
        
    }
}
