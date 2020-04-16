<?php

namespace app\log;

use \yangzie\YZE_DBAImpl;
use \yangzie\YZE_Model;
use \yangzie\YZE_SQL;

/**
 * 留言
 * @version $Id$
 * @package log
 *         
 */
class Comment_Model extends YZE_Model {
	use Comment_Model_Log;
	
	const TABLE = "comment";
	const VERSION = 'modified_on';
	const MODULE_NAME = "log";
	const KEY_NAME = "id";
	const CLASS_NAME = 'app\log\Comment_Model';
	
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
	 * 留言内容
	 * @var string
	 */
	const F_COMMENT = "comment";
	/**
	 * 留言时间
	 * @var date
	 */
	const F_DATE = "date";
	/**
	 *
	 * @var integer
	 */
	const F_USER_ID = "user_id";
	/**
	 *
	 * @var string
	 */
	const F_TARGET_CLASS = "target_class";
	/**
	 *
	 * @var integer
	 */
	const F_TARGET_ID = "target_id";
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
		'comment' => array (
			'type' => 'string',
			'null' => false,
			'length' => '',
			'default' => ''
		),
		'date' => array (
			'type' => 'date',
			'null' => false,
			'length' => '',
			'default' => ''
		),
		'user_id' => array (
			'type' => 'integer',
			'null' => false,
			'length' => '11',
			'default' => ''
		),
		'target_class' => array (
			'type' => 'string',
			'null' => false,
			'length' => '45',
			'default' => ''
		),
		'target_id' => array (
			'type' => 'integer',
			'null' => false,
			'length' => '11',
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
		'user_id' => 'fk_comment_user1_idx',
		'target_class' => 'fk_target_table_id',
		'target_id' => 'fk_target_table_id'
	);
	

	/**
	 * 获取留言, 留言的排序是按照id从大到小排列
	 * 
	 * @param string $target_class 目标表类名
	 * @param number $target_id 目标记录
	 * @param number $last_id 最后留言的ID, 传入则返回该id之前的comment，及id小于last id的comment
	 * @param string $user_model_class User的类
	 * @return \yangzie\array(Comment_Model) key 为comment.id
	 * @author liizii@170613 重构
	 */
	public static function getComments ( $target_class, $target_id, $last_id = 0, $user_model_class=null ) {
		$comments = array ();
		if ( ! $target_class|| ! $target_id ) return $comments;
		
		$where = "";
		$args = [":target_cls"=>$target_class,":target_id"=>$target_id];
		if($last_id > 0){
		    $where =" and c.id < :last_id";
		    $args[":last_id"] = $last_id;
		}
		
		foreach(self::from("c")
		        ->left_join($user_model_class, "u", "u.id=c.user_id")
		        ->where("c.target_class=:target_cls and c.target_id=:target_id $where")->limit(0, PAGESIZE)->order_By("id", "desc","c")->select($args) as $obj){
            $obj['c']->set_user($obj['u']);
		    $comments[ $obj['c']->id ] = $obj['c'];
		}
		return $comments;
	}
	
	/**
	 * 返回第一条comment，用于在分页是判断是否该继续加载
	 * 
	 * @param unknown $target_class
	 * @param unknown $target_id
	 * @return Comment_Model
	 */
	public static function getFirstComment ( $target_class, $target_id) {
	    if ( ! $target_class|| ! $target_id ) return 0;
	    
	    $args = [":target_cls"=>$target_class,":target_id"=>$target_id];
	    return self::from("c")
	            ->where("c.target_class=:target_cls and c.target_id=:target_id")->order_By("id", "asc","c")->getSingle($args);
	}

	/**
	 * 获取留言在总数
	 * @param string $target_class
	 * @param number $target_id
	 * @return number
	 * @author guoxingcai 2017年3月2日 下午3:09:15
	 */
	public static function getCommentNums ( $target_class, $target_id ) {
	    if ( ! $target_class|| ! $target_id ) return 0;
		$sql = new YZE_SQL ();
		$sql->from ( Comment_Model::CLASS_NAME, 'c' );
		$sql->where ( 'c', Comment_Model::F_TARGET_CLASS, YZE_SQL::EQ, $target_class);
		$sql->where ( 'c', Comment_Model::F_TARGET_ID, YZE_SQL::EQ, $target_id );
		$sql->count ( 'c', Comment_Model::F_ID, 'num' );
		$res = \yangzie\YZE_DBAImpl::getDBA ()->getSingle ( $sql );
		return $res ? $res->num : 0; 
	}
}
?>