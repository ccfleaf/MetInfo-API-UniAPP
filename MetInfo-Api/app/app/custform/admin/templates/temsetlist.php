<!--<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 

defined('IN_MET') or exit('No permission');
echo <<<EOT
-->
<!--
EOT;
if($_M[form][type]==6){
$_M[form][style] = $_M[form][style]?$_M[form][style]:4;
$_M[form][style] = $_M[form][style]==2?3:$_M[form][style];
echo <<<EOT
-->
	<div class="v52fmbx temsetlist" data-temid="{$_M[form][id]}">
		<h3 class="v52fmbx_hr">设置选项</h3>
		<dl>
			<dt>栏目选择</dt>
			<dd class="ftype_radio">
				<div class="fbox">
					<label><input name="temstyle" type="radio" value="4" data-checked="{$_M[form][style]}">可选所有栏目</label>
					<label><input name="temstyle" type="radio" value="1">只能选择一级栏目</label>
					<label><input name="temstyle" type="radio" value="3">只能选择带信息列表的栏目（文章、产品、图片、下载、招聘）</label>
				</div>
				<span class="tips">可以限制用户选择的栏目，以便于正确的引导用户设置模板。<br/>比如文章列表只能显示带信息列表的栏目。</span>
			</dd>
		</dl>
		<dl>
			<dt>值结构</dt>
			<dd class="ftype_radio">
				栏目ID-cm / 模块标识-md
				<br/>用户选中指定栏目并保存后，前台对应的变量可以得到相应的值。
				<br/>通过函数标签：<a href="http://edu.metinfo.cn/mblable/89-cn.html" target="_blank">tmpcentarr()</a>，可以转化为指定栏目数组标签，这样你就能够在模板中使用。
			</dd>
		</dl>
		<dl class="noborder">
			<dt> </dt>
			<dd>
				<input type="submit" name="style-submit" value="确定" class="submit" style="float:left;" >
				<span class="tips" style="float:left; margin-left:10px; margin-top:5px;">点击页面底部的保存按钮才生效</span>
			</dd>
		</dl>
	</div>
<!--
EOT;
}
echo <<<EOT
-->
<!--
EOT;
if($_M[form][type]==4){
$label = '$M$';
$_M[form][selectd] = str_replace('$T$','|',$_M[form][selectd]);
echo <<<EOT
-->
	<div class="v52fmbx temsetlist" data-temid="{$_M[form][id]}">
		<h3 class="v52fmbx_hr">设置选项</h3>
		<dl>
			<dt>单选设置</dt>
			<dd class="ftype_tags">
				<div class="fbox">
					<input name="selectd" type="hidden" data-label="{$label}" value="{$_M[form][selectd]}">
				</div>
				<span class="tips">选项文字|值，中间用竖线隔开。如 开启|1  关闭|0。</span>
			</dd>
		</dl>
		<dl class="noborder">
			<dt> </dt>
			<dd>
				<input type="submit" name="selectd-submit" value="确定" class="submit" style="float:left;" >
				<span class="tips" style="float:left; margin-left:10px; margin-top:5px;">点击页面底部的保存按钮才生效</span>
			</dd>
		</dl>
	</div>
	
<!--
EOT;
}
echo <<<EOT
-->
<!--
EOT;
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>