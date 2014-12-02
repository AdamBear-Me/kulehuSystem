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
class GoodsController extends AdminController {


	//添加产品
	public function add(){
		if (IS_POST){
			$upload = new \Think\Upload(C('IMAGES_UPLOAD'));// 实例化上传类
			// 上传单个文件
			$info1 = $upload->uploadOne($_FILES['image1']);

			if(!$info1){
				$this->error($upload->getError());
			}
			$Goods = M('Goods');

			if($Goods -> create()) {
				$Goods -> image1 = $info1['savepath'].$info1['savename'];

				$result = $Goods -> add();
				if($result) {
					$this->success('操作成功！', U('show'));
				}else{
					$this->error('写入错误！');
				}
			}else{
				$this->error($Goods->getError());
			}
				
		}else {
			$this->display();
		}
	}
	//查看产品
	public function show(){
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

	//删除产品
	public function delete($id = 0){

		$Goods = M('Goods');

		if ($Goods->delete($id)){
			$this->success("删除成功");
		}else {
			$this->error("删除失败");
		}
	}

	public function edit($id = 0){
		echo "";
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

				$store_data['size']=$value['G'];

				$store_data['brand_id']=$value['H'];
				$store_data['style_id']=$value['I'];
				$store_data['material_id']=$value['J'];
				$store_data['usage_id']=$value['K'];
				$store_data['origin_id']=13;//$value['L'];//用途
				$store_data['activity_id']=0;

				$store_data['image1']='190x144/'.$value['N'];
				$store_data['image2']='348x348/'.$value['O'];
				$store_data['image3']='707x707/'.$value['P'];

				$store_data['sort']=$value['Q'];
				//	$store_data['image4']=$value['Q'];
				//	$store_data['image5']=$value['O'];
				$store_data['image4']=0;
				$store_data['image5']=0;
				//				dump($store_data);
				//				die();

				if ($store_data['name']!=NULL){

					$Goods = M('Goods');
					$result = $Goods -> add($store_data);
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

		header('Content-type: text/html;charset=UTF-8');
		echo '出错的数据';
		dump($error_data);
		echo '完成'.$i."个数据";
		die();
	}




}
