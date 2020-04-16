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
$get_second_attribute = $this->get_data('get_second_attribute');
$get_first_attribute = First_Attribute_Model::find_all();
?>
<div class="m-3">
    <div class="ml-3 layui-card flex-grow-1 pt-3 mt-2 pb-2">
        <form class="layui-form" id="manager-form">
            <div class="layui-form-item">
                <label class="layui-form-label">名称：</label>
                <div class="layui-input-inline">
                    <input type="text" id="name" value="<?= $get_second_attribute->name ? $get_second_attribute->name : '' ?>"
                           lay-verify="" autocomplete="off" class="layui-input">
                    <input type="hidden" id="second_attribute_id"
                           value="<?= $get_second_attribute->id ? $get_second_attribute->id : '' ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">一级属性：</label>
                <div class="layui-input-inline">
                    <select name="product_type_id" id="first_attribute_id" lay-filter="river_select" lay-search="">
                        <option value="">请选择</option>
                        <?php foreach ($get_first_attribute as $item) { ?>
                            <?php if ($item->id == $get_second_attribute->first_attribute_id) { ?>
                                <option value="<?= $item->id ?>" selected><?= $item->name ?></option>
                            <?php } else { ?>
                                <option value="<?= $item->id ?>"><?= $item->name ?></option>
                            <?php }
                        } ?>
                    </select>
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
                url: "/attribute/second_attribute/add",
                // contentType: "application/json;charset=UTF-8",
                method: "post",
                data: {
                    name: $("#name").val(),
                    second_attribute_id: $("#second_attribute_id").val(),
                    first_attribute_id: $("#first_attribute_id").val()
                },
                success: function (res) {
                    if (res.success) {
                        YDJS.spin_clear("#save-btn");
                        YDJS.toast("<?= $get_second_attribute->id ? '修改成功' : '添加成功' ?>", YDJS.ICON_SUCCESS, function () {
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
