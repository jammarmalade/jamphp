<?php

return [
	'defaultModule'				=> 'home',//默认模块
	'defaultController'			=> 'index',//默认控制器
	'defaultAction'				=> 'index',//默认方法
	'defaultTheme'				=> 'web',//主题
	'defaultLayout'				=> 'main',//默认布局文件
	'tablePrefix'				=> 't_',//表前缀
	//数据配置
	'db'=> [
		//多个配置 ,第一个为主库
		[
			'host' => 'localhost',
			'user' => 'root',
			'pwd' => '123456',
			'dbname' => 'test',
		],
		//后面可有多个,但都是从库,可做读写分离
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
	],

];