<?php 
namespace Common\Model;
use Think\Model;

class NewsContentModel extends Model {
	private $_db = '';

	public function __construct() {
		$this->_db = M('news_content');
	}
	public function insert($data = array()){
		if(!is_array($data) || !$data){
			return 0;
		}
		$data['create_time'] = time();
		if(isset($data['content']) && $data['content']){
			$data['content'] = htmlspecialchars($data['content']);
		}
		return $this->_db->add($data);
	}
	public function find($id){
		return $this->_db->where('news_id='.$id)->find();
	}

	public function updateNewsById($id,$data){
		if(!$id || !is_numeric($id)){
			throw_exception("is not legal");
			
		}
		if(!$data || !is_array($data)){
			throw_exception("data not leagal");
			
		}

		if(isset($data['content']) && $data['content']){
			$data['content'] = htmlspecialchars($data['content']);
			
		}
		return $this->_db->where('news_id='.$id)->save($data);
	}

}