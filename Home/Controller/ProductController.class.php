<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class ProductController extends HomeController {


	//产品中心
	public function product(){
		$this->display();
	}

	//样本列表
	public function pro_list($id=0,$id2=0){
		$Produce_group = M('Produce_group');

		if ($_GET["ppid"]==0){
			$this->error("数据错误");
		}else if ($_GET["ppid"] == 1) {
			$where["pid"] = $_GET["pid"];
		}else if ($_GET["ppid"] == 2) {
			$where["style_id"] = $_GET["pid"];
		}else if ($_GET["ppid"] == 3) {
			$where["material_id"] = $_GET["pid"];
		}else if ($_GET["ppid"] == 6) {
			$where["origin_id"] = $_GET["pid"];
		}else{
			//$where["origin_id"] = null;
		}
		$list2 = $Produce_group->where($where)->order('sort desc,id desc')->select();

		$group1 = $Produce_group ->where("id=".$_GET["ppid"])->find();
		$group2 = $Produce_group ->where("id=".$_GET["pid"])->find();

		$this->assign('group1',$group1);
		$this->assign('group2',$group2);

		$this->assign('list',$list2);//1
		//$this->assign('list2',$list2);//2
		$this->assign("ppid",$_GET["ppid"]);
		$this->assign("pid",$_GET["pid"]);

		$this->display();
	}

	public function pro_list_brand(){
		$Produce_group = M('Produce_group');
		$map['pid'] = $_GET["ppid"];
		$list = $Produce_group->where($map)->order('sort desc,id desc')->select();

		$group1 = $Produce_group ->where("id=".$_GET["ppid"])->find();
		$this->assign('group1',$group1);
		$this->assign("list",$list);
		$this->assign("ppid", $_GET["ppid"]);
		$this->display("pro_list_brand");
	}
	//产品商品列表
	//$id 2级分类
	public function pro($id = 0){

		$Produce_group = M('Produce_group');

		$group3 = $Produce_group->where("id=".$_GET["pidd"])->find();//3级
		$group1 = $Produce_group->where("id=".$_GET["ppid"]) ->find();//一级分类

		if ($_GET['ppid'] ==1 ) {
			$where["brand_id"] = $_GET['pidd'];
			$group2 = $Produce_group->where("id=".$_GET["pid"])->find();//2级分类
			$this->assign('pid',$_GET["pid"]);
			$this->assign('group2',$group2);
		}else if ($_GET['ppid'] ==2) {
			$where["style_id"] = $_GET['pidd'];
		}else if ($_GET['ppid'] ==3) {
			$where["material_id"] = $_GET['pidd'];
		}else if ($_GET['ppid'] ==5) {
			$where["origin_id"] = $_GET['pidd'];
		}else {

		}
		$Produce= M('Produce');

		$list = $Produce->where($where)->order('sort desc,id desc')->select();
		
		$this->assign('group1',$group1);
		$this->assign('group3',$group3);
		$this->assign('list',$list);
		$this->assign("ppid",$_GET["ppid"]);
		$this->assign("pidd",$_GET["pidd"]);
		$this->display();
	}

	//产品商品详情页面
	public function pro_page($id = 0,$group3_id=0){
		$Produce= M('Produce');
        $produce = $Produce -> find(I("id"));

        $uid = session("UID");
        $price = $this->getPrice($id,$uid);
        if($price){
            $produce['price'] = $price;
        }
        if(I('pr') != ''){
            $produce['price'] = I('pr');
        }
        
       
        //dump($list);
        if(I('change_price') != ''){    //修改成功
            $this->success("修改成功");
        }
        $group4 = $Produce->where("id=".I("id"))->find();//3级
        $Produce_group = M('Produce_group');
        if ($_GET['ppid'] ==1 ) {
        	$list=$Produce->where("brand_id=".I("pidd"))->select();  //样本下的产品图片
        	$group2 = $Produce_group->where("id=".I("pid"))->find();//2级分类
        	$this->assign('group2',$group2);
        	$this->assign("pid",I("pid"));
        }elseif ($_GET['ppid'] ==2) {
        	$list=$Produce->where("style_id=".I("pidd"))->select(); //风格下的产品图片
        }elseif ($_GET['ppid'] ==3) {
        	$list=$Produce->where("material_id=".I("pidd"))->select(); //材质下的产品图片
        }elseif ($_GET['ppid'] ==5) {
        	$list=$Produce->where("origin_id=".I("pidd"))->select(); //产地下的产品图片
        }
        $group3 = $Produce_group->where("id=".I("pidd"))->find();//3级       
        $group1 = $Produce_group->where("id=".I("ppid")) ->find();//一级分类
        $this->assign('group1',$group1);
        $this->assign('group3',$group3);
        $this->assign('group4',$group4);
        $this->assign('list',$list);
        $this->assign('pro',$produce);
        $this->assign("ppid",I("ppid"));
        $this->assign("pidd",I("pidd"));
        $this->assign("id",I("id"));
        $this->display('pro_page');
	}

	//产品商品价格修改页面，代理商有权限，业务员没有
	public function pro_xg($id = 0, $pr = -1, $group3_id=0){
        $this->assign('group3_id',$group3_id);

        $Agent = M('Agent');
        $uid = session("UID");
        $map['uid'] = $uid;
        $agent = $Agent->where($map)->find();//代理商
        if(!$agent){
            $this->error("您没有权限修改价格");
        }

        if(IS_POST){
            if ($pr != -1){
                $Price= M('Price');
                $map['produce_id'] = $id;

                $data['uid'] = $uid;
                $data['produce_id'] = $id;
                $data['price'] = $pr;

                $price = $Price->where($map)->field('price')->find();
                if($price){
                    $re = $Price->where($map)->save($data);
                }else {
                    $re = $Price->add($data);
                }

                if ($re){
                    $this->success("修改成功");
                }else {
                    $this->error("修改失败");
                }
            }else {
                $this->error("数据错误！");
            }

        }else {//读取
            $Produce= M('Produce');
            $produce = $Produce->find($id);

            $map['produce_id'] = $id;
            $price = $this->getPrice($id,$uid);
            if($price){
                $produce['price'] = $price;
            }
            if(I('price')){
                $produce['price'] = I('price');
            }
            $this->assign("ppid",$_GET["ppid"]);
            $this->assign("pid",$_GET["pid"]);
            $this->assign("pidd",$_GET["pidd"]);
            $this->assign("id",$_GET["id"]);
            $this->assign('pro',$produce);
            $this->display();
        }
    }

	//产品商品详情页面
	public function pro_ddtj($id = 0,$count=0){
		$uid = session("UID");
		$map['uid'] = $uid;
		$salesman = M('Salesman')->where($map)->find();//业务员
		if(!$salesman){
			$this->error("您没有权限提交表单");
		}

		if(IS_POST){//提交订单

			$Orders = M('Orders');
			if($Orders -> create()) {
				$Orders -> uid = $uid;
				$Orders -> time = NOW_TIME;
				$result = $Orders -> add();
				if($result) {
					$this->success('操作成功！');
				}else{
					$this->error('写入错误！');
				}
			}else{
				$this->error($Orders -> getError());
			}

		}else {
			$Produce= M('Produce');
			$produce = $Produce->find($id);


			$price = $this->getPrice($id,$uid);
			if($price){
				$produce['price'] = $price;
			}
			$price = $produce['price']*$count;
			$rand = generate_rand(3);

			$this->assign('rand',$rand);
			$this->assign('price',$price);
			$this->assign('count',$count);
			$this->assign('time',NOW_TIME);
			$this->assign('pro',$produce);
			$this->display();
		}
	}

	//获取价格
	private function getPrice($produce_id=0,$uid=0){


		$map['uid'] = $uid;
		$agent = M('Agent')->field('puid')->where($map)->find();//代理商

		if(!$agent){
			$salesman = M('Salesman')->field('puid')->where($map)->find();//业务员
			$map['uid'] = $salesman['puid'];
		}

		$map['produce_id'] = $produce_id;
		$price = M('Price')->where($map)->field('price')->find();
		if($price){
			return $price['price'];
		}else{
			return null;
		}
	}

	/*
	 0 未处理
	 1已处理
	 */
	//我自己的订单
	public function wddd($status=0){
        $uid = session("UID");
        $map['uid'] = $uid;
        $map['status'] = $status;

        $Orders = M('orders');
        $count = $Orders->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,7);
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');

        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Orders->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);
        $this->assign('order_count',$count);
        $this->assign('page',$show);
        $this->assign('status',$status);
        $this->display();
    }

	//DIY页面
	public function dbdiy($id=2,$group3_id=0,$doormodel_id=0){
		//分类
		$Produce_group = M('Produce_group');
		$group3 = $Produce_group->find($_GET["group3_id"]);//3级
		//$group1 = $Produce_group->find($_GET['pid']);//1级分类
		$group2 = $Produce_group->find($group3['pid']);//2级分类
		$group1 = $Produce_group ->find($group2['pid']);//1级分类
		/*if ($_GET['ppid'] ==1 ){
			$group2 = $Produce_group->find($_GET['pid']);//2级分类
		}*/
		if ($_GET['ppid']!=1){
			$this->error('该系类没有产品展示！');
		}
		
		//产品列表
		
		$Produce= M('Produce');
		$diy = M("diy");
		//2级户型
		$Doormodel = M('Doormodel');
		//查看该样本下面的户型图
		$list = $Doormodel->where("brand_id=".$_GET["group3_id"])->order('id desc')->select();
		//dump($list);
		if ($doormodel_id == 0){
			$map['doormodel_id'] = $list[0]['id'];
			$plist = $diy->where($map)->order('id desc')->select();
			//dump($plist);
			$this->assign('doormodel',$list[0]);
		}else {
			$door = $Doormodel->where("id=".$doormodel_id)->find();
			$map['doormodel_id'] = $_GET['doormodel_id'];
			$plist = $diy->where($map)->order('id desc')->select();
			$this->assign('doormodel',$door);
		}
		$group4 = $Produce->find($_GET["id"]);//4级
		$this->assign('group1',$group1);
		$this->assign('group2',$group2);
		$this->assign('group3',$group3);
		$this->assign('group4',$group4);

		$produce = $Produce -> find($id);
		$this->assign('pro',$produce);
		$this->assign('list',$list);

		$img_url = C('IMAGES_UPLOAD_ROOT_PATH');
		$String = "[";
		foreach ($plist as $p){
			$pro = $Produce->where("id=".$p['pro_id'])->find();
			$String .=  "{id:'".$p['pro_id']."',showtxt:'".$pro['name']."',smallpic:'".$img_url.$p['image4']."',bigpic:'".$img_url.$p['image5']."'},";
		}
		$String = substr($String,0,strlen($String)-1);
		$String .= "]";

		//查询产品价格
		$price = $Produce->where("id=".$_GET["id"])->find();
		$this->assign('str',$String);
		$this->assign("ppid",$_GET["ppid"]);
		$this->assign("pid",$_GET["pid"]);
		$this->assign("pidd",$_GET["group3_id"]);
		$this->assign("id",$_GET["id"]);
		$this->assign("price",$price);
		$this->display();
	}

	//搜索
	public function search($name=''){
		$map['name'] = $name;

		$Produce = M('Produce');
		$list = $Produce->where($map)->order('sort desc,id desc')->select();

		$this->assign('list',$list);
		$this->display();
	}

	//高级搜索
	public function gsearch($name=''){
		
		if (IS_POST){
		$map['name'] = $name;

		$Produce = M('Produce');
		$list = $Produce->where($map)->order('sort desc,id desc')->select();

		$this->assign('list',$list);
		
		}else {
			$this->display();
		}
	}
	
	//统计
	public function statistic($time1='',$time2=''){
		$uid = session("UID");
		$map['uid'] = $uid;

		$Orders = M('View_orders');
		//当月统计
		$y=date("Y",time());
		$m=date("m",time());
		$d = date("t");
		$start_time = mktime(0, 0, 0, $m, 01 ,$y);
		$end_time = mktime(0, 0, 0, $m, $d ,$y);
		$map['time'] = array(array('gt',$start_time),array('lt',$end_time));
		$list = $Orders->where($map)->select();
		$price = 0;
		foreach ($list as $vo){
			$price += $vo['count']*$vo['price'];
		}
		$this->assign('price',$price);
		$this->assign('count',count($list));
		//全部
		$allmap['uid'] = $uid;
		$list = $Orders->where($allmap)->select();
		$price = 0;
		foreach ($list as $vo){
			$price += $vo['count']*$vo['price'];
		}
		$this->assign('allprice',$price);
		$this->assign('allcount',count($list));

		//查询
		if ($time1!=''&&$time2!=''){
			
			$m = substr($time1,0,2);
			$d = substr($time1,3,2);
			$y = substr($time1,6,4);
			$start_time = mktime(0, 0, 0, $m, $d ,$y);

			$m = substr($time2,0,2);
			$d = substr($time2,3,2);
			$y = substr($time2,6,4);
			$end_time = mktime(0, 0, 0, $m, $d ,$y);

			$map['time'] = array(array('gt',$start_time),array('lt',$end_time));

			$list = $Orders->where($map)->select();
			$price = 0;
			foreach ($list as $vo){
				$price += $vo['count']*$vo['price'];
			}
			$this->assign('cxprice',$price);
			$this->assign('cxcount',count($list));
		}else {
			$this->assign('cxprice',0);
			$this->assign('cxcount',0);
		}


		$this->display();
	}

	//订单查询
	public function ddcx($recipient='',$time1='',$time2='',$number='',$name=''){
		if ($recipient!=''){
			$map['recipient'] = $recipient;
		}
		if ($time1!=''&&$time2!=''){
			$m = substr($time1,0,2);
			$d = substr($time1,3,2);
			$y = substr($time1,6,4);
			$start_time = mktime(0, 0, 0, $m, $d ,$y);

			$m = substr($time2,0,2);
			$d = substr($time2,3,2);
			$y = substr($time2,6,4);
			$end_time = mktime(0, 0, 0, $m, $d ,$y);

			$map['time'] = array(array('gt',$start_time),array('lt',$end_time));
		}
		if ($number!=''){
			$map['number'] = $number;
		}
		if ($name!=''){
			$map['name'] = $name;
		}

		$uid = session("UID");
		$map['uid'] = $uid;

		$Orders = M('orders');
		$count = $Orders->where($map)->count();// 查询满足要求的总记录数

		$Page = new \Think\Page($count,7);
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $Orders->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->assign('status',$status);
		$this->display();
	}

	//用户资料
	public function user(){
		$uid = session("UID");
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
			$map['uid'] = $uid;
			$salesman = M('Salesman')->where($map)->find();//业务员
			$this->assign('salesman',$salesman);
			$this->display();
		}
	}
	
	///////////////////////////////
	public function goods(){
		$Goods =   M('Goods');
		$count = $Goods->where($map)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,10);
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $Goods->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}


	//购物车页面
	public function shop_cart(){
        if(I('del_id')){    //删除购物车中的产品
            unset($_SESSION['car_name'][session("UID")][I('del_id')]);//删除购物车
            session_destroy($_SESSION['car_name'][session("UID")][I('del_id')]);
        }else{
            if(I('id')){        //有新产品添加购物车
                $Produce= M('Produce');
                $produce = $Produce -> find(I('id'));
                $uid = session("UID");

                $price = $this->getPrice($id,$uid);
                if($price){                     //默认从上级获取
                    $produce['price'] = $price;
                }
                if(I('xg_price')){              //手动调价
                    $produce['price'] = I('xg_price');
                }
                if([$produce['id']]){
                    $_SESSION['car_name'][session("UID")][$produce['id']] = $produce;    //添加购物车
                }
            }
        }
//        unset($_SESSION['car_name'][$car_name]['']);//删除购物车
//        dump($_SESSION['car_name'][$car_name]);    //显示购物车 car_name 就是用户的id，car_name的下一级是产品id(已经测试过)
//        exit();

        $price_count = 0;
        foreach($_SESSION['car_name'][session("UID")] as $key => $value){
            $price_count += $value['price'];
        }
        $this->assign("price_count",$price_count);
        $this->assign("shop_cart",$_SESSION['car_name'][session("UID")]);
		$this->display();
	}

	public function shop_cart2(){
        $uid = session("UID");
        $map['uid'] = $uid;
        $salesman = M('Salesman')->where($map)->find();//业务员
        if(!$salesman){
//            $this->error("您没有权限提交表单");
        }

        if(IS_POST){//提交订单
            $Orders = M('Orders');
            $produce_id = "";
            foreach($_SESSION['car_name'][session("UID")] as $key => $value){    //产品id
                $produce_id .= $value['id'].",";
            }
            $produce_id = substr($produce_id,0,strlen($produce_id)-1);      //截取最后掉最后一个逗号
            if($data = $Orders -> create()) {

                $data['product_id'] = $produce_id;                              //产品id
                $data['uid'] = $uid;                                          //用户id
                $data['count'] = count($_SESSION['car_name'][session("UID")]);       //商品种类
                $data['price'] = rtrim( trim(I('play_count_price')), '元' );                         //商品总额价格
                $data['recipient'] = I('recipient');                            //收件人
                $data['address'] = I('address');                                //收件人地址
                $data['phone'] = I('phone');                                    //收件人电话
                $data['time'] = NOW_TIME;                                     //创建时间
                $result = $Orders -> add($data);
                if($result) {
                    unset($_SESSION['car_name'][session("UID")]);               //清空购物车
                    $this->success('操作成功！');
                    $this->redirect('Product/wddd');
                }else{
                    $this->error('写入错误！');
                }
            }else{
                $this->error($Orders -> getError());
            }

        }else {
            $this->assign("number",I('number'));
            $this->display();
        }
	}
	//产品收藏夹
	public function prosc(){
		$this->display();
	}

	public function huodong(){
		$this->display();
	}

	public function news_info(){
		$this->display();
	}
	
}