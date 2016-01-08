<?php

namespace Framework;

class Error {

    //异常处理方法
    public static function exceptionError($e) {

        //抛出的错误信息
        $errorMsg = 'code : ' . $e->getCode() . '<br>message : ' . $e->getMessage();
        if (method_exists($e, 'getSql')) {
            $errorMsg.='<br><br><div>SQL : ' . $e->getSql() . '</div>';
        }
        //追踪信息
        $debug_trace = $e->getTrace();
        //逆向排序(这样就是从文件入口开始)
        krsort($debug_trace);
        $extend='';
        foreach ($debug_trace as $k => $error) {
            //调用的方法
            $fun = $error['function'];
            //若存在调用的类
            if (isset($error['class'])) {
                $fun = $error['class'] . $error['type'] . $error['function'];
            }
            if(isset($error['file'])){
                $extend.='<span style="width:500px;">file: ' . $error['file'] . '</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                $extend.='<span style="width:100px;">line: ' . $error['line'] . '</span>&nbsp;&nbsp;&nbsp;&nbsp;';
            }
            $extend.='<span>function: ' . $fun . '</span><br>';
        }
        //记录错误日志
        $cacheName = 'error_'.date('Ymd', TIMESTAMP) . '.log';
        $exesql = method_exists($e, 'getSql') ? $e->getSql() : '';
        $logmsg = date('Ymd-H:i:s') . ' [-] ' . $e->getCode() . ' [-] ' . $e->getMessage() .' [-] '.$exesql.' [-] '.$extend. PHP_EOL;
        $path = __ROOT__.CACHE_DIR.'log/'.$cacheName;
        if ($fp = fopen($path, 'a+')) {
            flock($fp, 2);
            fwrite($fp, $logmsg);
            fclose($fp);
        }

        if(AJAX){
            $return['status'] = false;
            $return['msg'] = $e->getSql();
            $return['data'] = '';
            $res = json_encode($return, JSON_UNESCAPED_UNICODE);
            echo $res;
            exit();
        }
        self::errorPage($errorMsg, 'db', $extend);
    }

    //错误处理方法
    public static function errorError($errno, $errstr, $errfile, $errline) {
        $error['type'] = $errno;
        $error['message'] = $errstr;
        $error['file'] = $errfile;
        $error['line'] = $errline;
        self::runInfo('错误信息',$error);
        exit();
    }
    
    public static function runInfo($infoTitle= '',$error=''){
        global $_debug;
        $endTime = microtime(true);
        $endMemery = memory_get_usage();

        $shutDownHtml = '<h3>'.$infoTitle.'</h3>';
        $shutDownHtml .= '<div>用时 : ' . (number_format($endTime - $_debug['startTime'], 4)) . ' s</div>';
        $shutDownHtml .= '<div>内存使用 : ' . size_count($endMemery - $_debug['startMemery']).'</div>';
        if (isset($_debug['sql'])) {
            $shutDownHtml .= '<div>执行了 ' . count($_debug['sql']) . ' 条 sql</div>';
            foreach($_debug['sql'] as $v){
                $shutDownHtml .= '<div style="padding-left:20px;">'.$v.'</div>';
            }
        }
        if(!$error){
            $error = error_get_last();
        }
        //主要为提醒类错误，以使开发者写出规范的代码
        if($error){
            $shutDownHtml .= '<div>错误信息 : ' . $error['message'] . ' </div>';
            $shutDownHtml .= '<div>错误文件 : ' . $error['file'] . ' </div>';
            $shutDownHtml .= '<div>错误行号 : ' . $error['line'] . ' </div>';
        }
        echo <<<EOF
<style type="text/css">
.shut-div{
  padding: 15px;
  margin-bottom: 20px;
  border: 1px solid transparent;
  border-radius: 4px;
  color: #a94442;
  background-color: #fcf8e3;
  border-color: #ebccd1;
}
</style>   
<div class='shut-div'>$shutDownHtml</div>
EOF;
    }
    //运行信息
    public static function shutdownError() {
        self::runInfo('运行信息');
    }
    
    public static function errorPage($msg, $type = '', $extend = '') {
        
        echo <<<EOT
<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<title>系统错误 Error</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE" />
	<style type="text/css">
	.alert {
	  padding: 15px;
	  margin-bottom: 20px;
	  border: 1px solid transparent;
	  border-radius: 4px;
	}
	.alert-danger {
	  color: #a94442;
	  background-color: #f2dede;
	  border-color: #ebccd1;
	}
	.alert-warning{
	  color: #a94442;
	  background-color: #fcf8e3;
	  border-color: #ebccd1;
	}
	.alert-danger div{
	  font-weight:bold;
	  padding:5px 5px;
	  color: #fff;
	  background-color:#FF6464
	}
	.alert-warning span{
	  display:inline-block;
	}
	</style>
</head>
<body>
<div id="container">
<h2>系统错误 Error</h2>
	<div class='alert alert-danger'>$msg</div>
EOT;
        if ($extend) {
            echo "<div class='alert alert-warning'>$extend</div>";
        }
        echo <<<EOT
</body>
</html>
EOT;
        exit();
    }

}
