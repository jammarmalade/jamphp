<?php

namespace Framework;

class Controller {

    public $theme = '';
    public $layout = '';
    private $_params = [];

    public function __construct() {
        $this->theme = config('defaultTheme');
        $this->layout = config('defaultLayout');
    }
    /**
     * 显示模板
     * @param string 模板文件名称，默认为方法名
     * @param boolean true/false ,是否返回缓存的数据，默认 false（直接输出），true（返回数据 用户ajax返回html）
     * @return type
     */
    public function display($view = '', $return = false) {
        //默认为 方法名
        $viewName = ACTION_NAME;
        if ($view) {
            $viewName = $view;
        }
        //编译模板文件，并返回编译后的文件路径

        $viewFile = $this->fetchTemplate($viewName, $return);
        //从数组中将变量导入到当前的符号表,第二个参数，默认为 EXTR_OVERWRITE (就是覆盖)
        extract($this->_params);
        //已开启 ob_startob_start();
        ob_implicit_flush(false); //缓存输出，不立即flush到浏览器
        require($viewFile);

        //返回缓存的数据，并清空缓存数据
        $content = ob_get_clean();
        if ($return) {
            return $content;
        } else {
            @header('Content-Type: text/html; charset=utf-8');
            echo $content;
            exit();
        }
    }

    /**
     * 为模版分配变量
     * @param string 分配的变量名
     * @param string/array 分配的变量值
     */
    public function assign($key, $value) {
        $this->_params[$key] = $value;
    }

    //自定义异常处理方法
    public function error($msg) {
        throw new \Exception($msg);
    }
    /**
     * ajax 返回数据
     * @param string 提示内容
     * @param string/array 返回的数据
     * @param boolean true/false,默认是true
     */
    public function ajaxReturn($msg, $data='', $status = true) {

        $return['status'] = $status;
        $return['msg'] = $msg;
        $return['data'] = $data;
        $res = json_encode($return, JSON_UNESCAPED_UNICODE);
        echo $res;
        exit();
    }
    /**
     * 返回模板文件编译后的php文件真实路径
     * @param string 模版文件名，如：common/view，
     * @param boolean true/false ,是否返回数据，据此来使用布局模板，默认 false （不返回，使用布局模板）
     * @return type
     */
    public function fetchTemplate($viewPath, $return = false) {
        if(strpos($viewPath,'/' )){
            list($preFileName,$viewName) = explode('/', $viewPath);
        }else{
            $preFileName = CONTROLLER_NAME;
            $viewName = $viewPath;
        }
        //模板文件路径(可自定义)
        //若有主题
        $prePath = '';
        if ($this->theme) {
            $prePath = $this->theme . '/';
        }
        $viewFile = APP_PATH . MODULE_NAME . '/view/' . $prePath . $preFileName . '/' . $viewName . '.html';
        //不存在，则抛出异常
        if (!is_file($viewFile)) {
            $this->error("template does't exists!!!\n" . $viewFile);
        }
        //编译后存放的路径
        $tplcachepath = __ROOT__ . CACHE_DIR.'tplcache/' . $this->theme . '-'.MODULE_NAME.'-' . CONTROLLER_NAME . '-'.ACTION_NAME.'-' . $viewName . '.tpl.php';
        //是否有布局模板
        $layoutpath = '';
        if ($return == false && $this->layout) {
            $layoutpath = APP_PATH . MODULE_NAME . '/view/' . $prePath . $this->layout . '.html';
        }

        //编译模版
        $template = new \Framework\Template;
        $viewfile = $template->display($viewFile, $tplcachepath, $layoutpath);
        return $viewfile;
    }

    //自定义错误处理方法
    public function halt($errstr) {
        //错误触发器
        trigger_error($errstr);
        exit();
    }
    
    /**
     * 显示错误页面
     * @param string/array 提示信息，也可以是数组
     * @param array $ext，额外信息，数组。url，跳转链接
     */
    public function showError($msg='',$ext=[]){
        if(!isset($ext['url'])){
            $ext['url'] = getReferer();
        }
        if(!isset($ext['linkmsg'])){
            $ext['linkmsg'] = '返回上一级';
        }
        
        $this->assign('msg', $msg);
        $this->assign('ext', $ext);
        $this->display('common/_error');
    }
}
