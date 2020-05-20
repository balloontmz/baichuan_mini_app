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
$get_pics = $this->get_data('swiper_pics');
$sign_pic = $this->get_data('sign_pic');
?>
<style>
    .upimg img {
        width: 300px;
    }
</style>
<div class="m-3">
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="font-weight-bold">系统配置</h2>
        <div class="">
            <button type="button" class="layui-btn layui-btn-normal yd-link" data-url="/setting/index/add">新增
            </button>
        </div>
    </div>
    <div class="ml-3 layui-card flex-grow-1 pt-3 mt-2 pb-2">
        <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
            <ul class="layui-tab-title">
                <li class="layui-this">轮播配置</li>
                <li>启动页配置</li>

            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
<!--                    <div class="layui-upload">-->
                        <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                            预览图：
                            <?php foreach ($get_pics as $item) { ?>
                                <div class="layui-upload-list upimg" id="demo2">
                                    <img src="<?= UPLOAD_SITE_URI . $item->pic_url ?>" class="layui-upload-img">
                                </div>
                                <button type="button" class="layui-btn yd-spin-btn" onclick="delImg(<?= $item->id ?>)"
                                        lay-filter="demo1">
                                    删除
                                </button>
                            <?php } ?>
                        </blockquote>
<!--                    </div>-->
                </div>
                <div class="layui-tab-item">
                    <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                        预览图：
                            <div class="layui-upload-list upimg" id="demo2">
                                <img src="<?= UPLOAD_SITE_URI . $sign_pic->pic_url ?>" class="layui-upload-img">
                            </div>
                            <button type="button" class="layui-btn yd-spin-btn" onclick="delImg(<?= $sign_pic->id ?>)"
                                    lay-filter="demo1">
                                删除
                            </button>
                    </blockquote>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function delImg(id) {
        $.ajax({
            url: "/setting/index/remove",
            method: "post",
            data: {
                id:id
            },
            success: function (ret) {
                if (ret && ret.success) {
                    YDJS.spin_clear("#save-btn");
                    YDJS.toast("删除成功", YDJS.ICON_SUCCESS, function () {
                        window.location.reload();
                    });
                } else {
                    YDJS.spin_clear("#save-btn");
                    YDJS.toast(ret.msg, YDJS.ICON_WARN);
                }
            },
            error: function () {
                YDJS.spin_clear("#save-btn");
                YDJS.toast("系统错误", YDJS.ICON_ERROR);
            }
        });
    }
</script>