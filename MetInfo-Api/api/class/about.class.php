<?php
# Metinfo's API for 客户端APP、微信小程序等
# Copyright (C) 角摩网 (http://www.joymo.cc). All rights reserved.

defined('IN_MET') or exit('No permission');

load::sys_class('api');

class about extends api {
    
    public $comid = 39;//公司介绍在column中的id
    
    public function __construct() {
		global $_M;
		parent::__construct();
	}

	public function doabout() {
	   global $_M;
	   $query = "SELECT value FROM {$_M['config']['tablepre']}custform WHERE id = {$_M['form']['no']}";
	   $ret = DB::get_one($query);
       $data = jsondecode($ret['value']);

       $infos = array();
       $touch = array();
       foreach ($data as $k => $v) {
           $touch[$k] = $v['value'];
       }
       $infos['touch'] = $touch;
	   
       $query = "SELECT content FROM {$_M['table']['column']} WHERE id = ".$this->comid;
       $ret = DB::get_one($query);
       $infos['profile'] = $ret['content'];
       
       $jobs = array();
       $query = "SELECT * FROM {$_M['table']['job']} WHERE lang = 'cn'";
       $ret = DB::get_all($query);
       foreach ($ret as $k => $v) {
           $jobs[] = array('position'=>$v['position'],'count'=>$v['count'],'place'=>$v['place'],'deal'=>$v['deal'],'content'=>$v['content']);
       }
       $infos['job'] = $jobs;
	   $this->success("success",$infos);
	}
	
	/**
	 * 获取联系方式（电话、QQ)
	 */
	public function docustomsrv() {
	    
	    global $_M;
	    $ret = array();
	    $table = load::sys_class('tabledata', 'new');
	    $where = "lang='{$_M['lang']}' and type in (0, 3)";
	    $list = $table->getdata($_M['table']['online'], 'value,type', $where, "no_order");
	    foreach($list as $k=>$v) {
	        if($v['type'] == '3')
	           $ret['phone'] = $v;
	        elseif($v['type'] == '0')
	           $ret['qq'][] = $v;
	    }
	    $this->success("success", $ret);
	}
	
}

?>
