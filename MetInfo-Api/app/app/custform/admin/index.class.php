<?php
# MetInfo Enterprise Content Management System
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.

defined('IN_MET') or exit('No permission');

load::sys_class('admin');

class index extends admin {
	public function __construct() {
		global $_M;
		parent::__construct();
		nav::set_nav(1, '表单管理', $_M['url']['own_form'].'&a=doformlist');
		nav::set_nav(2, '客户端API教程', 'http://www.joymo.cc/','target');
	}

	public function doformlist() {
		global $_M;
		nav::select_nav(1);
		require $this->template('own/temlist');
	}
	public function dotable_temlist_json() {
		global $_M;
		$table = load::sys_class('tabledata', 'new'); //加载表格数据获取类
		$where = "";
		$order = ""; //排序方式
		$array = $table->getdata('met_custform', '*', $where, $order);
		foreach($array as $key => $val) {
			$list = array();
			$list[] = $val['title'];
			$list[] = "
						<a href=\"{$_M['url']['own_name']}c=formset&a=doset&no={$val[id]}\">自定义字段</a>
						<span class=\"line\">|</span>
						<a href=\"{$_M['url']['own_name']}c=index&a=dode&id={$val[id]}\" data-confirm=\"您确定要删除该信息吗？删除之后无法再恢复。\">删除</a>
						<span class=\"line\">|</span>
			";
			$rarray[] = $list;

		}
		$table->rarray['draw']=0;
		$table->rdata($rarray);
	}

	public function dotemin() {
		global $_M;
		require $this->template('own/temin');
	}

	public function doin(){
		global $_M;
		if($_M['form']['temname']&&$_M['form']['temname']!=''){
		    $time = time();
			$query = "INSERT INTO met_custform SET title='{$_M['form']['temname']}',addtime='{$time}'";
			DB::query($query);
		}else{
			turnover("{$_M[url][own_name]}c=index&a=doformlist",'操作失败！请填写表单名称！');
			die();
		}

		turnover("{$_M[url][own_name]}c=index&a=doformlist");
	}
	public function dode(){
		global $_M;

		$query = "DELETE FROM {$_M['table']['skin_table']} WHERE id='{$_M['form']['id']}'";
		DB::query($query);
		$query = "DELETE FROM {$_M['table']['templates']} WHERE no='{$_M['form']['no']}'";
		DB::query($query);
		turnover("{$_M[url][own_name]}c=temtool&a=dotemlist");
	}

	public function get_sql($data) {
        global $_M;

        $sql = "";
        foreach ($data as $key => $value) {
            $sql .= " {$key} = '{$value}',";
        }
        return trim($sql,',');
    }
}

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>