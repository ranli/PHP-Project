<?php
/**
 * 后台Index相关
 */
namespace Admin\Controller;
use Think\Controller;


class BasicController extends CommonController {
    
    public function index(){
 		$conf = D('Basic')->select();
 		$this->assign('vo',$conf);
        $this->assign('type',1);
 		$this->display();

    }

    public function add(){
    	if($_POST){
    		if(!$_POST['title']){
    			return show(0,'title required');
    		}
    		if(!$_POST['keywords']){
    			return show(0,'keywords required');
    		}
    		if(!$_POST['description']){
    			return show(0,'description required');
    		}

    		D('Basic')->save($_POST);
    		return show(1,'set success');
    	}else{
    		return show(0,'no data post');
    	}
    }

    public function cache() {
        $this->assign('type',2);
        $this->display();
    }
}