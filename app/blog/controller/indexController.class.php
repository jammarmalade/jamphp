<?php

/*
 * 博客首页
 */

namespace Blog\Controller;

use \Common\webController;

class indexController extends webController {
    /*
     * 博客首页
     */
    public function index() {
        
        bheader('article/index');
//        $data = Model('article')->getArticleList(PAGE);
//        
//        $this->assign('articleList', $data['articleList']);
//        $this->assign('pageHtml', $data['pageHtml']);
//        $this->assign('imgids', $data['imgids']);
//        $this->display();
        
    }
    
    /**
     * 测试
     */
    public function test(){
        
        $where['cid'] = 12;
        $arr = Model('comment')->field('authorid,author')->where($where)->fetch();
        printarr($arr);
    }
}
