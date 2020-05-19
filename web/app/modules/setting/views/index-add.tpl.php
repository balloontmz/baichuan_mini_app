<?php
namespace app\setting;
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
<style>
    .upimg img {
        width: 300px;
    }
</style>
<div class="m-3">
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="font-weight-bold">新增轮播图</h2>
    </div>
    <div class="ml-3 layui-card flex-grow-1 pt-3 mt-2 pb-2">
        <form class="layui-form" id="landscape_form">
            <div class="layui-form-item">
                <label class="layui-form-label">上传图片：</label>
                <div class="layui-input-block">
                    <button type="button" class="layui-btn" id="test2"><i class="layui-icon"></i>选择图片</button>
                    <div class="layui-upload">
                        <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                            预览图：
                            <div class="layui-upload-list upimg" id="demo2"></div>
                        </blockquote>
                    </div>
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
    var pic_path = [];
    layui.use('upload', function () {
        var $ = layui.jquery
            , upload = layui.upload;
        //多图片上传
        upload.render({
            elem: '#test2'
            , url: '/common/upload'
            , multiple: true
            , before: function (obj) {
                //预读本地文件示例，不支持ie8
                obj.preview(function (index, file, result) {
                    $('#demo2').append('<img src="' + result + '" alt="' + file.name + '" class="layui-upload-img">')
                });
            }
            , done: function (res) {
                //上传完毕
                pic_path.push(res.data.path);
            }
        });
    });
    $(function () {
        $('#save-btn').click(function () {
            var send_data = $('#landscape_form').serializeArray();
            send_data.push({name:'pics',value:pic_path});
            // console.log(send_data);
            $.ajax({
                url : "/setting/index/add",
                method : "post",
                data : send_data,
                success : function (ret) {
                    if(ret && ret.success){
                        YDJS.spin_clear("#save-btn");
                        YDJS.toast("添加成功",YDJS.ICON_SUCCESS,function(){
                            window.location.reload();
                        });
                    }else{
                        YDJS.spin_clear("#save-btn");
                        YDJS.toast(ret.msg,YDJS.ICON_WARN);
                    }
                },
                error : function () {
                    YDJS.spin_clear("#save-btn");
                    YDJS.toast("系统错误",YDJS.ICON_ERROR);
                }
            });
        })
    });
</script>