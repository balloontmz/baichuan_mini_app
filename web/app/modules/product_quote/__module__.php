<?php

namespace app\product_quote;

use \yangzie\YZE_Base_Module as YZE_Base_Module;

/**
 *
 * @version $Id$
 * @package Product_quote
 */
class Product_Quote_Module extends YZE_Base_Module
{
    public $auths = "*";
    public $no_auths = array();

    protected function _config()
    {
        return array(
            'name' => 'Product_quote',
            'routers' => array(
//                '' => array(
//                    'controller' => 'index',
//                    'args' => array(),
//                ),
            )
        );
    }
}

?>