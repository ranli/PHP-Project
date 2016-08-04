<?php
namespace Common\Model;
use Think\Model;

class PositionContentModel extends Model {
	private $_db = '';
	public function __construct() {
		$this->_db = M('position_content');
	}

	public function getPosition(){
		$res = $this->_db->select();
		return $res;
	}

	public function find($id){
		$data = $this->_db->where('id='.$id)->find();
		return $data;
	}
	public function insert($data){
		if(!$data || !is_array($data)){
			return 0;
		}
		return $this->_db->add($data);
	}
	
	public function select($data=array(),$limit=0){
		if($data['title']) {
			$data['title'] = array('like','%'.$data['title'].'%');
		}
		$this->_db->where($data)->order('listorder desc, id desc');
		if($limit){
			$this->_db->limit($limit);
		}
		$list = $this->_db->select();
		return $list;
	}

	public function updateById($id,$data){
		if(!$id || !is_numeric($id)){
			throw_exception("is not legal");
			
		}
		if(!$data || !is_array($data)){
			throw_exception("data not leagal");
			
		}
		return $this->_db->where('id='.$id)->save($data);
	}

	public function updateStatusById($id,$status){
		if(!is_numeric($status)){
			throw_exception('status not legal');
		}

		if(!$id || !is_numeric($id)){
			throw_exception('id not leagal');

		}

		$data['status'] = $status;
		return $this->_db->where('id ='.$id)->save($data);
	}

	public function updateNewsListorderById($id,$listorder){
		if(!$id || !is_numeric($id)){
			throw_exception('id not leagal');
		}

		$data = array('listorder' => intval($listorder));
		return $this->_db->where('id='.$id)->save($data);
	}
}