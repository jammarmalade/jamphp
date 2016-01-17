<?php

return [
    'defaultModule' => 'blog',          //默认模块
    'defaultController' => 'index',     //默认控制器
    'defaultAction' => 'index',         //默认方法
    'defaultTheme' => 'web',            //默认主题
    'defaultLayout' => 'main',          //默认布局文件
    'tablePrefix' => 'pre_',            //表前缀
    'staticPath' => '/static',         //静态资源地址
    'jsPath' => '/static/js',          //js文件路径
    'cssPath' => '/static/css',        //css文件路径
    'imgPath' => '/static/image',      //图片文件路径
    'cachePath' => '/data/',            //缓存路径
    'cookiePre' => 'jamphp_',            //cokkie 前缀
    //数据配置
    'db' => [
        //多个配置 ,第一个为主库
        [
            'host' => 'localhost',
            'user' => 'root',
            'pwd' => '123456',
            'dbname' => 'blog',
        ],
        //后面可有多个,但都是从库,可做读写分离
        /*
        [
            'host' => '192.168.1.200',
            'user' => 'root',
            'pwd' => '123456',
            'dbname' => 'test',
        ],
        [
            'host' => '192.168.1.201',
            'user' => 'root',
            'pwd' => '123456',
            'dbname' => 'test',
        ],
         */
    ],
];
