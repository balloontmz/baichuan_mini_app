<?php

/**
 * 定义一些系统回调，需要定义的回调有：
 * YZE_FILTER_GET_USER_ARO_NAME: 返回用户的aro，默认为/
 * YZE_FILTER_YZE_EXCEPTION: 扬子鳄处理过程中出现的异常回调
 *
 * @author leeboo
 *        
 */
namespace app;

use app\common\Rv_User_Model;
use yangzie\YZE_Hook;
use yangzie\YZE_Redirect;
use yangzie\YZE_Request;
use const YZE_FILTER_GET_USER_ARO_NAME;
use const YZE_FILTER_YZE_EXCEPTION;
use const YZE_HOOK_GET_LOGIN_USER;
use const YZE_HOOK_SET_LOGIN_USER;

YZE_Hook::add_hook ( YZE_HOOK_GET_LOGIN_USER, function  ( $datas ) {
	$loginUser = @$_SESSION [ 'admin' ];
	if( ! $loginUser){
	    $request = YZE_Request::get_instance();
	    // 是否是微信登录 下面HTTP头需要跟前端商量
	    if($_SERVER [ "HTTP_YDHL_ASSET_WX_USER_OPENID" ]){
            $user = Rv_User_Model::find_by_openid($_SERVER [ "HTTP_YDHL_ASSET_WX_USER_OPENID" ]);
            return $user;
        }
	    return null;
	}
	
	return $loginUser;
} );

YZE_Hook::add_hook ( YZE_HOOK_SET_LOGIN_USER, function  ( $data ) {
	$_SESSION [ 'admin' ] = $data;
} );

YZE_Hook::add_hook ( YZE_FILTER_GET_USER_ARO_NAME, function  ( $data ) {
	if ( !@$_SESSION [ 'admin' ] )return "";
    return "/".$_SESSION [ 'admin' ]->type;
} );


YZE_Hook::add_hook(YZE_FILTER_YZE_EXCEPTION, function ($datas){
    //如果array("exception"=>$e, "controller"=>$controller, "response"=>$response)
    // 把signin替换成自己的登录url

    $request = YZE_Request::get_instance();
    if(! is_a($datas['exception'], "\\yangzie\\YZE_Need_Signin_Exception")) return $datas;
    
    $datas['response'] = new YZE_Redirect("/signin", $datas['controller']);
    if($request->isInWeixin()){
        $datas['response'] = new YZE_Redirect("/signin", $datas['controller']);
    }
    return $datas;
});
?>