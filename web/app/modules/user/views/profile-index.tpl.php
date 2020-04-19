<?php
namespace app\user;

use \yangzie\YZE_Resource_Controller;
use \yangzie\YZE_Request;
use \yangzie\YZE_Redirect;
use \yangzie\YZE_Session_Context;
use \yangzie\YZE_RuntimeException;

/**
 * 视图的描述
 * @param type name optional
 *
 */

$data = $this->get_data('arg_name');
$store_user = $this->get_data('store_user');
?>
<div class='m-3'>
    <div class="layui-card">
        <div class="layui-card-body">
            <form class="layui-form" id="save-user" onsubmit="return false;">
                <input type="hidden" name="id" value="<?= $store_user->id ?>"/>
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        <fieldset class="layui-elem-field layui-field-title">
                            <legend class="font-weight-light">基本信息</legend>
                        </fieldset>

                        <div class="layui-form-item">
                            <label class="layui-form-label">店铺名称</label>
                            <div class="layui-input-inline">
                                <input type="text" name="store_name" value="<?= $store_user->store_name ?>"
                                       placeholder="请输入"
                                       autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">联系方式</label>
                            <div class="layui-input-inline">
                                <input type="text" name="store_phone" value="<?= $store_user->store_phone ?>"
                                       placeholder="请输入"
                                       autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">店铺地址</label>
                            <div class="layui-input-inline">
                                <input type="text" name="store_address" value="<?= $store_user->store_address ?>"
                                       placeholder="请输入"
                                       autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">登陆密码</label>
                            <div class="layui-input-inline">
                                <input type="text" name="password" id="password"
                                       value="<?= base64_decode($store_user->password) ?>"
                                       placeholder="请输入"
                                       autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button type="button" class="layui-btn yd-spin-btn" onclick="save_user(this)">保存
                                </button>
                            </div>
                        </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    layui.use(['form'], function () {
        var form = layui.form
    });

    function save_user(obj) {
        $.post("/user/profile/update", $("#save-user").serialize(), function (rst) {
            YDJS.spin_clear(obj);
            if (rst.success) {
                YDJS.toast("保存成功", YDJS.ICON_SUCCESS);
                if ($("#password").val() != <?=  base64_decode($store_user->password) ?>) {
                    window.location.href = "<?=SITE_URI?>signout";
                }
            } else {
                YDJS.toast(rst.msg || "保存失败", YDJS.ICON_ERROR);
            }
        });
    }
</script>