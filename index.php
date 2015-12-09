<?php


//根目录
define('__ROOT__', __dir__.'/');
//工作目录
define('APP_PATH', __ROOT__.'app/');


require './framework/jam.class.php';

(new Jam\jam)->run();

?>

