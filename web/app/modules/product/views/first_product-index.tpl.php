<?php
namespace app\product;
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
        <h2 class="font-weight-bold">产品一级分类列表</h2>
        <div class="">
            <button type="button" class="layui-btn layui-btn-normal yd-dialog" data-url="/product/first_product/add">新增
            </button>
        </div>
    </div>
    <blockquote class="layui-elem-quote news_search mt-1">
        <form class="layui-form">
            <div class="layui-inline">
                <input class="layui-input" value="" id="search_query" name="query" autocomplete="off"
                       placeholder="请输入一级产品名称"
                       style="width: 300px;">
            </div>
            <button class="layui-btn layui-btn-normal" type="button" onclick="reloadTable()"><i class="layui-icon"></i>搜索
            </button>
        </form>
    </blockquote>
    <table class="layui-table" lay-data="{url:'/product/first_product/index.json', page:true, id:'test'}"
           lay-filter="test">
        <thead>
        <tr>
            <th lay-data="{field:'name'}">名称</th>
            <th lay-data="{field:'product_type_name'}">上级分类名称</th>
            <th lay-data="{fixed: 'right', align: 'center',width:120, toolbar: '#barDemo'}">操作</th>
        </tr>
        </thead>
    </table>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-normal layui-btn-xs yd-dialog"
       data-url="/product/first_product/add?first_product_id={{d.id}}" lay-event="edit">修改</a>
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
                query: $("#search_query").val()
            }
        });
    }

</script>