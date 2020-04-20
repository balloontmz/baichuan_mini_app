<?php
namespace app\user;

use app\product\First_Product_Model;
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
?>
<form class="layui-form">
    <div class="m-3">
        <fieldset class="layui-elem-field layui-field-title">
            <legend class="font-weight-light">店铺配置</legend>
        </fieldset>
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
                data-url="/product_quote/index/add"
                data-redirect="/product_quote/index/index"
                type="button">
            <i class="iconfont icon-baocun"></i> 保存
        </button>
    </div>
</form>
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
                    console.log(res.tr);
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
        $('.atrlist').delegate(".removetr", 'click', function () {
            if ($(".atrlist tr").length <= 1) {
                layer.alert("至少保留一行");
            } else {
                $(this).parents("tr").remove();
            }
        });

        //删除行
        $('.qtrlist').delegate(".removetr", 'click', function () {
            if ($(".qtrlist tr").length <= 1) {
                layer.alert("至少保留一行");
            } else {
                $(this).parents("tr").remove();
            }
        });
    });
</script>