<?php

namespace app\file;

use \yangzie\YZE_Base_Module as YZE_Base_Module;

/**
 *
 * @version $Id$
 * @package File
 */
class File_Module extends YZE_Base_Module {

    public $auths = "*";
    public $no_auths = array();

    protected function _config() {
        return array(
            'name' => 'File',
            'routers' => array(
                
                'files' => [
                    'controller' => 'index',
                    'args' => ["action"=>"search"]
                ],
                'file-(?P<type_id>[^\/]+)' => [
                    'controller' => 'index',
                    'args' => []
                ],
                'file/(?P<file_id>[^\/]+)/delete' => array(
                    'controller' => 'index',
                    'args' => array(
                        "action" => "delete"
                    )
                ),
                //查看某文件的历史版本 liulongxing@20170511
                'file/(?P<file_id>[^\/]+)/history' => array(
                    'controller' => 'index',
                    'args' => array(
                        "action" => "history"
                    )
                ),
            	'file/target/(?P<target_id>\d+)' => array(
            		'controller' => 'index',
            		'args' => array(
            			"action" => "target_file"
            		)
            	)
            )
        );
    }

}

?>