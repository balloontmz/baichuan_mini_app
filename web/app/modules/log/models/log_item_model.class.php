<?php

namespace app\log;

use \app\log\Log_Model;
use \yangzie\YZE_DBAImpl;
use \yangzie\YZE_Model;
use \yangzie\YZE_SQL;
use yangzie\YZE_FatalException;

/**
 *
 * @version $Id$
 * @package log
 *         
 */
class Log_Item_Model extends YZE_Model {
	
	const TABLE = "log_item";
	const VERSION = 'modified_on';
	const MODULE_NAME = "log";
	const KEY_NAME = "id";
	const CLASS_NAME = 'app\log\Log_Item_Model';
	
	/**
	 *
	 * @var integer
	 */
	const F_ID = "id";
	/**
	 *
	 * @var date
	 */
	const F_CREATED_ON = "created_on";
	/**
	 *
	 * @var date
	 */
	const F_MODIFIED_ON = "modified_on";
	/**
	 *
	 * @var integer
	 */
	const F_LOG_ID = "log_id";
	/**
	 *
	 * @var string
	 */
	const F_COLUMN = "column";
	/**
	 *
	 * @var string
	 */
	const F_OLD_VALUE = "old_value";
	/**
	 *
	 * @var string
	 */
	const F_NEW_VALUE = "new_value";
	public static $columns = array (
		'id' => array (
			'type' => 'integer',
			'null' => false,
			'length' => '11',
			'default' => ''
		),
		'created_on' => array (
			'type' => 'date',
			'null' => false,
			'length' => '',
			'default' => 'CURRENT_TIMESTAMP'
		),
		'modified_on' => array (
			'type' => 'date',
			'null' => false,
			'length' => '',
			'default' => 'CURRENT_TIMESTAMP'
		),
		'log_id' => array (
			'type' => 'integer',
			'null' => false,
			'length' => '11',
			'default' => ''
		),
		'column' => array (
			'type' => 'string',
			'null' => true,
			'length' => '45',
			'default' => ''
		),
		'old_value' => array (
			'type' => 'string',
			'null' => true,
			'length' => '',
			'default' => ''
		),
		'new_value' => array (
			'type' => 'string',
			'null' => true,
			'length' => '',
			'default' => ''
		)
	);
	// array('attr'=>array('from'=>'id','to'=>'id','class'=>'','type'=>'one-one||one-many') )
	// $this->attr
	protected $objects = array ();
	/**
	 *
	 * @see YZE_Model::$unique_key
	 */
	protected $unique_key = array (
		'id' => 'PRIMARY',
		'log_id' => 'fk_log_items_logs1_idx'
	);
	
	private $log;

	/**
	 *
	 * @return \app\log\Log_Model
	 */
	public function get_log () {
		if ( ! $this->log ) {
			$this->log = Log_Model::find_by_id ( $this->get ( self::F_LOG_ID ) );
		}
		return $this->log;
	}

	/**
	 *
	 * @return Log_Item_Model
	 */
	public function set_log ( Log_Model $new ) {
		$this->log = $new;
		return $this;
	}

	/**
	 * 获取日志明细
	 * @param unknown $log_id
	 * @author guoxingcai 2017年3月9日 上午9:54:15
	 */
	public static function getItems ( $log_id ) {
		if ( ! $log_id ) return array ();
		return Log_Item_Model::from ()->where ( "log_id=:log_id " )->select ( array (
			":log_id" => $log_id
		) );
	}

	/**
	 * 删除日志明细（如果某日志的明细被删空，则该log也会被删除）
	 * @param array $item_ids
	 * @throws YZE_FatalException
	 * @return boolean
	 * @author guoxingcai 2017年3月9日 上午10:59:47
	 */
	public static function deleteItems ( $item_ids = array() ) {
		if ( ! $item_ids || ! is_array ( $item_ids ) ) return false;
		$db = \yangzie\YZE_DBAImpl::getDBA ();
		// 1. 查询日志
		$sql = new YZE_SQL ();
		$sql->from ( Log_Model::CLASS_NAME, 'l' );
		$sql->left_join ( Log_Item_Model::CLASS_NAME, 'li', 'li.log_id=l.id' );
		$sql->where ( 'li', self::F_ID, YZE_SQL::IN, $item_ids );
		$sql->select ( 'l' );
		$sql->distinct ( 'l', 'id' );
		$logs = $db->select ( $sql );
		if ( ! $logs ) return false;
		
		// 2. 删除明细
		$sql = new YZE_SQL ();
		$sql->from ( Log_Item_Model::CLASS_NAME, 'li' );
		$sql->where ( 'li', self::F_ID, YZE_SQL::IN, $item_ids );
		$sql->delete ();
		$db->execute ( $sql );
		
		// 3. 删除日志
		foreach ( $logs as $log ) {
			$items = $log->getItems ();
			if ( ! $items ) {
				$log->remove ();
				if ( $log->id ) throw new YZE_FatalException ( "日志删除失败!" );
			}
		}
		return true;
	}
}
?>