<?php

namespace app;

use app\log\Log_Item_Model;
use app\log\Log_Model;
use yangzie\YZE_Hook;
use yangzie\YZE_Model;

// 增加记录，做数据库插入操作时
YZE_Hook::add_hook ( YZE_HOOK_MODEL_INSERT, function ( $model ) {
	if ( ! ( $model instanceof YZE_Model ) ) return $model;
	$user = YZE_Hook::do_hook ( YZE_HOOK_GET_LOGIN_USER );
	$class_name = get_class ( $model );
	$trait_name = "{$class_name}_Log";
	$date = date ( "Y-m-d H:i:s" );
	if ( trait_exists ( $trait_name ) && $user ) {
		// 1. 保存log
		$log = new Log_Model ();
		$log->set ( "action", Log_Model::ACTION_INSERT );
		$log->set ( "user_id", $user->id );
		$log->set ( "target_id", $model->id );
		$log->set ( "target_class", $class_name);
		$log->set ( "created_on", $date );
		$log->save ();
		if ( ! $log->id ) return $model;
		
		// 2. 保存明细
		$record = $model->get_records ();
		foreach ( $record as $key => $val ) {
			if ( $key == "id" || $key == "created_on" || $key == "modified_on" ) continue;
			$item = new Log_Item_Model ();
			$item->set ( "log_id", $log->id );
			$item->set ( "column", $key );
			$item->set ( "old_value", "" );
			$item->set ( "new_value", $val );
			$item->set ( "created_on", $date );
			$item->save ();
		}
	}
	return $model;
} );

// 修改记录，做数据库修改操作时时
YZE_Hook::add_hook ( YZE_HOOK_MODEL_UPDATE, function ( $model ) {
	if ( ! ( $model instanceof YZE_Model ) ) return $model;
	$class_name = get_class ( $model );
	$old_data = $class_name::find_by_id ( $model->id );
	$old_record = $old_data->get_records ();
	$new_record = $model->get_records ();
	$user = YZE_Hook::do_hook ( YZE_HOOK_GET_LOGIN_USER );
	$trait_name = "{$class_name}_Log";
	if ( trait_exists ( $trait_name ) && $user ) {
		$log = new Log_Model ();
		$log->set ( 'user_id', $user->id );
		$log->set ( 'target_class', $class_name );
		$log->set ( 'target_id', $model->id );
		$log->set ( 'action', Log_Model::ACTION_UPDATE );
		$log->save ();
		foreach ( $new_record as $key => $val ) {
			if ( $val == $old_record [ $key ] || $key == "modified_on" ) continue;
			$log_item = new Log_Item_Model ();
			$log_item->set ( 'log_id', $log->id );
			$log_item->set ( 'column', $key );
			$log_item->set ( 'old_value', $old_record [ $key ] );
			$log_item->set ( 'new_value', $val );
			$log_item->save ();
		}
	}
	return $model;
} );

// 删除记录，做数据库删除操作时时
YZE_Hook::add_hook ( YZE_HOOK_MODEL_DELETE, function ( $model ) {
	if ( ! ( $model instanceof YZE_Model ) ) return $model;
	$user = YZE_Hook::do_hook ( YZE_HOOK_GET_LOGIN_USER );
	$class_name = get_class ( $model );
	$trait_name = "{$class_name}_Log";
	$date = date ( "Y-m-d H:i:s" );
	if ( trait_exists ( $trait_name ) && $user ) {
		$log = new Log_Model ();
		$log->set ( "action", Log_Model::ACTION_DELETE );
		$log->set ( "user_id", $user->id );
		$log->set ( "target_id", $getTableName->id );
		$log->set ( "target_class", $class_name );
		$log->set ( "created_on", $date );
		$log->save ();
	}
	return $model;
} );

?>