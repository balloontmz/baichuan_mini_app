<?php

use yangzie\YZE_Form;

$this->layout = "signin.layui";
?>
<style>
    /** layuiAdmin.std-v1.2.1 LPPL License By http://www.layui.com/admin/ */
    #LAY_app, body, html {
        height: 100%
    }

    .layui-layout-body {
        overflow: auto
    }

    #LAY-user-login, .layadmin-user-display-show {
        display: block !important
    }

    .layadmin-user-login {
        position: relative;
        left: 0;
        top: 0;
        padding: 110px 0;
        min-height: 100%;
        box-sizing: border-box
    }

    .layadmin-user-login-main {
        width: 375px;
        margin: 0 auto;
        box-sizing: border-box;
        background-color: rgba(255, 255, 255, 0.7);
        border-radius: 7px;
    }

    .layadmin-user-login-box {
        padding: 20px
    }

    .layadmin-user-login-header {
        text-align: center
    }

    .layadmin-user-login-header h2 {
        margin-bottom: 10px;
        font-weight: 300;
        font-size: 30px;
        color: #000
    }

    .layadmin-user-login-header p {
        font-weight: 300;
        color: #999
    }

    .layadmin-user-login-body .layui-form-item {
        position: relative
    }

    .layadmin-user-login-icon {
        position: absolute;
        left: 1px;
        top: 1px;
        width: 38px;
        line-height: 36px;
        text-align: center;
        color: #d2d2d2
    }

    .layadmin-user-login-body .layui-form-item .layui-input {
        padding-left: 38px
    }

    .layadmin-user-login-codeimg {
        max-height: 38px;
        width: 100%;
        cursor: pointer;
        box-sizing: border-box
    }

    .layadmin-user-login-other {
        position: relative;
        font-size: 0;
        line-height: 38px;
        padding-top: 20px
    }

    .layadmin-user-login-other > * {
        display: inline-block;
        vertical-align: middle;
        margin-right: 10px;
        font-size: 14px
    }

    .layadmin-user-login-other .layui-icon {
        position: relative;
        top: 2px;
        font-size: 26px
    }

    .layadmin-user-login-other a:hover {
        opacity: .8
    }

    .layadmin-user-jump-change {
        float: right
    }

    .layadmin-user-login-footer {
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        line-height: 30px;
        padding: 20px;
        text-align: center;
        box-sizing: border-box;
        color: rgba(0, 0, 0, .5)
    }

    .layadmin-user-login-footer span {
        padding: 0 5px
    }

    .layadmin-user-login-footer a {
        padding: 0 5px;
        color: rgba(0, 0, 0, .5)
    }

    .layadmin-user-login-footer a:hover {
        color: rgba(0, 0, 0, 1)
    }

    .layadmin-user-login-main[bgimg] {
        background-color: #fff;
        box-shadow: 0 0 5px rgba(0, 0, 0, .05)
    }

    .ladmin-user-login-theme {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        text-align: center
    }

    .ladmin-user-login-theme ul {
        display: inline-block;
        padding: 5px;
        background-color: #fff
    }

    .ladmin-user-login-theme ul li {
        display: inline-block;
        vertical-align: top;
        width: 64px;
        height: 43px;
        cursor: pointer;
        transition: all .3s;
        -webkit-transition: all .3s;
        background-color: #f2f2f2
    }

    .ladmin-user-login-theme ul li:hover {
        opacity: .9
    }

    @media screen and (max-width: 768px) {
        .layadmin-user-login {
            padding-top: 60px
        }

        .layadmin-user-login-main {
            width: 300px;
        }

        .layadmin-user-login-box {
            padding: 10px
        }
    }

    body {
        background-image: url(/img/timg.jpg);
        background-attachment: fixed;
        background-size: cover;
    }
</style>
<div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login">

    <div class="layadmin-user-login-main">
        <div class="layadmin-user-login-box layadmin-user-login-header">
            <h2><?= APPLICATION_NAME ?></h2>
        </div>
        <form method="post" onsubmit="doLogin()" id="login-form">
            <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-username"
                           for="LAY-user-login-username"></label>
                    <input type="text" autocomplete="off" name="username" id="LAY-user-login-username"
                           lay-verify="required" placeholder="店铺名称" class="layui-input">
                </div>
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-password"
                           for="LAY-user-login-password"></label>
                    <input type="password" autocomplete="off" name="password" id="LAY-user-login-password"
                           lay-verify="required" placeholder="密码" class="layui-input">
                </div>
                <div class="layui-form-item">
                    <button class="layui-btn layui-btn-fluid yd-spin-btn" onclick="doLogin()" id="save-btn"
                            type="submit">登 录
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="layui-trans layadmin-user-login-footer">

        <p>© <?= ORG_NAME ?> / 技术支持：韦乾来 18212446530
        </p>
    </div>

</div>
<script>
    function doLogin() {
        //请求登入接口
        $.post("/signin.json", $("#login-form").serialize(), function (rst) {
            YDJS.spin_clear("#save-btn");
            if (rst.success) {
                window.location.href = "/";
            } else {
                layer.msg(rst.msg || "登录失败,请检查用户名和密码", {
                    offset: '15px',
                    icon: 2
                });
            }
        });
    }
</script>