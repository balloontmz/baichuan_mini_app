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
class Second_Attribute_Model extends YZE_Model{
    
    const TABLE= "second_attribute";
    const VERSION = 'modified_on';
    const MODULE_NAME = "attribute";
    const KEY_NAME = "id";
    const CLASS_NAME = 'app\attribute\Second_Attribute_Model';
    
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
    const F_FIRST_ATTRIBUTE_ID = "first_attribute_id";
    public static $columns = array(
               'id'         => array('type' => 'integer', 'null' => false,'length' => '11','default'	=> '',),
       'uuid'       => array('type' => 'string', 'null' => true,'length' => '45','default'	=> '',),
       'name'       => array('type' => 'string', 'null' => true,'length' => '180','default'	=> '',),
       'first_attribute_id' => array('type' => 'integer', 'null' => true,'length' => '11','default'	=> '',),

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

    public static function getByFid($first_attribute_id){
        return Second_Attribute_Model::from()
            ->where("first_attribute_id=:first_attribute_id")
            ->select([":first_attribute_id"=>$first_attribute_id]);
    }
    		
    
	
}?>