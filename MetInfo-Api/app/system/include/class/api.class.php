<?php
# MetInfo Enterprise Content Management System
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.

defined('IN_MET') or exit('No permission');

load::sys_class('common.class.php');
load::sys_func('web');

/**
 * api基类
 */
class api extends common {
	/**
	  * 初始化
	  */
	 /**
	  * 获取url传递的参数
	  * @var [type]
	  */
	protected $input;

	public function __construct() {
	    if(!isset($_GET['lang'])) $_GET['lang'] = "cn";
	    
		parent::__construct();

		$this->tem_dir();//确定模板根目录
		$this->load_domain();//加载绑定域名的语言
		$this->load_language();//语言加载

//		$this->load_publuc_data();//加载公共数据
		$this->sys_input();
		load::sys_class('user', 'new')->get_login_user_info();//会员登陆
//		load::plugin('doweb');//加载插件

	}


	/**
	  * 重写common类的load_form方法，前台对提交的GET，POST，COOKIE进行安全的过滤处理
	  */
	protected function load_form() {
		global $_M;
		parent::load_form();
		foreach ($_M['form'] as $key => $val) {
			$_M['form'][$key] = sqlinsert($val);
		}
		if ($_M['form']['id']!='' && !is_numeric($_M['form']['id'])) {
			$_M['form']['id'] = '';
		}
		if ($_M['form']['class1']!='' && !is_numeric($_M['form']['class1'])) {
			$_M['form']['class1'] = '';
		}
		if ($_M['form']['class2']!='' && !is_numeric($_M['form']['class2'])) {
			$_M['form']['class2'] = '';
		}
		if ($_M['form']['class3']!='' && !is_numeric($_M['form']['class3'])) {
			$_M['form']['class3'] = '';
		}
	}

	protected function input_class($id) {
		if(!$id){
			$REQUEST_URIs = explode('/', REQUEST_URI);
			$c = load::sys_class('label', 'new')->get('column')->get_column_folder($REQUEST_URIs[count($REQUEST_URIs) - 2]);
			$id = $c['id'];
		}
		$c123_releclass = load::sys_class('label', 'new')->get('column')->get_class123_reclass($id);
		$this->add_input('releclass1', $c123_releclass['class1']['id']);
		$this->add_input('releclass2', $c123_releclass['class2']['id']);
		$this->add_input('releclass3', $c123_releclass['class3']['id']);
		$c123 = load::sys_class('label', 'new')->get('column')->get_class123_no_reclass($id);
		$this->add_input('classnow', 0);
		$this->add_input('class1', $c123['class1']['id']);
		$this->add_input('class2', $c123['class2']['id']);
		$this->add_input('class3', $c123['class3']['id']);
		if($c123['class3']['id']){
			$this->add_input('classnow', $c123['class3']['id']);
			$c = load::sys_class('label', 'new')->get('column')->get_column_id($c123['class3']['id']);
		}else{
			if($c123['class2']['id']){
				$this->add_input('classnow', $c123['class2']['id']);
				$c = load::sys_class('label', 'new')->get('column')->get_column_id($c123['class2']['id']);
			}else{
				$this->add_input('classnow', $c123['class1']['id']);
				$c = load::sys_class('label', 'new')->get('column')->get_column_id($c123['class1']['id']);
			}
		}
		$this->add_input('module', $c['module']);
		return $c['id'];
	}

	/**
		* 对页面的class1进行处理
		*/
	protected function seo($title = '', $keywords = '', $description = '') {
		global $_M;
		switch ($_M['config']['met_title_type']) {
			case '0':
				$this->add_input('page_title', $title);
			break;
			case '1':
				$this->add_input('page_title', $title.'-'.$_M['config']['met_keywords']);
			break;
			case '2':
				$this->add_input('page_title', $title.'-'.$_M['config']['met_webname']);
			break;
			case '3':
				$this->add_input('page_title', $title.'-'.$_M['config']['met_webname'].'-'.$_M['config']['met_keywords']);
			break;
		}
		if ($keywords) {
			$this->add_input('page_keywords', $keywords);
		} else {
			$this->add_input('page_keywords', $_M['config']['met_keywords']);
		}

		if ($description) {
			$this->add_input('page_description', $description);
		} else {
			$this->add_input('page_description', $_M['config']['met_description']);
		}

	}

	protected function seo_title($title = '') {
		if($title){
			$this->add_input('page_title', $title);
		}
	}

