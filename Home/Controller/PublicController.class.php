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
class PublicController extends Controller {

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

	/* 用户登录检测 */
	public function login($username = null, $password = null){
		if(IS_POST){ //登录验证

			/* 调用UC登录接口登录 */
			$user = new UserApi;
			$uid = $user->login($username, $password);

			if(0 < $uid){ //UC登录成功
				/* 登录用户 */

				session("UID",$uid);
				//	dump(session("UID"));
				$this->success("登录成功",U("Index/index"));
			} else { //登录失败
				switch($uid) {
					case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
					case -2: $error = '密码错误！'; break;
					default: $error = '未知错误！'; break; // 0-接口参数错误（调试阶段使用）
				}
				$this->error($error);
			}

		} else { //显示登录表单 ,
			$uid = session("UID");
			if (session("?UID")){
				$this->success("您已经登录！");
			}else {
				$this->display();
			}
		}
	}

	/* 退出登录 */
	public function logout(){
		if(session("?UID")){
			session('[destroy]');
			$this->success('退出成功！', U('login'));
		} else {
			$this->redirect('login');
		}
	}

}
