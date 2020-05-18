<?php

namespace app;

use app\user\Store_User_Model;
use app\user\User_Model;
use yangzie\YZE_Hook;
use yangzie\YZE_Redirect;
use yangzie\YZE_Request;
use const YD_COMMON_SIGNIN_POST;
use const YD_COMMON_SIGNIN_REDIRECT;
use const YD_COMMON_SIGNOUT;

define("YD_ASSET_WX_USER_LOGIN", "YD_ASSET_WX_USER_LOGIN");

// 返回登录界面
YZE_Hook::add_hook(YD_COMMON_SIGNIN_POST, function () {
    $request = \yangzie\YZE_Request::get_instance();
    $admin = Store_User_Model::login($request->get_from_post("username"), base64_encode($request->get_from_post("password")));
    if ($admin) {
        YZE_Hook::do_hook(YZE_HOOK_SET_LOGIN_USER, $admin);
        return $admin;
    } else {
        $wx_data = YZE_Hook::do_hook(YD_ASSET_WX_USER_LOGIN);
//        var_dump($wx_data);
        if ($wx_data AND $wx_data['userInfo']['openid']) return $wx_data;
        if ($wx_data AND $wx_data['userInfo']['status'] == -1) {
            throw new \yangzie\YZE_FatalException("小程序登录失败,您的账号存在异常！");
        } else {
            throw new \yangzie\YZE_FatalException("登录失败,请检查登录账号信息");
        }
    }
});

// 返回登录后链接
YZE_Hook::add_hook(YD_COMMON_SIGNIN_REDIRECT, function () {
    return "/";
});

// 登出处理,默认注销登录
YZE_Hook::add_hook(YD_COMMON_SIGNOUT, function ($controller) {
    session_destroy();
    return new YZE_Redirect ("/signin", $controller);
});

YZE_Hook::add_hook(YD_COMMON_SIGNIN_VIEW, function () {
    if (UI_FRAMEWORK_NAME == "layui") {
        $signin_view = new \yangzie\YZE_Simple_View(YZE_APP_VIEWS_INC . "layui.signin", [], \yangzie\YZE_Request::get_instance()->controller());
        return $signin_view;
    }
});

YZE_Hook::add_hook(YD_ASSET_WX_USER_LOGIN, function () {
    $data = array();

    // 1. 微信登录
    $request = YZE_Request::get_instance();
    $code = $request->get_from_post("wx_code");
    $name = $request->get_from_post('nickName');
    $gender = $request->get_from_post('gender');
    $avatar = $request->get_from_post('avatar');
    $wx_appid = $request->get_from_post('wx_appid');
    $cellphone = $request->get_from_post('cellphone');
    $secret = Store_User_Model::get_by_wx_appid($wx_appid);
    $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$wx_appid}&secret={$secret->app_secret}&js_code={$code}&grant_type=authorization_code";
    $http = new \YDHttp();
    $res = json_decode($http->get($url), true);
    if (!$res || !$res ["openid"]) return null;
    $data["openid"] = $res ["openid"];
    $data["session_key"] = $res["session_key"];
    // 2. 用户查询
    $user = User_Model::find_by_openid($data["openid"]);
    if (!$user) {       //新用户
        $user = new User_Model();
        $user->set(User_Model::F_UUID, uuid());
        $user->set(User_Model::F_STATUS, "1");
        $user->set(User_Model::F_LOGIN_DATE, date('Y-m-d H:i:s', time()));
        $user->set(User_Model::F_WX_APPID, $wx_appid);
        $user->set(User_Model::F_OPENID, $data["openid"])->save();
    } else {      //已经注册过的用户
        if ($name) {
            $save_data = [];
            $save_data['name'] = $name;
            $save_data['gender'] = $gender;
            $save_data['wx_avatar'] = $avatar;
            $save_data['login_date'] = date('Y-m-d H:i:s', time());
            User_Model::update_by_id($user->id, $save_data);
            $data["avatarUrl"] = $avatar;
            $data["gender"] = $gender;
            $data["nickName"] = $name;
            $data["cellphone"] = $user->cellphone;
            $data["status"] = $user->status;
        } else {
            $data["avatarUrl"] = $user->wx_avatar;
            $data["gender"] = $user->gender;
            $data["nickName"] = $user->name;
            $data["cellphone"] = $user->cellphone;
            $data["status"] = $user->status;
        }
    }


    return array("userInfo" => $data);
});
?>