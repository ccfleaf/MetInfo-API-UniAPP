<?php
# Metinfo's API for 客户端APP、微信小程序等
# Copyright (C) 角摩网 (http://www.joymo.cc). All rights reserved.

defined('IN_MET') or exit('No permission');

load::sys_class('api');

class home extends api {
	public function __construct() {
		global $_M;
		parent::__construct();
	}

	/**
	 * 首页产品区列表接口
	 */
	public function doproductlist(){
	    global $_M;
	    
	    $ret = load::sys_class('label', 'new')->get('product')->get_module_list(4,4,'all');
	    
	    $this->success("success",$ret);
	}
	
	/**
	 * 首页新闻区列表接口
	 */
    public function dolist() {
    	global $_M;
    
    	$ret = load::sys_class('label', 'new')->get('product')->get_list_page(4,$_M['form']['page'],0);
    	$this->success("success",$ret);
    }
    
    /**
     * 首页幻灯接口
     */
    public function dobanner(){
      global $_M;
      
      // 提取的参数统一命名，便于前端用统一函数处理
      $query = "SELECT id,img_title as title,img_path as imgurl FROM {$_M['table']['flash']} ";
      $where = "WHERE wap_ok=0 and lang = 'cn'";
      $order = " ORDER BY no_order";
      
      $query .= $where.$order;
      $ret = DB::get_all($query);
      
      $this->success("success",$ret);
    }
}

?>
