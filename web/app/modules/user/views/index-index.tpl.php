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
            <input class="layui-input" value="" id="search_query" name="query" autocomplete="off" placeholder="请输入"
                   style="width: 300px;">
        </div>
        <div class="layui-inline">
            <div class="layui-form">
                <select name="query" id="select_enable" lay-verify="required" lay-search="">
                    <option value="">请选择</option>
                    <option value='enable'>非禁用用户</option>
                    <option value='disable'>禁用用户</option>
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
            <th lay-data="{field:'status'}">是否禁用</th>
            <th lay-data="{field:'login_date' }">最近登陆时间</th>
            <th lay-data="{field:'out_date'}">最近登出时间</th>
            <th lay-data="{fixed: 'right', align: 'center', toolbar: '#barDemo'}">操作</th>
        </tr>
        </thead>
    </table>
</div>
<script type="text/html" id="barDemo">
    <a class='layui-btn layui-btn-xs' lay-event="ban">{{(d.is_enabled=='是')?'禁用':'启用'}}</a>
</script>