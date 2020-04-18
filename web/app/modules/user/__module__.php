<?php

namespace app\user;

use \yangzie\YZE_Base_Module as YZE_Base_Module;

/**
 *
 * @version $Id$
 * @package User
 */
class User_Module extends YZE_Base_Module
{
    public $auths = "*";
    public $no_auths = array();

    protected function _config()
    {
        return array(
            'name' => 'User',
            'routers' => array(
                '' => array(
                    'controller' => 'index',
                    'args' => array(),
                ),
            )
        );
    }
}

?>