<?php
namespace app\product_quote;

use app\attribute\First_Attribute_Model;
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
$product_price_str = implode(",", $this->get_data('product_price_ids'));
$get_first_product = First_Product_Model::find_all();

?>
<div class='m-3'>
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="font-weight-bold">产品报价列表</h2>
        <div class="">
            <button type="button" class="layui-btn layui-btn-normal yd-link" data-url="/product_quote/index/add">新增
            </button>
        </div>
    </div>
    <blockquote class="layui-elem-quote news_search mt-1">
        <form class="layui-form">
            <input id="product_price_str" type="hidden" value="<?= $product_price_str ?>">
            <div class="layui-inline">
                <select name="" class="layui-select first_product"
                        lay-filter="first_product" lay-search="">
                    <option value="">请选择</option>
                    <?php foreach ($get_first_product as $item) { ?>
                        <option value="<?= $item->id ?>"><?= $item->name ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="layui-inline">
                <select name="product_id" id="query" class="layui-select product"
                        lay-filter="select-question" lay-search="">
                    <option value="">请选择</option>
                </select>
            </div>
            <button class="layui-btn layui-btn-normal" type="button" onclick="reloadTable()"><i class="layui-icon"></i>搜索
            </button>
            <button class="layui-btn layui-btn-warm" type="button" id="super_edit"><i
                        class="layui-icon layui-icon-release" style="font-size: 15px; color: white;"></i>一键改价
            </button>
        </form>

    </blockquote>
    <table class="layui-table" lay-data="{ url:'/product_quote/index/index.json', page:true, id:'test'}"
           lay-filter="test">
        <thead>
        <tr>
            <th lay-data="{field:'name'}">产品名称</th>
            <th lay-data="{field:'second_attribute_names'}">属性</th>
            <th lay-data="{field:'quote_standard'}">报价</th>
            <th lay-data="{fixed: 'right', align: 'center',width:180, toolbar: '#barDemo'}">操作</th>
        </tr>
        </thead>
    </table>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-normal layui-btn-xs yd-dialog"
       data-url="/product_quote/index/add?id={{d.id}}" lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-normal layui-btn-xs yd-dialog"
       data-url="/product_quote/index/edit_quote?id={{d.id}}" lay-event="edit">报价</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs " lay-event="del">删除</a>
</script>
<script>
    var table;
    layui.use(['form', 'table'], function () {
        table = layui.table;
        layuiform = layui.form;
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

        table.on('tool(test)', function (obj) {
            var id = obj.data.id;
            if (obj.event === 'del') {
                layer.confirm("确定要该分类吗？", function (index) {
                    $.ajax({
                        url: "/product/first_product/remove",
                        method: "post",
                        data: {
                            id: id
                        },
                        success: function (res) {
                            if (res.success) {
                                // obj.del();
                                YDJS.toast("删除成功", YDJS.ICON_SUCCESS, function () {
                                    window.location.reload();
                                });
                            } else {
                                YDJS.toast("删除失败", YDJS.ICON_WARN);
                            }
                        },
                        error: function () {
                            YDJS.toast("系统错误", YDJS.ICON_ERROR);
                        }
                    });
                    layer.close(index);
                });
            }
        });
    });

    function reloadTable() {
        table.reload("test", {
            where: {
                query: $("#query").val()
            }
        });
    }

    $("#super_edit").click(function () {
        
    })

</script>