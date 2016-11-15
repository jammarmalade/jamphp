<?php

/*
 * 文章
 */

namespace Blog\Controller;

use \Common\webController;

class articleController extends webController {

    public function index() {

        $sideBarData = Controller('index')->sideBarData();
        $tagId = input('get.tagid',0);
        $data = Model('article')->getArticleList($tagId);

        $this->assign('articleList', $data['articleList']);
        $this->assign('pageHtml', $data['pageHtml']);
        $this->assign('imgids', $data['imgids']);
        $this->assign('sideBarData', $sideBarData);
        $this->display();
    }

    /**
     * 查看文章
     */
    public function view() {

        $aid = input('get.aid', 0);
        if (!$aid) {
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

        $this->assign('pageTitle', $articleInfo['subject'] . ' - ' . $this->siteName);
        $this->assign('articleInfo', $articleInfo);
        $this->assign('commentList', $resData['list']);
        $this->assign('next', $resData['next']);
        $this->display();
    }

    /**
     * 赞文章
     */
    public function zan() {
        if (!AJAX) {
            $this->showError('非法操作');
        }
        if (!session('user.uid')) {
            if (!AJAX) {
                $this->showError('非法操作');
            } else {
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
            Model('article')->where(['aid' => $aid])->decrease('like');
            $this->ajaxReturn('取消赞', 'del', true);
        } else {
            //不存在
            Model('articleLike')->add([
                'uid' => session('user.uid'),
                'username' => session('user.username'),
                'aid' => $aid,
                'dateline' => TIMESTAMP
            ]);
            Model('article')->where(['aid' => $aid])->increase('like');
            $this->ajaxReturn('增加赞', 'add', true);
        }
    }

    /**
     * 写文章
     */
    public function add() {
        if (AJAX) {
            if (!session('user.uid')) {
                $this->ajaxReturn('请先登录', 'login', false);
            }
            $subject = input('post.subject', '');
            $content = input('post.content', '');

            if ($subject == '' || $content == '') {
                $this->ajaxReturn('标题或内容不能为空', '', false);
            }
            //以后添加审核内容关键词

            $res_content = Model('article')->html2ubb($content, 1);
            $image = 0;
            if ($res_content['image']) {
                $tmpimgs = $res_content['image'];
                $image = array_shift($tmpimgs);
            }
            $insert = array(
                'subject' => $subject,
                'content' => $res_content['content'],
                'authorid' => session('user.uid'),
                'author' => session('user.username'),
                'dateline' => TIMESTAMP,
                'image' => $image,
                'views' => 1,
            );
            $aid = Model('article')->add($insert);
            //文章添加成功并且有图片
            if ($aid && $image) {
                //将图片状态改为正在使用，图片归属　id　改为文章 aid 
                $where['uid'] = session('user.uid');
                $where['id'] = ['in', $res_content['image']];
                Model('image')->where($where)->update(['aid' => $aid, 'status' => 1]);
                fCache('sideBar', NULL);
                $this->ajaxReturn('添加成功', '?m=blog&c=article&a=view&aid=' . $aid);
            } else {
                if (!$aid) {
                    $this->ajaxReturn('添加失败', '', false);
                } else {
                    //删除缓存
                    fCache('sideBar', NULL);
                    $this->ajaxReturn('添加成功', '?m=blog&c=article&a=view&aid=' . $aid);
                }
            }
        }
        //跳转到登陆页面
        if (!session('user.uid')) {
            $this->showError('请先登录', ['url' => 'javascript:;', 'linkclass' => 'login', 'linkmsg' => '点击登录']);
        }

        $setting = $this->setting();
        $this->assign('pageTitle', '写文章 - ' . $setting['blog']['blogName']);
        $this->assign('defaultcontent', '');
        $this->assign('type', 'add');
        $this->display('write');
    }

    /**
     * 修改文章
     */
    public function update() {
        if (AJAX) {
            if (!session('user.uid')) {
                $this->ajaxReturn('请先登录', 'login', false);
            }
            $subject = input('post.subject', '');
            $content = input('post.content', '');

            if ($subject == '' || $content == '') {
                $this->ajaxReturn('标题或内容不能为空', '', false);
            }
            $aid = input('post.aid', 0);
            if (!$aid || !is_numeric($aid)) {
                $this->ajaxReturn('编辑的文章不存在', '', false);
            }
            //是否是文章的作者
            $articleInfo = Model('article')->getArticleInfo($aid);
            if (session('user.uid') != $articleInfo['authorid'] || ($articleInfo['status'] != 1 && !IS_ADMIN)) {
                $this->ajaxReturn('无权编辑该文章', '', false);
            }
            
            //以后添加审核内容关键词
            $res_content = Model('article')->html2ubb($content, 1);
            $image = 0;
            if ($res_content['image']) {
                $tmpimgs = $res_content['image'];
                $image = array_shift($tmpimgs);
            }
            $update = array(
                'subject' => $subject,
                'content' => $res_content['content'],
                'lastupdate' => TIMESTAMP,
                'image' => $image,
            );
            $resStatus = Model('article')->where(['aid' => $aid])->update($update);
            //文章添加成功并且有图片
            if ($resStatus && $image) {
                //先将所有图片改为未使用
                Model('image')->where(['uid' => session('user.uid'), 'aid' => $aid])->update(['status' => 0]);
                //将图片状态改为正在使用，图片归属　id　改为文章 aid 
                $where['uid'] = session('user.uid');
                $where['id'] = ['in', $res_content['image']];
                Model('image')->where($where)->update(['aid' => $aid, 'status' => 1]);
                fCache('sideBar', NULL);
                $this->ajaxReturn('添加成功', '?m=blog&c=article&a=view&aid=' . $aid);
            } else {
                if (!$resStatus) {
                    $this->ajaxReturn('添加失败', '', false);
                } else {
                    //删除缓存
                    fCache('sideBar', NULL);
                    $this->ajaxReturn('添加成功', '?m=blog&c=article&a=view&aid=' . $aid);
                }
            }
        }

        //跳转到登陆页面
        if (!session('user.uid')) {
            $this->showError('请先登录', ['url' => 'javascript:;', 'linkclass' => 'login', 'linkmsg' => '点击登录']);
        }
        $aid = input('get.aid', 0);
        if (!$aid || !is_numeric($aid)) {
            $this->showError('编辑文章不存在');
        }

        $articleInfo = Model('article')->getArticleInfo($aid);
        if (!$articleInfo) {
            $this->showError('编辑文章不存在');
        }
        //是否有图片
        $aidattach = $articleInfo['image'] ? $aid : 0;
        //编辑器使用的文本
        $defaultcontent = Model('article')->ubb2html($articleInfo['content'], $aidattach, 'update');
        if (session('user.uid') != $articleInfo['authorid'] || ($articleInfo['status'] != 1 && !IS_ADMIN)) {
            $this->showError('无权编辑该文章');
        }

        $this->assign('pageTitle', '编辑文章 - ' . $articleInfo['subject']);
        $this->assign('defaultcontent', $defaultcontent);
        $this->assign('articleInfo', $articleInfo);
        $this->assign('type', 'update');
        $this->assign('aid', $aid);
        $this->display('write');
    }

}
