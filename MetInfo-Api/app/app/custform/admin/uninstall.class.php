<?php
defined('IN_MET') or exit ('No permission');

load::sys_class('admin');
load::sys_class('nav.class.php');
load::sys_func('file');

class uninstall extends admin {
    public $appno;
    public $appdir;
    
    public function __construct()
    {
        parent::__construct();
        $this->appno = 11080;
        $this->appname = 'custform';
        
    }

	/*
	 * 卸载插件
	 */
	public function dodel() {
	    global $_M;
	    
	    /*
	     $del_applist = "DELETE FROM {$_M['table']['applist']} WHERE no = {$this->appno}";
	     
	     DB::query($del_applist);
	     deldir(PATH_WEB.$_M['config']['met_adminfile'].'/update/app/'.$this->appno);
	     deldir('../app/app/'.$this->appname);
	     
	     // 删除自定义表单所使用表
	     DB::query("DROP TABLE IF EXISTS `met_custform`");
	     */
	    turnover("{$_M['url']['own_form']}a=doformlist","系统应用，无法卸载");
	}
}
?>