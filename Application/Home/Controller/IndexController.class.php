<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index($type=''){
    	$rankNews = $this->getRank();

    	$topPicNews = D('PositionContent')->select(array('status' => 1, 'position_id' =>2),3);
        
    	$topSmallPicNews = D('PositionContent')->select(array('status' => 1, 'position_id' =>3),3);

    	$listNews = D('News')->select(array('status' => 1, 'thumb'=>array('neq','')),30);

    	$advNews = D('PositionContent')->select(array('status' => 1, 'position_id' =>5),2);

        $this->assign('result', array(
        	'topPicNews'=> $topPicNews,
        	'topSmallPicNews'=> $topSmallPicNews,
        	'listNews'=> $listNews,
        	'advNews'=> $advNews,
        	'rankNews'=> $rankNews,
        	'catId'=> 0
        	));

        if($type == 'buildHtml'){
            $this->buildHtml('index',HTML_PATH, 'Index/index');
        }else{
            $this->display();
        }
    }


    public function build_html(){
        $this->index('buildHtml');
        return show(1,"首页缓存生成成功");
    }

    public function crontab_build_html(){
        if(!APP_CRONTAB != 1){
            die("file must exec conttable");
        }
        $result = D('Basic')->select();
        if(!$result['casheindex']){
            die("system do not set automatic cashe");
        }
        $this->index('buildHtml');
    }

    public function getCount(){
        if(!$_POST){
            return show(0,'no data');
        }

        $newsId = array_unique($_POST);

        try{
            $list = D('News')->getNewsByNewsID($newsId);
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }

          if(!$list){
            return show(0,'no data');
        }

        $data = array();
        foreach ($list as $key => $value) {
            $data[$value['news_id']] = $value['count'];
        }
        return show(1,'success',$data);
    }
}