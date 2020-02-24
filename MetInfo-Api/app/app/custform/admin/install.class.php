<?php

class install {
    public $appno = 11080;
    public $ver = '1.0';
    public $appname = 'custform';
    public $apptitle= '自定义表单';
    public $description = '可以自定义字段和对应内容';
    
    /*public function __construct() {
     global $_M;
     parent::__construct();
     }*/
    
    /**
     * 注册应用
     * @return [type]
     */
    public function dosql() {
        
        global $_M;
        $hasapp = DB::get_one("SELECT * FROM {$_M['table']['applist']} WHERE no = '{$this->appno}'");
        
        if($hasapp) {
            
            $this->update();
            
        } else {
            
            $time = time();
            $query = "INSERT INTO `{$_M['table']['applist']}` SET
            `id`        =  '',
            `no`        =  '{$this->appno}',
            `ver`       =  '{$this->ver}',
            `m_name`    =  '{$this->appname}',
            `m_class`   =  'index',
            `m_action`  =  'doformlist',
            `appname`   =  '{$this->apptitle}',
            `info`      =  '{$this->description}',
            `addtime`   =  {$time},
            `updatetime`=  {$time}";
            DB::query($query);
            
            $query = "CREATE TABLE `met_custform` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `title` varchar(255) DEFAULT '',
                      `value` text,
                      `addtime` int(11) DEFAULT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
            DB::query($query);
        }
		return 'complete';
	}
}

?>