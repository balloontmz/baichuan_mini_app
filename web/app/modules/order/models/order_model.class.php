<?php
namespace app\order;
use \yangzie\YZE_Model;
use \yangzie\YZE_SQL;
use \yangzie\YZE_DBAException;
use \yangzie\YZE_DBAImpl;

/**
*
*
* @version $Id$
* @package order
*/
class Order_Model extends YZE_Model{
    
    const TABLE= "order";
    const VERSION = 'modified_on';
    const MODULE_NAME = "order";
    const KEY_NAME = "id";
    const CLASS_NAME = 'app\order\Order_Model';
    
    /**
     * 
     * @var integer
     */
    const F_ID = "id";
    /**
     * 
     * @var string
     */
    const F_UUID = "uuid";
    /**
     * 
     * @var string
     */
    const F_STATUS = "status";
    /**
     * 
     * @var integer
     */
    const F_USER_ID = "user_id";
    /**
     * 
     * @var string
     */
    const F_DESC = "desc";
    /**
     * 
     * @var string
     */
    const F_PRICE = "price";
    /**
     * 
     * @var integer
     */
    const F_COUNT = "count";
    public static $columns = array(
               'id'         => array('type' => 'integer', 'null' => false,'length' => '11','default'	=> '',),
       'uuid'       => array('type' => 'string', 'null' => false,'length' => '45','default'	=> '',),
       'status'     => array('type' => 'string', 'null' => true,'length' => '255','default'	=> '',),
       'user_id'    => array('type' => 'integer', 'null' => true,'length' => '11','default'	=> '',),
       'desc'       => array('type' => 'string', 'null' => true,'length' => '200','default'	=> '',),
       'price'      => array('type' => 'string', 'null' => true,'length' => '255','default'	=> '',),
       'count'      => array('type' => 'integer', 'null' => true,'length' => '11','default'	=> '',),

    );
    //array('attr'=>array('from'=>'id','to'=>'id','class'=>'','type'=>'one-one||one-many') )
    //$this->attr
    protected $objects = array();
    /**
     * @see YZE_Model::$unique_key
     */
    protected $unique_key = array (
  'id' => 'PRIMARY',
);
    		
    
	
}?>