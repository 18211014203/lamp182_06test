<?php

	class login
	{	
		// 登录
		function showlogin()	// 显示登录界面
		{
			require('./view/login/showlogin.html');
		}
		
		// 登录验证
		function checklogin()
		{
			// 判断是否为空
			if (empty($_POST['uname']) || empty($_POST['upwd'])){
			   echo '用户名或密码不能为空';
			   header('refresh:2;url=./index.php?c=login&a=showlogin');
			   die;
			}
			
			// 判断账号密码登录
			$uname = $data['uname'] = $_POST['uname'];
			$upwd = $data['upwd'] = md5($_POST['upwd']);
			
			$condition = " where uname='{$uname}' and upwd='{$upwd}'";

			$user = DB::Table('shop_users') -> select($condition);
			
			if ($user) {
				echo '登录成功';
				$_SESSION['flag'] = true;
				$_SESSION['userInfo'] = $user[0];
				header('refresh:2;url=./index.php?c=first&a=index');
				die;
			} else {
				echo '用户名或密码错误';
				header('refresh:2;url=./index.php?c=login&a=showlogin');
				die;
			}
			
		}
		
		
		// 退出
		function logout()
		{
			unset($_SESSION['flag']);
			unset($_SESSION['userInfo']);
			echo '正在退出...';
			header('refresh:2;url=./index.php?c=first&a=index');
			die;
		}
		
		
	}

?>