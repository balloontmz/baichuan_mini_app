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
class Quote_Standard_Model extends YZE_Model{
    
    const TABLE= "quote_standard";
    const VERSION = 'modified_on';
    const MODULE_NAME = "attribute";
    const KEY_NAME = "id";
    const CLASS_NAME = 'app\attribute\Quote_Standard_Model';
    
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
    const F_DESC = "desc";
    public static $columns = array(
               'id'         => array('type' => 'integer', 'null' => false,'length' => '11','default'	=> '',),
       'uuid'       => array('type' => 'string', 'null' => true,'length' => '45','default'	=> '',),
       'name'       => array('type' => 'string', 'null' => true,'length' => '80','default'	=> '',),
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