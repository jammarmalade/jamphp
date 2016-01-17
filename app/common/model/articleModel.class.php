<?php

namespace Common\Model;

use \Framework\Model;

class articleModel extends Model {

    public $tablename = 'article';

    public function __construct() {
        parent::__construct($this->tablename);
    }
    
    public function getArticleList($pageNow = 1,$limit = 10){
        $startLimit = ($pageNow - 1) * $limit;
        $whereArticle['status'] = 1;
        $articleList = $this->where($whereArticle)->order('dateline DESC')->limit($startLimit,$limit)->fetchAll();
        $imgids = [];
        foreach ($articleList as $k => $v) {
            $articleList[$k]['content'] = cutstr($this->strip_ubb($v['content']), 300);
            $articleList[$k]['formattime'] = formatTime($v['dateline'], 1);
            $articleList[$k]['time'] = formatTime($v['dateline']);
            $articleList[$k]['link'] = '?m=blog&c=article&a=view&aid=' . $v['aid'];
            if ($v['image']) {
                $imgids[$v['aid']] = $v['image'];
            }
        }
        //获取图片数据
        if ($imgids) {
            $str_ids = join(',', $imgids);
            $where['id'] = array('in',$str_ids);
            $imginfos =  Model('image')->where($where)->fetchAll();
            foreach ($imginfos as $k => $v) {
                $suff = $v['thumbH'] ? '.thumb.jpg' : '';
                $imgids[$v['aid']] = SITE_URL . $v['path'] . $suff;
            }
        }
        $count=$this->where($whereArticle)->count();
        //组织分页html
        $pageHtml=simplePage($count,$pageNow,$limit,'?m=blog&c=article&a=list');
        $return['articleList'] = $articleList;
        $return['pageHtml'] = $pageHtml;
        $return['imgids'] = $imgids;
        return $return; 
    }
    public function html2ubb($content, $image = 0) {
        $content = preg_replace(array(
            '/<pre>([\s\S]*?)<\/pre>/i',
            '/<pre code="(\w+)">([\s\S]*?)<\/pre>/i',
            '/<(p|div)[^>]*?>/i',
            '/<\/(p|div)>/',
            '/<br[^>]*?>/i',
            '/<blockquote>(.+?)<\/blockquote>/i',
            '/<h3>(.+?)<\/h3>/i',
            '/<a[^>]*?href="([^"]+?)"[^>]*?>(.+?)<\/a>/i',
            '/<span\s*style="font\-weight:\s*bold;">(.+?)<\/span>/i',
            '/<span\s*style="font\-style:\s*italic;">(.+?)<\/span>/i',
            '/<span\s*style="text\-decoration:\s*underline;">(.+?)<\/span>/i',
            '/<(b|i|u|kbd)>(.+?)<\/\\1>/i',
            '/<hr>/',
                ), array(
            "[code=php]$1[/code]",
            "[code=$1]$2[/code]",
            "[div]",
            "[/div]",
            "\n",
            "[quote]$1[/quote]",
            "[h3]$1[/h3]",
            "[url=$1]$2[/url]",
            "[b]$1[/b]",
            "[i]$1[/i]",
            "[u]$1[/u]",
            "[$1]$2[/$1]",
            "[hr]",
                ), $content);
        //转换代码文本
        $content = preg_replace_callback("/\[code=(\w+)\]([\s\S]*?)\[\/code\]/is", function($matches) {
            return $this->_code($matches[1],$matches[2],'ubb');
        }, $content);

        if ($image) {//是否转换图片
            $content = preg_replace('/<img[^>]*?data\-filename="(\d+)"[^>]*?>/i', "[attach]$1[/attach]", $content); //本站
            $content = preg_replace('/<img[^>]*?src="([^"]+?)"[^>]*?>/i', "[img=$1]", $content); //站外
            preg_match_all('/\[attach\](\d+)\[\/attach\]/', $content, $find);
            $return['content'] = strip_tags($content);
            $return['image'] = isset($find[1]) ? $find[1] : array();
            return $return;
        } else {
            return strip_tags($content);
        }
    }

