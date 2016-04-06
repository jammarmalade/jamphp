<?php

namespace Jam;

class jam {

    public function run() {
        //报告所有错误(除了提醒，其他都报错，提醒类的错误，将在 shutdown 中提示，以写出优质的代码)
        error_reporting(E_ALL ^ E_NOTICE);
        //是否 开启 debug
        define('DEBUG', true);
        if (DEBUG) {
            global $_debug;
            $_debug['startTime'] = microtime(true);
            $_debug['startMemery'] = memory_get_usage();
            $prePath = '/jamphp';
        }else{
            $prePath = '';
        }

        //异常处理
        set_exception_handler('Jam\jam::jamException');
        //错误处理 (除了提醒，其他都报错)
        set_error_handler('Jam\jam::jamError',E_ALL ^ E_NOTICE);
        //定义PHP程序执行完成后执行的函数(测试错误时注释:set_exception_handler ,set_error_handler)
        register_shutdown_function('Jam\jam::jamShutdown');
        //自动加载类 php 5.1+    function __autoload($class)
        spl_autoload_register('Jam\jam::autoload');

        // ' " \ NULL 等字符转义 当magic_quotes_gpc=On的时候，函数get_magic_quotes_gpc()就会返回1
        define('MAGIC_QUOTES_GPC', function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc());
        // ob 缓存压缩输出
        define('GZIP', function_exists('ob_gzhandler'));
        //东八区 北京时间
        date_default_timezone_set('Etc/GMT-8');
        define('TIMESTAMP', time());
        define('FORMATTIME', date('Y-m-d H:i:s',TIMESTAMP));
        //是否是ajax 请求
        $ajax = 0;
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $ajax = 1;
        }
        define('AJAX', $ajax);
        //xss 攻击检查
        $this->_xssCheck();

        //-----其它配置项-----
        //引入公共方法
        include(__ROOT__ . '/framework/functions.php');
        //获取配置信息
        $config = config();
        //常用路径常量定义
        define('STATIC_DIR', $prePath.$config['staticPath']);
        define('JS_DIR', $prePath.$config['jsPath']);
        define('CSS_DIR', $prePath.$config['cssPath']);
        define('IMG_DIR', $prePath.$config['imgPath']);
        define('CACHE_DIR', $config['cachePath']);
        //常用 数据常量 设置
        define('PAGE', max(isset($_GET['page']) ? $_GET['page'] : 1,1));
        define('REQUEST_IP', get_client_ip());
        define('SITE_URL', get_site_url());
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : SITE_URL;
        define('REFERER', substr($referer, -1) == '?' ? substr($referer, 0, -1) : $referer);

        //-----初始输入项-----
        //若开启安全项 ,最好的方法是设置为 magic_quotes_gpc=off
        if (MAGIC_QUOTES_GPC) {
            $_GET = astripslashes($_GET);
            $_POST = astripslashes($_POST);
            $_COOKIE = astripslashes($_COOKIE);
        }

        //-----初始输出项-----
        //浏览器是否支持 gzip 压缩
        $agentGzip = true;
        if (!empty($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') === false) {
            $agentGzip = false;
        }
        // 浏览器支持压缩，不是ajax 请求，并且  ob_gzhandler 方法存在
        $allowgzip = $agentGzip && !$ajax && GZIP;
        if (!ob_start($allowgzip ? 'ob_gzhandler' : null)) {
            //在此之前不能有输出
            ob_start();
        }

        //-----执行应用-----
        $module = isset($_GET['m']) ? strtolower($_GET['m']) : $config['defaultModule'];
        define('MODULE_NAME', $module);
        $controller = isset($_GET['c']) ? strtolower($_GET['c']) : $config['defaultController'];
        define('CONTROLLER_NAME', $controller);
        $action = isset($_GET['a']) ? strtolower($_GET['a']) : $config['defaultAction'];
        define('ACTION_NAME', $action);
        //当前模块的视图路径
        define('VIEW_PATH', APP_PATH.MODULE_NAME.'/view/');

        $class = $this->parseController($controller, $module);
        //利用 反射方法 
        $method = new \ReflectionMethod($class, $action);

        //是 public 方法,并且不是 static 静态方法
        if ($method->isPublic() && !$method->isStatic()) {
            // 不带参数 ，若方法上有参数 则用 invokeArgs；
            $method->invoke($class);
        } else {
            //抛出异常,会自动被 jamException 捕获
            throw new \Exception("method " . $action . " maybe does't exists ,or Modifier isn't public");
        }
    }

    //实例化控制器
    public function parseController($c, $m) {
        static $_instanceC;
        $path = $m . '\Controller\\' . $c . 'Controller';
        if (!$_instanceC[$path]) {
            $_instanceC[$path] = new $path();
        }
        return $_instanceC[$path];
    }

    //xss 攻击检查
    private function _xssCheck() {
        static $check = array('"', '>', '<', '\'', '(', ')', 'CONTENT-TRANSFER-ENCODING');
        $temp = $_SERVER['REQUEST_URI'];
        if (!empty($temp)) {
            $temp = strtoupper(urldecode(urldecode($temp)));
            foreach ($check as $str) {
                if (strpos($temp, $str) !== false) {
                    throw new \Exception("非法操作");
                }
            }
        }
        return true;
    }

    //自定义异常处理
    public static function jamException($exc) {
        \Framework\Error::exceptionError($exc);
    }

    //自定义错误处理 (一般用于调试,E_ERROR:E_PARSE:E_CORE_ERROR:E_COMPILE_ERROR:E_USER_ERROR: 这些级别的错误，都将提示)
    public static function jamError($errno, $errstr, $errfile, $errline) {
        //若是 debug 
        if (DEBUG) {
            \Framework\Error::errorError($errno, $errstr, $errfile, $errline);
        }
    }

    //自定义 程序执行完成或意外死掉导致PHP执行即将关闭时，将被调用的函数
    public static function jamShutdown() {
        //若是 debug 调试用并且不是ajax请求
        if (DEBUG && !AJAX) {
//        if (DEBUG) {
            \Framework\Error::shutdownError();
        }
    }

    //自动加载
    public static function autoload($class) {
        $class = strtolower($class);
        $prePath = __ROOT__;
        $namespace = explode('\\', $class)[0];
        if ($namespace != 'framework') {
            $prePath = APP_PATH;
        }
        //提取路径 并 加上类文件后缀
        $path = $prePath . str_replace('\\', '/', $class) . '.class.php';
        if (!is_file($path)) {
            throw new \Exception("class file does't exists!!!" . $path);
        }
        include $path;
    }

}

?>