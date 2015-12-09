<?php

namespace Framework;

class Error{
	//异常处理方法
	public static function exceptionError($e){
		
		//抛出的错误信息
		$errorMsg = '<font color="red">'.$e->getMessage().'</font>';
		//追踪信息
		$debug_trace=$e->getTrace();
		//逆向排序(这样就是从文件入口开始)
		krsort($debug_trace);
		$extend = '<div style="background-color:yellow;">';
		foreach($debug_trace as $k=>$error){
			//调用的方法
			$fun=$error['function'];
			//若存在调用的类
			if($error['class']){
				$fun=$error['class'].$error['type'].$error['function'];
			}
			$extend.='<span style="width:500px;">file: '.$error['file'].'</span>&nbsp;&nbsp;&nbsp;&nbsp;';
			$extend.='<span style="width:100px;">line: '.$error['line'].'</span>&nbsp;&nbsp;&nbsp;&nbsp;';
			$extend.='<span>function: '.$fun.'</span><br>';
		}
		$extend .='</div>';
		@header('Content-Type: text/html; charset=utf-8');
		if(method_exists($e,'getSql')){
			$errorMsg .= '<br>'.$e->getSql();
		}
		echo $errorMsg.' <br> '.$extend;
		exit();
	}
	//错误处理方法
	public static function errorError($errno, $errstr, $errfile, $errline){
		echo $errno.'<br>';
		echo $errstr.'<br>';
		echo $errfile.'<br>';
		echo $errline.'<br>';

		exit();
	}

	//
	public static function shutdownError(){
		global $_debug;
		$endTime = microtime();
		$endMemery = memory_get_usage();
		
		
		echo '用时:'.($endTime - $_debug['startTime']).'<br>';
		echo '内存使用:'.size_count($endMemery - $_debug['startMemery']);
		echo '<br>';
		if(isset($_debug['sql'])){
			echo '执行了 '.count($_debug['sql']).' 条 sql';
			echo "<pre>";
			print_r($_debug['sql']);
			echo "</pre>";
		}

		$error = error_get_last();
		echo "<pre>";
		print_r($error);
		echo "</pre>";
		echo 'shutdownError';
	}
}