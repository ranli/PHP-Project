<?php
/**
 * 后台Index相关
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Upload;

class ImageController extends CommonController {
	private $_uploadObj;
    public function __construct(){
    	
    }

    public function ajaxuploadimage() {
    	$upload = D('UploadImage');
    	$res = $upload->imageUpload();
    	if($res === false){
    		return show(0,'uoload failed','');
    	}else{
    		return show(0, 'upload cusseed',$res);
    	}
    }

    public function kindupload(){
    	$upload = D('UploadImage');
    	$res = $upload->upload();
    	if($res === false){
    		return showKind(1,'failed upload');

    	}
    	return showKind(0,$res);
    }



}