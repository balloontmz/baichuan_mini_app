<?php

namespace app\log;

use \yangzie\YZE_Model;

/**
 * 系统通知
 * @version $Id$
 * @package log
 *
 */
class Notice_Model extends YZE_Model
{
    const TABLE = "notice";
    const VERSION = 'modified_on';
    const MODULE_NAME = "log";
    const KEY_NAME = "id";
    const CLASS_NAME = 'app\log\Notice_Model';

    /**
     *
     * @var integer
     */
    const F_ID = "id";
    /**
     *
     * @var date
     */
    const F_CREATED_ON = "created_on";
    /**
     *
     * @var date
     */
    const F_MODIFIED_ON = "modified_on";
    /**
     * 通知内容
     * @var string
     */
    const F_CONTENT = "content";
    /**
     * 通知产生的记录id
     * @var integer
     */
    const F_TARGET_ID = "target_id";
    /**
     * 通知产生的表
     * @var string
     */
    const F_TARGET_CLASS = "target_class";
    /**
     * 是否阅读
     * @var integer
     */
    const F_IS_READED = "is_readed";
    /**
     *
     * @var integer
     */
    const F_USER_ID = "user_id";
    /**
     * 点击通知进入的地址
     * @var string
     */
    const F_LINK = "link";
    /**
     * 通知标题
     * @var string
     */
    const F_SUBJECT = "subject";
    /**
     * 消息类型
     * @var string
     */
    const F_TYPE = "type";
    public static $columns = array(
        'id' => array(
            'type' => 'integer',
            'null' => false,
            'length' => '11',
            'default' => ''
        ),
        'created_on' => array(
            'type' => 'date',
            'null' => false,
            'length' => '',
            'default' => 'CURRENT_TIMESTAMP'
        ),
        'modified_on' => array(
            'type' => 'date',
            'null' => false,
            'length' => '',
            'default' => 'CURRENT_TIMESTAMP'
        ),
        'content' => array(
            'type' => 'string',
            'null' => false,
            'length' => '',
            'default' => ''
        ),
        'target_id' => array(
            'type' => 'integer',
            'null' => false,
            'length' => '11',
            'default' => ''
        ),
        'target_class' => array(
            'type' => 'string',
            'null' => false,
            'length' => '45',
            'default' => ''
        ),
        'is_readed' => array(
            'type' => 'integer',
            'null' => false,
            'length' => '1',
            'default' => '0'
        ),
        'user_id' => array(
            'type' => 'integer',
            'null' => false,
            'length' => '11',
            'default' => ''
        ),
        'link' => array(
            'type' => 'string',
            'null' => true,
            'length' => '255',
            'default' => ''
        ),
        'subject' => array(
            'type' => 'string',
            'null' => true,
            'length' => '45',
            'default' => ''
        ),
        'type' => array(
            'type' => 'string',
            'null' => true,
            'length' => '15',
            'default' => ''
        )
    );
    // array('attr'=>array('from'=>'id','to'=>'id','class'=>'','type'=>'one-one||one-many') )
    // $this->attr
    protected $objects = array();
    /**
     *
     * @see YZE_Model::$unique_key
     */
    protected $unique_key = array(
        'id' => 'PRIMARY',
        'user_id' => 'fk_notice_user1_idx',
        'target_class' => 'fk_target_table_id',
        'target_id' => 'fk_target_table_id'
    );


    /**
     * 返回userid未读的消息
     *
     * @param unknown $userid
     * @author leeboo
     */
    public static function getUnReadedNotice($userid)
    {
        return self::from()->where("is_readed=0 and user_id=:uid")->select(array(":uid" => $userid));
    }
}

?>