<?php

//----------------------异常处理
/*
set_exception_handler('myException');

function myException($e){
	//可自定义样式
	echo $e->getMessage();
}
throw new Exception("myException test");
*/

//----------------------错误处理
/*
set_error_handler('myError');
function myError($errno, $errstr, $errfile, $errline){
	echo $errno.'<br>';
	echo $errstr.'<br>';
	echo $errfile.'<br>';
	echo $errline.'<br><br>';
}
//不规范的写法
$in = $_GET['in'] ? $_GET['in'] : 1;

//触发器调用
trigger_error('myError test');
*/
//----------------------定义PHP程序执行完成后执行的函数
/*

register_shutdown_function('myShutdown');

echo $in;
function myShutdown(){
	//
	$error = error_get_last();
	echo "<pre>";
	print_r($error);
	echo "</pre>";
	echo 'myShutdown test';
}

*/

