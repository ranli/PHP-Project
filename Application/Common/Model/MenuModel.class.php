<?php
namespace Common\Model;
use Think\Model;

class MenuModel extends Model {
	private $_db = '';
	public function __construct() {
		$this->_db = M('Menu');
	}


	public function insert($data){
		if(!$data || !is_array($data)){
			return 0;
		}
		return $this->_db->add($data);
	}


	public function getMenus($data,$page,$pageSize=10) {
		$data['status'] = array('neq',-1);
		$offset = ($page - 1) * $pageSize;
		$list = $this->_db->where($data)->order('listorder desc, menu_id desc')->limit($offset,$pageSize)->select();
		return $list;

	}

	public function getMenusCount($data = array()){
		$data['status'] = array('neq',-1);
		return $this->_db->where($data)->count();
	}

	public function find ($id) {
		if(!$id || !is_numeric($id)){
			return array();
		}
		return $this->_db->where('menu_id='.$id)->find();
	}

	public function UpdateMenuById($id, $data){
		if(!$id || !is_numeric($id)){
			throw_exception("id not legal");
		}
		if(!$data || !is_array($data)){
			throw_exception("data not legal");
		}

		return $this->_db->where('menu_id='.$id)->save($data);
	}

	public function UpdateStatusById($id,$status){
		if(!$id || !is_numeric($id)){
			throw_exception("id not legal");
		}
		if(!is_numeric($status)){
			throw_exception("status not legal");
		}

		$data['status'] = $status;
		return $this->_db->where('menu_id='.$id)->save($data);
	}
	

	public function UpdateMenuListorderById($id,$listorder){
		if(!$id || !is_numeric($id)){
			throw_exception("id not legal");
		}

		$data = array('listorder' => intval($listorder));
		return $this->_db->where('menu_id='.$id)->save($data);
	}

	public function getAdminMenus(){
		$data = array(
			'status' => array('neq',-1),
			'type' => 1,
			);
		return $this->_db->where($data)->order('listorder desc, menu_id desc')->select();
	}

	public function getBarMenus(){
		$data = array('status' => array('neq',-1),
					'type' => 0, );
		$res = $this->_db->where($data)->order('listorder desc, menu_id desc')->select();
		return $res;
	}


}

