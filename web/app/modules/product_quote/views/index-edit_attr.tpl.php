<?php
namespace app\product_quote;

use app\attribute\First_Attribute_Model;
use app\attribute\Quote_Standard_Model;
use app\attribute\Second_Attribute_Model;
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
$attribute_ids = $this->get_data('attribute_ids');
$get_first_attribute = First_Attribute_Model::find_all();
$get_second_attribute = Second_Attribute_Model::find_all();
$product_price_id = $this->get_data('product_price_id');
?>
<form class="layui-form">
    <input name="product_price_id" type="hidden" value="<?= $product_price_id ?>"/>
    <div class="m-3">
        <div class="card">
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
                <?php for ($i = 0; $i < count($attribute_ids); $i++) { ?>
                    <tr>
                        <td class="p-0">
                            <select name="" class="layui-select first_attribute"
                                    lay-filter="first_attribute" lay-search="">
                                <option value="">请选择</option>
                                <?php foreach ($get_first_attribute as $item) { ?>
                                    <?php if ($item->id == Second_Attribute_Model::find_by_id($attribute_ids[$i])->first_attribute_id) { ?>
                                        <option value="<?= $item->id ?>" selected><?= $item->name ?></option>
                                    <?php } else { ?>
                                        <option value="<?= $item->id ?>"><?= $item->name ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </td>
                        <td class="p-0">
                            <select name="second_attribute_id[]" class="layui-select second_attribute"
                                    lay-filter="select-question" lay-search="">
                                <option value="">请选择</option>
                                <?php foreach ($get_second_attribute as $item) { ?>
                                    <?php if ($item->id == $attribute_ids[$i]) { ?>
                                        <option value="<?= $item->id ?>" selected><?= trim($item->name) ?></option>
                                    <?php } else { ?>
                                        <option value="<?= $item->id ?>"><?= trim($item->name) ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </td>
                        <td class="p-1">
                            <button type="button" class="layui-btn layui-btn-xs layui-btn-danger removetr">删除</button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <button class="layui-btn layui-btn-default yd-form-submit yd-spin-btn mt-3"
                data-submit-cb="afterSubmit"
                data-in-top=1
                data-url="/product_quote/index/edit_attr"
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

