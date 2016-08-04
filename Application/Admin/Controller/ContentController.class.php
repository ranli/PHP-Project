<?php
/**
 * 后台Index相关
 */
namespace Admin\Controller;
use Think\Controller;


class ContentController extends CommonController {
    
    public function index(){
    	$conds = array();
    	$title = $_GET['title'];
    	if($title){
    		$conds['title'] = $title;
    	}
    	if($_GET['catid']){
    		$conds['catid'] = intval($_GET['catid']);
    	}


    	$page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
    	$pageSize = 5;
    	
    	$news = D('News') -> getNews($conds,$page,$pageSize);
    	$count = D('News') -> getNewsCount($conds);

    	$res = new \Think\Page($count,$pageSize);
    	$pageRes = $res->show();
        $positions = D('Position')->getNormalPositions();

    	$this->assign('pageRes',$pageRes);
    	$this->assign('news',$news);
        $this->assign('positions',$positions);

    	$this->assign('websiteMenu',D('Menu')->getBarMenus());
    	$this->display();
    }

    public function add() {
    	if($_POST){
    		if(!isset($_POST['title']) || !$_POST['title']){
    			return show(0,'no title');
    		}
    		if(!isset($_POST['small_title']) || !$_POST['small_title']){
    			return show(0,'no small_title');
    		}
    		if(!isset($_POST['catid']) || !$_POST['catid']){
    			return show(0,'no catid');
    		}
    		if(!isset($_POST['keywords']) || !$_POST['keywords']){
    			return show(0,'no keywords');
    		}
    		if(!isset($_POST['content']) || !$_POST['content']){
    			return show(0,'no content');
    		}

    		if($_POST['news_id']){
    			return $this->save($_POST);
    		}

    		$newsId = D('News')->insert($_POST);
    		if($newsId) {
    			$newContentData['content'] = $_POST['content'];
    			$newContentData['id'] = $newsId;
    			$cId = D('NewsContent')->insert($newContentData);
    			if($cId){
    				return show(1,"add success");
    			}else{
    				return show(1,"main chart success, subchart failed");
    			}
    		}else{
    			return show(0,'add failed');
    		}

    	}else{
    		$websiteMenu = D('Menu')->getBarMenus();
	    	$titleFontColor = C('TITLE_FONT_COLOR');
	    	$copyFrom = C('COPY_FROM');
	    	$this->assign('websiteMenu',$websiteMenu);
	    	$this->assign('titleFontColor',$titleFontColor);
	    	$this->assign('copyFrom',$copyFrom);
	    	$this->display();
    	}
    	
    }

    public function edit(){
    	$newsId = $_GET['id'];
    	if(!$newsId){
    		$this->redirect('/admin.php?c=content');
    	}
    	$news = D('News')->find($newsId);
    	if(!$news){
    		$this->redirect('/admin.php?c=content');
    	}
    	$newsContent = D('NewsContent')->find($newsId);
    	if($newsContent){
    		$news['content'] = $newsContent['content'];
    	}

    	$websiteMenu = D('Menu') -> getBarMenus();
    	$this->assign('websiteMenu',$websiteMenu);
    	$this->assign('titleFontColor', C("TITLE_FONT_COLOR"));
    	$this->assign('copyFrom', C("COPY_FROM"));

    	$this->assign('news',$news);
    	$this->display();
    }

    public function save($data){
    	$newsId = $data['news_id'];
    	unset($data['news_id']);
    	try{
    		$id = D('News')->updateById($newsId,$data);
    		$newsContentData['content'] = $data['content'];
    		$contId = D('NewsContent')->updateNewsById($newsId,$newsContentData);
    		if($id === false || $contId === false){
    			return show(0,"update failed");

    		}
    		return show(1,'update success');
    	}catch(Exception $e){
    		return show(0, $e->getMessage());
    	}
    }

    public function setStatus(){
	 	parent::setStatus($_POST,'Content');
    }	

    public function listorder(){
    	$listorder = $_POST['listorder'];
    	$jumpUrl = $_SERVER['HTTP_REFERER'];
    	$errors = array();
    	try{
		    	if($listorder){
		    		foreach ($listorder as $newsId => $v) {
		    			$id = D('News')->updateNewsListorderById($newsId,$v);
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

    public function push(){
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $positionId = intval($_POST['position_id']);
        $push = $_POST['push'];

        if(!$push || !is_array($push)){
            return show(0,'pls selece position id');
        }
        if(!$positionId){
            return show(0,'no position id');
        }

        try{
            $news = D('News')->getNewsByNewsId($push);

            if(!$news){
                return show(0,'no releated content');
            }

            foreach ($news as $new){
                $data = array(
                    'position_id' => $positionId,
                    'title' => $new['title'],
                    'thumb' => $new['news_id'],
                    'news_id' => $new['news_id'],
                    'status' => 1,
                    'create_time' => $new['create_time'],
                    );
                $position = D('PositionContent')->insert($data);
            }
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }

        return show(1,'push success',array('jump_url'=>$jumpUrl));
    }


}