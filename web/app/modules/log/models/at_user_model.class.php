<?php
namespace app\log;
use \yangzie\YZE_Model;
use yangzie\YZE_DBAImpl;
use yangzie\YZE_SQL;

/**
*
*
* @version $Id$
* @package log
*/
class At_User_Model extends YZE_Model{
	use At_User_Model_Log;
    
    const TABLE= "at_user";
    const VERSION = 'modified_on';
    const MODULE_NAME = "log";
    const KEY_NAME = "id";
    const CLASS_NAME = 'app\log\At_User_Model';
    
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
    const F_TARGET_CLASS= "target_class";
    /**
     * 
     * @var integer
     */
    const F_TARGET_ID = "target_id";
    /**
     * 
     * @var integer
     */
    const F_USER_ID = "user_id";
    /**
     * 
     * @var date
     */
    const F_READ_DATE = "read_date";
    public static $columns = array(
               'id'         => array('type' => 'integer', 'null' => false,'length' => '11','default'	=> '',),
       'created_on' => array('type' => 'date', 'null' => false,'length' => '','default'	=> 'CURRENT_TIMESTAMP',),
       'modified_on' => array('type' => 'date', 'null' => false,'length' => '','default'	=> 'CURRENT_TIMESTAMP',),
       'target_class' => array('type' => 'string', 'null' => false,'length' => '45','default'	=> '',),
       'target_id'  => array('type' => 'integer', 'null' => false,'length' => '11','default'	=> '',),
       'user_id'    => array('type' => 'integer', 'null' => false,'length' => '11','default'	=> '',),
       'read_date'  => array('type' => 'date', 'null' => false,'length' => '','default'	=> '',),

    );
    //array('attr'=>array('from'=>'id','to'=>'id','class'=>'','type'=>'one-one||one-many') )
    //$this->attr
    protected $objects = array();
    /**
     * @see YZE_Model::$unique_key
     */
    protected $unique_key = array (
  'id' => 'PRIMARY',
  'user_id' => 'fk_at_user_user1_idx',
);
    		
    
	private $user;
	
	/**
	 * 获取某个对象的读取用户数
	 */
	public static function read_count($target_class, $target_id, $is_readed){
	    return self::from()->where("target_class=:tb and target_id=:tid and is_readed=:readed")->count("id",[":tb"=>$target_class, ":tid"=>$target_id, ":readed"=>$is_readed]);
	}
	/**
	 * 返回某个对象，某个用户是否已读
	 * @param unknown $target_class
	 * @param unknown $target_id
	 * @param unknown $user_id
	 * @return number
	 */
	public static function has_readed($target_class, $target_id, $user_id){
	    return At_User_Model::from()
	    ->where("target_class=:tb and target_id=:tid and user_id=:uid")->count("id",[":tb"=>$target_class, ":tid"=>$target_id, ":uid"=>$user_id]);
	}

	/**
	 * 标记某个对象、某个用户的读取状态
	 * 
	 * @param unknown $target_class
	 * @param unknown $target_id
	 * @param unknown $is_readed
	 * @param unknown $user_id
	 * 
	 * @author leeboo
	 */
	public static function add_at_user($target_class, $target_id, $is_readed, $user_id){
	    $sql = new YZE_SQL();
	    $sql->insert ( "t", array (
	            "created_on" => date ( "Y-m-d H:i:s" ),
	            "read_date"  => date ( "Y-m-d H:i:s" ),
	            "target_class" => $target_class,
	            "target_id" => $target_id,
	            "is_readed" => $is_readed,
	            "user_id"   => $user_id,
	    ), YZE_SQL::INSERT_NOT_EXIST )->from ( "\\app\\log\\At_User_Model", "t" )
	    ->where ( "t", "target_class", YZE_SQL::EQ, $target_class)
	    ->where ( "t", "target_id", YZE_SQL::EQ,   $target_id)
	    ->where ( "t", "user_id", YZE_SQL::EQ,     $user_id );
	    
	    YZE_DBAImpl::getDBA ()->execute ( $sql );
	}
	
	/**
	 * 返回所有的用户id
	 * @param unknown $target_class
	 * @param unknown $target_id
	 * @author leeboo@170622
	 * @return array
	 */
	public static function get_user_ids($target_class, $target_id){
	    $sql = "select group_concat(user_id) as uids from at_user 
            where target_class='".addslashes($target_class)."' and target_id=".intval($target_id);
	    $rst = YZE_DBAImpl::getDBA()->nativeQuery2($sql);
	    $rst->next();
	    return array_filter(explode(",", $rst->f("uids")));
	}

}?>