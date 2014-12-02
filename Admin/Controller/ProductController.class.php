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
class ProductController extends AdminController {

	/**
	 * 添加产品
	 * @author huajie <banhuajie@163.com>
	 */

	//查看分类1
	public function showGroup1(){
		$Produce_group =   M('Produce_group');
		$map['pid'] = 0;
		$count = $Produce_group->where($map)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,10);
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $Produce_group->where($map)->order('sort desc,id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	//查看分类2
	public function showGroup2($id=0){
		$Produce_group =   M('Produce_group');
		$map['pid'] = $id;
		$res = $Produce_group->where("id=".$id)->getField("pid");
		$count = $Produce_group->where($map)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,10);
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $Produce_group->where($map)->order('sort desc,id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		$p = $Produce_group->find($id);

		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->assign('produce_group',$p);
		$this->assign("ppid",$res);
		$this->display();
	}

	//查看分类3
	public function showGroup3($id=0){
		$Produce_group =   M('Produce_group');
		$map['pid'] = $id;
		if($id){
			$res = $Produce_group->where("id=".$id)->getField("pid");
			$count = $Produce_group->where($map)->count();// 查询满足要求的总记录数
			$Page = new \Think\Page($count,10);
			$show = $Page->show();// 分页显示输出
			// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			$list = $Produce_group->where($map)->order('sort desc,id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			$p = $Produce_group->find($id);
			$this->assign('produce_group',$p);
			$this->assign("ppid",$res);
		}else{
			if(I('name')){
		        $where['name'] = array('like',"%".I('name')."%");
		    }
			$pid = $Produce_group->where("pid=1")->select();
			$arr_id = array();
			for ($i=0; $i <count($pid) ; $i++) { 
				$arr_id[] = $pid[$i]["id"];
			}
			$where["pid"] = array('in',$arr_id);

			/*$pid1 = $Produce_group->where($data1)->select();

			$arr_id1 = array();
			for ($i=0; $i <count($pid1) ; $i++) { 
				$arr_id1[] = $pid1[$i]["id"];
			}
			
			$where["id"] = array('in',$arr_id1);*/
			//$where['pid'] = "-1";
			//$where['_logic'] = 'or';
			$count = $Produce_group->where($where)->count();// 查询满足要求的总记录数
			$Page = new \Think\Page($count,10);
			$show = $Page->show();// 分页显示输出
			// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			$list = $Produce_group->where($where)->order('sort desc,id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			$p = $Produce_group->find($id);
			$this->assign("ppid","1");
		}
		

		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}
	//增加分类
	public function addGroup($id = 0){
		//dump(IS_POST);//判断是否是提交操作
		if (IS_POST){
			$Produce_group =   M('Produce_group');
			
			if($Produce_group->create()) {
				$result =   $Produce_group->add();
				if($result) {
					$this->success('操作成功！', U('showGroup1'));
				}else{
					$this->error('写入错误！');
				}
			}else{
				$this->error($Produce_group->getError());
			}
		}else {
			$this->assign("pid",$id);
			$this->display();
		}
	}
	public function addGroup1(){
		if (IS_POST){
			$Produce_group =   M('Produce_group');
			// 上传单个文件
			$upload = new \Think\Upload(C('IMAGES_UPLOAD'));// 实例化上传类
			$info   =   $upload->uploadOne($_FILES['image']);
			if(!$info) {// 上传错误提示错误信息
				$this->error($upload->getError());
			}
	
			if($Produce_group->create()) {
				$Produce_group->image = $info['savepath'].$info['savename'];
				$result =   $Produce_group->add();
				if($result) {
					$this->success('操作成功！', U('showGroup3'));
				}else{
					$this->error('写入错误！');
				}
			}else{
				$this->error($Produce_group->getError());
			}
		}else {
			$Standard =   M('Standard');
			$standard = $Standard->order('sort desc,id desc')->select();
			$Factory =   M('Factory');
			$factory = $Factory->order('factory_sort desc,factory_id desc')->select();

			$Produce_group =   M('Produce_group');
			$group1 = $Produce_group->where('pid=1')->order('sort desc,id desc')->select();
			$group2 = $Produce_group->where('pid=2')->order('sort desc,id desc')->select();
			$group3 = $Produce_group->where('pid=3')->order('sort desc,id desc')->select();
			$group4 = $Produce_group->where('pid=4')->order('sort desc,id desc')->select();
			$group5 = $Produce_group->where('pid=5')->order('sort desc,id desc')->select();
			$group6 = $Produce_group->where('pid=6')->order('sort desc,id desc')->select();

			if ($group2!=NULL){
				$group21 = $Produce_group->where('pid='.$group2[0]['id'])->order('sort desc,id desc')->select();
			}
			if ($group3!=NULL){
				$group31 = $Produce_group->where('pid='.$group3[0]['id'])->order('sort desc,id desc')->select();
			}
			if ($group4!=NULL){
				$group41 = $Produce_group->where('pid='.$group4[0]['id'])->order('sort desc,id desc')->select();
			}
			if ($group5!=NULL){
				$group51 = $Produce_group->where('pid='.$group5[0]['id'])->order('sort desc,id desc')->select();
			}
			if ($group6!=NULL){
				$group61 = $Produce_group->where('pid='.$group6[0]['id'])->order('sort desc,id desc')->select();
			}

			$this->assign('group1',$group1);
			$this->assign('group2',$group2);
			$this->assign('group3',$group3);
			$this->assign('group4',$group4);
			$this->assign('group5',$group5);
			$this->assign('group6',$group6);
			$this->assign('standard',$standard);
			$this->assign('factory',$factory);

			$this->assign('group21',$group21);
			$this->assign('group31',$group31);
			$this->assign('group41',$group41);
			$this->assign('group51',$group51);
			$this->assign('group61',$group61);

			//$this->assign("pid",$_GET["id"]);
			$this->display();
		}
	}
	//修改分类
	public function editGroup($id = 0){
		if (IS_POST){
			$Produce_group =   M('Produce_group');
			/*$upload = new \Think\Upload(C('IMAGES_UPLOAD'));// 实例化上传类
			// 上传单个文件
			$info = $upload->uploadOne($_FILES['image']);
*/
			if($Produce_group->create()) {
				/*if($info) {
					$Produce_group->image = $info['savepath'].$info['savename'];//修改图片
					//			unlink(C('IMAGES_UPLOAD_ROOT_PATH').I('old_image'));//删除图片
				}*/
				$result = $Produce_group->save();
				if($result) {
					$this->success('操作成功！',U('showGroup1'));
				}else{
					$this->error('写入错误！');
				}
			}else{
				$this->error($Produce_group->getError());
			}

		}else {
			$Produce_group =   M('Produce_group');
			// 读取数据
			$data = $Produce_group->find($id);
			if($data) {
				$this->assign('produce_group',$data);
			}else{
				$this->error('数据错误');
			}
			$this->display();
		}
	}

	public function editGroup1(){
		if (IS_POST){
			$Produce_group =   M('Produce_group');
			$upload = new \Think\Upload(C('IMAGES_UPLOAD'));// 实例化上传类
			// 上传单个文件
			$info = $upload->uploadOne($_FILES['image']);
			if(!$info) {// 上传错误提示错误信息
				$this->error($upload->getError());
			}

			if($Produce_group->create()) {
				/*if($info) {
					$Produce_group->image = $info['savepath'].$info['savename'];//修改图片
					//			unlink(C('IMAGES_UPLOAD_ROOT_PATH').I('old_image'));//删除图片
				}*/
				$Produce_group->image = $info['savepath'].$info['savename'];
				$result = $Produce_group->save();
				if($result) {
					$this->success('操作成功！',U('showGroup3'));
				}else{
					$this->error('写入错误！');
				}
			}else{
				$this->error($Produce_group->getError());
			}

		}else {
			$Standard =   M('Standard');
			$standard = $Standard->order('sort desc,id desc')->select();
			$Factory =   M('Factory');
			$factory = $Factory->order('factory_sort desc,factory_id desc')->select();

			$Produce_group =   M('Produce_group');
			// 读取数据
			$data = $Produce_group->find($_GET["id"]);
			if($data) {
				$this->assign('produce_group',$data);
			}else{
				$this->error('数据错误');
			}
			$group1 = $Produce_group->where('pid=1')->order('sort desc,id desc')->select();
			$group2 = $Produce_group->where('pid=2')->order('sort desc,id desc')->select();
			$group3 = $Produce_group->where('pid=3')->order('sort desc,id desc')->select();
			$group4 = $Produce_group->where('pid=4')->order('sort desc,id desc')->select();
			$group5 = $Produce_group->where('pid=5')->order('sort desc,id desc')->select();
			$group6 = $Produce_group->where('pid=6')->order('sort desc,id desc')->select();


			$this->assign('group1',$group1);
			$this->assign('group2',$group2);
			$this->assign('group3',$group3);
			$this->assign('group4',$group4);
			$this->assign('group5',$group5);
			$this->assign('group6',$group6);
			$this->assign('standard',$standard);
			$this->assign('factory',$factory);

			$this->assign("id",$_GET["id"]);
			$this->display();
		}
	}
	//删除分类
	public function deleteGroup($id = 0){

		$Produce_group =   M('Produce_group');
		$group = $Produce_group->field('id,image')-> find($id);
		if ($Produce_group->delete($id)){
			//			unlink(C('IMAGES_UPLOAD_ROOT_PATH').$group['image']);//删除图片
			$this->success("删除成功");
		}else {
			$this->success("删除失败");
		}
	}

	/////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////

	//添加产品
	public function add(){
		if (IS_POST){
			$Produce = M('Produce');
			$DIY = M("diy");
			$upload = new \Think\Upload(C('IMAGES_UPLOAD'));// 实例化上传类
			// 上传单个文件
			//$info1  =  $upload->uploadOne($_FILES['image1']);
			$info2  =  $upload->uploadOne($_FILES['image2']);
			$info3  =  $upload->uploadOne($_FILES['image3']);
			$info4  =  $upload->uploadOne($_FILES['image4']);
			$info5  =  $upload->uploadOne($_FILES['image5']);
			$data1['name'] = $_POST['name'];
			$data1['number'] = $_POST['number'];
			$data1['price'] = $_POST['price'];
			$data1['base_price'] = $_POST['base_price'];
			$data1['size'] = $_POST['size'];
			$data1['unit'] = $_POST['unit'];
			$data1['factory'] = $_POST['factory'];
			$data1['style_id'] = $_POST['style_id'];
			$data1['material_id'] = $_POST['material_id'];
			$data1['origin_id'] = $_POST['origin_id'];
			$data1['group1'] = $_POST['group1'];
			$data1['sort'] = $_POST['sort'];
			$data1['brand_id'] = $_POST['brand_id'];
			$data1['image2'] = $info2['savepath'].$info2['savename'];
			$data1['image3'] = $info3['savepath'].$info3['savename'];
			$data2['doormodel_id'] = $_POST['doormodel_id'];
			$data2['image4'] = $info4['savepath'].$info4['savename'];
			$data2['image5'] = $info5['savepath'].$info5['savename'];
			$pro_res = $Produce->add($data1);
			if($pro_res){
				$data2['pro_id'] = $pro_res;
				$diy_res = $DIY->add($data2);
				if ($diy_res) {
					$this->success('操作成功！', U('show'));
				}else{
					$this->error('写入错误！');
				}
			}else{
				$this->error('写入错误！');
			}
		}else {
			$Standard =   M('Standard');
			$standard = $Standard->order('sort desc,id desc')->select();
			$Unit =   M('Unit');
			$unit = $Unit->order('sort desc,id desc')->select();
			$Factory =   M('Factory');
			$factory = $Factory->order('factory_sort desc,factory_id desc')->select();

			//分类
			$Produce_group =   M('Produce_group');
			$Doormodel = M('Doormodel');
			$group1 = $Produce_group->where('pid=1')->order('sort desc,id desc')->select();
			$group2 = $Produce_group->where('pid=2')->order('sort desc,id desc')->select();
			$group3 = $Produce_group->where('pid=3')->order('sort desc,id desc')->select();
			$group4 = $Produce_group->where('pid=4')->order('sort desc,id desc')->select();
			$group5 = $Produce_group->where('pid=5')->order('sort desc,id desc')->select();
			$group6 = $Produce_group->where('pid=6')->order('sort desc,id desc')->select();
			
			if ($group1!=NULL){
				$group11 = $Produce_group->where('pid='.$group1[0]['id'])->order('sort desc,id desc')->select();	
			}
			$doormodel = $Doormodel->where('brand_id='.$group11[0]['id'])->order('sort desc,id desc')->select();
			$this->assign('group1',$group1);
			$this->assign('group2',$group2);
			$this->assign('group3',$group3);
			$this->assign('group4',$group4);
			$this->assign('group5',$group5);
			$this->assign('group6',$group6);
			$this->assign('doormodel',$doormodel);
			$this->assign('standard',$standard);
			$this->assign('unit',$unit);
			$this->assign('factory',$factory);

			$this->assign('group11',$group11);
			$this->display();
		}
	}


	//分类选择读取AJAX
	public function change(){
		$id = I("id", 0 ,'intval');
		$produce_group =   M('Produce_group')->where("pid=".$id)->order('sort desc,id desc')->select();
		$String = null;
		foreach ($produce_group as $date){
			$String = $String."<option value=".$date['id'].">".$date['name']."</option>";
		}
		//		dump('ddd');
		echo $String;
		//	$this->assign("String",$String);
		//	$this->display();
	}

	public function change_doormodel(){
		$id = I("id", 0 ,'intval');
		$Doormodel =   M('Doormodel')->where("brand_id=".$id)->order('sort desc,id desc')->select();
		$String = null;
		foreach ($Doormodel as $date){
			$String = $String."<option value=".$date['id'].">".$date['name']."</option>";
		}
		echo $String;
	}

	//查看产品
	public function show(){
		$Produce =   M('Produce');
		if(I('name')){
	        $map['number'] = array('like',"%".I('name')."%");
	    }
		$count = $Produce->where($map)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,10);
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $Produce->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	//删除产品
	public function delete($id = 0){

		$Produce = M('Produce');

		if ($Produce->delete($id)){
			$this->success("删除成功");
		}else {
			$this->error("删除失败");
		}
	}

	//修改产品
	public function edit($id = 0){
		$Produce = M('Produce');
		if(IS_POST){
			$upload = new \Think\Upload(C('IMAGES_UPLOAD'));// 实例化上传类
			// 上传单个文件
			$info2 = $upload->uploadOne($_FILES['image2']);
			$info3 = $upload->uploadOne($_FILES['image3']);
			$info4 = $upload->uploadOne($_FILES['image4']);
			$info5 = $upload->uploadOne($_FILES['image5']);

			if($Produce->create()) {
				if($info2) {
					$Produce->image2 = $info2['savepath'].$info2['savename'];//修改图片
				}
				if($info3) {
					$Produce->image3 = $info3['savepath'].$info3['savename'];//修改图片
				}
				if($info4) {
					$Produce->image4 = $info4['savepath'].$info4['savename'];//修改图片
				}
				if($info5) {
					$Produce->image5 = $info5['savepath'].$info5['savename'];//修改图片
				}

				$result = $Produce->save();
				if($result) {
					$this->success('操作成功！',U('show'));
				}else{
					$this->error('写入错误！');
				}
			}else{
				$this->error($Produce->getError());
			}
		}else {
			$Standard =   M('Standard');
			$standard = $Standard->order('sort desc,id desc')->select();
			$Unit =   M('Unit');
			$unit = $Unit->order('sort desc,id desc')->select();
			$Factory =   M('Factory');
			$factory = $Factory->order('factory_sort desc,factory_id desc')->select();

			$Produce_group =   M('Produce_group');
			$Doormodel = M('Doormodel');
			$group1 = $Produce_group->where('pid=1')->order('sort desc,id desc')->select();
			$group2 = $Produce_group->where('pid=2')->order('sort desc,id desc')->select();
			$group3 = $Produce_group->where('pid=3')->order('sort desc,id desc')->select();
			$group4 = $Produce_group->where('pid=4')->order('sort desc,id desc')->select();
			$group5 = $Produce_group->where('pid=5')->order('sort desc,id desc')->select();
			$group6 = $Produce_group->where('pid=6')->order('sort desc,id desc')->select();
			

			if ($group1!=NULL){
				$group11 = $Produce_group->where('pid='.$group1[0]['id'])->order('sort desc,id desc')->select();	
			}
			$doormodel = $Doormodel->where('brand_id='.$group11[0]['id'])->order('sort desc,id desc')->select();
			$this->assign('group1',$group1);
			$this->assign('group2',$group2);
			$this->assign('group3',$group3);
			$this->assign('group4',$group4);
			$this->assign('group5',$group5);
			$this->assign('group6',$group6);
			$this->assign('doormodel',$doormodel);
			$this->assign('standard',$standard);
			$this->assign('unit',$unit);
			$this->assign('factory',$factory);

			$this->assign('group11',$group11);

			$DIY=M("diy");
			$diy = $DIY->where("pro_id=".$_GET['id'])->select();
			//dump($diy);
			$pro = $Produce -> find($id);
			$this->assign('pro',$pro);
			$this->assign("diy",$diy);
			$this->display();
		}
	}

	//导入Excel
	public function excelIn(){
		$this->display();
	}

	//导入Excel
	public function excel(){
		echo $filename;
		import("Org.Util.PHPExcel");
		//要导入的xls文件，位于根目录下的Public文件夹
		$filename=$_FILES["import"]["tmp_name"];
		//创建PHPExcel对象，注意，不能少了\
		$PHPExcel=new \PHPExcel();
		//如果excel文件后缀名为.xls，导入这个类
		import("Org.Util.PHPExcel.Reader.Excel5");
		//如果excel文件后缀名为.xlsx，导入这下类
		//import("Org.Util.PHPExcel.Reader.Excel2007");
		//$PHPReader=new \PHPExcel_Reader_Excel2007();

		$PHPReader=new \PHPExcel_Reader_Excel5();
		//载入文件
		$PHPExcel=$PHPReader->load($filename);
		//获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
		$currentSheet=$PHPExcel->getSheet(0);
		//获取总列数
		$allColumn=$currentSheet->getHighestColumn();
		//获取总行数
		$allRow=$currentSheet->getHighestRow();
		//循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
		for($currentRow=1;$currentRow<=$allRow;$currentRow++){
			//从哪列开始，A表示第一列
			for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
				//数据坐标
				$address=$currentColumn.$currentRow;
				//读取到的数据，保存到数组$arr中
				$arr[$currentRow][$currentColumn]=$currentSheet->getCell($address)->getValue();
			}
		}

		$error_data = array();

		$i = 0;

		//		dump($arr);
		//		die();
		foreach ($arr as $key => $value){
			if ($key!=1) {
				$store_data['name']=$value['A'];
				$store_data['number']=$value['B'];

				$store_data['base_price']=$value['C'];
				$store_data['daili_price']=$value['D'];
				$store_data['sheji_price']=$value['E'];
				$store_data['price']=$value['F'];
				$store_data['unit']=$value['G'];
				$store_data['size']=$value['H'];

				$store_data['brand_id']=$value['I'];
				$store_data['style_id']=$value['J'];
				$store_data['material_id']=$value['K'];
				$store_data['origin_id']=$value['L'];
				$store_data['factory']=$value['M'];

				$store_data['image2']='191x145/'.$value['N'];
				$store_data['image3']='800x521/'.$value['O'];

				$store_data['sort']=$value['P'];

				/*$store_data['image4']='DIY/'.$value['P'];
				$store_data['image5']='DIY/'.$value['Q'];*/
				//				dump($store_data);
				//				die();

				if ($store_data['name']!=NULL){

					$Produce = M('Produce');
					$result = $Produce -> add($store_data);
					if($result) {
						$i++;
					}else{
						$error_data[] = $store_data;
						//	$this->error('写入错误！');
					}

				}else {
					$error_data[] = $store_data;
				}
			}
		}

//		header('Content-type: text/html;charset=UTF-8');
//		echo '出错的数据';
//		dump($error_data);
//		echo '完成'.$i."个数据"."共".$allRow."行";
//		die();
        header('Content-type: text/html;charset=UTF-8');
        if($i == $allRow-1){
           echo ("导入成功，".'完成'.$i."个数据"."共".($allRow-1)."个数据");
        }else{
            echo '出错的数据';
            dump($error_data);
        }

		die();
	}

