<?php
/**
 * 后台Index相关
 */
namespace Admin\Controller;
use Think\Controller;


class AdminController extends CommonController {
	public function index(){
		$admins = D('Admin')->getAdmins();
		$this->assign('admins',$admins);
		$this->display();
	}

	public function add(){
		if($_POST){
    		if(!isset($_POST['username']) || !$_POST['username']){
    			return show(0,'username require');
    		}
    		if(!isset($_POST['password']) || !$_POST['password']){
    			return show(0,'password require');
    		}

    		$_POST['password'] = getMd5Password($_POST['password']);
    		$admin = D('Admin')->getAdminByUsername($_POST['username']);
    		if($admin && $admin['status'] != -1){
    			return show(0,'user already exist!');
    		}


    		$id = D('Admin')->insert($_POST);

    		if(!$id){
    			return show(0,'add user failed');
    		}
    		return show(1,"add user success");

    	}
    	$this->display();
	}

	

	public function setStatus(){
    	parent::setStatus($_POST,'Admin');
    }




    public function personal(){
    	$res = $this->getLoginUser();
    	$user = D('Admin')->getAdminByAdminId($res['admin_id']);
    	$this->assign('vo',$user);
    	$this->display();
    }

    public function save(){
    	$user = $this->getLoginUser();
    	if(!$user){
    		return show(0,"user not exist");
    	}

    	$data['realname'] = $_POST['realname'];
    	$data['email'] = $_POST['email'];

    	try{
    		$id = D('Admin')->updateByAdminId($user['admin_id'],$data);
    		if($id === false){
    			return show(0,'failed');
    		}
    		return show(1,'success');
    	}catch(Exception $e){
    		return show(0,$e->getMessage());
    	}
    }

}