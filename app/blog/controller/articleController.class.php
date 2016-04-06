<?php

/*
 * 文章
 */

namespace Blog\Controller;

use \Common\webController;

class articleController extends webController {

    public function index() {
        
        $sideBarData = Controller('index')->sideBarData();
        $data = Model('article')->getArticleList(PAGE);
        
        $this->assign('articleList', $data['articleList']);
        $this->assign('pageHtml', $data['pageHtml']);
        $this->assign('imgids', $data['imgids']);
        $this->assign('sideBarData', $sideBarData);
        $this->display();
        
    }
    /**
     * 查看文章
     */
    public function view(){
        
        $aid = input('get.aid',0);
        if(!$aid){
            $this->showError('该文章不存在');
        }
        $articleModel = Model('article');
        $articleInfo = $articleModel->getArticleInfo($aid);
        
        if ($articleInfo['status'] != 1 && !IS_ADMIN) {
            $this->showError('该文章不存在');
        }
        //是否有图片
        $aidattach = $articleInfo['image'] ? $articleInfo['aid'] : 0;
        $articleInfo['content'] = $articleModel->ubb2html($articleInfo['content'], $aidattach);
        $articleInfo['formattime'] = formatTime($articleInfo['dateline'], 1);
        $articleInfo['time'] = formatTime($articleInfo['dateline']);
        //获取评论
        $resData = Model('comment')->getCommentList($aid);
        
        //获取标签
        $articleInfo['tags'] = Model('tags')->getArticleTags($aid);
        //增加查看次数
        $articleModel->addViews($aid);
        
        $sideBarData = Controller('index')->sideBarData();
        $this->assign('sideBarData', $sideBarData);
        
        $this->assign('pageTitle', $articleInfo['subject'].' - '.$this->siteName);
        $this->assign('articleInfo', $articleInfo);
        $this->assign('commentList', $resData['list']);
        $this->assign('next', $resData['next']);
        $this->display();
    }
    
    
    /**
     * 赞文章
     */
    public function zan() {
        if(!AJAX){
            $this->showError('非法操作');
        }
        if(!session('user.uid')){
            if(!AJAX){
                $this->showError('非法操作');
            }else{
                $this->ajaxReturn('请先登录', 'login', false);
            }
        }
        $aid = input('aid');
        if (!$aid) {
            $this->ajaxReturn('缺少参数', '', false);
        }
        //删除成功
        $deleteWhere['uid'] = session('user.uid');
        $deleteWhere['aid'] = $aid;
        $resStatus = Model('articleLike')->where($deleteWhere)->delete();
        if ($resStatus) {
            //已存在
            Model('article')->where(['aid'=>$aid])->decrease('like');
            $this->ajaxReturn('取消赞', 'del', true);
        } else {
            //不存在
            Model('articleLike')->add([
                'uid' => session('user.uid'),
                'username' => session('user.username'),
                'aid' => $aid,
                'dateline' => TIMESTAMP
            ]);
            Model('article')->where(['aid'=>$aid])->increase('like');
            $this->ajaxReturn('增加赞', 'add', true);
        }
    }

}