	/**
		* 对页面的class1进行处理
		*/
	protected function class_handle() {
		global $_M;

		if($_M['form']['class3']){
			$class2_array = load::sys_class('label', 'new')->get('column')->get_parent_column($_M['form']['class3']);
			$class1_array = load::sys_class('label', 'new')->get('column')->get_parent_column($class2_array['id']);
			$_M['form']['class2'] = $class2_array['id'];
			$_M['form']['class1'] = $class1_array['id'];
			$_M['form']['classnow'] = $_M['form']['class3'];
			return true;
		}

		if($_M['form']['class2']){
			$class1_array = load::sys_class('label', 'new')->get('column')->get_parent_column($_M['form']['class2']);
			$_M['form']['class1'] = $class1_array['id'];
			$_M['form']['class3'] = 0;
			$_M['form']['classnow'] = $_M['form']['class2'];
			return true;
		}

		if($_M['form']['class1']){
			$_M['form']['class2'] = 0;
			$_M['form']['class3'] = 0;
			$_M['form']['classnow'] = $_M['form']['class1'];
			return true;
		}

		$REQUEST_URIs = explode('/', REQUEST_URI);
		$c = load::sys_class('label', 'new')->get('column')->get_column_folder($REQUEST_URIs[count($REQUEST_URIs) - 2]);
		if ($c) {
			$_M['form']['class2'] = 0;
			$_M['form']['class3'] = 0;
			$_M['form']['class1'] = $c['id'];
			$_M['form']['classnow'] = $c['id'];
		} else {
			$_M['form']['class2'] = 0;
			$_M['form']['class3'] = 0;
			$_M['form']['class1'] = 10001;
			$_M['form']['classnow'] = 10001;
		}
		return true;
	}

	/**
	  * 重写common类的load_url_unique方法，获取前台特有URL
	  */
	protected function load_url_unique() {
		global $_M;
		$_M['url']['ui'] = $_M['url']['site'].'app/system/include/public/ui/web/';
	}

	protected function load_domain(){
		global $_M;

		$domain = trim(str_replace($_SERVER['REQUEST_SCHEME'].'://', '', $_M['url']['site']),'/');

		$query = "SELECT * FROM {$_M['table']['lang']} WHERE link = '{$domain}'";
		$res = DB::get_one($query);
		if($res){
			$_M['lang'] = $res['mark'];
		}
	}

	/**
	  * 获取当前语言参数
	  */
	protected function load_language() {
		global $_M;
		$this->load_word($_M['lang'] ,0);
		$this->load_template_lang();
	}

	/**
	  * 获取前台公用数据
	  */
	protected function load_publuc_data() {
		global $_M;
		//$this->class_handle();//确认栏目id
		$this->load_flashset_data();
	}

	/**
	  * 获取前台模板的语言参数配置，存放在$_M['word']中，系统语言参数数组。
	  */
	protected function load_template_lang() {
		global $_M;
		//模板文件
		$query = "SELECT * FROM {$_M['table']['templates']} WHERE no='{$_M[config][met_skin_user]}' AND lang='{$_M['lang']}' order by no_order ";
		$inc = DB::get_all($query);
		$tmpincfile=PATH_WEB."templates/{$_M[config][met_skin_user]}/metinfo.inc.php";
		if(file_exists($tmpincfile))require $tmpincfile;
		$_M['config']['metinfover'] = $metinfover;
		foreach($inc as $key=>$val){
			$name = $val['name'];
			if($val[type]==7&&strstr($val['value'],"../upload/")&&$index=='index'&&($metinfover=='v1' || $metinfover=='v2')){//增加判断（新模板框架v2）
				$val['value']=explode("../",$val['value']);
				$val['value']=$val['value'][1];
			}
			$_M['word'][$name] = trim($val['value']);
		}

		load::sys_class('view/ui_compile');
		$ui_compile = new ui_compile();
		$templates = $ui_compile->list_templates_config();
		$_M['word'] = array_merge($_M['word'],$templates);
	}

	/**
	  * 前台权限检测
	  * @param int 会员组编号
	  * 如果会员拥有权限则，程序代码向后正常执行，如果没有则提示没有权限。
	  */
	protected function check($groupid = 0) {
		global $_M;
		$power = load::sys_class('user', 'new')->check_power($groupid);
		if($power < 0){
			$gourl = $_M['gourl'] ? urlencode($_M['gourl']) : urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			$gourl = $gourl == -1 ? "":$gourl;
			if($_M['lang'] != $_M['config']['met_index_type']){
				$lang = "&lang={$_M['lang']}";
			}
			if($power == -2){
				okinfo($_M['url']['site'].'member/index.php?gourl='.$gourl.$lang, '您没有权限访问这个内容！请登陆后访问！');
			}
			if($power == -1){
				okinfo($_M['url']['site'].'index.php?gourl='.$gourl.$lang, '您所在用户组没有权限访问这个内容！');
			}
		}
		// if($groupid != 0 && !get_met_cookie('metinfo_admin_name')){
		// 	$user = $this->get_login_user_info();
		// 	$gourl = $_M['gourl'] ? urlencode($_M['gourl']) : urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		// 	$gourl = $gourl == -1 ? "":$gourl;
		// 	if($_M['lang'] != $_M['config']['met_index_type']){
		// 		$lang = "&lang={$_M['lang']}";
		// 	}
		// 	if($groupid == 0 && !$user){
		// 		okinfo($_M['url']['site'].'member/login.php?gourl='.$gourl.$lang, '您没有权限访问这个内容！请登陆后访问！');
		// 	}

		// 	$group = load::sys_class('group', 'new')->get_group($groupid);
		// 	if($user['access'] < $group['access']){
		// 		okinfo($_M['url']['site'].'index.php?gourl='.$gourl.$lang, '您没有权限访问这个内容！');
		// 	}
		// }
	}

