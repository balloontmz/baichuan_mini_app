<?php

namespace app\log;

use \yangzie\YZE_JSON_View;
use \yangzie\YZE_Resource_Controller;
use \yangzie\YZE_RuntimeException;
use app\log\Notice_Model;
use yangzie\YZE_Hook;

/**
 *
 * @version $Id$
 * @package common
 *         
 */
class Notice_Controller extends YZE_Resource_Controller {
	
	function __construct ( $request ) {
		parent::__construct ( $request );
	}
	
	// 消息中心
	// 地址映射 域名/notice
	public function index () {
		$user = YZE_Hook::do_hook ( YZE_HOOK_GET_LOGIN_USER );
		$request = $this->request;
		$page=$request->get_from_get('page',1);
		$notices=YZE_Hook::do_hook(YD_NOTICE_GET_NOTICES,["user_id"=>$user->id,"page"=>$page,"pagesize"=>PAGE_SIZE]);
		$this->set_view_data ( 'yze_page_title', '消息中心' );
		$this->set_view_data ( 'notices', $notices['data']);
		$this->set_view_data ( 'total',   $notices['total']);
	}
	
	// 消息详情
	// 地址映射 域名/notice/{notice_id}
	public function detail () {
		$request = $this->request;
		$id = $request->get_var ( "notice_id" );
		$notice = Notice_Model::find_by_id ( $id );
		if ( $notice && $notice->user_id == $this->user->id ) {
			$data = array (
				"notice_ids" => array (
					$id
				),
				"user_id" => $this->user->id
			);
			YZE_Hook::do_hook ( YD_NOTICE_SIGN_READ, $data );
			$this->set_view_data ( 'notice', $notice );
		}
		$this->set_view_data ( 'yze_page_title', '消息详情' );
	}
	
	// 消息标记已读
	// 地址映射 域名/notice/read
	public function post_read () {
		$request = $this->request;
		$this->layout = "";
		$ids = $request->get_from_post ( "notice_ids" );
		$data = array (
			"notice_ids" => $ids,
			"user_id" => $this->user->id
		);
		YZE_Hook::do_hook ( YD_NOTICE_SIGN_READ, $data );
		return \yangzie\YZE_JSON_View::success ( $this );
	}
	
	// 消息删除
	// 地址映射 域名/notice/delete
	public function post_delete () {
		$request = $this->request;
		$this->layout = "";
		$ids = $request->get_from_post ( "notice_ids" );
		$data = array (
			"notice_ids" => $ids,
			"user_id" => $this->user->id
		);
		YZE_Hook::do_hook ( YD_NOTICE_DELETE, $data );
		return \yangzie\YZE_JSON_View::success ( $this );
	}

	public function exception ( YZE_RuntimeException $e ) {
		$request = $this->request;
		$this->layout = 'error';
		if ( $request->is_post() ) {
			$this->layout = "";
			return \yangzie\YZE_JSON_View::error ( $this, $e->getMessage (), $e->getCode () );
		}
		// 处理中出现了异常，如何处理，没有任何处理将显示500页面
		// 如果想显示get的返回内容可调用 :
		$this->post_result_of_json = YZE_JSON_View::error ( $this, $e->getMessage () );
		// 通过request->the_method()判断是那个方法出现的异常
		// return $this->wrapResponse($this->yourmethod())
	}
}
?>