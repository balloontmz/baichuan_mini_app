<?php
namespace app\user;

use app\product\First_Product_Model;
use yangzie\YZE_Hook;
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
$store_user = Store_User_Model::get_normal();
$loginUser = YZE_Hook::do_hook(YZE_HOOK_GET_LOGIN_USER);
?>
<form class="layui-form">
    <div class="m-3">
        <fieldset class="layui-elem-field layui-field-title">
            <legend class="font-weight-light">店铺配置</legend>
        </fieldset>
        <?php if ($loginUser->type == "admin") { ?>
            <div class="card">
                <div class="card-header">请选择店铺</div>
                <table class="layui-table m-0">
                    <thead>
                    <tr>
                        <th>店铺名称</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="p-0">
                            <select name="store_user_id" id="store_user_id" class="layui-select store_user"
                                    lay-filter="first_product" lay-search="">
                                <option value="">请选择</option>
                                <?php foreach ($store_user as $item) { ?>
                                    <option value="<?= $item->id ?>"><?= $item->store_name ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <input class="layui-input" name="store_user_id" id="store_user_id" value="<?= $loginUser->id ?>"
                   type="hidden">
        <?php } ?>

        <div class="card mt-2">
            <div class="card-header">请选择产品</div>
            <table class="layui-table m-0">
                <colgroup>
                    <col>
                    <col>
                    <col>
                    <col width="80">
                </colgroup>
                <thead>
                <tr>
                    <th>产品名称</th>
                    <th>加价或减价</th>
                    <th>加价或减价的值</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody id="fprlist">
                </tbody>
            </table>
        </div>
        <button class="layui-btn layui-btn-default yd-form-submit yd-spin-btn mt-3"
                data-submit-cb="afterSubmit"
                data-in-top=1
                data-url="/user/store_option/add"
                id="save_data"
                type="button" style="display: none">
            <i class="iconfont icon-baocun"></i> 保存
        </button>
    </div>
</form>
<div class="layui-input-block m-3">
    <button class="layui-btn layui-btn-normal w-100" id="edit">
        <i class="layui-icon layui-icon-edit"></i> 编 辑
    </button>
</div>
<!--data-redirect="/user/store_option/index"-->
<script type="text/javascript">
    var layuiform, layuitable;
    layui.use(['form', 'laydate', 'table'], function () {
        layuiform = layui.form;
        layuitable = layui.table;
        layuiform.render();

        layuiform.on('select(first_product)', function (data) {
            $.ajax({
                url: '/user/store_option/index.json',
                data: {
                    store_user_id: data.value
                },
                method: 'get',
                dataType: "JSON",
                success: function (res) {
                    $("#fprlist").html(res.tr);
                    YDJS.rebind();
                    layuiform.render();
                }
            });
        });

        layuiform.on('select(first_attribute)', function (data) {
            var addinput = $(this).parents("tr").outerHTML();
            $(this).parents("tbody").append(addinput);
            YDJS.rebind();
            layuiform.render();
        });
    });
    $(function () {
        //删除行
        <?php if($loginUser->type == "normal"){ ?>
            $.ajax({
                url: '/user/store_option/index.json',
                data: {
                    store_user_id: <?=$loginUser->id?>
                },
                method: 'get',
                dataType: "JSON",
                success: function (res) {
                    $("#fprlist").html(res.tr);
                    YDJS.rebind();
                    layuiform.render();
                }
            });
        <?php } ?>
        $('.atrlist').delegate(".removetr", 'click', function () {
            if ($(".atrlist tr").length <= 1) {
                layer.alert("至少保留一行");
            } else {
                $(this).parents("tr").remove();
            }
        });

        //删除行
        $('#fprlist').delegate(".removetr", 'click', function () {
            if ($(".qtrlist tr").length <= 1) {
                layer.alert("至少保留一行");
            } else {
                $(this).parents("tr").remove();
            }
        });
    });
    $("#edit").click(function () {
        $("#save_data").css("display", "");
        $("#edit").css("display", "none")
    })
    $("#save_data").click(function () {
        $("#edit").css("display", "");
        $("#save_data").css("display", "none")
    })
</script>


