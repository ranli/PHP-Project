<?php
/**
 * 后台Index相关
 */
namespace Admin\Controller;
use Think\Controller;


class CronController {
	public function dumpmysql(){
		$result = D('Basic')->select();
		echo $result['dumpmysql'];exit;
		if(!$result['dumpmysql']){
			die("system does not open automatic bakup");
		}
		$shell = "mysqldump -u".C("DB_USER")." -p".C("DB_PWD")." ".C("DB_NAME")." > /php/htdocs/project/".date("Ymd").".sql";
		exec($shell);
	}
}