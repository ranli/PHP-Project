<?php
/**
 * menu相关
 */
namespace Admin\Controller;
use Think\Controller;
class MenuController extends CommonController {
    
    public function index(){
    	$data = array();
    	if(isset($_REQUEST['type']) && in_array($_REQUEST['type'], array(0,1))){
    		$data['type'] = intval($_REQUEST['type']);
    		$this->assign('type',$data['type']);
    	}else{
    		$this->assign('type',-1);
    	}


    	$page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
    	$pageSize = $_REQUEST['pageSize'] ? $_REQUEST['pageSize'] : 3;
    	$menus = D('Menu')->getMenus($data,$page,$pageSize);
    	$menusCount = D('Menu')->getMenusCount($data);

    	$res = new \Think\Page($menusCount,$pageSize);
    	$pageRes = $res->show();
    	$this->assign('pageRes', $pageRes);
    	$this->assign('menus', $menus);
    	$this->display();
    }

    public function add() {
    	if($_POST){
    		if(!isset($_POST['name']) || !$_POST['name']){
    			return show(0,'menu name require');
    		}
    		if(!isset($_POST['m']) || !$_POST['m']){
    			return show(0,'model name require');
    		}
    		if(!isset($_POST['c']) || !$_POST['c']){
    			return show(0,'controller name require');
    		}
    		if(!isset($_POST['f']) || !$_POST['f']){
    			return show(0,'function name require');
    		}

    		if($_POST['menu_id']){
    			return $this->save($_POST);
    		}
    		$menuId = D('Menu')->insert($_POST);
    		if($menuId){
    			return show(1,'success', $menuId);
    		}
    		return show(0,'failed', $menuId);
    	}else{
    	$this->display();
		}    	
    }

    public function edit(){
    	$menuId = $_GET['id'];
    	$menu = D('Menu')->find($menuId);
    	$this->assign('menu', $menu);
    	$this->display();
    }

    public function save($data){
    	$menuId = $data['menu_id'];
    	unset($data['menu_id']);
    	try{
    		D('Menu')->UpdateMenuById($menuId,$data);
    		if($id === false){
    			return show(0,'update failed');
    		}
    		return show(1,'update success');
    	}catch(Exception $e){
    		return show(0,$e->getMessage());
    	}
    	
    }

    public function setStatus(){
    	parent::setStatus($_POST,'Menu');
    }
    public function listorder(){
    	$listorder = $_POST['listorder'];
    	$jumpUrl = $_SERVER['HTTP_REFERER'];
    	$error = array();
    	if($listorder){
    		try{
	    		foreach ($listorder as $menuId => $v) {
	    			D('Menu')->UpdateMenuListorderById($menuId, $v);
	    			if($id === false){
	    				$errors[] = $menuId;
	    			}
	    		}
	    	}catch(Exception $e){
	    		return show(0,$e->gerMessage(),array('jump_url' => $jumpUrl));
	    	}


    		if($errors){
    			return show(0,'failed order'.implode(',', $errors),array('jump_url' => $jumpUrl));
    		}
    		return show(1,'sucess',array('jump_url' => $jumpUrl));
    	}
    	return show(0,'shibai',array('jump_url' => $jumpUrl));


    }


}