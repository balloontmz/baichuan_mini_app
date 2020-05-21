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
class Product_Model extends YZE_Model{
    
    const TABLE= "product";
    const VERSION = 'modified_on';
    const MODULE_NAME = "product";
    const KEY_NAME = "id";
    const CLASS_NAME = 'app\product\Product_Model';
    
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
     * @var integer
     */
    const F_FIRST_PRODUCT_ID = "first_product_id";
    /**
     * 
     * @var string
     */
    const F_NAME = "name";
    /**
     * 
     * @var string
     */
    const F_COMMENT = "comment";
    public static $columns = array(
               'id'         => array('type' => 'integer', 'null' => false,'length' => '11','default'	=> '',),
       'uuid'       => array('type' => 'string', 'null' => false,'length' => '45','default'	=> '',),
       'first_product_id' => array('type' => 'integer', 'null' => false,'length' => '11','default'	=> '',),
       'name'       => array('type' => 'string', 'null' => false,'length' => '80','default'	=> '',),
       'comment'    => array('type' => 'string', 'null' => true,'length' => '255','default'	=> '',),

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

    //通过上级产品id查询产品
    public static function getProductByFid($first_product_id)
    {
        return Product_Model::from()
            ->where("first_product_id=:first_product_id")
            ->select([":first_product_id" => $first_product_id]);
    }
	
}?>