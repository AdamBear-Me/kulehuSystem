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
 * 产品控制器
 * @author WYJ <365657359@qq.com>
 */
class NewsController extends AdminController {

	//添加新闻
	public function add(){
		if (IS_POST){
			$News = M('News');
			if($News -> create()) {
				$News -> time = NOW_TIME;
				$result = $News -> add();
				if($result) {
					$this->success('操作成功！', U('show'));
				}else{
					$this->error('写入错误！');
				}
			}else{
				$this->error($News -> getError());
			}
		}else {
			$this->display();
		}
	}



	//查看
	public function show(){
		$News = M('News');
		$count = $News->where($map)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,10);
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $News->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	//删除分类
	public function delete($id = 0){

		$News = M('News');

		if ($News -> delete($id)){
			$this->success("删除成功");
		}else {
			$this->success("删除失败");
		}
	}

	public function edit($id = 0){
		if (IS_POST){
			$News = M('News');
			if($News->create()) {
				$result = $News -> save();
				if($result) {
					$this->success('操作成功！',U('show'));
				}else{
					$this->error('写入错误！');
				}
			}else{
				$this->error($News->getError());
			}

		}else {
			$News =   M('News');
			// 读取数据
			$data = $News->find($id);
			if($data) {
				$this->assign('news',$data);
			}else{
				$this->error('数据错误');
			}
			$this->display();
		}

	}
}
