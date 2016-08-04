<?php
/**
 * 后台Index相关
 */
namespace Admin\Controller;
use Think\Controller;
class IndexController extends CommonController {
    
    public function index(){

    	$news = D('News')->maxCount();
    	$newscount = D('News')->getNewsCount(array('status' => 1));
    	$position = D('Position')->getCount(array('status' => 1));
    	$admincount = D('Admin')->getLastLoginUsers();
    	$this->assign('news',$news);
    	$this->assign('newscount',$newscount);
    	$this->assign('position',$position);
    	$this->assign('admincount',$admincount);
    	$this->display();
    }

    public function main() {
    	$this->display();
    }
}