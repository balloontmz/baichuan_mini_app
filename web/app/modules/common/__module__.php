<?php

namespace app\common;

use \yangzie\YZE_Base_Module as YZE_Base_Module;


/**
 * 登录视图hook，传入参数controller对象，返回yze_simple_view,如果有注册的hook则输出返回的view，如果没有，则默认显示一个登录界面
 *
 * @author leeboo
 * @var unknown
 */
define("YD_COMMON_SIGNIN_VIEW", "YD_COMMON_SIGNIN_VIEW");

/**
 * 该hook用于鉴定用户：
 *
 * 无传入参数，hook中需要的参数由request全局对象获取
 * 自己主动do hook的可自定义传入参数
 * 返回任意非空内容表示登录成功，登录失败抛出异常
 *
 * 该hook不应该出现无内容返回也没异常的情况，出现这种情况当中登录失败处理
 * 返回的内容可以是一个model，也可以是任意表示用户登录成功的数据，但要注意
 * 该hook是在post_signin中处理，如果抛出异常表示登录失败；
 * 如果是传统的表单提交，可通过在登录视图的合适位置放置yze_controller_error来输出异常信息
 * 如果是ajax提交，可直接输出结果中的msg部分
 * 也就是是common控制器的exception部分要考虑客户端api调用还是传统的调用
 * 如果有内容返回，那么调用YZE_HOOK_SET_LOGIN_USER hook把内容设置到会话中以标记系统登录成功，这是系统的其他任何地方都可以通过YZE_HOOK_GET_LOGIN_USER来返回登录用户新
 * 开发者自行决定登录后返回的是什么内容，从而决定YZE_HOOK_GET_LOGIN_USER得到的内容怎么处理；比如登录后返回的是用户对象，那么YZE_HOOK_GET_LOGIN_USER就取得该用户对象
 * 如果说开发者决定登录后返回登录用户的id值，那么YZE_HOOK_GET_LOGIN_USER就返回该id值，要去掉登录用户的数据对象就需要在查找一次
 * @author leeboo
 */
define("YD_COMMON_SIGNIN_POST", "YD_COMMON_SIGNIN_POST");

/**
 * 该hook在登录成功后触发
 * 无传入参数
 * 返回YZE_Redirect对象，表示返回的重定向
 * @author leeboo
 */
define("YD_COMMON_SIGNIN_REDIRECT", "YD_COMMON_SIGNIN_REDIRECT");

/**
 * 该hook在登出后触发
 * 无传入参数
 * 返回YZE_Redirect对象，表示返回的重定向
 * @author leeboo
 */
define("YD_COMMON_SIGNOUT", "YD_COMMON_SIGNOUT");

/**
 * 文件上传action HOOK, 传入hook参数
 * ["id" => "",//hook把文件保存成功后，设置返回给前端js
 * "path" => 上传后的相对路径，相当于指定的上传目录,
 * "extra" => 上传文件信息,
 * "action" => "区分上传目的"]
 * @var string
 *
 */
define ( "YD_COMMON_UPLOADED", "YD_COMMON_UPLOADED" );
/**
 * 文件下载hook, 下载地址/common/download 至于要下载什么文件,自行设计get参数
 * @var string
 */
define ( "YD_COMMON_DOWNLOAD", "YD_COMMON_DOWNLOAD" );



/**
 * 登录\登出\上传下载
 * @version $Id$
 * @package Common
 */
class Common_Module extends YZE_Base_Module {

    public $auths = array("upload" => "*");
    public $no_auths = array();

    protected function _config() {
        return array(
            'name' => 'Common',
            'routers' => array(
                'signin' => array(
                    'controller' => 'sign',
                    'args' => array()
                ),
                'signout' => array(
                    'controller' => 'sign',
                    'args' => array(
                        "action" => "signout"
                    )
                ),
                'common/download' => array(
                    'controller' => 'upload',
                    'args' => array(
                        "action" => "download"
                    )
                ),
            )
        );
    }

}

?>