	/**
	  * 前台权限检测
	  * @param string m_auth 会员登陆授权码
	  * @param string m_key  会员登陆密钥
	  * 如果会员拥有权限则，程序代码向后正常执行，如果没有则提示没有权限。
	  * get_met_cookie函数兼容也调用login_by_auth,如果修改请一并修改。
	  */
	protected function get_login_user_info($met_auth = '', $met_key = '') {
		global $_M;
		return load::sys_class('user', 'new')->get_login_user_info($met_auth, $met_key);
	}

	/**
		* 模板文件
		* @param string $file 模板主文件
		* @param int    $mod  标签数组
		*/
	protected function template($file) {
		global $_M;
		if (!$_M['config']['metinfover'] || $_M['config']['metinfover'] == 'v1') {
			if ($_M['custom_template']['file']) {
				return parent::template($file);
			} else {
				$_M['custom_template']['file'] = $file;
				return parent::template('ui/metv5');
			}
		} else {
			return $this->temcache(parent::template($file), $this->input);
		}
	}

	/**
		* 模板解析
		* @param string $file 模板文件
		*/
	protected function temcache($file, $mod) {
		global $_M;
		$view = load::sys_class('engine','new');
		return  $view->dodisplay($file, $mod);

	}

	/**
	  * 应用兼容模式加载前台模板，会自动加载当前选定模板的顶部，尾部，左侧导航(可选)，只有内容主题可以自定义。
	  * @param string $content 页面主体内容部分调用的文件名，为自定的应用模板文件
	  * @param int    $left    收加载模板的左侧栏，2：加载会员左侧导航 1:加载一般页面左侧导航，0:不加载
	  */
	protected function custom_template($content, $left) {
		global $_M;
		$_M['custom_template']['content'] = $content;
		$_M['custom_template']['left'] = $left;
		return $this->template('ui/app');
	}

	/**
	  * 确定模板根目录
	  */
	protected function tem_dir(){
		global $_M;
		if($_M['config']['met_wap']){//兼容手机版
			$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
			$uachar = "/(nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|mobile|wap|Android|ucweb)/i";
			if($ua != '' && preg_match($uachar, $ua)){
				$is_mobile = 1;
			}else{
				$is_mobile = 0;
			}
			if($_M['form']['met_mobileok']){
				$_M['config']['met_skin_user'] = $_M['config']['wap_skin_user'];
			}else{
				if($is_mobile == 1){
					$_M['config']['met_skin_user'] = $_M['config']['wap_skin_user'];
				}else{
					$_M['config']['met_skin_user'] = $_M['config']['met_skin_user'];
				}
			}
			if($is_mobile == 1){
				met_setcookie("met_mobileok", '1');
			}else{
				met_setcookie("met_mobileok", '0');
			}
		}else{
			$_M['config']['met_skin_user'] = $_M['config']['met_skin_user'];
		}
		define('PATH_TEM', PATH_WEB."templates/".$_M['config']['met_skin_user'].'/');//模板根目录
	}

	/**
	  * input变量处理
	  */
	protected function sys_input() {
		global $_M;
		if($_M['form']['pseudo_jump'] && $_M['form']['list']){
			if(!is_numeric($_M['form']['metid'])){
				$column = load::sys_class('label', 'new')->get('column')->get_column_by_filename($_M['form']['metid']);
				if($column){
					$_M['form']['class'.$column['classtype']] = $column['id'];
				}
			}
		}
		$this->input['class1'] 		= $_M['form']['class1'];
		$this->input['class2'] 		= $_M['form']['class2'];
		$this->input['class3'] 		= $_M['form']['class3'];
		$this->input['classnow'] 	= $_M['form']['classnow'];
		$this->input['page'] 		= $_M['form']['page'] ? $_M['form']['page'] : 1;
		$this->input['id'] 			= isset($_M['form']['id']) ? intval($_M['form']['id']) : 0;
		$this->input['lang'] 		= isset($_M['form']['lang']) ? $_M['form']['lang'] : $_M['config']['met_index_type'];
		$this->input['synchronous'] = $_M['langlist']['web'][$this->input['lang']]['synchronous'];
		$column = load::sys_class('label', 'new')->get('column')->get_column_id($this->input['classnow']);
		$this->input['module'] = $column['module'] ? $column['module'] : 10001;
		//unset($_M['form']);
	}

