<?php
namespace app\product;
use \yangzie\YZE_Base_Module as YZE_Base_Module;
/**
 *
 * @version $Id$
 * @package Product
 */
class Product_Module extends YZE_Base_Module{
    public $auths = "*";
    public $no_auths = array();
    protected function _config(){
        return array(
        'name'=>'Product',
        'routers' => array(
        	/*'uri'	=> array(
			'controller'	=> 'controller name',
        		'args'	=> array(
        		),
        	),*/
        )
        );
    }
}
?>