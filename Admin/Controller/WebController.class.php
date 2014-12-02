<?php
namespace Admin\Controller;
use Think\Controller;
/**
 *  webUploader后台上传自定义控制器
 */
class WebController extends AdminController {

	//上传首页
	public function index(){
		$this->savePath = isset($_GET['savePath']) ? $_GET['savePath'] + 0 : 190;
		$this->display();
	}

	public function upload(){
		$config = array(
			'rootPath'   =>    './Uploads/Images/',
			'savePath'	 =>		$_POST['savaPath'].'/',
			'saveName'   =>    $_POST['name'],
			'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
			'autoSub'    =>    true,
			'subName'    =>    array('date','Y-m-d')
		);
		$upload = new \Think\Upload($config);// 实例化上传类
		$info   =   $upload->upload();
		if(!$info) {
			// 上传错误提示错误信息
			//header("HTTP/1.0 500 Internal Server Error");
			$data = array('status'=>0,'msg'=>$upload->getError());
		}else{
			// 上传成功 获取上传文件信息
			$data = array('status'=>1);
		}
		echo json_encode($data);
	}
}