	/**
	  * 添加input
		* @param string $key    $key
	  * @param string $val    $val
	  */
	protected function add_input($key, $val) {
		global $_M;
		$this->input[$key] = $val;
	}

	/**
		* 添加input
		* @param string $key    $key
		* @param string $val    $val
		*/
	protected function add_array_input($data) {
		global $_M;
		foreach ($data as $key=>$val) {
			$this->add_input($key, $val);
		}
	}
	/**
	 * 操作成功跳转的快捷方法
	 * @access protected
	 * @param mixed $msg 提示信息
	 * @param mixed $data 返回的数据
	 * @param array $header 发送的Header信息
	 * @return void
	 */
	protected function success($msg = '', $data = '', array $header = [])
	{
	    $code   = 1;
	    $result = [
	        'code' => $code,
	        'msg'  => $msg,
	        'data' => $data,
	    ];
	    
	    echo jsonencode($result);
	}
	
	/**
	 * 操作错误跳转的快捷方法
	 * @access protected
	 * @param mixed $msg 提示信息,若要指定错误码,可以传数组,格式为['code'=>您的错误码,'msg'=>'您的错误消息']
	 * @param mixed $data 返回的数据
	 * @param array $header 发送的Header信息
	 * @return void
	 */
	protected function error($msg = '', $data = '', array $header = [])
	{
	    $code = 0;
	    if (is_array($msg)) {
	        $code = $msg['code'];
	        $msg  = $msg['msg'];
	    }
	    $result = [
	        'code' => $code,
	        'msg'  => $msg,
	        'data' => $data,
	    ];
	    
	    echo json_encode($result);
	}
	
	
	/**
	  * 销毁
	  */
	public function __destruct(){
		global $_M;
		//读取缓冲区数据
		$output = str_replace(array('<!--<!---->','<!---->','<!--fck-->','<!--fck','fck-->','',"\r",substr($admin_url,0,-1)),'',ob_get_contents());
		ob_end_clean();//清空缓冲区
		$output = $this->video_replace('/(<video.*?edui-upload-video.*?>).*?<\/video>/', $output);
		$output = $this->video_replace('/(<embed.*?edui-faked-video.*?>)/', $output);
        if($_M['config']['met_qiniu_cloud']) {
            $output = load::plugin('dofooter_replace', 1, array('data' => $output));
        }
		/**
		 * 标签数据处理
		 */
		load::sys_class('view/ui_compile');
		$ui_compile = new ui_compile();
		$output = $ui_compile->replace_attr($output);

		if($_M['form']['html_filename'] && $_M['form']['metinfonow'] == $_M['config']['met_member_force']){
			$filename = urldecode($_M['form']['html_filename']);
			if(stristr(PHP_OS,"WIN")) {
				$filename = @iconv("utf-8", "GBK", $filename);
			}
			if(file_put_contents(PATH_WEB.$filename, $output)){
				jsoncallback(array('suc'=>1));
			}else{
				jsoncallback(array('suc'=>0));
			}
		}else{
			echo $output;//输出内容
		}

		DB::close();//关闭数据库连接
		exit;
	}

	/**
	  * 视频插件替换
	  * @param string $preg    替换的正则规则
	  * @param string $content 被替换内容
	  */
	function video_replace($preg, $content){
		preg_match_all($preg, $content, $out);
		foreach ($out[1] as $key => $val) {
			preg_match_all('/width=(\'|")([0-9]+)(\'|")/', $val, $w_out);
			$width = $w_out[2][0];

			preg_match_all('/height=(\'|")([0-9]+)(\'|")/', $val, $h_out);
			$height = $h_out[2][0];

			preg_match_all('/src=(\'|")(.+?)(\'|")/', $val, $src_out);
			$src = $src_out[2][0];

			preg_match_all('/style=(\'|")(.+?)(\'|")/', $val, $style_out);
			$style = $style_out[2][0];

			$str = "<video class=\"metvideobox\" data-metvideo=\"{$width}|{$height}||false|{$src}\" style=\"width:{$width}px; height:{$height}px; background:#000 url() no-repeat 50% 50%; background-size:contain;{$style}\" /></video>";

			$content = str_replace($out[0][$key], $str, $content);
		}
		return $content;
	}

}

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
