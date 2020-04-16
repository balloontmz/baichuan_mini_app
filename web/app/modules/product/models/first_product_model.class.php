<?php
namespace app\product;
use \yangzie\YZE_Model;
use \yangzie\YZE_SQL;
use \yangzie\YZE_DBAException;
use \yangzie\YZE_DBAImpl;

/**
*
*
* @version $Id$
* @package product
*/
class First_Product_Model extends YZE_Model{
    
    const TABLE= "first_product";
    const VERSION = 'modified_on';
    const MODULE_NAME = "product";
    const KEY_NAME = "id";
    const CLASS_NAME = 'app\product\First_Product_Model';
    
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
    const F_NAME = "name";
    /**
     * 
     * @var integer
     */
    const F_PRODUCT_TYPE_ID = "product_type_id";
    public static $columns = array(
               'id'         => array('type' => 'integer', 'null' => false,'length' => '11','default'	=> '',),
       'uuid'       => array('type' => 'string', 'null' => false,'length' => '45','default'	=> '',),
       'name'       => array('type' => 'string', 'null' => false,'length' => '200','default'	=> '',),
       'product_type_id' => array('type' => 'integer', 'null' => false,'length' => '11','default'	=> '',),

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