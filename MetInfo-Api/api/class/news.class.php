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

	public function showpage($module) {
		global $_M;
		$data = load::sys_class('label', 'new')->get($module)->get_one_list_contents($_M['form']['id']);
		$data['updatetime'] = date($_M['config']['met_contenttime'], strtotime($data['original_updatetime']));
		$data['addtime'] = date($_M['config']['met_contenttime'], strtotime($data['original_addtime']));
		$this->check($data['access']);
		$this->add_array_input($data);
		$classnow = $data['class3'] ? $data['class3'] :($data['class2'] ? $data['class2'] : $data['class1']);
		$this->input_class($classnow);
		$this->seo($data['title'], $data['keywords'], $data['description']);
		$this->seo_title($data['ctitle']);
		$this->success("success",$data);
	}

	public function listpage($module) {
		global $_M;
		if($_M['form']['pseudo_jump'] && $_M['form']['list']!=1){
			if(!is_numeric($_M['form']['metid'])){
				$custom = load::sys_class('label', 'new')->get($module)->database->get_list_by_filename($_M['form']['metid']);
				$_M['form']['metid'] = $custom['0']['id'];
			}
			$_M['form']['id'] = $_M['form']['metid'];
			return 'show';
		}

		$classnow = $_M['form']['class3'] ? $_M['form']['class3'] :($_M['form']['class2'] ? $_M['form']['class2'] : $_M['form']['class1']);
		$classnow = $classnow ? $classnow : $_M['form']['metid'];
		if(!is_numeric($classnow)){
			$custom = load::sys_class('label', 'new')->get('column')->get_column_folder($_M['form']['metid']);
			$classnow = $custom['0']['id'];
		}
		$classnow = $this->input_class($classnow);
		$data = load::sys_class('label', 'new')->get('column')->get_column_id($classnow);
		$this->check($data['access']);
		unset($data['id']);
		$this->add_array_input($data);
		$this->seo($data['name'], $data['keywords'], $data['description']);
		$this->seo_title($data['ctitle']);
		$this->add_input('page', $_M['form']['page']);
		$this->add_input('list', 1);
		return 'list';
	}

  public function donews() {
		global $_M;
		$this->success('success',$_M);
  }

	public function doshownews(){
		global $_M;
		$this->showpage('news');
		require_once $this->template('tem/shownews');
	}

}

?>
