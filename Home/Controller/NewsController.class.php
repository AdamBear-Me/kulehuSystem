<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;
use User\Api\UserApi;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class NewsController extends Controller {

	/* 空操作，用于输出404页面 */
	public function _empty(){
		$this->redirect('Index/index');
	}

	protected function _initialize(){
		/* 读取站点配置 */
		$config = api('Config/lists');
		C($config); //添加配置

		if(!C('WEB_SITE_CLOSE')){
			$this->error('站点已经关闭，请稍后访问~');
		}
	}

	//信息列表
	public  function index(){
		$News = M('News');
		$count = $News->where($map)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,20);
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $News->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		//dump($list);
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display("news_list");
	}

	//详细信息
	public function news_page($id=0){
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
