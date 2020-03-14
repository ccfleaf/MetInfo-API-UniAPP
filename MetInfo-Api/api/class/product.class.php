<?php
# Metinfo's API for 客户端APP、微信小程序等
# Copyright (C) 角摩网 (http://www.joymo.cc). All rights reserved.

defined('IN_MET') or exit('No permission');

load::sys_class('api');

class product extends api {
	public function __construct() {
		global $_M;
		parent::__construct();
	}

	/**
	 * 获取产品详情页接口
	 */
	public function dodetail(){
	    global $_M;
	    
	    $data = load::sys_class('label', 'new')->get('product')->get_one_list_contents($_M['form']['id']);
	    $ret = array('id '=> $data['id'],
	        'title' => $data['title'],
	        'desc' => $data['description'],
	        'content' => $data['content'],
	        'specs' => $data['content1'],
	        'package' => $data['content2'],
	        'para' => $data['para']
	    );
	    
	    $ret['imgs'] = array();
	    if(sizeof($data['displayimgs']) > 0) {
	        foreach ($data['displayimgs'] as $k => $v){
	            $ret['imgs'][] = ['title'=>$v['title'],'src'=>"http://".$_SERVER['HTTP_HOST']."/".$v['img']];
	        }
	    }
	    else {
	        $ret['imgs'][0] = ['title'=>'','src'=>"http://".$_SERVER['HTTP_HOST']."/".$data['imgurl']];
	    }
	    
	    $this->success("success",$ret);
	}
	
	/**
	 * 获取产品列表接口
	 */
    public function dolist() {
		global $_M;

		$ret = load::sys_class('label', 'new')->get('product')->get_list_page(4,$_M['form']['page'],0);
		$this->success("success",$ret);
    }

}

?>
