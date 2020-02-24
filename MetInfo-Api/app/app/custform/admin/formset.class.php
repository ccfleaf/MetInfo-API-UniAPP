<?php
# MetInfo Enterprise Content Management System
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.

defined('IN_MET') or exit('No permission');

load::sys_class('admin');

class formset extends admin {
	public function __construct() {
		global $_M;
		parent::__construct();
		nav::set_nav(4,'表单管理',$_M['url']['own_name'].'c=index&a=doformlist');
		nav::set_nav(0,'字段管理',"{$_M['url']['own_name']}c=formset&a=doset&no={$_M['form']['no']}");

	}
	public function doset() {
		global $_M;
		$_M[form][pos] = $_M[form][pos]?$_M[form][pos]:0;
		nav::select_nav($_M[form][pos]);
		require $this->template('own/temset');
	}

	public function dosetlist(){
		global $_M;
		require $this->template('own/temsetlist');
	}

	function dotable_add(){
		global $_M;
		$id = 'new-'.$_M[form][ai];
		$metinfo ="<tr class=\"even newlist\">
					<td class=\"met-center\"><input name=\"id\" type=\"checkbox\" value=\"{$id}\" />\n<input name=\"bigclass-{$id}\" type=\"hidden\"/></td>
					<td class=\"met-center\"><i class=\"fa fa-caret-right\" style='display:none;'></i></td>
					<td><input type=\"text\" name=\"name-{$id}\" class=\"ui-input\" placeholder=\"变量名\" data-norepeat='namenopt' data-required=\"1\" value=\"\" ></td>
					<td><input type=\"text\" name=\"valueinfo-{$id}\" class=\"ui-input\" value=\"\" placeholder=\"名称\" data-required=\"1\"></td>
					<td><input type=\"text\" name=\"tips-{$id}\" class=\"ui-input\" placeholder=\"内容\" value=\"\" ></td>
					<td>
						<a href=\"{$_M[url][own_form]}a=dosetlist\" class='selectd'>设置选项</a><span class=\"line selectd\">|</span>
				<a href=\"{$_M[url][own_form]}a=dotable_add&&pos={$_M['form']['pos']}\" class='nowaddlist' style=\"display:none;\">添加子选项</a>
				<span class=\"line nowaddlist\" style=\"display:none;\">|</span><a href=\"\" class=\"delet\">撤销</a>
					</td>
				</tr>";
		echo $metinfo;
	}

	public function dotable_temset_json() {
		global $_M;

		$table = load::sys_class('tabledata', 'new'); //加载表格数据获取类
		$where = "id ={$_M['form']['no']}";
		$order = ""; //排序方式
		$datas = $table->getdata('met_custform', '*', $where, $order);
		$array = json_decode($datas[0]['value'],true);
		$i = 1;
		foreach($array as $key => $val) {
		    $list = array();
			$list[] = "<input name=\"id\" type=\"checkbox\" value=\"{$i}\"/>\n<input name=\"bigclass-{$i}\" type=\"hidden\" value=\"\"/>";
			$list[] = '<i class="fa fa-caret-right"></i>';
			$list[] = "<input type=\"text\" name=\"name-{$i}\" class=\"ui-input\" placeholder=\"变量名\" data-norepeat='namenopt' data-required=\"1\" value=\"{$key}\">";
			$list[] = "<input type=\"text\" name=\"valueinfo-{$i}\" class=\"ui-input\" value=\"{$val['name']}\" placeholder=\"名称\" data-required=\"1\">";
			$list[] = "<input type=\"text\" name=\"tips-{$i}\" class=\"ui-input\" placeholder=\"内容\" value=\"{$val['value']}\" >";

			$list[] = "
				<a href=\"{$_M[url][own_form]}a=dosetlist\" class='selectd'>设置选项</a>
				<span class=\"line selectd\">|</span>
				<a href=\"{$_M[url][own_form]}a=dotable_add&pos={$_M['form']['pos']}\" class='nowaddlist'>添加子选项</a>
				<span class=\"line nowaddlist\">|</span>
				<input type='hidden' name='selectd-{$i}' value='' />
				<input type='hidden' name='style-{$i}' value='' />
				<a href=\"{$_M['url']['own_form']}a=dosetsave&allid={$val[id]},&submit_type=del&no={$_M['form']['no']}&pos={$_M['form']['pos']}\" data-confirm=\"您确定要删除该信息吗？删除之后无法再恢复。<br/>如果删除分区，分区下的子选项不会被删除。\">删除</a>";
				//{$_M['url']['own_name']}c=setedit&a=dosetedit&id={$val[id]}

			$rarray[] = $list;
			$i++;
		}
		$table->rdata($rarray);
	}

	public function pos($pos) {
		switch($pos) {
			case 0:
				$s = "全局";
			break;
			case 1:
				$s = "首页";
			break;
			case 2:
				$s = "列表页";
			break;
			case 3:
				$s = "详细页";
			break;
		}
		return $s;
	}

	public function type($type) {
		switch($type) {
			case 1:
				$s = "分区";
			break;
			case 2:
				$s = "简短文本";
			break;
			case 3:
				$s = "多行文本";
			break;
			case 4:
				$s = "单选按钮";
			break;
			case 6:
				$s = "栏目选择";
			break;
			case 7:
				$s = "上传组件";
			break;
			case 8:
				$s = "编辑器";
			break;
			case 9:
				$s = "颜色选择器";
			break;
		}
		return $s;
	}

	public function select($type,$id) {
		$select = "<select name='type-{$id}' class='temset_select' data-checked='{$type}'>";
		for($i = 1; $i <= 12; $i++){
			$txt = $this->type($i);
			if($txt){
				$select.= "<option value='{$i}'>{$txt}</option>";
			}
			if(intval($type)==1) break;
		}
		$select.= "</select>";
		return $select;
	}

	public function dosetsave(){
		global $_M;
		$list = explode(",",$_M[form][allid]);
		$datas = array();
		
		foreach($list as $id){
			if($id){
			    $datas[$_M['form']['name-'.$id]] = array("name"=>$_M['form']['valueinfo-'.$id], "value"=>$_M['form']['tips-'.$id]);
			}
		}

		$json = jsonencode($datas);
        
        if($json!=''){
            $time = time();
            $query = "UPDATE met_custform SET value='{$json}',addtime='{$time}' WHERE id = {$_M['form']['no']}";
            DB::query($query);
        }
        turnover("{$_M[url][own_form]}a=doset&no={$_M['form']['no']}", '操作成功');
        
	}


	// 6.0tag模板保存一份配置
	public function set_tag_json()
	{
		global $_M;
		$query = "SELECT * FROM {$_M['table']['templates']} WHERE no = '{$_M['form']['no']}' AND lang = '{$_M['lang']}' AND bigclass = 0";
		$data = DB::get_all($query);
		foreach ($data as $k => $v) {
			$query = "SELECT * FROM {$_M['table']['templates']} WHERE no = '{$_M['form']['no']}' AND lang = '{$_M['lang']}' AND bigclass = {$v['id']}";
			$data[$k]['sub'] = DB::get_all($query);
		}
		file_put_contents(PATH_WEB."templates/{$_M['form']['no']}/install/template.json", json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

	}


}

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>