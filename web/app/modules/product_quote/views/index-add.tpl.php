<?php
namespace app\product_quote;

use app\attribute\First_Attribute_Model;
use app\attribute\Quote_Standard_Model;
use app\product\First_Product_Model;
use app\product\Product_Model;
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
$get_first_product = First_Product_Model::find_all();
$get_first_attribute = First_Attribute_Model::find_all();
$get_quote_standard = Quote_Standard_Model::find_all();
?>
<form class="layui-form">
    <div class="m-3">
        <fieldset class="layui-elem-field layui-field-title">
            <legend style="font-weight: bold;">新增报价</legend>
        </fieldset>
        <div class="card">
            <div class="card-header">请选择产品</div>
            <table class="layui-table m-0">
                <thead>
                <tr>
                    <th>一级产品名称</th>
                    <th>产品名称</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="p-0">
                        <select name="" class="layui-select first_product"
                                lay-filter="first_product" lay-search="">
                            <option value="">请选择</option>
                            <?php foreach ($get_first_product as $item) { ?>
                                <option value="<?= $item->id ?>"><?= $item->name ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td class="p-0">
                        <select name="product_id" class="layui-select product"
                                lay-filter="select-question" lay-search="">
                            <option value="">请选择</option>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="card mt-2">
            <div class="card-header">请选择属性</div>
            <table class="layui-table m-0">
                <colgroup>
                    <col>
                    <col>
                    <col width="80">
                </colgroup>
                <thead>
                <tr>
                    <th>一级属性</th>
                    <th>二级属性</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody class="atrlist">
                <tr>
                    <td class="p-0">
                        <select name="" class="layui-select first_attribute"
                                lay-filter="first_attribute" lay-search="">
                            <option value="">请选择</option>
                            <?php foreach ($get_first_attribute as $item) { ?>
                                <option value="<?= $item->id ?>"><?= $item->name ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td class="p-0">
                        <select name="second_attribute_id[]" class="layui-select second_attribute"
                                lay-filter="select-question" lay-search="">
                            <option value="">请选择</option>
                        </select>
                    </td>
                    <td class="p-1">
                        <button type="button" class="layui-btn layui-btn-xs layui-btn-danger removetr">删除</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="card mt-2">
            <div class="card-header">请选择报价标准</div>
            <table class="layui-table m-0">
                <colgroup>
                    <col width="140">
                    <col>
                    <col width="140">
                    <col width="80">
                </colgroup>
                <thead>
                <tr>
                    <th>报价标准</th>
                    <th>标准描述</th>
                    <th>价格</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody class="qtrlist">
                <tr>
                    <td class="p-0 text-center">
                        <select name="quote_standard_id[]" class="layui-select quote_standard"
                                lay-filter="quote_standard"
                                placeholder="请选择" lay-search="">
                            <option value="">请选择</option>
                            <?php foreach ($get_quote_standard as $item) { ?>
                                <option value="<?= $item->id ?>"><?= $item->name ?></option>
                            <?php } ?>
                        </select></td>
                    <td class="p-0 desc">
                    </td>
                    <td class="p-0 add-user-input">
                        <input type="text" value="" name="price[]"
                               lay-verify="" autocomplete="off" class="layui-input">
                    </td>
                    <td class="p-1">
                        <button type="button" class="layui-btn layui-btn-xs layui-btn-danger removetr">删除</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <button class="layui-btn layui-btn-default yd-form-submit yd-spin-btn mt-3"
                data-submit-cb="afterSubmit"
                data-in-top=1
                data-url="/product_quote/index/price"
                data-redirect=""
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
                url: '/product_quote/index/product',
                data: {
                    first_product_id: data.value
                },
                method: 'post',
                // dataType: 'json',
                success: function (res) {
                    var str = "<option value=\"\">请选择</option>";
                    if (res.data) {
                        var p_list = res.data;
                        for (var i = 0; i < p_list.length; i++) {
                            str += "<option value=" + p_list[i].id + ">" + p_list[i].name + "</option>"
                        }

                        $(".product").html(str);
                    }
                    YDJS.rebind();
                    layuiform.render();
                }
            });
        });
        layuiform.on('select(first_attribute)', function (data) {
            var td_2 = $(this).parents("tr").children("td").eq(1).children("select");
            console.log(td_2);
            $.ajax({
                url: '/product_quote/index/second_attribute',
                data: {
                    first_attribute_id: data.value
                },
                method: 'post',
                // dataType: 'json',
                success: function (res) {
                    var str = "<option value=\"\">请选择</option>";
                    if (res.data) {
                        var p_list = res.data;
                        for (var i = 0; i < p_list.length; i++) {
                            str += "<option value=" + p_list[i].id + ">" + p_list[i].name + "</option>"
                        }
                        td_2.html(str);
                    }
                    YDJS.rebind();
                    layuiform.render();
                }

            });
            var addinput = $(this).parents("tr").outerHTML();
            $(this).parents("tbody").append(addinput);
            YDJS.rebind();
            layuiform.render();
        });
        layuiform.on('select(quote_standard)', function (data) {
            var desc_str;
            var td_2 = $(this).parents("tr").children("td").eq(1);
            $.ajax({
                url: '/product_quote/index/quote_standard_desc',
                data: {
                    quote_standard_id: data.value
                },
                method: 'post',
                // dataType: 'json',
                success: function (res) {
                    if (res.data) {
                        desc_str = res.data;
                        td_2.text(desc_str);
                    }
                }

            });
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
