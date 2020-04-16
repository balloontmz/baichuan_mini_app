<?php
namespace app\attribute;
use \yangzie\YZE_Model;
use \yangzie\YZE_SQL;
use \yangzie\YZE_DBAException;
use \yangzie\YZE_DBAImpl;

/**
*
*
* @version $Id$
* @package attribute
*/
class First_Attribute_Model extends YZE_Model{
    
    const TABLE= "first_attribute";
    const VERSION = 'modified_on';
    const MODULE_NAME = "attribute";
    const KEY_NAME = "id";
    const CLASS_NAME = 'app\attribute\First_Attribute_Model';
    
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
    public static $columns = array(
               'id'         => array('type' => 'integer', 'null' => false,'length' => '11','default'	=> '',),
       'uuid'       => array('type' => 'string', 'null' => false,'length' => '45','default'	=> '',),
       'name'       => array('type' => 'string', 'null' => true,'length' => '180','default'	=> '',),

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