<!--<?php
# MetInfo Enterprise Content Management System
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.

defined('IN_MET') or exit('No permission');
echo <<<EOT
-->
<form method="POST" class="ui-from" name="myform" action="{$_M['url']['own_name']}c=index&a=doin" target="_self">
	<div class="v52fmbx">
		<h3 class="v52fmbx_hr">新增表单</h3>
		<dl>
			<dt>表单名称</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="temname" data-required="1" value="" style="width:100px;">
				</div>
				<span class="tips"></span>
			</dd>
		</dl>
		<dl class="noborder">
			<dt> </dt>
			<dd>
				<input type="submit" name="submit" value="保存" class="submit">
			</dd>
		</dl>
	</div>
</form>
<!--
EOT;
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>