<?php

namespace app\log;

use \yangzie\YZE_Base_Module as YZE_Base_Module;

/**
 * 保存消息，传入数组内容：
 *
 * array 	user_ids 			该次消息要发送给哪些人，是系统中user表的id组成的数组；（必传）
 *
 * string 	content 			发送的消息文本；（必传）
 *
 * string 	link 				点击消息时，将会跳转的地址
 *
 * string 	target_class 		目标对象名
 *
 * int 		target_id 			目标对象id
 *
 */
define ( "YD_NOTICE_SAVE", "YD_NOTICE_SAVE" );

/**
 * 获取消息数
 * string 	target_class 		目标对象名
 *
 * array 	target_ids 			目标对象id
 *
 * int 		user_id 			用户ID(必须)
 *
 * int		is_read				0表示未读，1表示所有，-1表示全部（默认未读）
 *
 * @return int
 */
define ( "YD_NOTICE_GET_NUM", "YD_NOTICE_GET_NUM" );


/**
 * 获取消息的notice_model对象数组
 * int 		user_id				用户ID (必须)
 *
 * string 	target_class 		目标对象名
 *
 * array 	target_ids 			目标对象id数组
 *
 * page 		int 				查询页(大于0整数表示分页)
 *
 *  pagesize 	int 				每页大小(默认10)
 *
 *  is_read	int					0表示未读，1表示所有，-1表示全部（默认未读）
 *
 * @return array(Notice_Model)
 */
define ( "YD_NOTICE_GET_NOTICES", "YD_NOTICE_GET_NOTICES" );

/**
 * 消息标记已读
 *  array		notice_ids			消息ID (必须)
 *
 *  int 	 	user_id				用户ID (必须)
 */
define ( "YD_NOTICE_SIGN_READ", "YD_NOTICE_SIGN_READ" );

/**
 * 删除消息
 *  array 	notice_ids			消息ID (必须)
 *
 *  int 	 	user_id				用户ID (必须)
 */
define ( "YD_NOTICE_DELETE", "YD_NOTICE_DELETE" );
/**
 * @version $Id$
 * @package Log
 */
class Log_Module extends YZE_Base_Module {

    public $auths = "*";
    public $no_auths = array();

    protected function _config() {
        return array(
            'name' => 'Log',
            'routers' => array(
                'log' => array(
                    'controller' => 'index',
                    'args' => array()
                ),
                'log/delete' => array(
                    'controller' => 'index',
                    'args' => array(
                        'action' => 'delete'
                    )
                ),
                'comment' => array(
                    'controller' => 'comment',
                    'args' => array()
                ),
                'notice' => array(
                    'controller' => 'notice',
                    'args' => array()
                ),
                'notice/(?P<notice_id>\d+)' => array(
                    'controller' => 'notice',
                    'args' => array(
                        "action" => "detail"
                    )
                ),
                'notice/read' => array(
                    'controller' => 'notice',
                    'args' => array(
                        "action" => "read"
                    )
                ),
                'notice/delete' => array(
                    'controller' => 'notice',
                    'args' => array(
                        "action" => "delete"
                    )
                )
            )
        );
    }

}

?>