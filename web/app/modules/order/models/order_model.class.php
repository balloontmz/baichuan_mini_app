<?php

namespace app\order;

use app\user\User_Model;
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
class Order_Model extends YZE_Model
{

    const TABLE = "order";
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
    /**
     *
     * @var date
     */
    const F_ORDER_TIME = "order_time";
    /**
     *
     * @var string
     */
    const F_WX_APPID = "wx_appid";
    /**
     *
     * @var string
     */
    const F_CONSIGNOR = "consignor";
    /**
     *
     * @var string
     */
    const F_ADDRESS = "address";
    /**
     *
     * @var string
     */
    const F_EXPRESS_COMPANY = "express_company";
    /**
     *
     * @var string
     */
    const F_EXPRESS_NUM = "express_num";
    public static $columns = array(
        'id' => array('type' => 'integer', 'null' => false, 'length' => '11', 'default' => '',),
        'uuid' => array('type' => 'string', 'null' => false, 'length' => '45', 'default' => '',),
        'status' => array('type' => 'string', 'null' => true, 'length' => '255', 'default' => '',),
        'user_id' => array('type' => 'integer', 'null' => true, 'length' => '11', 'default' => '',),
        'desc' => array('type' => 'string', 'null' => true, 'length' => '200', 'default' => '',),
        'price' => array('type' => 'string', 'null' => true, 'length' => '255', 'default' => '',),
        'count' => array('type' => 'integer', 'null' => true, 'length' => '11', 'default' => '',),
        'order_time' => array('type' => 'date', 'null' => true, 'length' => '', 'default' => '',),
        'wx_appid' => array('type' => 'string', 'null' => true, 'length' => '255', 'default' => '',),
        'consignor' => array('type' => 'string', 'null' => true, 'length' => '255', 'default' => '',),
        'address' => array('type' => 'string', 'null' => true, 'length' => '255', 'default' => '',),
        'express_company' => array('type' => 'string', 'null' => true, 'length' => '255', 'default' => '',),
        'express_num' => array('type' => 'string', 'null' => true, 'length' => '255', 'default' => '',),

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
     * @param $openid
     * @param $wx_appid
     * @param $status
     */
    public function get_by_openid($openid, $wx_appid, $status)
    {
        $user = User_Model::find_by_openid($openid);
        return Order_Model::from()
            ->where("user_id=:user_id and wx_appid=:wx_appid and status=:status")
            ->select([":user_id" => $user->id, ":wx_appid" => $wx_appid, ":status" => $status]);
    }

    /**
     * @param $openid
     * @param $wx_appid
     */
    public function get_all($openid, $wx_appid)
    {
        $user = User_Model::find_by_openid($openid);
        return Order_Model::from()
            ->where("user_id=:user_id and wx_appid=:wx_appid")
            ->select([":user_id" => $user->id, ":wx_appid" => $wx_appid]);
    }

    /**
     * @param $status
     */
    public function get_status_text($status)
    {
        $text = "";
        switch ($status) {
            case "unshipped":
                $text = "待发货";
                break;
            case "shipped":
                $text = "已发货";
                break;
            case "looking":
                $text = "待验机";
                break;
            case "collect":
                $text = "待收款";
                break;
            case "finish":
                $text = "已完成";
                break;
            case "reback":
                $text = "待退回";
                break;
            case "returned":
                $text = "已退回";
                break;
        }
        return $text;
    }


}

?>