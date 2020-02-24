<!--<?php
# MetInfo Enterprise Content Management System
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.

defined('IN_MET') or exit('No permission');

require $this->template('ui/head');
$s='$';
echo <<<EOT
-->
<link rel="stylesheet" href="{$_M[url][own_tem]}css/metinfo.css?{$jsrand}" />
<style>

</style>
<div class="nowtem">
	<span>当前表单：{$_M['form']['no']}</span>
</div>
<form method="POST" name="myform" class="ui-from" action="{$_M['url']['own_form']}a=dosetsave&no={$_M['form']['no']}" target="_self">
	<div class="v52fmbx temset">
		<dl>
			<dd class="ftype_description">
				本页面表格不同行支持拖曳排序。
			</dd>
		</dl>
		<table class="display dataTable ui-table" data-table-ajaxurl="{$_M['url']['own_form']}a=dotable_temset_json&no={$_M['form']['no']}" data-table-pageLength='1000'>
			<thead>
				<tr>
					<th width="25" data-table-columnclass="met-center"><input name="id" data-table-chckall="id" type="checkbox" value="" /></th>
					<th width="20" data-table-columnclass="met-center"><i class="fa fa-caret-right"></i></th>
					<th width="120">变量名</th>
					<th width="130">名称</th>
					<th>内容</th>
					<th width="130">编辑</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="met-center"><input name="id" data-table-chckall="id" type="checkbox" value="" /></th>
					<th width="20" class="met-center"><i class="fa fa-caret-right"></i></th>
					<th>变量名</th>
					<th>名称</th>
					<th>内容</th>
					<th>编辑</th>
				</tr>
				<tr>
					<th colspan="9" class="formsubmit">
						<a href="#" class="ui-addlist" data-table-addlist="{$_M['url']['own_form']}a=dotable_add&pos={$_M['form']['pos']}" style="margin-right:15px;"><i class="fa fa-plus-circle"></i>增加自定义标签</a>
						<input type="submit" name="save" value="保存" class="submit" />
						<input type="submit" name="del" value="删除" data-confirm='您确定要删除选中的自定义标签吗？' class="submit" />
					</th>
				</tr>
			</tfoot>
		</table>
		<div class="remodal" data-remodal-id="modal"><div class="temset_box"></div></div>
	</div>
</form>
<!--
EOT;
require $this->template('ui/foot');

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>