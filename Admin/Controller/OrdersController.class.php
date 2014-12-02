<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.xinyuncz.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: WYJ <365657359@qq.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 订单控制器
 * @author WYJ <365657359@qq.com>
 */
class OrdersController extends AdminController {

	public function myOrders($status=-1){
		$map['puid'] = UID;

		if ($status!=-1){
			$map['status'] = $status;
		}

		$Orders = M('View_orders_salasman');
		$count = $Orders->where($map)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,7);
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $Orders->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	//修改订单状态
	public function updateStatus($status=-1,$id=0){
		$data['id'] = $id;
		$data['status'] = $status;

		$Orders = M('Orders');

		$re = $Orders -> save($data);

		if ($re){
			$this->success("修改成功");
		}else{
			$this->error("修改失败");
		}


	}

	//所有订单
	public function allOrders($status=-1){

		if ($status!=-1){
			$map['status'] = $status;
		}

		$Orders = M('View_orders_salasman');
		if(I('name')){
	            $map['number'] = array('like',"%".I('name')."%");
	    }
		$count = $Orders->where($map)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,7);
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $Orders->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}
}