	///////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////

	//添加户型
	public function addDoormodel(){
		if (IS_POST){
			$upload = new \Think\Upload(C('IMAGES_UPLOAD'));// 实例化上传类
			$upload->rootPath = './Uploads/Images/'; 
			// 上传单个文件
			$info1  =  $upload->uploadOne($_FILES['image1']);
			$info2  =  $upload->uploadOne($_FILES['image2']);

			if(!$info1){
				$this->error($upload->getError());
			}if(!$info2){
				$this->error($upload->getError());
			}

			$Doormodel = M('Doormodel');

			if($Doormodel -> create()) {
				$Doormodel -> image1 = $info1['savepath'].$info1['savename'];
				$Doormodel -> image2 = $info2['savepath'].$info2['savename'];

				$Doormodel -> style_id = 0;
				$Doormodel -> material_id = 0;
				$Doormodel -> usage_id = 0;
				$Doormodel -> origin_id = 0;
				$Doormodel -> activity_id = 0;
				
				$result = $Doormodel -> add();
				if($result) {
					$this->success('操作成功！', U('showDoormodel'));
				}else{
					$this->error('写入错误！');
				}
			}else{
				$this->error($Doormodel->getError());
			}
		}else {
			//分类-1级
			$Produce_group =   M('Produce_group');
			$group1 = $Produce_group->where('pid=1')->order('sort desc,id desc')->select();

			if ($group1!=NULL){
				$group11 = $Produce_group->where('pid='.$group1[0]['id'])->order('sort desc,id desc')->select();
			}
				
			$this->assign('group1',$group1);
			$this->assign('group11',$group11);

			$this->display();
		}
	}
	///////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////

