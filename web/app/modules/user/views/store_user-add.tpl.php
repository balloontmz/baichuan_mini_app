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
$store_user_obj = $this->get_data('store_user_obj');
?>
<div class="m-3">
    <div class="ml-3 layui-card flex-grow-1 pt-3 mt-2 pb-2">
        <form class="layui-form" id="manager-form">
            <div class="layui-form-item">
                <label class="layui-form-label">店铺名称：</label>
                <div class="layui-input-inline">
                    <input type="text" id="store_name" value="<?= $store_user_obj->store_name ?>"
                           lay-verify="" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">联系方式：</label>
                <div class="layui-input-inline">
                    <input type="text" id="store_phone" value="<?= $store_user_obj->store_phone ?>"
                           lay-verify="" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">店铺地址：</label>
                <div class="layui-input-inline">
                    <input type="text" id="store_address" value="<?= $store_user_obj->store_address ?>"
                           lay-verify="" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">店铺AppId：</label>
                <div class="layui-input-inline">
                    <input type="text" id="wx_appid" value="<?= $store_user_obj->wx_appid ?>"
                           lay-verify="" autocomplete="off" class="layui-input">
                    <input type="hidden" id="store_user_id" value="<?= $store_user_obj->id ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button type="button" class="layui-btn yd-spin-btn" id="save-btn" lay-filter="demo1">保存</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(function () {
        $('#save-btn').click(function () {
            $.ajax({
                url: "/user/store_user/add",
                // contentType: "application/json;charset=UTF-8",
                method: "post",
                data: {
                    store_user_id:$("#store_user_id").val(),
                    store_name: $("#store_name").val(),
                    store_phone: $("#store_phone").val(),
                    store_address: $("#store_address").val(),
                    wx_appid: $("#wx_appid").val()
                },
                success: function (res) {
                    if (res.success) {
                        YDJS.spin_clear("#save-btn");
                        YDJS.toast("<?= $store_user_obj->id ? '修改成功' : '添加成功' ?>", YDJS.ICON_SUCCESS, function () {
                            window.parent.location.reload();
                            window.location.reload();
                        });
                    } else {
                        YDJS.spin_clear("#save-btn");
                        YDJS.toast(res.msg, YDJS.ICON_WARN);
                    }
                },
                //请求失败，包含具体的错误信息
                error: function (e) {
                    YDJS.spin_clear("#save-btn");
                    YDJS.toast("系统错误", YDJS.ICON_ERROR);
                }
            })
        })
    })
</script>
