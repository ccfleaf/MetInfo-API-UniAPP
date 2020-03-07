<?php
# Metinfo's API for 客户端APP、微信小程序等
# Copyright (C) 角摩网 (http://www.joymo.cc). All rights reserved.

defined('IN_MET') or exit('No permission');

load::sys_class('api');

class news extends api {
	public function __construct() {
		global $_M;
		parent::__construct();
	}

	public function donewsid(){
	    global $_M;
	    
	    $query = "SELECT id,title,content,concat('http://".$_SERVER['HTTP_HOST']."/',imgurl) as imgurl,updatetime,publisher FROM {$_M['table']['news']} ";
	    $where = "WHERE lang = 'cn' and id=".$_M['form']['id'];

	    
	    $query .= $where;
	    $ret = DB::get_one($query);
	    
	    $this->success("success",$ret);
	}
	
  public function donewslist() {
		global $_M;

		$query = "SELECT id,title,content,imgurl,updatetime,publisher FROM {$_M['table']['news']} ";
		$where = "WHERE top_ok<>1 and lang = 'cn'";
		$where .= $_M['form']['minId']?" AND id<{$_M['form']['minId']}":'';
		$pagesize = $_M['form']['pageSize']?$_M['form']['pageSize']:10;
		$limit =" LIMIT ".$pagesize;
		$order = " ORDER BY id DESC";
		
		$query .= $where.$order.$limit;
		$ret = DB::get_all($query);
		/*
		foreach ($ret as $k => $v) {
		    $jobs[] = array('position'=>$v['position'],'count'=>$v['count'],'place'=>$v['place'],'deal'=>$v['deal'],'content'=>$v['content']);
		}
		$infos['job'] = $jobs;
		*/
		$this->success("success",$ret);
		
  }

	public function donewsbanner(){
	    global $_M;
	    
	    $query = "SELECT id,title,content,concat('http://".$_SERVER['HTTP_HOST']."/',imgurl) as imgurl,updatetime,publisher FROM {$_M['table']['news']} ";
	    $where = "WHERE top_ok=1 and lang = 'cn'";
	    $order = " ORDER BY id DESC";
	    
	    $query .= $where.$order;
	    $ret = DB::get_one($query);

	    $this->success("success",$ret);
	}

}

?>
