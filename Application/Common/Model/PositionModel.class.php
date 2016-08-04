<?php
namespace Common\Model;
use Think\Model;

class PositionModel extends Model {
	private $_db = '';
	public function __construct() {
		$this->_db = M('Position');
	}

	public function insert($data){
		if(!$data || !is_array($data)){
			return 0;
		}
		return $this->_db->add($data);
	}

	public function getPositions($data,$page,$pageSize=10) {
		//$data['status'] = array('neq',-1);
		$conditions = $data;
		if(isset($data['title']) && $data['title']){
			$conditions['name'] = array('like','%'.$data['title'].'%');
		}
		if(isset($data['position_id']) && $data['position_id']){
			$conditions['id'] = intval($data['position_id']);
		}
		
		$offset = ($page - 1) * $pageSize;
		$list = $this->_db->where($conditions)->order('id desc')->limit($offset,$pageSize)->select();
		return $list;

	}

	public function getPositionsCount($data = array()){
		//$data['status'] = array('neq',-1);
		return $this->_db->where($data)->count();
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

	public function getNormalPositions(){
		$conditions = array('status'=>1);
		$list = $this->_db->where($conditions)->order('id')->select();
		return $list;
	}

	public function getCount($data=array()){
		
		return $this->_db->where($data)->count();
	}

}