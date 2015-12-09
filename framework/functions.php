<?php


function test(){
	echo 'test function !!!';
}

function astripslashes($string) {
	if(empty($string)) return $string;
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = astripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}

//实例化模型
function ModelIstance($name = ''){
	static $_instanceM;
	if($name){
		$path = MODULE_NAME.'\\Model\\'.$name.'Model';
	}else{
		$path = 'Framework\\'.$name.'Model';
	}
	if(!$_instanceM[$path]){
		$_instanceM[$path] = new $path($name);
	}
	return $_instanceM[$path];
}

function config($key=''){
	static $_config;
	if(!$_config){
		$config = include(__ROOT__.'/app/common/config/config.php');
		$_config = $config;
	}
	return $key ? $_config[$key] : $_config;
}

function fput($msg, $arr = 0,$ext = '') {
    $time = date('Y-m-d H:i:s', TIMESTAMP);
    $ext .= CONTROLLER_NAME.'  - '.ACTION_NAME;
    if ($arr) {
        file_put_contents(__ROOT__.'/log.txt', var_export($msg, true) . ' - '.$ext.' - ' . $time . PHP_EOL, FILE_APPEND);
    } else {
        file_put_contents(__ROOT__.'/log.txt', $msg . ' - '.$ext.' - ' . $time . PHP_EOL, FILE_APPEND);
    }
}
function printarr($arr){
	echo "<pre>";
	print_r($arr);
	echo "</pre>";
}

function size_count($size) {
    $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}