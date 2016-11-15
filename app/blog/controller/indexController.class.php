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
        
//        bheader('article/index');
        $tid = input('get.tid',0);
        $data = Model('article')->getArticleList($tid);
        
        $this->assign('articleList', $data['articleList']);
        $this->assign('pageHtml', $data['pageHtml']);
        $this->assign('imgids', $data['imgids']);
        $this->assign('sideBarData', $this->sideBarData());
        $this->display();
        
    }
    /**
     * 侧边栏数据
     */
    public function sideBarData(){
        $cacheKey = 'sideBar';
        $data = fCache($cacheKey);
        if(!$data){
            $data['articleList'] = Model('article')->latest();
            $data['commentList'] = Model('comment')->latest();
            $data['tagList'] = Model('tags')->hotTag();
            fCache($cacheKey, $data);
        }
        return $data;
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
