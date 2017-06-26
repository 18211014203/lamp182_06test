<?php

	// 数据库配置信息
	const HOST = 'localhost';
	const DBNAME = 'nwj';
	const USER = 'root';
	const UPWD = '';
	const DSN = "mysql:host=".HOST.";dbname=".DBNAME.";charset=utf8";
	
	
	// 配置文件上传信息
	const SAVEPATH = '../uploads/goods/';
	const TYPES = ['image/jpeg','image/png','image/gif'];
	const UPSIZE = 2048000;