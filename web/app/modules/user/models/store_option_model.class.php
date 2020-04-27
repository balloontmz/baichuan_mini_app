<?php

namespace app\user;

use \yangzie\YZE_Model;
use \yangzie\YZE_SQL;
use \yangzie\YZE_DBAException;
use \yangzie\YZE_DBAImpl;

/**
 *
 *
 * @version $Id$
 * @package user
 */
class Store_Option_Model extends YZE_Model
{

    const TABLE = "store_option";
    const VERSION = 'modified_on';
    const MODULE_NAME = "user";
    const KEY_NAME = "id";
    const CLASS_NAME = 'app\user\Store_Option_Model';

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
    const F_STORE_USER_ID = "store_user_id";
    /**
     *
     * @var integer
     */
    const F_FIRST_PRODUCT_ID = "first_product_id";
    /**
     *
     * @var integer
     */
    const F_SYMBOL = "symbol";
    /**
     *
     * @var string
     */
    const F_STORE_PRICE = "store_price";
    public static $columns = array(
        'id' => array('type' => 'integer', 'null' => false, 'length' => '11', 'default' => '',),
        'uuid' => array('type' => 'string', 'null' => true, 'length' => '45', 'default' => '',),
        'store_user_id' => array('type' => 'integer', 'null' => true, 'length' => '11', 'default' => '',),
        'first_product_id' => array('type' => 'integer', 'null' => true, 'length' => '11', 'default' => '',),
        'symbol' => array('type' => 'integer', 'null' => true, 'length' => '11', 'default' => '',),
        'store_price' => array('type' => 'string', 'null' => true, 'length' => '11', 'default' => '',),

    );
    //array('attr'=>array('from'=>'id','to'=>'id','class'=>'','type'=>'one-one||one-many') )
    //$this->attr
    protected $objects = array();
    /**
     * @see YZE_Model::$unique_key
     */
    protected $unique_key = array(
        'id' => 'PRIMARY',
    );

    /**
     * @param $first_product_id
     * @param $store_user_id
     * @return Store_Option_Model
     * @author weiqianlai
     */
    public function get_by_fpsu_id($store_user_id, $first_product_id)
    {
        return Store_Option_Model::from()
            ->where("first_product_id=:first_product_id AND store_user_id=:store_user_id")
            ->getSingle([":first_product_id" => $first_product_id, ":store_user_id" => $store_user_id]);
    }

    /**
     * @param $store_user_id
     * @return array
     * @author weiqianlai
     */
    public function get_by_su_id($store_user_id)
    {
        return Store_Option_Model::from()
            ->where("store_user_id=:store_user_id")
            ->select([":store_user_id" => $store_user_id]);
    }


}

?>