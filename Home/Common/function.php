<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 前台公共库文件
 * 主要定义前台公共函数库
 */
//订单状态
function get_status($status) {
	switch ($status){
		case 0  : return    '未处理';     break;
		case 1  : return    '处理中';     break;
		case 2  : return    '已处理';     break;
		default : return    false;      break;
	}
}


/**随机数***********
 *@l 长度
 */
function generate_rand($l=3){
	$c= "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	srand((double)microtime()*1000000);
	for($i=0; $i<$l; $i++) {
		$rand.= $c[rand()%strlen($c)];
	}
	return $rand;
}

//

//获取用户名
function getName(){
	if (session("?UID")){
		$uid = session("UID");
		$map['uid'] = $uid;
		$agent = M('Agent')->field('name')->where($map)->find();//代理商

		if(!$agent){
			$salesman = M('Salesman')->field('name')->where($map)->find();//业务员
			if (!$salesman){
				return "管理员您已登录";
			}else {
				return $salesman['name']."您已登录"."<a href='".U('Public/logout')."' class='ico_2'>退出</a>";
			}
		}else {
			return $agent['name']."您已登录"."<a href='".U('Public/logout')."' class='ico_2'>退出</a>";
		}
	}else{
		return "您未登录";
	}
}

//查找返回品牌名称
function cate($id=0){
	if ($id==0){
		return  '';
	}else {
		$Produce_group = M('Produce_group');

		$group1 = $Produce_group->field('pid,name')->find($id);
		$group2 = $Produce_group->field('name')->find($group1['pid']);

		return $group2['name'].'-'.$group1['name'];

	}
}


/**
 * 检测验证码
 * @param  integer $id 验证码ID
 * @return boolean     检测结果
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function check_verify($code, $id = 1){
	$verify = new \Think\Verify();
	return $verify->check($code, $id);
}

/**
 * 获取列表总行数
 * @param  string  $category 分类ID
 * @param  integer $status   数据状态
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_list_count($category, $status = 1){
	static $count;
	if(!isset($count[$category])){
		$count[$category] = D('Document')->listCount($category, $status);
	}
	return $count[$category];
}

/**
 * 获取段落总数
 * @param  string $id 文档ID
 * @return integer    段落总数
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_part_count($id){
	static $count;
	if(!isset($count[$id])){
		$count[$id] = D('Document')->partCount($id);
	}
	return $count[$id];
}

/**
 * 获取导航URL
 * @param  string $url 导航URL
 * @return string      解析或的url
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_nav_url($url){
	switch ($url) {
		case 'http://' === substr($url, 0, 7):
		case '#' === substr($url, 0, 1):
			break;
		default:
			$url = U($url);
			break;
	}
	return $url;
}