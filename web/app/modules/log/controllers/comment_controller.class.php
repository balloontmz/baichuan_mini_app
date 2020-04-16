<?php

namespace app\log;

use \yangzie\YZE_JSON_View;
use \yangzie\YZE_Resource_Controller;
use \yangzie\YZE_RuntimeException;
use app\log\Comment_Model;
use yangzie\YZE_Hook;

/**
 * 留言管理
 * @version $Id$
 * @package common
 *         
 */
class Comment_Controller extends YZE_Resource_Controller {

	public function index () {
		$request      = $this->request;
		$last_id      = $request->get_from_get ( "last_id" );
		$target_id    = $request->get_from_get ( "target_id" );
		$target_class = $request->get_from_get ( "target_class" );
		
		$comments = Comment_Model::getComments ( $target_class, $target_id, $last_id );
		$this->set_View_Data ( "comments", $comments );
	}

	//post common/comment
	public function post_index () {
		$request = $this->request;
		$this->layout = "";
		$comment      = $request->get_from_post ( "comment" );
		$target_id    = $request->get_from_post ( "target_id" );
		$notify_uids  = array_filter(explode(",", $request->get_from_post ( "notify_uids" )));
		$notify_link  = $request->get_from_post ( "notify_link" );
		$target_class = $request->get_from_post ( "target_class" );
		$user = YZE_Hook::do_hook ( YZE_HOOK_GET_LOGIN_USER );

		if ( ! class_exists($target_class) || !$target_class::find_by_id($target_id)) return \yangzie\YZE_JSON_View::error ( $this, "留言主体不存在" );
		
		if (! $user ) return \yangzie\YZE_JSON_View::error ( $this, "请登录" );
		if (! $comment ) return \yangzie\YZE_JSON_View::error ( $this, "留言不能为空" );
		
		//TODO 权限验证 leeboo@170613
		
		$date = date ( "Y-m-d H:i:s" );
		$commentModel = new Comment_Model ();
		$commentModel->set ( "comment",       $comment );
		$commentModel->set ( "target_class",  $target_class);
		$commentModel->set ( "target_id",     $target_id );
		$commentModel->set ( "user_id",       $user->id );
		$commentModel->set ( "date",          $date );
		$commentModel->set ( "created_on",    $date );
		$commentModel->save ();
		
		$c = $commentModel->get_records ();
		$e = $user->getEmployee ();
		$c [ "user" ] = $e->get_records ();
		$c [ "user" ] [ "avatar" ] = $c [ "user" ] [ "avatar" ] ? $c [ "user" ] [ "avatar" ] : "/img/avatar_default.png";

		if($notify_uids){
	       $notify_uids = array_diff($notify_uids, [$user->id]);
		   YZE_Hook::do_hook(YD_NOTICE_SAVE, array (
		        "user_ids" => $notify_uids,
		        "content"  => "{$comment}",
		        "link"     => $notify_link?:"#",
		        "target_class" => $target_class,
		        "target_id"    => $target_id
		        ));
		}
		return \yangzie\YZE_JSON_View::success ( $this, $c );
		
	}

	public function check_request_token () {
		return;
	}

	public function exception ( YZE_RuntimeException $e ) {
		$request = $this->request;
		$this->layout = 'error';
		// 处理中出现了异常，如何处理，没有任何处理将显示500页面
		// 如果想显示get的返回内容可调用 :
		$this->post_result_of_json = YZE_JSON_View::error ( $this, $e->getMessage () );
		// 通过request->the_method()判断是那个方法出现的异常
		// return $this->wrapResponse($this->yourmethod())
	}
}
?>