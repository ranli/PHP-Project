<?php 
namespace Common\Model;
use Think\Model;

class AdminModel extends Model {
	private $_db = '';
	public function __construct() {
		$this->_db = M('Admin');
	}
	public function getAdmins(){
		$cond['status'] = array('neq',-1);
		$list = $this->_db->where($cond)->select();
		return $list;
	}
	public function getAdminByUsername($username){
		$ret = $this->_db->where('username="'.$username.'"')->find();
		return $ret;
	}

	public function insert($data){
		if(!$data || !is_array($data)){
			return 0;
		}

		$data['lastlogintime'] = time();
		return $this->_db->add($data);
	}

	public function UpdateStatusById($id,$status){
		if(!$id || !is_numeric($id)){
			throw_exception("id not legal");
		}
		
		if(!is_numeric($status)){
			throw_exception("status not legal");
		}

		$data['status'] = $status;
		return $this->_db->where('admin_id='.$id)->save($data);
	}

	public function updateByAdminId($id,$data){
		if(!$id || !is_numeric($id)){
			throw_exception("id not legal");
		}
		if(!$data || !is_array($data)){
			throw_exception("data not legal");
		}

		return $this->_db->where('admin_id='.$id)->save($data);
	}
	
	public function getAdminByAdminId($id){
		if(!$id || !is_numeric($id)){
			throw_exception("id not legal");
		}
		return $this->_db->where('admin_id='.$id)->find();
	}

	public function getLastLoginUsers(){
		$time = mktime(0,0,0,date("m"),date("d"),date("y"));
		$data = array(
				'status' => 1,
				'lastlogintime' => array('gt', $time),
				);

		$res = $this->_db->where($data)->count();
		return $res['tp_count'];
	}
}