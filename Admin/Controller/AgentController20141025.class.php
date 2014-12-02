<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.xinyuncz.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: WYJ <365657359@qq.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use User\Api\UserApi;
/**
 * 分销商控制器
 * @author WYJ <365657359@qq.com>
 */
class AgentController extends AdminController {

	//用户错误信息
	private function showRegError($code = 0){
		switch ($code) {
			case -1:  $error = '用户名长度必须在16个字符以内！'; break;
			case -2:  $error = '用户名被禁止注册！'; break;
			case -3:  $error = '用户名被占用！'; break;
			case -4:  $error = '密码长度必须在6-30个字符之间！'; break;
			case -5:  $error = '邮箱格式不正确！'; break;
			case -6:  $error = '邮箱长度必须在1-32个字符之间！'; break;
			case -7:  $error = '邮箱被禁止注册！'; break;
			case -8:  $error = '邮箱被占用！'; break;
			case -9:  $error = '手机格式不正确！'; break;
			case -10: $error = '手机被禁止注册！'; break;
			case -11: $error = '手机号被占用！'; break;
			default:  $error = '未知错误';
		}
		return $error;
	}

	//添加代理商
	private function addAgent($username='',$password='', $repassword = '',$email = '',$name='',$level=''){
		/* 检测密码 */
		if($password != $repassword){
			$this->error('密码和重复密码不一致！');
		}
		/* 调用注册接口注册用户 */
		$User = new UserApi;
		$uid = $User->register($username, $password, $email);
		if(0 < $uid){ //注册成功
			$user = array('uid' => $uid, 'nickname' => $username, 'status' => 1);
			if(!M('Member')->add($user)){
				$this->error('用户添加失败！');
			}
			$Agent = M('Agent');
			$user = array('uid' => $uid, 'puid' => UID, 'level' => $level, 'name' => $name);
			$result = $Agent -> add($user);
			if($result) {
				$auth = array('uid' => $uid, 'group_id' => $level+2);
				M('Auth_group_access')->add($auth);
				$this->success('操作成功！');
			}else{
				$this->error('写入错误！');
			}
		} else { //注册失败，显示错误信息
			$this->error($this->showRegError($uid));
		}

	}
	//添加1级代理商
	public function add1($username='',$password='', $repassword = '',$email = '',$name=''){
		if (IS_POST){
			$this->addAgent($username,$password,$repassword,$email,$name,1);
		}else {
			$this->display();
		}
	}

	//添加2级代理商
	public function add2($username='',$password='', $repassword = '',$email = '',$name=''){
		if (IS_POST){
			$this->addAgent($username,$password,$repassword,$email,$name,2);
		}else {
			$this->display();
		}
	}

	//添加3级代理商
	public function add3($username='',$password='', $repassword = '',$email = '',$name=''){
		if (IS_POST){
			$this->addAgent($username,$password,$repassword,$email,$name,3);
		}else {
			$this->display();
		}
	}
	//查看全部代理商
	public function show(){
		$Agent = M('Agent');
		$count = $Agent->where($map)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,10);
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $Agent->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	///////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////

	//添加业务员
	public function addSalesman($username='',$password='', $repassword = '',$email = '',$name=''){
		if (IS_POST){
			if($password != $repassword){
				$this->error('密码和重复密码不一致！');
			}
			/* 调用注册接口注册用户 */
			$User = new UserApi;
			$uid = $User -> register($username, $password, $email);
			if(0 < $uid){ //注册成功
				$Salesman = M('Salesman');
				$user = array('uid' => $uid, 'puid' => UID, 'name' => $name);
				$result = $Salesman -> add($user);
				if($result) {
					$this->success('操作成功！');
				}else{
					$this->error('写入错误！');
				}
			} else { //注册失败，显示错误信息
				$this->error($this->showRegError($uid));
			}
		}else {
			$this->display();
		}
	}

	public function showSalesman(){

		$Salesman = M('Salesman');
		$map['puid'] = UID;
		$count = $Salesman->where($map)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,10);
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $Salesman->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	//修改业务员资料
	public function editSalesman($id = 0){

		if (IS_POST){
			$Salesman = M('Salesman');
			if($Salesman->create()) {
				$result = $Salesman->save();
				if($result) {
					$this->success('操作成功！');
				}else{
					$this->error('写入错误！');
				}
			}else{
				$this->error($Salesman->getError());
			}

		}else {
			$salesman = M('Salesman')->find($id);//业务员
			$this->assign('salesman',$salesman);
			$this->display();
		}

	}


}
