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
    const F_GENDER = "gender";
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
     * @var string
     */
    const F_CELLPHONE = "cellphone";
    /**
     * 
     * @var integer
     */
    const F_STATUS = "status";
    /**
     * 
     * @var string
     */
    const F_WX_APPID = "wx_appid";
    /**
     * 
     * @var string
     */
    const F_WX_AVATAR = "wx_avatar";
    public static $columns = array(
               'id'         => array('type' => 'integer', 'null' => false,'length' => '11','default'	=> '',),
       'uuid'       => array('type' => 'string', 'null' => true,'length' => '45','default'	=> '',),
       'name'       => array('type' => 'string', 'null' => true,'length' => '45','default'	=> '',),
       'gender'     => array('type' => 'string', 'null' => true,'length' => '45','default'	=> '',),
       'openid'     => array('type' => 'string', 'null' => true,'length' => '180','default'	=> '',),
       'login_date' => array('type' => 'date', 'null' => true,'length' => '','default'	=> '',),
       'out_date'   => array('type' => 'date', 'null' => true,'length' => '','default'	=> '',),
       'cellphone'  => array('type' => 'string', 'null' => true,'length' => '45','default'	=> '',),
       'status'     => array('type' => 'integer', 'null' => true,'length' => '11','default'	=> '',),
       'wx_appid'   => array('type' => 'string', 'null' => true,'length' => '90','default'	=> '',),
       'wx_avatar'  => array('type' => 'string', 'null' => true,'length' => '255','default'	=> '',),

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
     * @param $openid
     * @return object
     * @author weiqianlai 20200422
     */
    public function find_by_openid($openid)
    {
        return User_Model::from()
            ->where("openid=:openid")
            ->getSingle([":openid" => $openid]);
    }

    /**
     * @param $wx_aapid
     * @return array
     * @author weiqianlai 20200425
     */
    public function find_by_wxAppid($wx_aapid)
    {
        return User_Model::from()
            ->where('wx_appid=:wx_appid')
            ->select([':wx_appid' => $wx_aapid]);
    }

    /**
     * @param $wx_aapid
     * @return int
     * @author weiqianlai 20200425
     */
    public function get_user_count($wx_aapid)
    {
        return User_Model::from()
            ->where('wx_appid=:wx_appid')
            ->count("id",[':wx_appid' => $wx_aapid]);
    }
    		
    
	
}?>