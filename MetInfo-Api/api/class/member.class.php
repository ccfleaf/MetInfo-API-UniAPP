<?php
# Metinfo's API for 客户端APP、微信小程序等
# Copyright (C) 角摩网 (http://www.joymo.cc). All rights reserved.

defined('IN_MET') or exit('No permission');

load::sys_class('api');

class member extends api {
    public $userclass;
    
    public function __construct() {
		global $_M;
		parent::__construct();
		$this->userclass = load::sys_class('user', 'new');
	}

	/**
	 * 登录接口，会在header中返回身份认证的auth和key，前端需要记录，并每次带到服务端
	 */
	public function dosignin(){
	    global $_M;
	    $session = load::sys_class('session', 'new');
	    
	    if ($_M['user']['id']) {
	        $this->success("login");
	    }
	    $user = $this->userclass->login_by_password($_M['form']['username'],$_M['form']['password'],'');;
	    if ($user) {
	        if (!$user['valid']) {
	            $this->error($_M['word']['membererror6']);
	        }
	        
	        $session->del('logineorrorlength');
	        $this->userclass->set_login_record($user);
	        
	        $this->success("success");
	    } else {
	        $length = $session->get("logineorrorlength");
	        $length++;
	        $session->set("logineorrorlength", $length);
	        $this->error($_M['word']['membererror1']);
	    }

	}

}

?>
