<?php

/*
 * 文章
 */

namespace Blog\Controller;

use \Common\webController;

class articleController extends webController {

    public function index() {
        
        $data = Model('article')->getArticleList(PAGE);
        
        $this->assign('articleList', $data['articleList']);
        $this->assign('pageHtml', $data['pageHtml']);
        $this->assign('imgids', $data['imgids']);
        $this->assign('theme', $this->theme);
        $this->display();
        
    }
    
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
        
        $this->assign('pageTitle', $articleInfo['subject'].' - '.$this->siteName);
        $this->assign('articleInfo', $articleInfo);
        $this->assign('commentList', $resData['list']);
        $this->assign('next', $resData['next']);
        $this->display();
    }

}
