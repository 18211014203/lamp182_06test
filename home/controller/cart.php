<?php

	/*
		购物车控制器
	*/
	
	class cart
	{
		// 添加到购物车
		function tocart()
		{
			// 接收商品ID
			$gid = $_GET['gid'];
			
			// 放入session数组,商品ID做下标
			if (empty($_SESSION['cart'][$gid])) {
				$_SESSION['cart'][$gid] = DB::Table('shop_goods')->select(' where gid='.$gid)[0];
				
				$_SESSION['cart'][$gid] -> cnt = 1;
			} else {
				$_SESSION['cart'][$gid] -> cnt += 1 ;
			}
			
			// 给goods变量赋值
			$goods = $_SESSION['cart'][$gid];
			
			// echo '<pre>';
			// print_r($_SESSION['cart']);
			
		// 显示加入购物车成功页面
		require('./view/goods/precart.html');	
			
		}
		
		
		// 购物车列表
		function index()
		{
			// 显示列表
			require('./view/cart/mycart.html');
		}
		
		
		// 从购物车中删除一种商品
		function del()
		{
		   $gid = $_GET['gid'];
		   unset($_SESSION['cart'][$gid]);
		   header('refresh:0;url=./index.php?c=cart&a=index');
		   die;
		}
		
		
		// 购物车中商品加1
		function increment()
		{
			$gid = $_GET['gid'];
			$_SESSION['cart'][$gid] -> cnt += 1;
			header('refresh:0;url=./index.php?c=cart&a=index');
			die;
		}
		
		// 购物车中商品减1
		function decrement()
		{
			$gid = $_GET['gid'];
			$_SESSION['cart'][$gid] -> cnt -= 1;
			if ($_SESSION['cart'][$gid] -> cnt < 1) {
			    $_SESSION['cart'][$gid] -> cnt = 1;
			}
			
			header('refresh:0;url=./index.php?c=cart&a=index');
			die;
		}
	}

?>