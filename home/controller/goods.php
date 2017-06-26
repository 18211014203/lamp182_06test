<?php

	class goods
	{
		// 商品列表
		function index()
		{
			// 拿到以及类别信息,为的是遍历处菜单
			$types = DB::Table('shop_type') -> select("where pid=0 order by npath");
			
			// 要查看的商品类型
			$tid = $_GET['tid'];
			// select * from shop_type where npath like '%-1-%';
			
			// 获取类型中的子类tid
			$tids = DB::Table('shop_type') -> select("where npath like '%-{$tid}-%'");
			//echo '<pre>';
			// print_r($tids);
			
			// 把该类以及它的子类拼接成一个字符串 tid in(字符串) tid in(本类,子类1,子类2,子类3)
			$str = [];
			foreach($tids as $k=>$v){
				$str[]= $v->tid;
			}
			$xxoo = implode(',',$str);
			
			// 根据所有类别 查出相应的商品
			$goods = DB::Table('shop_goods') -> select("where tid in({$xxoo})");
			
			// print_r($goods);
			
			// 把数据显示到页面上
			require('./view/goods/index.html');
		}
		
		
		// 商品详情
		function details()
		{
			// 接收ID
			$gid = $_GET['gid'];
			
			// 查询该商品信息
			$goods = DB::Table('shop_goods') -> select(" where gid={$gid}")[0];
			
			//显示到页面
			require('./view/goods/details.html');
		}
	}

?>