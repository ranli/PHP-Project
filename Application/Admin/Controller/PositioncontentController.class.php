<?php
/**
 * menu相关
 */
namespace Admin\Controller;
use Think\Controller;

class PositioncontentController extends CommonController {
    
    public function index(){
    	$positions = D('Position')->getNormalPositions();
    	
    	$data['status'] = array('neq', -1);
    	if($_GET['title']){
    		$data['title'] = trim($_GET['title']);
    		$this->assign('title',$data['title']);
    	}
    	$data['position_id'] = $_GET['position_id'] ? intval($_GET['position_id']) : $positions[0]['id'];
    	$contents = D('PositionContent')->select($data);
    	$this->assign('positions',$positions);
    	$this->assign('contents',$contents);
    	$this->assign('positionId',$data['position_id']);
 		$this->display();
    }

    public function add(){
    	if($_POST){
    		if(!isset($_POST['position_id']) || !$_POST['position_id']){
    			return show(0,'no position_id');
    		}
    		if(!isset($_POST['title']) || !$_POST['title']){
    			return show(0,'no title');
    		}
    		if(!$_POST['url'] && !$_POST['news_id']){
    			return show(0,'newsid and url can not be empty both');
    		}
    		if(!isset($_POST['thumb']) || !$_POST['thumb']){
    			if($_POST['news_id']){
    				$res = D('News')->find($_POST['news_id']);
    				if($res && is_array($res)){
    					$_POST['thumb'] = $res['thumb'];
    				}
    			}else{
    				return show(0,'image required');
    			}
    		}
    		if($_POST['id']){
    			return $this->save($_POST);
    		}
    		try{
    			$id = D('PositionContent')->insert($_POST);
    			if($id){
    				return show(1,'add success');
    			}
    			return show(0,'add failed');
    		}catch(Exceptioin $e){
    			return show(0,$e->getMessage());
    		}
	    }else{
	    	$positions =D('Position')->getNormalPositions();
	    	$this->assign('positions',$positions);

	    	$this->display();
	   		}
    }

    public function edit(){

    	$id = $_GET['id'];
    	$position = D('PositionContent')->find($id);
    	$positions =D('Position')->getNormalPositions();

    	$this->assign('positions',$positions);
    	$this->assign('vo',$position);
    	$this->display();
    }

    public function save($data){
    	$id = $data['id'];
    	unset($data['id']);
    	try{
    		$resId = D('PositionContent')->updateById($id,$data);
    		if($resId === false){
    			return show(0,'update failed');
    		}
    		return show(1,'update success');
    	}catch(Exceptioin $e){
    		return show(0,$e->getMessage());
    	}
    }

    public function setStatus(){
    	parent::setStatus($_POST,'PositionContent');
    }	
    
    public function listorder(){
    	$listorder = $_POST['listorder'];
    	$jumpUrl = $_SERVER['HTTP_REFERER'];
    	$errors = array();
    	try{
		    	if($listorder){
		    		foreach ($listorder as $newsId => $v) {
		    			$id = D('PositionContent')->updateNewsListorderById($newsId,$v);
		    			if($id === false){
		    				$errors[] = $newsId;
		    			}

		    		}
		    		if($errors){
		    			return show(0,'order failed'.implode(',',$errors), array('jump_url' => $jumpUrl));
		    		}
		    		return show(1,'order success', array('jump_url' => $jumpUrl));
		    	}
		    }catch(Exception $e){
		    	return show(0,$e->getMessage());
		    }
		    return show(0,'order data failed',array('jump_url' => $jumpUrl));
    }
}