<?php

	class reg
	{
		// 注册
		function showreg()
		{
			// 显示注册页面
			require('./view/reg/reg.html');
		}
		
		// 接收数据,并保存
		function doreg()
		{	
		
			$data['upwd'] = md5($_POST['upwd']);
		    $data['uname'] = $_POST['uname'];
			$data['tel'] = $_POST['tel'];
			$data['regtime'] = time();
			
			if ($_POST['upwd'] != $_POST['reupwd'] || empty($_POST['upwd'])) {
			   echo '密码不一致';
			   header("refresh:2;url=./index.php?c=reg&a=showreg");
			   die;
			}
			
			$res = DB::Table('shop_users') -> insert($data);

			if ($res) {
			   echo '注册成功';
			} else {
			   echo '注册失败';
			}
			
			header("refresh:2;url=./index.php?c=login&a=showlogin");
			die;
		}
	}

?>