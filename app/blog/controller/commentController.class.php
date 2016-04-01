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
    /**
     * 添加评论
     */
    public function add() {
        if(!AJAX){
            $this->ajaxReturn('非法操作', '', false);
        }
        if (!session('user.uid')) {
            $this->ajaxReturn('请先登录', 'login', false);
        }
        $aid = input('aid',0);
        if(!$aid){
            $this->ajaxReturn('数据错误，请刷新重试', '', false);
        }
        //回复id
        $rcid = input('rcid',0);
        if (is_numeric($rcid) && $rcid > 0) {
            $where['cid'] = 12;
            $rinfo = Model('comment')->field('authorid,author')->where(['cid'=>$rcid])->fetch();
            if ($rinfo) {
                $insert['rcid'] = $rcid;
                $insert['ruid'] = $rinfo['authorid'];
                $insert['username'] = $rinfo['author'];
            }
        }
        $content = input('content','');
        if ($content == '') {
            $this->ajaxReturn('评论内容不能为空', '', false);
        }else{
            $content = Model('article')->commentubb($content);
        }

        $insert['aid'] = $aid;
        $insert['authorid'] = session('user.uid');
        $insert['author'] = session('user.username');
        $insert['content'] = $content;
        $insert['dateline'] = TIMESTAMP;
        
        $cid = Model('comment')->add($insert);

        if ($cid) {
            $insert['content'] = Model('article')->ubb2html($insert['content']);
            $insert['cid'] = $cid;
            $insert['formattime'] = '刚刚';
            $insert['time'] = formatTime(TIMESTAMP);
            $insert['avatar'] = IMG_DIR . 'jam.png';
            
            $comlist[0] = $insert;
            $this->assign('commentList', $comlist);
            $data = $this->display('article/_comment',true);
            
            $this->ajaxReturn('success', $data, true);
        } else {
            $this->ajaxReturn('评论失败', '', false);
        }

    }

}
