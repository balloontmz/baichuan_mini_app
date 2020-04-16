<?php
namespace app\setting;
use \yangzie\YZE_Model;
use \yangzie\YZE_SQL;
use \yangzie\YZE_DBAException;
use \yangzie\YZE_DBAImpl;

/**
*
*
* @version $Id$
* @package setting
*/
class Setting_Model extends YZE_Model{
    
    const TABLE= "setting";
    const VERSION = 'modified_on';
    const MODULE_NAME = "setting";
    const KEY_NAME = "id";
    const CLASS_NAME = 'app\setting\Setting_Model';
    
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
    const F_TYPE = "type";
    /**
     * 
     * @var string
     */
    const F_PIC_URL = "pic_url";
    /**
     * 
     * @var string
     */
    const F_DESC = "desc";
    public static $columns = array(
               'id'         => array('type' => 'integer', 'null' => false,'length' => '11','default'	=> '',),
       'uuid'       => array('type' => 'string', 'null' => false,'length' => '45','default'	=> '',),
       'name'       => array('type' => 'string', 'null' => true,'length' => '200','default'	=> '',),
       'type'       => array('type' => 'string', 'null' => true,'length' => '45','default'	=> '',),
       'pic_url'    => array('type' => 'string', 'null' => true,'length' => '200','default'	=> '',),
       'desc'       => array('type' => 'string', 'null' => true,'length' => '200','default'	=> '',),

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