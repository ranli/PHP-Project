<?php 
namespace Common\Model;
use Think\Model;

class NewsModel extends Model {
	private $_db = '';

	public function __construct() {
		$this->_db = M('news');
	}
	public function insert($data = array()){
		if(!is_array($data) || !$data){
			return 0;
		}
		$data['create_time'] = time();
		$data['username'] = getLoginUsername();
		return $this->_db->add($data);
	}
	public function getNews($data,$page,$pageSize){
		$conditions = $data;
		$conditions['status'] = array('neq',-1);
		if(isset($data['title']) && $data['title']){
			$conditions['title'] = array('like','%'.$data['title'].'%');
		}
		if(isset($data['catid']) && $data['catid']){
			$conditions['catid'] = intval($data['catid']);
		}
		$offest = ($page - 1) * $pageSize;
		$list = $this->_db->where($conditions)->order('listorder desc, news_id desc')->limit($offest,$pageSize)->select();
		return $list;
	}
	public function getNewsCount($data = array()){
		$conditions = $data;
		$conditions['status'] = array('neq',-1);
		if(isset($data['title']) && $data['title']){
			$conditions['title'] = array('like','%'.$data['title'].'%');
		}
		if(isset($data['catid']) && $data['catid']){
			$conditions['catid'] = intval($data['catid']);
		}
		return $this->_db->where($conditions)->count();
	}

	public function find($id){
		$data = $this->_db->where('news_id='.$id)->find();
		return $data;
	}
	public function updateById($id,$data){
		if(!$id || !is_numeric($id)){
			throw_exception("is not legal");
			
		}
		if(!$data || !is_array($data)){
			throw_exception("data not leagal");
			
		}
		return $this->_db->where('news_id='.$id)->save($data);
	}

	public function updateStatusById($id,$status){
		if(!is_numeric($status)){
			throw_exception('status not legal');
		}

		if(!$id || !is_numeric($id)){
			throw_exception('id not leagal');

		}

		$data['status'] = $status;
		return $this->_db->where('news_id ='.$id)->save($data);
	}
	public function updateNewsListorderById($id,$listorder){
		if(!$id || !is_numeric($id)){
			throw_exception('id not leagal');
		}

		$data = array('listorder' => intval($listorder));
		return $this->_db->where('news_id='.$id)->save($data);
	}

	public function getNewsByNewsId($push){
		if(!is_array($push)){
			throw_exception('parameter is not leagal');
		}

		$data = array('news_id' => array('in',implode(',',$push)),
		 );
		return $this->_db->where($data)->select();
	}

	public function getRank($data=array(), $limit=100){
		$list = $this->_db->where($data)->order('count desc, news_id desc')->limit($limit)->select();
		return $list;
	}

	public function select($data=array(),$limit=0){
		$this->_db->where($data);
		if($limit){
			$this->_db->limit($limit);
		}
		$list = $this->_db->select();
		return $list;
	}

	public function updateCount($id,$count){
		if(!is_numeric($id) || !$id){
			throw_exception('id not leagal');
		}
		if(!is_numeric($count)){
			throw_exception('count must be interger');
		}
		$data['count'] = $count;
		return $this->_db->where('news_id='.$id)->save($data);
	}

	public function maxCount(){
		$data = array(
					'status' => 1,
					);
		return $this->_db->where($data)->order('count desc')->limit(1)->find();
	}

}