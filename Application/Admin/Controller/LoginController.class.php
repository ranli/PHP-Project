<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * use Common\Model 这块可以不需要使用，框架默认会加载里面的内容
 */
class LoginController extends Controller {

    public function index(){
        if(session('adminUser')){
            $this->redirect('/index.php?m=admin&c=index');
        }
        return $this->display();
    } 

    public function check() {
       
        $username = $_POST['username'];
        $password = $_POST['password'];

        if(!trim($username)){
            return show(0,'username reuqired');
        }
        if(!trim($password)){
            return show(0,'password reuqired');
        }

        $ret = D('Admin')->getAdminByUsername($username);
        if(!$ret || $ret['status'] != 1){
            return show(0,'user not exist!');
        }
        if($ret['password'] != getMd5Password($password)){
            return show(0,'wrong password');
        }

        D('Admin')->updateByAdminId($ret['admin_id'],array('lastlogintime' => time()));
        session('adminUser', $ret);
        return show(1,'success');
    }

    public function loginout() {
        session('adminUser', null);
        $this->redirect('/index.php?m=admin&c=login');
    }
}

