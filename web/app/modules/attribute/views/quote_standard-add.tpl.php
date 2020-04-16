<?php
namespace app\attribute;

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
$get_quote_standard = $this->get_data('get_quote_standard');
?>
<div class="m-3">
    <div class="ml-3 layui-card flex-grow-1 pt-3 mt-2 pb-2">
        <form class="layui-form" id="manager-form">
            <div class="layui-form-item">
                <label class="layui-form-label">名称：</label>
                <div class="layui-input-inline">
                    <input type="text" id="name"
                           value="<?= $get_quote_standard->name ? $get_quote_standard->name : '' ?>"
                           lay-verify="" autocomplete="off" class="layui-input">
                    <input type="hidden" id="quote_standard_id"
                           value="<?= $get_quote_standard->id ? $get_quote_standard->id : '' ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">标准描述：</label>
                <div class="layui-input-inline">
                    <textarea type="text" id="desc" lay-verify="" autocomplete="off" class="layui-textarea"><?= $get_quote_standard->desc ? $get_quote_standard->desc : '' ?></textarea>
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
                url: "/attribute/quote_standard/add",
                // contentType: "application/json;charset=UTF-8",
                method: "post",
                data: {
                    name: $("#name").val(),
                    desc: $("#desc").val(),
                    quote_standard_id: $("#quote_standard_id").val(),
                },
                success: function (res) {
                    if (res.success) {
                        YDJS.spin_clear("#save-btn");
                        YDJS.toast("<?= $get_quote_standard->id ? '修改成功' : '添加成功' ?>", YDJS.ICON_SUCCESS, function () {
                            window.parent.location.reload();
                            window.location.reload();
                        });
                    } else {
                        YDJS.spin_clear("#save-btn");
                        YDJS.toast("添加失败", YDJS.ICON_WARN);
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