	//查看户型
	public function showDoormodel(){
		$Doormodel = M('Doormodel');
		$count = $Doormodel->where($map)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,10);
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $Doormodel->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	//删除产品
	public function deleteDoormodel($id = 0){

		$Doormodel = M('Doormodel');

		if ($Doormodel->delete($id)){
			$this->success("删除成功");
		}else {
			$this->success("删除失败");
		}
	}

	//用户显示产品
	public function showI(){
		if (UID == 1) {
			# code...
			$Produce =   M('Produce');
			if(I('name')){
	            $map['number'] = array('like',"%".I('name')."%");
	        }
			$count = $Produce->where($map)->count();// 查询满足要求的总记录数
			$Page = new \Think\Page($count,10);
			$show = $Page->show();// 分页显示输出
			$list = $Produce->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		}else{
			$user_sample = M("User_sample");
			if(I('name')){
	            $map['number'] = array('like',"%".I('name')."%");
	        }

			$uid = M("agent")->where("uid=".UID)->getField("id");//查询代理商在agent表中的id
			$map['uid'] = $uid;
			$count = $user_sample->where($map)->count();
			$Page = new \Think\Page($count,10);
			$show = $Page->show();
			$list = $user_sample->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
		}
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->assign("UID",UID);
		$this->display();
	}

