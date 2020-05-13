<?php
namespace app\order;

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
?>
<div class='m-3'>
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="font-weight-bold">订单列表</h2>
    </div>
    <blockquote class="layui-elem-quote news_search mt-1">
        <form class="layui-form">
            <div class="layui-inline">
                <select name="" id="status" class="layui-select"
                        lay-filter="select_edit_price">
                    <option value="">请选择</option>
                    <option value="unshipped">待发货</option>
                    <option value="shipped">已发货</option>
                    <option value="looking">待验机</option>
                    <option value="collect">待收款</option>
                    <option value="finish">已完成</option>
                    <option value="reback">待退回</option>
                    <option value="returned">已退回</option>
                </select>
            </div>
            <button class="layui-btn layui-btn-normal" type="button" onclick="reloadTable()"><i class="layui-icon"></i>搜索
            </button>
        </form>
    </blockquote>
    <table class="layui-table" lay-data="{ url:'/order/index/index.json', page:true, id:'test'}"
           lay-filter="test">
        <thead>
        <tr>
            <th lay-data="{field:'order_time'}">下单时间</th>
            <th lay-data="{field:'desc'}">订单描述</th>
            <th lay-data="{field:'count'}">订单数量</th>
            <th lay-data="{field:'price'}">订单金额</th>
            <th lay-data="{field:'consignor'}">发货人</th>
            <th lay-data="{field:'address'}">发货地址</th>
            <th lay-data="{field:'express_company'}">快递公司</th>
            <th lay-data="{field:'express_num'}">快递单号</th>
            <th lay-data="{field:'status'}">订单状态</th>
            <th lay-data="{fixed: 'right', align: 'center',width:180, toolbar: '#barDemo'}">操作</th>
        </tr>
        </thead>
    </table>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-normal layui-btn-xs yd-dialog"
       data-url="/product/index/add?product_id={{d.id}}" lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs " lay-event="del">删除</a>
</script>
<script>
    var table;
    layui.use(['form', 'table'], function () {
        table = layui.table;

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
                status: $("#status").val()
            }
        });
    }
</script>