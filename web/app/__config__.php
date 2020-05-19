<?php
namespace  app;

use yangzie\YZE_FatalException;

include_once dirname(__FILE__).DS.'__app_setting__.php';

/**
 * 布局侧边栏菜单View hook, 返回html内容
 */
define("YD_LAYOUT_SIDEBAR_VIEW", "YD_LAYOUT_SIDEBAR_VIEW");

/**
 * app模块配置
 * 
 * @author leeboo
 *
 */
class App_Module extends \yangzie\YZE_Base_Module{

	//数据库配置
	public $db_user = YZE_MYSQL_USER;
	public $db_host= YZE_MYSQL_HOST_M;
	public $db_name= YZE_MYSQL_DB;
	public $db_port = YZE_MYSQL_PORT;
	public $db_psw= YZE_MYSQL_PASS;
	public $db_charset= 'UTF8';
	
	public function check(){
		$error = array();
		if( version_compare(PHP_VERSION,'7.0','lt')){
			throw new YZE_FatalException("要求7.0及以上PHP版本");
		}
	}

	protected function _config()
	{
		//动态返回配置
		return array();
	}
	
	/**
	 * 应用启动时需要加载的文件
	 */
	public function module_include_files() {
        $files = array(
           "app/vendor/pomo/translation_entry.class.php",
           "app/vendor/pomo/pomo_stringreader.class.php",
           "app/vendor/pomo/pomo_cachedfilereader.class.php",
           "app/vendor/pomo/pomo_cachedIntfilereader.class.php",
           "app/vendor/pomo/translations.class.php",
           "app/vendor/pomo/gettext_translations.class.php",
           "app/vendor/pomo/mo.class.php",
           "app/vendor/util.php",
           "app/vendor/identity.class.php",
			"app/vendor/PHPEXCEL/PHPExcel.php",
			"app/vendor/http.php",
			"app/vendor/TCPDF/tcpdf.php",
			"app/vendor/pinyin.php",
			"app/vendor/diff.class.php",
			"app/vendor/uploader.class.php"
		);
        
        return $files;
	}
	
	/**
	 * js资源分组，在加载时方便直接通过分组名加载; 这里是静态指定，如果模块中需要动态指定，可通过Request->addJSBundle制定
	 * 资源路径以web 绝对路径/开始，/指的上public_html目录
	 * @return array(资源路径1，资源路径2)
	 */
	public function js_bundle($bundle){
		$config = array (
				"layui" => array (
                        "/js/ydhl-layui.js"
				),
                "bootstrap" => array (
						"/bootstrap4_3_1/js/bootstrap.min.js"
				),
				"jquery" => array (
						"/js/jquery-1.11.2.min.js"
				),
				"pjax" => array (
						"/js/jquery.pjax.js" 
				),
				"yangzie" => array (
						"/js/json.js",
						"/js/yze_ajax_front_controller.js",
						"/js/jquery-ui.min.js",
						"/js/outerHTML-2.1.0-min.js",
						"/js/ydhl.js",
                        "/js/ydjs/yd_tree_select.js",
                        "/js/ydjs/yd_tree.js",
                        "/js/ydjs/yd_dynamic_select.js",
                        "/js/ydjs/yd_upload.js",
                        "/js/select2/js/select2.full.min.js",
                        "/js/select2/js/i18n/zh-CN.js",
                        "/js/plupload/plupload.full.min.js",
                        "/js/popper.min.js",
                        "/vendor/identity.js"
				) 
		);
		return $config[$bundle];
	}
	/**
	 * css资源分组，在加载时方便直接通过分组名加载; 这里是静态指定，如果模块中需要动态指定，可通过Request->addCSSBundle制定
	 * 资源路径以web 绝对路径/开始，/指的上public_html目录
	 * @return array(资源路径1，资源路径2)
	 */
	public function css_bundle($bundle){
		$config = array (
                "ww" => array (
						"/bootstrap4_3_1/css/bootstrap.css",
                        "/iconfont/iconfont.css",
						"/css/popper.css",
                        "/js/select2/css/select2.min.css",
                        "/css/yd_dynamic_select_layui.css",
						"/css/bsfix.css",
						"/css/prisonoa.css"
				),
		);
		return $config[$bundle];
	}
}
?>
