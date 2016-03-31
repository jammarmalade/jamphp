<?php

/*
 * 评论
 */

namespace Blog\Controller;

use \Common\webController;

class commentController extends webController {
    
    /**
     * 获取评论列表
     */
    public function getList(){
        if(!AJAX){
            $this->ajaxReturn('不被允许的操作', '', false);
        }
        $aid = input('aid',0);
	if(!$aid){
            $this->ajaxReturn('数据出错，请刷新重试', '',false);
	}
        
	$resData = Model('comment')->getCommentList($aid,PAGE);
        
        $this->assign('commentList', $resData['list']);
        $data['content']=$this->display('article/_comment',true);
        
	$data['next']= $resData['next'] ? '?m=blog&c=comment&a=getList&aid='.$aid.'&page='.(PAGE+1) : '';
        
        $this->ajaxReturn('success', $data);
    }
}
