<?php
namespace app\log;
use \yangzie\YZE_Model;

/**
*
*
* @version $Id$
* @package log
*/
class Reminder_Model extends YZE_Model{
    
    const TABLE= "reminder";
    const VERSION = 'modified_on';
    const MODULE_NAME = "log";
    const KEY_NAME = "id";
    const CLASS_NAME = 'app\log\Reminder_Model';
    
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
    const F_TARGET_ID = "target_id";
    /**
     * 
     * @var string
     */
    const F_TARGET_CLASS = "target_class";
    /**
     * 
     * @var date
     */
    const F_REMIND_DATE = "remind_date";
    /**
     * 提醒内容
     * @var string
     */
    const F_REMIND_CONTENT = "remind_content";
    /**
     * 提醒谁
     * @var integer
     */
    const F_USER = "user";
    /**
     * 查看链接
     * @var string
     */
    const F_LINK = "link";
    public static $columns = array(
               'id'         => array('type' => 'integer', 'null' => false,'length' => '11','default'	=> '',),
       'created_on' => array('type' => 'date', 'null' => false,'length' => '','default'	=> 'CURRENT_TIMESTAMP',),
       'modified_on' => array('type' => 'date', 'null' => false,'length' => '','default'	=> 'CURRENT_TIMESTAMP',),
       'target_id'  => array('type' => 'integer', 'null' => false,'length' => '11','default'	=> '',),
       'target_class' => array('type' => 'string', 'null' => false,'length' => '45','default'	=> '',),
       'remind_date' => array('type' => 'date', 'null' => false,'length' => '','default'	=> '',),
       'remind_content' => array('type' => 'string', 'null' => false,'length' => '','default'	=> '',),
       'user'       => array('type' => 'integer', 'null' => false,'length' => '11','default'	=> '',),
       'link'       => array('type' => 'string', 'null' => false,'length' => '255','default'	=> '',),

    );
    //array('attr'=>array('from'=>'id','to'=>'id','class'=>'','type'=>'one-one||one-many') )
    //$this->attr
    protected $objects = array();
    /**
     * @see YZE_Model::$unique_key
     */
    protected $unique_key = array (
  'id' => 'PRIMARY',
  'target_id' => 'idx_target',
  'target_class' => 'idx_target',
);
    		
    
	
}?>