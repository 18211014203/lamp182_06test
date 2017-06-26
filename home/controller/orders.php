<?php

	/*
		订单控制器
	*/
	
	class orders
	{
		private $oid;	// 订单ID号
		private $pdo;
		
		// 收集订单明细 收货人,电话,地址,留言
		function info()
		{
			if (empty($_SESSION['flag'])) {
				echo '请先登录';
				$_SESSION['back'] = 'jsy';
				header('refresh:2;url=./index.php?c=user&a=login');
				die;
			}
			require('./view/orders/info.html');
		}
		
		
		// 结算页
		function jsy()
		{
			// echo '<pre>';
			// print_r($_POST);die;
			$_SESSION['orders']['rec'] = $_POST['rec'];
			$_SESSION['orders']['tel'] = $_POST['tel'];
			$_SESSION['orders']['addr'] = $_POST['addr'];
			$_SESSION['orders']['umsg'] = $_POST['umsg'];
			
			// 显示到结算页面
			require('./view/orders/index.html');
			
		}
		
		
		// 最终结账
		function finish()
		{
			// 开启事务
			$this -> pdo = new PDO(DSN, USER, UPWD);
			$this -> pdo -> beginTransaction();
			
			//   	 改库存方法				 写主表方法				写详情表方法
			if ( $this -> updateCnt() && $this -> writeOrder() && $this -> writeDetail() ) {
				$this -> pdo -> commit(); // 如果三者都成功,就提交
			} else {
				$this -> pdo -> rollBack();
				echo '订单提交失败';
				//header('refresh:2;url=./index.php?c=orders&a=jsy');
				die;
			}
			
			// 善后
			$this -> last();
			
		}
		
		
		// 写入主表
		private function writeOrder()
		{
			$data['oid'] = $this -> oid = $_SESSION['orders']['oid'] = date('YmdHis').uniqid();//订单号
			$data['ormb'] = $_SESSION['orders']['sum'];	//总价
			$data['ocnt'] = $_SESSION['orders']['count'];	//库存
			$data['uid'] = $_SESSION['userInfo'] -> uid;	//用户
			$data['rec'] = $_SESSION['orders']['rec'];	//收货人
			$data['tel'] = $_SESSION['orders']['tel'];	//电话
			$data['addr'] = $_SESSION['orders']['addr'];	//收货地址
			$data['status'] = 1;	//类型
			$data['umsg'] = $_SESSION['orders']['umsg'];	//买家留言
			$data['otime'] = time();	//下单时间
			
			$res = DB::Table('shop_orders') -> insert($data);
			
			if ($res==='0') {
				return true; // 写主表成功
			} else {
				return false; // 写主表失败
			}
		}
		
		
		// 写入详情表
		private function writeDetail()
		{
			// 拼接一条SQL语句
			$sql = "insert into shop_detail(oid,gid,bprice,bcnt) values";
			foreach($_SESSION['cart'] as $k=>$v){
				$sql .="('{$this->oid}',{$v->gid},{$v->price},{$v->cnt}),";
			}
			$sql = rtrim($sql,',');
			
			if ( $this -> pdo -> exec($sql)) {
				return true; // 写详情表成功
			} else {
				return false; // 写详情表失败
			}
			
		}
		
		
		// 修改库存和销量
		private function updateCnt()
		{
			foreach($_SESSION['cart'] as $k=>$v){
				$sql = "update shop_goods set cnt=cnt-{$v->cnt},salecnt=salecnt+{$v->cnt} where gid={$k}";
				if (!$this -> pdo -> exec($sql)) {
					return false; // 修改库存失败
				} else {
					return true; // 修改库存成功
				}
			}
		}
		
		
		// 最后的显示成功订单,清空购物车及订单操作
		private function last()
		{
			// 跳转回首页
			header('refresh:5;url=./index.php?c=first&a=index');
			
			// 显示订单成功的信息
			require('./view/orders/ok.html');
			
			// 清空
			unset($_SESSION['cart']);
			unset($_SESSION['orders']);
			
			die;
		}
	}

?>