    public function strip_ubb($content) {
        $tmp = preg_replace(array('/\[url=[^\]]+?\](.+?)\[\/url\]/i', '/' . PHP_EOL . '/i', '/\r|\n|\r\n/'), array("$1", '<br>', '<br>'), $content);
        //代码文本
        $tmp = preg_replace_callback("/\[code=(\w+)\]([\s\S]*?)\[\/code\]/is", function($matches) {
            return htmlspecialchars(htmlspecialchars_decode($matches[2],ENT_QUOTES),ENT_QUOTES);
        }, $tmp);
        $tmp = preg_replace(array(
            '/\[attach\]\d+\[\/attach\]/i',
            '/\[hr\]/i',
            '/\[img=[^\]]+?\]/i',
            '/\[[a-z0-9]+\]/i',
            '/\[\/[a-z0-9]+\]/i',
                ), '', $tmp);
        return preg_replace('/^(<br>| |&nbsp;){1,}/', '', $tmp); //删除头部的br
    }

//$attach 为文章id(有图片附件时才传)
    public function ubb2html($content, $attach = 0, $type = '') {
        if ($type != 'update') {
            $content = preg_replace(array(
                "/\[div\]/i",
                "/\[\/div\]/i",
                "/\n|\r|\r\n/",
                "/\[code\]([\s\S]*?)\[\/code\]/i",
                "/\[quote\]([\s\S]*?)\[\/quote\]/i",
                "/\[h3\](.+?)\[\/h3\]/i",
                "/\[url=([^\]]+?)\](.+?)\[\/url\]/i",
                "/\[(b|i|u|kbd)\](.+?)\[\/\\1\]/i",
                "/\[hr\]/i",
                "/\[img=([^\]]+?)\]/i",
                    ), array(
                '<div>',
                '</div>',
                PHP_EOL,
                '<pre>$1</pre>',
                '<blockquote>$1</blockquote>',
                '<h3>$1</h3>',
                '<a href="$1" target="_blank">$2</a>',
                '<$1>$2</$1>',
                '<hr>',
                '<img src="$1" width="600px">',
                    ), $content);
            //转换代码文本
            $content = preg_replace_callback("/\[code=(\w+)\]([\s\S]*?)\[\/code\]/is", function($matches) {
                return $this->_code($matches[1],$matches[2]);
            }, $content);
        }
        
        if ($attach) {
            $whereImg['aid'] = $attach;
            $whereImg['type'] = 'article';
            $resattachs = Model('image')->field('id,path,thumbH')->where($whereImg)->fetchAll();

            if ($resattachs) {
                $find = $replace = array();
                foreach ($resattachs as $k => $v) {
                    $find[] = "/\[attach\]" . $v['id'] . "\[\/attach\]/i";
                    $width = $suff = '';
                    if ($v['thumbH']) {
                        $width = 'width="600px"';
                        $suff = '.thumb.jpg';
                    }
                    $imgurl = SITE_URL . $v['path'] . $suff;
                    if ($type == 'update') {
                        $replace[] = '<img src="' . $imgurl . '" ' . $width . ' data-filename="' . $v['id'] . '">';
                    } else {
                        $replace[] = '<img src="' . IMG_DIR . '/l.gif" class="lazy" data-original="' . $imgurl . '" ' . $width . ' data-filename="' . $v['id'] . '">';
                    }
                }
                $content = preg_replace($find, $replace, $content);
            }
        }
        if ($type != 'update') {
            return $content;
        } else {
            //	return preg_replace("/(\t|\r\n|\r|\n){1,}/i",'<br>',htmlspecialchars(htmlspecialchars_decode($content,ENT_QUOTES),ENT_QUOTES));
            return preg_replace("/(\r\n|\r|\n){1,}/i", '<br>', str_replace("'", "\\'", $content));
        }
    }

//评论内容过滤
    public function commentubb($content) {
        if ($content == '') {
            return '';
        }

        return strip_tags(htmlspecialchars($content, ENT_QUOTES));
    }

    public function _code($code, $content, $type = 'html') {
        $content = str_replace(array("&nbsp;", "\\'", '\\"'), array(' ', '&#039;', '&quot;'), $content);
        $content = htmlspecialchars(htmlspecialchars_decode($content, ENT_QUOTES), ENT_QUOTES);
        if ($type == 'html') {
            if (in_array($code, array('html', 'php', 'javascript', 'js'))) {
                return '<pre class="brush:' . $code . '; toolbar: false; auto-links: false;">' . $content . '</pre>';
            } else {
                return "<pre>$content</pre>";
            }
        } else {
            return '[code=' . $code . ']' . $content . '[/code]';
        }
    }
    
    //获取文章
    public function getArticleInfo($aid){
        $where['aid'] = $aid;
        return $this->where($where)->fetch();
    }
    //增加查看次数
    public function addViews($aid) {
        $cookiekey = 'view-' . $aid;
        if (getcookie($cookiekey) != $aid) {
            $this->query("UPDATE %t SET views=`views`+1 WHERE aid=%d",array('article',$aid));
            bsetcookie($cookiekey, $aid, 300);
        }
        return true;
    }

}
