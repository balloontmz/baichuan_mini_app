<?php

namespace app\api;

use \yangzie\YZE_Base_Module as YZE_Base_Module;

/**
 *
 * @version $Id$
 * @package Api
 */
class Api_Module extends YZE_Base_Module
{
    public $auths = array();
    public $no_auths = array();

    protected function _config()
    {
        return array(
            'name' => 'Api',
            'routers' => array(
                'api/cellphone' => array(
                    'controller' => 'phone',
                    'args' => array(
                        "action" => "index"
                    ),
                ),
                'api/first_product' => array(
                    'controller' => 'index',
                    'args' => array(
                        "action" => "first_product"
                    ),
                ),
                'api/product' => array(
                    'controller' => 'index',
                    'args' => array(
                        "action" => "product"
                    ),
                ),
                'api/attribute' => array(
                    'controller' => 'index',
                    'args' => array(
                        "action" => "attribute"
                    ),
                ),
                'api/product_quote' => array(
                    'controller' => 'index',
                    'args' => array(
                        "action" => "product_quote"
                    ),
                ),
                'api/query_product' => array(
                    'controller' => 'index',
                    'args' => array(
                        "action" => "query_product"
                    ),
                ),
            )
        );
    }
}

?>