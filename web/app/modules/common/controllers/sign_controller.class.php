<?php

namespace app\common;

use yangzie\YZE_FatalException;
use yangzie\YZE_Hook;
use yangzie\YZE_JSON_View;
use yangzie\YZE_Redirect;
use yangzie\YZE_Resource_Controller;
use yangzie\YZE_RuntimeException;
use const APPLICATION_NAME;
use const YD_COMMON_SIGNIN_POST;
use const YD_COMMON_SIGNIN_REDIRECT;
use const YD_COMMON_SIGNOUT;
use const YZE_HOOK_GET_LOGIN_USER;
use const YZE_HOOK_SET_LOGIN_USER;

/**
 * 登陆登出控制器
 *
 * @version $Id$
 * @package common
 *
 */
class Sign_Controller extends YZE_Resource_Controller {

	/**
	 * 登录界面
	 * @return YZE_Redirect
	 * @author guoxingcai 2017年2月9日 下午3:54:27
	 */
	public function index () {
		$request = $this->request;
		$this->layout = "";
		$this->set_view_data ( 'yze_page_title', "登录界面 - " . APPLICATION_NAME );
		$admin = YZE_Hook::do_hook ( YZE_HOOK_GET_LOGIN_USER );
		if ( $admin ) {
			return new YZE_Redirect ( "/", $this );
		}
	}

	/**
	 * 登录逻辑
	 * @return YZE_JSON_View|YZE_Redirect
	 * @author guoxingcai 2017年2月9日 下午3:54:36
	 */
	public function post_index () {
		$request = $this->request;

		// 用户登录
		$user = YZE_Hook::do_hook ( YD_COMMON_SIGNIN_POST );
        //$wx_data = YZE_Hook::do_hook ( YD_ASSET_WX_USER_LOGIN );
        if( ! $user) throw new YZE_FatalException("登录失败,请检查用户名和密码");
		// 获取调整的地址,如果是微信登录，返回user，否则返回 "/"
        if(is_array($user)) $uri = $user;
        else $uri = YZE_Hook::do_hook ( YD_COMMON_SIGNIN_REDIRECT );
		// 用户信息保存Session
		YZE_Hook::do_hook ( YZE_HOOK_SET_LOGIN_USER, $user );
		
		if ( strtolower ( $request->get_output_format () ) == "json" ) {
			$this->layout = "";
			return YZE_JSON_View::success ( $this, $uri );
		} else {
			return new YZE_Redirect ( $uri, $this );
		}
	}

	/**
	 * 退出登录
	 * @link /signout
	 * @author guoxingcai 2017年2月9日 上午11:56:46
	 */
	public function signout () {
		return YZE_Hook::do_hook ( YD_COMMON_SIGNOUT, $this );
	}


	public function check_request_token () {
		return;
	}

	public function exception ( YZE_RuntimeException $e ) {
		$request = $this->request;
		$this->layout = 'error';
		if ( $request->the_method () == "post_index" ) {
			if ( strtolower ( $request->get_output_format () ) == "json" ) {
				$this->layout = '';
				return YZE_JSON_View::error ( $this, $e->getMessage (), $e->getCode () );
			} else {
				return $this->wrapResponse ( $this->index () );
			}
		}
		// 处理中出现了异常，如何处理，没有任何处理将显示500页面
		// 如果想显示get的返回内容可调用 :
		$this->post_result_of_json = YZE_JSON_View::error ( $this, $e->getMessage () );
		// 通过request->the_method()判断是那个方法出现的异常
		// return $this->wrapResponse($this->yourmethod())
	}
}
?>