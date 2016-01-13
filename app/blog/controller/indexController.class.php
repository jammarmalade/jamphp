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
        
        $data = Model('article')->getArticleList(PAGE);
        
        $this->assign('articleList', $data['articleList']);
        $this->assign('pageHtml', $data['pageHtml']);
        $this->assign('imgids', $data['imgids']);
        $this->assign('theme', $this->theme);
        $this->display();
        
        
//        $articles = J::t('article')->fetch_list('*', '`status`=1', $start, $limit, 'dateline DESC');
//        foreach ($articles as $k => $v) {
//            $articles[$k]['content'] = cutstr(strip_ubb($v['content']), 300);
//            $articles[$k]['formattime'] = btime($v['dateline'], 1);
//            $articles[$k]['time'] = btime($v['dateline']);
//            $articles[$k]['link'] = 'index.php?m=article&do=view&aid=' . $v['aid'];
//            if ($v['image']) {
//                $imgids[$v['aid']] = $v['image'];
//            }
//        }
//        if ($imgids) {
//            $str_ids = join(',', $imgids);
//            $imginfos = J::t('image')->fetch_all('*', "id IN($str_ids)");
//            foreach ($imginfos as $k => $v) {
//                $suff = $v['thumbH'] ? '.thumb.jpg' : '';
//                $imgids[$v['aid']] = $_B['siteurl'] . $v['path'] . $suff;
//            }
//        }
//        $count = J::t('article')->fetch_count();
//        $pagehtml = page($count, $_B['page'], $limit, 'index.php?m=article&do=list');
//
//        $_B['navtitle'] = $_B['setting']['blog']['blogName'] . ' - 列表';
    }

}
