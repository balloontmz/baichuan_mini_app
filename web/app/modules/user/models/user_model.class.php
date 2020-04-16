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
class User_Model extends YZE_Model{
    
    const TABLE= "user";
    const VERSION = 'modified_on';
    const MODULE_NAME = "user";
    const KEY_NAME = "id";
    const CLASS_NAME = 'app\user\User_Model';
    
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
     * @var string
     */
    const F_SEX = "sex";
    /**
     * 
     * @var string
     */
    const F_PASSWORD = "password";
    /**
     * 
     * @var string
     */
    const F_OPENID = "openid";
    /**
     * 
     * @var date
     */
    const F_LOGIN_DATE = "login_date";
    /**
     * 
     * @var date
     */
    const F_OUT_DATE = "out_date";
    /**
     * 
     * @var integer
     */
    const F_ORDER_ID = "order_id";
    /**
     * 
     * @var integer
     */
    const F_PHONE = "phone";
    /**
     * 
     * @var integer
     */
    const F_STATUS = "status";
    public static $columns = array(
               'id'         => array('type' => 'integer', 'null' => false,'length' => '11','default'	=> '',),
       'uuid'       => array('type' => 'string', 'null' => true,'length' => '45','default'	=> '',),
       'name'       => array('type' => 'string', 'null' => true,'length' => '45','default'	=> '',),
       'sex'        => array('type' => 'string', 'null' => true,'length' => '45','default'	=> '',),
       'password'   => array('type' => 'string', 'null' => true,'length' => '45','default'	=> '',),
       'openid'     => array('type' => 'string', 'null' => true,'length' => '180','default'	=> '',),
       'login_date' => array('type' => 'date', 'null' => true,'length' => '','default'	=> '',),
       'out_date'   => array('type' => 'date', 'null' => true,'length' => '','default'	=> '',),
       'order_id'   => array('type' => 'integer', 'null' => true,'length' => '11','default'	=> '',),
       'phone'      => array('type' => 'integer', 'null' => true,'length' => '45','default'	=> '',),
       'status'     => array('type' => 'integer', 'null' => true,'length' => '11','default'	=> '',),

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
    function login($name,$password){
        $user =  User_Model::from()
            ->where("name=:name and password=:password")
            ->select([":name"=>$name,":password"=>$password]);
        foreach ($user as $item){
            return $item->name;
        }
    }
    
	
}?>