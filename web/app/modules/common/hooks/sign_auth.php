<?php

namespace app;

use app\user\User_Model;
use yangzie\YZE_Hook;
use yangzie\YZE_Redirect;
use yangzie\YZE_Request;
use const YD_COMMON_SIGNIN_POST;
use const YD_COMMON_SIGNIN_REDIRECT;
use const YD_COMMON_SIGNOUT;

define ( "YD_ASSET_WX_USER_LOGIN", "YD_ASSET_WX_USER_LOGIN" );

// 返回登录界面
YZE_Hook::add_hook ( YD_COMMON_SIGNIN_POST, function () {
    $request = \yangzie\YZE_Request::get_instance();
    $admin = User_Model::login($request->get_from_post("username"), $request->get_from_post("password"));
    if( $admin){
        YZE_Hook::do_hook(YZE_HOOK_SET_LOGIN_USER, $admin);
        return $admin;
    }else{
        $wx_data = YZE_Hook::do_hook(YD_ASSET_WX_USER_LOGIN);
        if($wx_data AND $wx_data['userInfo']['is_enabled']==1) return $wx_data;
        if($wx_data AND $wx_data['userInfo']['is_enabled']!=1) {
            throw new \yangzie\YZE_FatalException("小程序登录失败,请联系管理员处理");
        }
        else {
            throw new \yangzie\YZE_FatalException("登录失败,请检查登录账号信息");
        }
    }
} );

// 返回登录后链接
YZE_Hook::add_hook ( YD_COMMON_SIGNIN_REDIRECT, function () {
    return "/";
} );

// 登出处理,默认注销登录
YZE_Hook::add_hook ( YD_COMMON_SIGNOUT, function ( $controller ) {
    session_destroy ();
    return new YZE_Redirect ( "/signin", $controller );
} );

YZE_Hook::add_hook(YD_COMMON_SIGNIN_VIEW, function(){
    if(UI_FRAMEWORK_NAME=="layui"){
        $signin_view = new \yangzie\YZE_Simple_View(YZE_APP_VIEWS_INC."layui.signin", [], \yangzie\YZE_Request::get_instance()->controller());
        return $signin_view;
    }
});

YZE_Hook::add_hook(YD_ASSET_WX_USER_LOGIN,function (){
    $data = array(
    );

    // 1. 微信登录
    $request = YZE_Request::get_instance();
    $code = $request->get_from_post("wx_code");
    $name = $request->get_from_post( 'name' );
    $gender = $request->get_from_post( 'gender' );
    $avatar = $request->get_from_post( 'avatar' );
    $phone = $request->get_from_post('cellphone');
    $appid = YD_WECHAT_APPLET_APPID;
    $secret = YD_WECHAT_APPLET_SECRET;
    $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$code}&grant_type=authorization_code";
    $http = new \YDHttp();
    $res = json_decode ( $http->get ( $url ), true );
    if ( ! $res || ! $res [ "openid" ] ) return null;
    //$data [ "wx_user" ] = $res;
    $data["openid"]=$res [ "openid" ];
    $data["session_key"] = $res["session_key"];
    //var_dump($res["session_key"]);


    // 2. 用户查询
    $user = Rv_User_Model::find_by_openid($data["openid"]);
    if(!$user){
        $user =  new Rv_User_Model();
        $user->set("uuid",uuid());
    }
    if ( $name ){
        $user->set(Rv_User_Model::F_WX_NAME, $name );
    }
    if ( $gender ){
        $user->set(Rv_User_Model::F_GENDER, $gender );
    }
    if ( $avatar ){
        $user->set(Rv_User_Model::F_WX_HEAD_URL, $avatar );
    }
//    if( $phone AND $phone!="" AND $phone!=null){
//        $user->set(Rv_User_Model::F_CELLPHONE, $phone);
//    }
    $user->set(Rv_User_Model::F_IS_ENABLED, is_numeric($user->is_enabled)?$user->is_enabled:1);
    $user->set(Rv_User_Model::F_LAST_LOGIN_TIME,date('Y-m-d H:i:s',time()));
    $user->set(Rv_User_Model::F_WX_OPEN_ID, $data["openid"] )->save();

    $manager = Rv_Admin_Model::from()
        ->where("is_deleted=0 AND type='manager' AND cellphone=:phone")
        ->getSingle([":phone"=>$user->cellphone]);

    $data["openid"] = $user->wx_open_id;
    $data["nickName"] = $user->wx_name;
    $data["avatarUrl"] = $user->wx_head_url;
    $data["gender"] = $user->gender;
    $data["name"] = $user->name;
    $data["cellphone"] = $user->cellphone;
    $data["is_enabled"] = $user->is_enabled;
    $data["black_list"] = $user->black_list;
    if($manager) $data["type"]="manager";
    else $data["type"]="user";

    return array("userInfo"=>$data);
});
?>