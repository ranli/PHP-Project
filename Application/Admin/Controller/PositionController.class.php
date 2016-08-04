<?php
/**
 * menu相关
 */
namespace Admin\Controller;
use Think\Controller;

class PositionController extends CommonController {
    
    public function index(){
		$data = array();
    	
    	$title = $_GET['title'];
    	if($title){
    		$data['title'] = $title;
    	}
    	if($_GET['position_id']){
    		$data['position_id'] = intval($_GET['position_id']);
    	}


    	$page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
    	$pageSize = $_REQUEST['pageSize'] ? $_REQUEST['pageSize'] : 3;
    	$positions = D('Position')->getPositions($data,$page,$pageSize);
    	$positionCount = D('Position')->getPositionsCount($data);

    	$res = new \Think\Page($positionCount,$pageSize);
    	$pageRes = $res->show();

    	
    	$this->assign('pageRes', $pageRes);
    	$this->assign('positions', $positions);
    	$this->assign('positionName',D('PositionContent')->getPosition());
    	$this->display();

    }

   
    public function setStatus(){
	 	parent::setStatus($_POST,'Position');
    }

}