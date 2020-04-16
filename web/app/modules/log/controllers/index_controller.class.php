<?php

namespace app\log;

use \yangzie\YZE_JSON_View;
use \yangzie\YZE_Resource_Controller;
use \yangzie\YZE_RuntimeException;
use app\log\Log_Item_Model;
use app\log\Log_Model;
use yangzie\YZE_DBAImpl;
use yangzie\YZE_SQL;

/**
 *
 * @version $Id$
 * @package log
 *         
 */
class Index_Controller extends YZE_Resource_Controller {
	
	// 日志列表
	// 地址列表：域名/log
	public function index () {
		$request = $this->request;
		$page = $request->get_from_get ( "page", 1 );
		$name = $request->get_from_get ( "name" );
		$start_date = $request->get_from_get ( "start_date" );
		$end_date = $request->get_from_get ( "end_date" );
		$column = $request->get_from_get ( "column" );
		$total = 0;
		
		$dba = YZE_DBAImpl::getDBA ();
		$sql = new YZE_SQL ();
		$sql->from ( Log_Model::CLASS_NAME, 'l' );
		$sql->left_join ( Log_Item_Model::CLASS_NAME, 'i', 'l.id=i.log_id' );
// 		$sql->left_join ( User_Model::CLASS_NAME, 'u', 'u.id=l.user_id' );
		if ( $name ) {
// 			$sql->where ( 'u', User_Model::F_NAME, YZE_SQL::LIKE, $name );
		}
                if ( $start_date && $end_date) {
			$sql->where ( 'l', Log_Model::F_CREATED_ON, YZE_SQL::BETWEEN, array($start_date." 00-00-00", $end_date." 23:59:59") );
		}else if ( $start_date && !$end_date ) {
			$sql->where ( 'l', Log_Model::F_CREATED_ON, YZE_SQL::GEQ, $start_date." 00-00-00" );
		}else if ( !$start_date && $end_date ) {
			$sql->where ( 'l', Log_Model::F_CREATED_ON, YZE_SQL::LEQ, $end_date." 23:59:59" );
		}
                if ( $column ) {
			$sql->where ( 'i', Log_Item_Model::F_COLUMN, YZE_SQL::LIKE, $column );
		}
		$sql->order_by ( "l", "created_on", YZE_SQL::DESC );
		
		$total = $sql->count ( 'l', 'id', 'num' );
		$total = $dba->select ( $sql );
		$total = $total [ 0 ] [ 'l' ]->num;
		$sql->clean_select ();
		$current_page = @$_GET [ "page" ] < 1 ? 1 : intval ( $_GET [ "page" ] ); // 得到当前是第几页
		$start = ( $current_page - 1 ) * PAGESIZE;
		
		$sql->limit ( $start, PAGESIZE );
		
		$rsts = array ();
		foreach ( ( array ) $dba->select ( $sql ) as $obj ) {
			$obj [ "l" ]->set_user ( $obj [ 'u' ] );
			$rsts [] = array (
				"log" => $obj [ 'l' ],
				"item" => $obj [ 'i' ]
			);
		}
		
		$this->set_view_data ( 'rsts', $rsts );
		$this->set_view_data ( 'total', $total );
		$this->set_view_data ( 'yze_page_title', '日志' );
	}
	
	// 删除日志
	// 地址列表：域名/log/delete
	public function post_delete () {
		$request = $this->request;
		$this->layout = "";
		$log_ids = $request->get_from_post ( "log_ids" );
		$item_ids = $request->get_from_post ( "item_ids" );
		Log_Item_Model::deleteItems ( $item_ids ); // 删除日志明细
		Log_Model::deteleLogs ( $log_ids ); // 删除日志（delete类型的日志没有明细）
		return \yangzie\YZE_JSON_View::success ( $this );
	}

	public function exception ( YZE_RuntimeException $e ) {
		$request = $this->request;
		$this->layout = 'error';
		if ( $request->the_method () == "post_delete" ) {
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