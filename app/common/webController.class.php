<?php

namespace Common;

use \Framework\Controller;

class webController extends Controller {

    public $mobile = false; //是否是手机端
    public $siteName = '我的博客'; //站点名称
    public $pageTitle = '我的博客';
    public $pageKeywords = 'php,nginx,mysql';
    public $pageDescription = '程序小白的工作和学习日记';

    public function __construct() {
        parent::__construct();

        if ($this->checkMobile()) {
            $this->theme = 'mobile';
            $this->mobile = true;
        }
        $this->assign('pageTitle', $this->pageTitle);
        $this->assign('pageKeywords', $this->pageKeywords);
        $this->assign('pageDescription', $this->pageDescription);
        //获取blog配置缓存
        $setting = $this->setting();
        $this->assign('setting', $setting);
        //验证登录
        
    }
    public function setting() {
        static $setting ;
        if (!$setting) {
            //获取缓存
            $cacheName = 'setting';
            $setting = fCache($cacheName);
            //若没有缓存，获取数据库的
            if(!$setting){
                $tmp = Model('setting')->fetchAll();
                foreach ($tmp as $k => $v) {
                    $setting[$v['sname']] = json_decode($v['svalue'], true);
                }
                fCache($cacheName,$setting);
            }
        }
        return $setting;
    }

    private function checkmobile() {
        static $mobilebrowser_list = array('iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini',
            'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung',
            'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser',
            'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource',
            'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone',
            'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop',
            'benq', 'haier', '^lct', '320x320', '240x320', '176x220');
        $pad_list = array('pad', 'gt-p1000');

        $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if ($this->dstrpos($useragent, $pad_list)) {
            return false;
        }
        if (($v = $this->dstrpos($useragent, $mobilebrowser_list, true))) {
            $this->mobile = $v;
            return true;
        }
        $brower = array('mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop');
        if ($this->dstrpos($useragent, $brower))
            return false;
    }

    //判断是平板电脑还是手机
    private function dstrpos($string, &$arr, $returnvalue = false) {
        if (empty($string))
            return false;
        foreach ((array) $arr as $v) {
            if (strpos($string, $v) !== false) {
                $return = $returnvalue ? $v : true;
                return $return;
            }
        }
        return false;
    }

}
