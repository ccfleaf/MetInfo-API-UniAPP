<!--<?php
# MetInfo Enterprise Content Management System
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.

defined('IN_MET') or exit('No permission');

require $this->template('ui/head');
echo <<<EOT
-->
<link rel="stylesheet" href="{$_M[url][own_tem]}css/metinfo.css?{$jsrand}" />
<form method="POST" name="myform" class="ui-from" action="" target="_self">
	<div class="v52fmbx">
		<table class="display dataTable ui-table" data-table-datatype="jsonp" data-table-ajaxurl="{$_M['url']['own_form']}a=dotable_temlist_json">
		    <thead>
		        <tr>
		            <th width="200" data-table-columnclass="met-center">表单名称</th>
					<th>操作</th>
		        </tr>
		    </thead>
			<tfoot>
				<tr>
					<th  class="formsubmit">
						<a href="{$_M['url']['own_name']}&c=index&a=dotemin" class="ui-addlist addtem" style="top:0px;left:0px;"><i class="fa fa-plus-circle"></i>新增表单</a>
					</th>
					<th>
					</th>
				</tr>
			</tfoot>
		</table>
	</div>
</form>
<div class="remodal" data-remodal-id="modal"><div class="temset_box"></div></div>
<!--
EOT;
require $this->template('ui/foot');

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>