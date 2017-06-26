<?php

	session_start();
	header('content_type:text/html;charset=utf-8');
	date_default_timezone_set('PRC');
	
	$c = empty($_GET['c']) ? 'first' : $_GET['c'];
	$a = empty($_GET['a']) ? 'index' : $_GET['a'];
	
	// 引入公共配置文件
	require('./public/config.php');
	
	// 引入数据库操作类
	require('./model/db.php');
	
	// 引入控制器
	require("./controller/{$c}.php");
	
	// 实例化
	$obj = new $c;
	
	$obj -> $a();

?>