<?php


//��Ŀ¼
define('__ROOT__', __dir__.'/');
//����Ŀ¼
define('APP_PATH', __ROOT__.'app/');


require './framework/jam.class.php';

(new Jam\jam)->run();

?>

