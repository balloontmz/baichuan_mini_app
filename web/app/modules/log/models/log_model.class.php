<?php

namespace app\log;

use \yangzie\YZE_Model;

/**
 *
 * @version $Id$
 * @package log
 *         
 */
class Log_Model extends YZE_Model {
	
	const TABLE = "log";
	const VERSION = 'modified_on';
	const MODULE_NAME = "log";
	const KEY_NAME = "id";
	const CLASS_NAME = 'app\log\Log_Model';
	
	/**
	 * 插入
	 * @var string
	 */
	const ACTION_INSERT = "insert";
	/**
	 * 更新
	 * @var string
	 */
	const ACTION_UPDATE = "update";
	/**
	 * 删除
	 * @var string
	 */
	const ACTION_DELETE = "delete";
	
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
	 * @var string
	 */
	const F_ACTION = "action";
	/**
	 *
	 * @var integer
	 */
	const F_USER_ID = "user_id";
	/**
	 *
	 * @var integer
	 */
	const F_TARGET_ID = "target_id";
	/**
	 *
	 * @var string
	 */
	const F_TARGET_CLASS = "target_class";
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
		'action' => array (
			'type' => 'string',
			'null' => false,
			'length' => '45',
			'default' => ''
		),
		'user_id' => array (
			'type' => 'integer',
			'null' => false,
			'length' => '11',
			'default' => ''
		),
		'target_id' => array (
			'type' => 'integer',
			'null' => true,
			'length' => '11',
			'default' => ''
		),
		'target_class' => array (
			'type' => 'string',
			'null' => true,
			'length' => '45',
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
		'user_id' => 'fk_logs_users1_idx'
	);
	
	private $items;


	/**
	 * 获取日志明细
	 * @return array(Log_Item_Model)
	 * @author guoxingcai 2017年3月9日 上午9:53:19
	 */
	public function getItems () {
		if ( ! $this->items ) {
			$items = Log_Item_Model::getItems ( $this->id );
			foreach ( $items as $item ) {
				$this->addItem ( $item );
			}
		}
		return $this->items;
	}

	/**
	 * 添加item
	 * @param Log_Item_Model $item
	 * @return \app\log\Log_Model
	 * @author guoxingcai 2017年3月9日 上午9:49:18
	 */
	public function addItem ( Log_Item_Model $item ) {
		$this->items [ $item->id ] = $item;
		return $this;
	}

	/**
	 * 获取操作的描述
	 * @return string
	 * @author guoxingcai 2017年3月9日 上午10:21:50
	 */
	public function getActionDesc () {
		switch ( $this->action ) {
			case self::ACTION_INSERT :
				return "新增";
			case self::ACTION_UPDATE :
				return "修改";
			case self::ACTION_DELETE :
				return "删除";
			default :
				return $this->action;
		}
	}

	/**
	 * 获取目标对象
	 * @return \yangzie\YZE_Model
	 * @author guoxingcai 2017年3月9日 下午2:57:22
	 */
	public function getTargetObject () {
	    $class = $this->target_class;
	    return $class::find_by_id ( $this->get ( Log_Model::F_TARGET_ID ) );
	}

	/**
	 * 删除日志
	 * @param array() $log_ids
	 * @return boolean
	 * @author guoxingcai 2017年3月9日 下午3:42:23
	 */
	public static function deteleLogs ( $log_ids ) {
		if ( ! $log_ids ) return false;
		$ids_str = implode ( ",", $log_ids );
		Log_Item_Model::from ()->where ( " log_id IN ({$ids_str}) " )->delete ();
		Log_Model::from ()->where ( " id IN ({$ids_str}) " )->delete ();
		return false;
	}

}
?>