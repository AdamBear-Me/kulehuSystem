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
use User\Model\UcenterMemberModel;

/**
 * 分销商控制器
 * @author WYJ <365657359@qq.com>
 */
define('UC_AUTH_KEY', '$=-t(hTqiP,MSEzAW4~);*x`KRCsG5NF/V0kd}Z1'); //加密KEY
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
	private function addAgent($username='',$password='', $repassword = '',$email = '',$name='',$level='',$id_card='',$birthday='',$province='',$city='',$county='',$address='',$company='',$reserve_phone='',$landline='',$qq=''){
		/* 检测密码 */
		if($password != $repassword){
			$this->error('密码和重复密码不一致！');
		}
		/* 调用注册接口注册用户 */
		$User = new UserApi;
		$uid = $User->register($username, $password, $email,$mobile='',$id_card,$birthday,$province,$city,$county,$address,$company,$reserve_phone,$landline,$qq);
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
	//添加代理商1
	public function add1($username='',$password='', $repassword = '',$email = '',$name='',$id_card='',$birthday='',$province='',$city='',$county='',$address='',$company='',$reserve_phone='',$landline='',$qq=''){
		if (IS_POST){
			$a=$this->addAgent($username,$password,$repassword,$email,$name,1,$id_card,$birthday,$province,$city,$county,$address,$company,$reserve_phone,$landline,$qq);
			echo $a;
		}else {
			$this->display();
		}
	}

    //添加经销商
    public function add2($username='',$password='', $repassword = '',$email = '',$name='',$id_card='',$birthday='',$province='',$city='',$county='',$address='',$company='',$reserve_phone='',$landline='',$qq=''){
        if (IS_POST){
            $a=$this->addAgent($username,$password,$repassword,$email,$name,2,$id_card,$birthday,$province,$city,$county,$address,$company,$reserve_phone,$landline,$qq);
            echo $a;
        }else {
            $this->display();
        }
    }

    //添加代理商1
    public function add3($username='',$password='', $repassword = '',$email = '',$name='',$id_card='',$birthday='',$province='',$city='',$county='',$address='',$company='',$reserve_phone='',$landline='',$qq=''){
        if (IS_POST){
            $a=$this->addAgent($username,$password,$repassword,$email,$name,3,$id_card,$birthday,$province,$city,$county,$address,$company,$reserve_phone,$landline,$qq);
            echo $a;
        }else {
            $this->display();
        }
    }
	//查看全部代理商
	public function show(){
		$Agent = M('Agent');
        if(I('name')){
            $map['name'] = array('like',"%".I('name')."%");
        }
        $map['puid'] = UID;
        $map['status'] = 1;
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
	public function addSalesman($username='',$password='', $repassword = '',$email = '',$name='',$id_card=''){
		if (IS_POST){
			if($password != $repassword){
				$this->error('密码和重复密码不一致！');
			}
			/* 调用注册接口注册用户 */
			$User = new UserApi;
			$uid = $User -> register($username, $password, $email,$mobile='',$id_card);
			if(0 < $uid){ //注册成功
				$Salesman = M('Salesman');
				$user = array('uid' => $uid, 'puid' => UID, 'name' => $name,'id_card'=>$id_card);
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
        $map['status'] = 1;
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
    //删除业务员
    public function deleteSalesman(){
        $ucenter_member = M('ucenter_member');
        $salesman = M('salesman');
        $ucenter_member->startTrans();
        $res1 = $ucenter_member->where("status= 1 and id=".I('id'))->setField("status",0);
        $res2 = $salesman->where("status=1 and uid=".I('id'))->setField('status',0);
         if($res1 && $res2){
            $ucenter_member->commit();
            $this->success("删除成功");
        }else{
            $ucenter_member->rollback();
            $this->error("删除失败");
        }
    }

    //删除代理商
    public function deleteAgent(){
        $ucenter_member = M('ucenter_member');      //用户总表
        $member = M('member');                     //后台用户登陆表
        $agent = M('agent');                        //商家表（代理商，经销商，业务员）
        $salesman = M('Salesman');                  //业务员表

        $res1 = $agent->where("status = 1 and puid = ".I('id'))->select();
        $res2 = $salesman->where("status = 1 and puid = ".I('id'))->select();
        if($res1 || $res2){
            $this->error("该用户下面已经创建了其他商家，不能删除！");
        }else{
            $agent_res = $agent->where("status = 1 and uid = ".I('id'))->find();
            if($agent_res){     //如果是删除商家，且存在（代理商，经销商，设计师）
                $ucenter_member->startTrans();      //开启事务
                $r1 = $ucenter_member->where("status = 1 and id = ".I('id'))->setField("status",0);
                $r2 = $member->where("status = 1 and uid = ".I('id'))->setField("status",0);
                $r3 = $agent->where("status = 1 and uid = ".I('id'))->setField('status',0);
                if($r1 && $r2 && $r3){
                    $ucenter_member->commit();
                    $this->success("删除成功！");
                }else{
                    $ucenter_member->rollback();
                    $this->error("删除失败！");
                }
            }else{
                $this->error("删除商家失败！");
            }
        }
    }
    //编辑商家
    public function editAgent(){
        $ucenter_member = M('ucenter_member');
        $member = M("member");
        $agent = M('agent');
        if(IS_POST){
           $data =  $ucenter_member->create();
            $data = array_splice($data,0,11);

            if($data){
                $ucenter_member->startTrans();
                $um_res = $ucenter_member->where("id=".I('id'))->data($data)->save();                   //修改ucenter_member
                $agent_res = $agent->where("uid=".I('id'))->setField("name",I('name'));                  //修改agent
                if(($um_res !== false) && ($agent_res !== false)){
                    $ucenter_member->commit();
                    $this->success("修改成功");
                }else{
                    $ucenter_member->rollback();
                    $this->error("修改失败");
                }
            }else{
                $this->error("创建失败");
            }
        }else{
            $agent_info = $ucenter_member
                ->table("bizhi_ucenter_member um")
                ->join("bizhi_agent a on um.id = a.uid")
                ->field("um.*,a.name")
                ->where("um.status=1 and um.id = ".I("id"))
                ->find();

            $this->assign("agent_info",$agent_info);
            $this->display();
        }

    }
    //重置密码
    public function reset_pwd(){
        $ucenter_member =  M('ucenter_member');
        $condition['id'] = I('id');
        $data['password'] = $this->think_ucenter_md5("123456", UC_AUTH_KEY);
        $res = $ucenter_member->where($condition)->data($data)->save();
        if($res !== false){
            $this->success("重置成功，新密码为：123456");
        }else{
            $this->error("密码重置失败！");
        }
    }

//    -------------重置密码用到的自定义加密函数--------------------
    function think_ucenter_md5($str, $key = 'ThinkUCenter'){
        return '' === $str ? '' : md5(sha1($str) . $key);
    }

}