	//修改产品批发价
	/*public function editPrice($id = 0,$pr = -1){
		if(IS_POST){

			if ($pr != -1){
				$Price= M('Daili_price');
				$map['produce_id'] = $id;

				$data['uid'] = UID;
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
			$price = $this->getPrice($id,UID);
			if($price){
				$produce['daili_price'] = $price;
			}
			$this->assign('pro',$produce);
			$this->display();
		}
	}*/
	//单项修改产品批发价
	public function editPrice(){
		$Lid = M("agent")->where("uid=".UID)->getField("id");//查询代理商在agent表中的id
		$data['daili_price'] = $_POST['daili_price'];
		if(UID==1){
			$produce = M("produce");
			$where['id'] = $_POST['produce_id'];
			$res = $produce->where($where)->save($data);
		}else{
			$user_sample = M("user_sample");
			$where['uid'] = $Lid;
			$where['produce_id'] = $_POST['produce_id'];
			$res = $user_sample->where($where)->save($data);
		}
		if($res){
			echo "a";
		}else{
			echo "b";
		}
	}
	//批量修改批发价
	public function manyeditPrice(){
		$Lid = M("agent")->where("uid=".UID)->getField("id");//查询代理商在agent表中的id
		$data['daili_price'] = $_POST['price'];
		if (UID == 1) {
			$produce= M('produce');
			foreach ($_POST['arr_id'] as $value) {
				$re = $produce->where("id=".$value)->save($data);
			}
		}else{
			$user_sample = M("user_sample");
			foreach ($_POST['arr_id'] as $value) {
				$re = $user_sample->where("produce_id=".$value." and uid=".$Lid)->save($data);
			}
		}
		if($re){
			echo "a";
		}else{
			echo "b";
		}
	}
	//单项修改零售价
	public function editlPrice(){
		$Lid = M("agent")->where("uid=".UID)->getField("id");//查询代理商在agent表中的id
		$data['price'] = $_POST['price'];
		if(UID==1){
			$produce = M("produce");
			$where['id'] = $_POST['produce_id'];
			$res = $produce->where($where)->save($data);
		}else{
			$user_sample = M("user_sample");
			$where['uid'] = $Lid;
			$where['produce_id'] = $_POST['produce_id'];
			$res = $user_sample->where($where)->save($data);
		}
		if($res){
			echo "a";
		}else{
			echo "b";
		}
	}
	//批量修改零售价
	public function manyeditlPrice(){
		$Lid = M("agent")->where("uid=".UID)->getField("id");//查询代理商在agent表中的id
		$data['price'] = $_POST['price'];
		if(UID==1){
			$produce = M("Produce");
			$where['id'] = array('in',$_POST['arr_id']);
			$re = $produce->where($where)->save($data);
		}else{
			$user_sample = M("user_sample");
			$where["produce_id"] = array('in',$_POST['arr_id']);
			$where['uid'] = $Lid;
			$re = $user_sample->where($where)->save($data);
		}
		if($re){
			echo "a";
		}else{
			echo "b";
		}
	}
	//单项修改进价
	public function editbasePrice(){
		$data['base_price'] = $_POST['base_price'];
		if(UID==1){
			$produce = M("produce");
			$where['id'] = $_POST['produce_id'];
			$res = $produce->where($where)->save($data);
		}
		if($res){
			echo "a";
		}else{
			echo "b";
		}
	}
	//批量修改进价
	public function alleditbasePrice(){
		$data['base_price'] = $_POST['base_price'];
		if(UID==1){
			$produce = M("Produce");
			$where['id'] = array('in',$_POST['arr_id']);
			$re = $produce->where($where)->save($data);
		}
		if($re){
			echo "a";
		}else{
			echo "b";
		}
	}
	//获取价格
	private function getPrice($produce_id=0,$uid=0){

		$map['uid'] = $uid;
		$map['produce_id'] = $produce_id;

		$price = M('Daili_price')->where($map)->field('price')->find();

		if($price){
			return $price;
		}else{
			//return;
		}
	}
	//===============分配样本=========================
	//展示下级代理商
	public function agent(){
		$Agent = M("agent");
		$map['puid'] = UID;

		if(I('name')){
	            $map['name'] = array('like',"%".I('name')."%");
	    }

		$count = $Agent->where($map)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,10);
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $Agent->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}
	public function addSampleSession(){
		$_SESSION["uid"] = array('in',$_POST["arr_uid"]);
		/*dump($_POST["arr_uid"]);*/
		echo count($_SESSION["uid"]);
	}
	//添加新样本页面
	public function addSample(){
		$uid = $_GET["uid"];
		$Lid = M("agent")->where("uid=".UID)->getField("id");//查询代理商在agent表中的id
		if (UID == 1) {
			$Produce =   M('Produce_group');
			$sql = "select id from bizhi_produce_group where pid = 1";
			$arr = $Produce->query($sql);
			$arr_id = Array();
			for($i=0;$i<count($arr);$i++){
				$arr_id[]= $arr[$i]["id"];
			}
			$where['pid'] = array('in',$arr_id); //查询条件的字段值为数组
			$sql1= "select * from bizhi_produce_group where pid in (".implode(",", $arr_id).")";
			$arr1 = $Produce->query($sql1);
			$count = count($arr1);
			$Page = new \Think\Page($count,10);
			$show = $Page->show();// 分页显示输出
			$list = $Produce->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		}else{
			$user_sample = M("user_sample");
			$sql = "select brand_id from bizhi_user_sample where uid=".$Lid." group by brand_id";
			$arr = $user_sample->query($sql);
			$count = count($arr);
			$Page = new \Think\Page($count,10);
			$show = $Page->show();// 分页显示输出
			$sql1 = "select brand_id from bizhi_user_sample where uid=".$Lid." group by brand_id limit ".$Page->firstRow.",".$Page->listRows.""; //原生sql分组
			$list = $user_sample->query($sql1);
		}
		
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->assign('uid',$uid);
		$this->assign("UID",UID);
		$this->display();
	}
	//编辑用户样本页面
	public function editSample(){
		$user_sample = M("user_sample");
		$uid = $_GET["uid"];
		$sql = "select brand_id from bizhi_user_sample where uid=".$uid." group by brand_id";
		$arr = $user_sample->query($sql);
		$this->assign("list",$arr);
		$this->assign("uid",$uid);
		$this->display();
	}
	//编辑用户产品页面
	public function editProduce(){
		$user_sample = M("user_sample");
		$where['uid'] = $_GET["uid"];
		$where['brand_id'] = $_GET["brand_id"];
		$arr = $user_sample->where($where)->select();
		//dump($arr[0]['id']);
		$count = count($arr);
		$Page = new \Think\Page($count,10);
		$show = $Page->show();// 分页显示输出
		$list = $user_sample->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign("list",$list);
		$this->assign('page',$show);
		$this->assign("uid",$where['uid']);
		$this->display();
	}
	//添加批发产品页面
	public function addProduce(){
		$uid = $_GET["uid"];
		$Lid = M("agent")->where("uid=".UID)->getField("id");//查询代理商在agent表中的id
		$brand_id = $_GET["id"];
		if (UID == 1) {
			$Produce =   M('Produce');
			$count = $Produce->where("brand_id=".$brand_id)->count();// 查询满足要求的总记录数
			$Page = new \Think\Page($count,10);
			$show = $Page->show();// 分页显示输出
			$list = $Produce->where("brand_id=".$brand_id)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		}else{
			$user_sample = M("user_sample");
			$sql = "select * from bizhi_user_sample where uid=".$Lid." and brand_id=".$brand_id;
			$arr = $user_sample->query($sql);
			$count = count($arr);
			$Page = new \Think\Page($count,10);
			$show = $Page->show();// 分页显示输出
			$sql1 = "select * from bizhi_user_sample where uid=".$Lid." and brand_id=".$brand_id." limit ".$Page->firstRow.",".$Page->listRows.""; //原生sql分组
			$list = $user_sample->query($sql1);
			//dump($list);
		}
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->assign("UID",UID);
		$this->assign("uid",$uid);
		$this->display();
	}
	///向用户批发具体产品
	public function addpro(){
		$user_sample = M("user_sample");
		$data["uid"] = $_POST["uid"];
		$data['pid'] = UID;
		$data["produce_id"] = $_POST["produce_id"];
		$data["in_price"] = $_POST["daili_price"];
		$data["daili_price"] = $_POST["daili_price"];
		$data["price"] = $_POST["price"];
		$arr = $user_sample->where("uid=".$data["uid"]." and produce_id=".$data["produce_id"])->select();
		if($arr){
			echo "c";
		}else{
			$res = $user_sample->add($data);
			if($res){
				echo "a";
			}else{
				echo "b";
			}
		}
	}

	//向用户批量批发产品
	public function alladdpro(){
		$user_sample = M("user_sample");
		$data["uid"] = $_POST["uid"];
		$data['pid'] = UID;
		$arr_id = $_POST['arr_id'];
		$n = $_POST['n'];
		$arr = json_decode($arr_id);
		for ($i=0; $i < $n ; $i++) { 
			$data["produce_id"] = $arr[$i][0];
			$data['in_price'] = $arr[$i][2];
			$data['daili_price'] = $arr[$i][2];
			$data['price'] = $arr[$i][3];
			$res = $user_sample->add($data);
		}

		if($res){
			echo "a";
		}else{
			echo "b";
		}
	}

	//分配样本
	public function addbrand(){
		$Lid = M("agent")->where("uid=".UID)->getField("id");//查询代理商在agent表中的id
		$user_sample = M("user_sample");
		if (UID == 1) {
			$produce = M("produce");
			$data["uid"] = $_POST["uid"];
			$data["pid"] = UID;
			$where["brand_id"] = $_POST["brand_id"];
			$arr = $produce->where($where)->select();
			if(count($arr) > 0){
				for ($i=0; $i < count($arr); $i++) { 
					$data["produce_id"] = $arr[$i]["id"];
					$data['in_price'] = $arr[$i]["daili_price"];
					$data['daili_price'] = $arr[$i]["daili_price"];
					$data['price'] = $arr[$i]["price"];
					$data['brand_id'] = $arr[$i]["brand_id"];
					$res = $user_sample->add($data);
				}
				if ($res) {
					echo "a";
				}else{
					echo "b";
				}
			}else{
				echo "c";
			}
			
		}else{	//代理商/经销商
			$data["uid"] = $_POST["uid"];
			$data["pid"] = $Lid;
			$where["brand_id"] = $_POST["brand_id"];
			$where["uid"] = $Lid;
			$arr = $user_sample->where($where)->select();
			if(count($arr) > 0){
				for ($i=0; $i < count($arr); $i++) { 
					$data["produce_id"] = $arr[$i]["produce_id"];
					$data['in_price'] = $arr[$i]["daili_price"];
					$data['daili_price'] = $arr[$i]["daili_price"];
					$data['price'] = $arr[$i]["price"];
					$data['brand_id'] = $arr[$i]["brand_id"];
					$res = $user_sample->add($data);
				}
				if ($res) {
					echo "a";
				}else{
					echo "b";
				}
			}else{
				echo "c";
			}
		}
	}

	//批量分配样本
	public function alladdbrand(){
		$Lid = M("agent")->where("uid=".UID)->getField("id");//查询代理商在agent表中的id
		$user_sample = M("user_sample");
		if (UID == 1) {
			$produce = M("produce");
			$data["uid"] = $_POST["uid"];
			$data["pid"] = UID;
			$arr_id = $_POST["arr_id"];
			$where['brand_id'] = array('in',$arr_id);
			$arr = $produce->where($where)->select();
			//echo count($array);
			if(count($arr) > 0){
				//echo "a";
				for ($i=0; $i < count($arr); $i++) { 
					$data["produce_id"] = $arr[$i]["id"];
					$data['in_price'] = $arr[$i]["daili_price"];
					$data['daili_price'] = $arr[$i]["daili_price"];
					$data['price'] = $arr[$i]["price"];
					$data['brand_id'] = $arr[$i]["brand_id"];
					$res = $user_sample->add($data);
				}
				if ($res) {
					echo "a";
				}else{
					echo "b";
				}
			}else{
				echo "c";
			}
			
		}else{	//代理商/经销商/设计师
			$data["uid"] = $_POST["uid"];
			$data["pid"] = $Lid;
			$arr_id = $_POST["arr_id"];
			$where['brand_id'] = array('in',$arr_id);
			$where['uid'] = $Lid;
			$arr = $produce->where($where)->select();
			if(count($arr) > 0){
				//echo "a";
				for ($i=0; $i < count($arr); $i++) { 
					$data["produce_id"] = $arr[$i]["produce_id"];
					$data['in_price'] = $arr[$i]["daili_price"];
					$data['daili_price'] = $arr[$i]["daili_price"];
					$data['price'] = $arr[$i]["price"];
					$data['brand_id'] = $arr[$i]["brand_id"];
					$res = $user_sample->add($data);
				}
				if ($res) {
					echo "a";
				}else{
					echo "b";
				}
			}else{
				echo "c";
			}
		}
	}

	//单项删除样本
	public function delSample(){
		$user_sample = M("user_sample");
		$where["uid"] = $_POST["uid"];
		$where["brand_id"] = $_POST["brand_id"];
		$res = $user_sample->where($where)->delete();
		if($res){
			echo "a";
		}else{
			echo "b";
		}
	}

	//批量删除样本
	public function alldelSample(){
		$user_sample = M("user_sample");
		$where["uid"] = $_POST["uid"];
		$arr_id = $_POST["arr_id"];
		$where["brand_id"] = array('in',$arr_id);
		$res = $user_sample->where($where)->delete();
		if($res){
			echo "a";
		}else{
			echo "b";
		}
	}
	//删除产品
	public function delProduce(){
		$user_sample = M("user_sample");
		$where["uid"] = $_POST["uid"];
		$where["produce_id"] = $_POST["produce_id"];
		$res = $user_sample->where($where)->delete();
		if($res){
			echo "a";
		}else{
			echo "b";
		}
	}

	//批量删除产品
	public function alldelProduce(){
		$user_sample = M("user_sample");
		$where["uid"] = $_POST["uid"];
		$arr_id = $_POST["arr_id"];
		$where["produce_id"] = array('in',$arr_id);
		$res = $user_sample->where($where)->delete();
		if($res){
			echo "a";
		}else{
			echo "b";
		}
	}
	//厂家信息
	public function factory_list(){
		$factory =   M('factory');
		$arr = $factory->select();
		$this->assign("list",$arr);
		$this->display();
	}

	public function addFactory(){
		if (IS_POST){
			$factory =   M('factory');
			
			if($factory->create()) {
				$result =   $factory->add();
				if($result) {
					$this->success('操作成功！', U('factory_list'));
				}else{
					$this->error('写入错误！');
				}
			}else{
				$this->error($factory->getError());
			}
		}else {
			$this->display();
		}
	}

	public function editFactory(){
		$factory =   M('factory');
		if (IS_POST){		
			if($factory->create()) {
				$result =   $factory->save();
				if($result) {
					$this->success('操作成功！', U('factory_list'));
				}else{
					$this->error('写入错误！');
				}
			}else{
				$this->error($factory->getError());
			}
		}else {
			$where["factory_id"] = $_GET["id"];
			$res = $factory->where($where)->find();
			$this->assign("list",$res);
			$this->display();
		}
	}

	public function deleteFactory(){
		$factory =   M('factory');
		$factory_id = $_GET["id"];
		if ($factory->delete($factory_id)){
			//			unlink(C('IMAGES_UPLOAD_ROOT_PATH').$group['image']);//删除图片
			$this->success("删除成功");
		}else {
			$this->success("删除失败");
		}
	}
	//产品规格
	public function standard_list(){
		$standard =   M('Standard');
		$arr = $standard->select();
		$this->assign("list",$arr);
		$this->display();
	}

	public function addStandard(){
		if (IS_POST){
			$standard =   M('Standard');
			
			if($standard->create()) {
				$result =   $standard->add();
				if($result) {
					$this->success('操作成功！', U('standard_list'));
				}else{
					$this->error('写入错误！');
				}
			}else{
				$this->error($standard->getError());
			}
		}else {
			$this->display();
		}
	}

	public function editStandard(){
		$Standard =   M('Standard');
		if (IS_POST){		
			if($Standard->create()) {
				$result =   $Standard->save();
				if($result) {
					$this->success('操作成功！', U('standard_list'));
				}else{
					$this->error('写入错误！');
				}
			}else{
				$this->error($Standard->getError());
			}
		}else {
			$where["id"] = $_GET["id"];
			$res = $Standard->where($where)->find();
			$this->assign("list",$res);
			$this->display();
		}
	}

	public function deleteStandard(){
		$Standard =   M('Standard');
		$Standard_id = $_GET["id"];
		if ($Standard->delete($Standard_id)){
			//			unlink(C('IMAGES_UPLOAD_ROOT_PATH').$group['image']);//删除图片
			$this->success("删除成功");
		}else {
			$this->success("删除失败");
		}
	}
	//单位管理
	public function unit_list(){
		$Unit =   M('Unit');
		$arr = $Unit->select();
		$this->assign("list",$arr);
		$this->display();
	}

	public function addUnit(){
		if (IS_POST){
			$Unit =   M('Unit');
			
			if($Unit->create()) {
				$result =   $Unit->add();
				if($result) {
					$this->success('操作成功！', U('unit_list'));
				}else{
					$this->error('写入错误！');
				}
			}else{
				$this->error($Unit->getError());
			}
		}else {
			$this->display();
		}
	}

	public function editUnit(){
		$Unit =   M('Unit');
		if (IS_POST){		
			if($Unit->create()) {
				$result =   $Unit->save();
				if($result) {
					$this->success('操作成功！', U('unit_list'));
				}else{
					$this->error('写入错误！');
				}
			}else{
				$this->error($Unit->getError());
			}
		}else {
			$where["id"] = $_GET["id"];
			$res = $Unit->where($where)->find();
			$this->assign("list",$res);
			$this->display();
		}
	}

	public function deleteUnit(){
		$Unit =   M('Unit');
		$Unit_id = $_GET["id"];
		if ($Unit->delete($Unit_id)){
			//			unlink(C('IMAGES_UPLOAD_ROOT_PATH').$group['image']);//删除图片
			$this->success("删除成功");
		}else {
			$this->success("删除失败");
		}
	}


	public function adddiy(){
		$Produce = M('Produce');
		if(IS_POST){
			$DIY = M('diy');
			$upload = new \Think\Upload(C('IMAGES_UPLOAD'));// 实例化上传类
			// 上传单个文件
			$info4 = $upload->uploadOne($_FILES['image4']);
			$info5 = $upload->uploadOne($_FILES['image5']);
			$pro_id = $_POST['pro_id'];
			if($DIY->create()) {
				if($info4) {
					$DIY->image4 = $info4['savepath'].$info4['savename'];//修改图片
				}
				if($info5) {
					$DIY->image5 = $info5['savepath'].$info5['savename'];//修改图片
				}

				$result = $DIY->add();
				if($result) {
					$this->success('操作成功！',U('edit?id='.$pro_id));
				}else{
					$this->error('写入错误！');
				}
			}else{
				$this->error($DIY->getError());
			}
		}else {
			$pro_id = $_GET['id'];
			
			$Doormodel = M("Doormodel");
			$brand_id = $Produce->where('id='.$pro_id)->getField("brand_id");
			$doormodel = $Doormodel->where("brand_id=".$brand_id)->select();
			$this->assign("doormodel",$doormodel);
			$this->assign("pro_id",$pro_id);
			$this->display();
		}
	}

	public function deldiy(){
		$DIY = M('diy');
		$diy_id = $_GET['id'];
		$pro_id = $_GET['pro_id'];
		$res = $DIY->where("id=".$diy_id)->delete();
		if ($res) {
			$this->success('删除成功！',U('edit?id='.$pro_id));
		}else{
			$this->success("删除失败");
		}
	}
}