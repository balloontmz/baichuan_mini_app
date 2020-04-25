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
?>
<div class='m-3'>
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="font-weight-bold">用户列表</h2>
        <div class="">
            <!--            <button type="button" class="layui-btn layui-btn-normal yd-link" data-url="/user/add">新增管理用户</button>-->
            <!--            <button type="button" class="layui-btn layui-btn-normal yd-toast" data-msg="" data-url="">导入</button>-->
            <!--            <button type="button" class="layui-btn layui-btn-normal" id="dao">导出</button>-->
        </div>
    </div>
    <blockquote class="layui-elem-quote news_search mt-1">
        <div class="layui-inline">
            <input class="layui-input" value="" id="user_name" name="query" autocomplete="off" placeholder="请输入"
                   style="width: 300px;">
        </div>
        <div class="layui-inline">
            <div class="layui-form">
                <select name="query" id="status" lay-verify="required" lay-search="">
                    <option value="">请选择</option>
                    <option value='1'>非禁用用户</option>
                    <option value='-1'>禁用用户</option>
                </select>
            </div>
        </div>
        <button class="layui-btn layui-btn-normal" type="button" onclick="reloadTable()"><i class="layui-icon"></i>搜索
        </button>
    </blockquote>
    <table class="layui-table" lay-data="{ url:'/user/index/index.json', page:true, id:'test'}" lay-filter="test">
        <thead>
        <tr>
            <th lay-data="{field:'name' }">姓名</th>
            <th lay-data="{field:'sex'}">性别</th>
            <th lay-data="{field:'phone' }">手机号</th>
            <th lay-data="{field:'status'}">是否被禁用</th>
            <th lay-data="{field:'login_date' }">最近登陆时间</th>
            <th lay-data="{field:'out_date'}">最近登出时间</th>
            <th lay-data="{fixed: 'right', align: 'center', width:120, toolbar: '#barDemo'}">操作</th>
        </tr>
        </thead>
    </table>
</div>
<script type="text/html" id="barDemo">
    <a class='layui-btn layui-btn-danger layui-btn-xs' lay-event="set_user">{{(d.status=='否')?'禁用':'解除禁用'}}</a>
</script>

<script>
    var table;
    layui.use(['form', 'table'], function () {
        table = layui.table;
        table.on('tool(test)', function (obj) {
            var id = obj.data.id;
            var status = obj.data.status == "否" ? 1 : -1;
            if (obj.event === 'set_user') {
                layer.confirm(obj.data.status == "否" ? "确定要禁用此用户吗？" : "确定要给此用户解除禁用吗？", function (index) {
                    $.ajax({
                        url: "/user/index/set_user",
                        method: "post",
                        data: {
                            user_id: id,
                            status: status
                        },
                        success: function (res) {
                            if (res.success) {
                                // obj.del();
                                YDJS.toast("设置成功", YDJS.ICON_SUCCESS, function () {
                                    table.reload("test", {});
                                });
                            } else {
                                YDJS.toast("设置失败", YDJS.ICON_WARN);
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
                user_name: $("#user_name").val(),
                status: $("#status").val()
            }
        });
    }

</script>

