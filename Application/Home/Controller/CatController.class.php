<?php
namespace Home\Controller;
use Think\Controller;
class CatController extends CommonController {
	public function index(){
		$id = intval($_GET['id']);
		if(!$id){
			return $this->error('ID not exist');
		}
		$nav = D('Menu')->find($id);
		if(!$nav || $nav['status'] =! 1){
			return $this->error('nav id not normal');
		}

		$advNews = D('PositionContent')->select(array('status' => 1, 'position_id' =>5),2);
		$rankNews = $this->getRank();


		$page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
    	$pageSize = 20;
    	$conds = array('status' => 1,
    					'thumb' => array('neq',''),
    					'catid' => $id );
    	
    	$news = D('News') -> getNews($conds,$page,$pageSize);
    	$count = D('News') -> getNewsCount($conds);

    	$res = new \Think\Page($count,$pageSize);
    	$pageRes = $res->show();

		$this->assign('result', array(
        	'listNews'=> $listNews,
        	'advNews'=> $advNews,
        	'rankNews'=> $rankNews,
        	'catId'=> $id,
        	'listNews' => $news,
        	'pageRes' => $pageRes,
        	));
		$this->display();
	}
}