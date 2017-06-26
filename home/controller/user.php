<?php

	/* 用户控制器 */
	
	class User
	{
		// 登录
		function login()
		{
			require('./view/user/login.html');
		}
		
		// 验证账户密码登录
		function dologin()
		{
			$uname = $_POST['uname'];
			$upwd = md5($_POST['upwd']);
			$user = DB::Table('shop_user') -> select(" where uname='{$uname}' and upwd='{$upwd}'");
			
			
			if ($user)
			{
				$_SESSION['flag'] = true;
				$_SESSION['userInfo'] = $user[0];
				
				echo '登陆成功';
				
				// 获取该用的权限
				$role = DB::Table('shop_role') -> select(" where rid={$user[0]->auth}")[0];
				$res = DB::Table('shop_access') -> select(" where aid in({$role->authrange})");
		
				$_SESSION['auth'] = [];
				foreach($res as $k=>$v){
					$_SESSION['auth'][] = $v->cont;
				}
				
				// 如果session中有back那就跳到结算页去
				if (!empty($_SESSION['back']) && $_SESSION['back']=='jsy') {
					header('refresh:2;url=./index.php?c=orders&a=info');
					die;
				}
				
				// 默认跳转到首页
				header('refresh:2;url=./index.php?c=first&a=index');
				die;
				
			} else {
				echo '用户名或密码错误';
				header('refresh:2;url=./index.php?c=user&a=login');
			}
			
			
		}
		
		
		// 注册
		// 在reg.php
		
		
		
		// 个人中心
			// 我的订单
		function myOrder()
		{	
		
			if (empty($_SESSION['userInfo'])) {
				echo '请登录';
				header('refresh:2;url=./index.php?c=login&a=showlogin');
				die;
			}
			
			$orders = DB::Table('shop_orders') -> select(' where uid='.$_SESSION['userInfo'] -> uid);
			
			// 显示页面
			require('./view/user/myorder.html');
		}
		
		
			// 我的账户设置
		function myInfo()
		{	
			// 得到用户信息赋值给$user 
			$user = $_SESSION['userInfo'];	
			// 显示页面
			require('./view/user/myinfo.html');
		}
		
		function edmyinfo()
		{
			$data['sex'] = $_POST['sex'];
			if (!empty($_POST['month'])) {
				$data['birth'] = strtotime($_POST['month'].'/1/'.$POST['year']);
				$data['email'] = $_POST['email'];
				
				$num = DB::Table('shop_user') -> update($data,$_SESSION['userInfo'] -> uid);	
				
				if ($num) {
					echo '修改成功';
					
					foreach ($data as $k=>$v) {
						$_SESSSION['userInfo'] -> $k = $v;
					}
					print_r($_SESSION['userInfo']);
				} else {
					echo '失败';
				}
				
				header('refresh:1;url=./index.php?c=user&a=myInfo');
				die;
				
			}
		}
		
		
		
		// 修改密码
		
		
		
		
		
		
		
		
	}

?>