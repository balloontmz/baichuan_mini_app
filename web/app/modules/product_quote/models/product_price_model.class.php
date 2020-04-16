<?php
namespace app\product_quote;
use \yangzie\YZE_Model;
use \yangzie\YZE_SQL;
use \yangzie\YZE_DBAException;
use \yangzie\YZE_DBAImpl;

/**
*
*
* @version $Id$
* @package product_quote
*/
class Product_Price_Model extends YZE_Model{
    
    const TABLE= "product_price";
    const VERSION = 'modified_on';
    const MODULE_NAME = "product_quote";
    const KEY_NAME = "id";
    const CLASS_NAME = 'app\product_quote\Product_Price_Model';
    
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
    const F_PRODUCT_ID = "product_id";
    /**
     * 
     * @var string
     */
    const F_SECOND_ATTRIBUTE_IDS = "second_attribute_ids";
    /**
     * 
     * @var string
     */
    const F_QUOTE_STANDARD_IDS = "quote_standard_ids";
    /**
     * 
     * @var string
     */
    const F_PRICE = "price";
    public static $columns = array(
               'id'         => array('type' => 'integer', 'null' => false,'length' => '11','default'	=> '',),
       'uuid'       => array('type' => 'string', 'null' => true,'length' => '45','default'	=> '',),
       'product_id' => array('type' => 'integer', 'null' => true,'length' => '11','default'	=> '',),
       'second_attribute_ids' => array('type' => 'string', 'null' => true,'length' => '45','default'	=> '',),
       'quote_standard_ids' => array('type' => 'string', 'null' => true,'length' => '45','default'	=> '',),
       'price'      => array('type' => 'string', 'null' => true,'length' => '120','default'	=> '',),

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

    /**
     * @param $product_id
     * @return array
     * @author weiqianlai 2020-04-17
     */
    public function get_by_product_id($product_id){
        return Product_Price_Model::from()
            ->where("product_id=:product_id")
            ->select([":product_id"=>$product_id]);
    }
    
	
}?>