<?php

namespace yangzie;

/**
 * LAYUI 前端框架布局,整体框架布局
 */
$sidebar = YZE_Hook::do_hook(YD_LAYOUT_SIDEBAR_VIEW, $this);
$loginUser = YZE_Hook::do_hook(YZE_HOOK_GET_LOGIN_USER);
$noticeCount = YZE_Hook::do_hook(YD_NOTICE_GET_NUM, ['user_id' => $loginUser->id]);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php echo $this->get_data("yze_page_title") ?> － <?php echo APPLICATION_NAME ?></title>
    <link rel="stylesheet" href="/layui_2_5_5/css/layui.css">
    <link rel="stylesheet" href="/layui_2_5_5/css/admin.css" media="all">
    <link rel="stylesheet" href="/js/jstree/themes/default/style.min.css" media="all">
    <script src="/layui_2_5_5/layui.all.js"></script>
    <script src="/layui_2_5_5/layui.js"></script>
    <script src="/js/ckeditor4/ckeditor.js"></script>
    <?php
    yze_module_css_bundle();
    yze_css_bundle("ww");
    yze_js_bundle("jquery,yangzie,bootstrap,layui");
    ?>
    <script src="/js/jstree/jstree.min.js"></script>
</head>
<body class="layui-layout-body" layadmin-themealias="ocean-header">

<div id="LAY_app">
    <div class="layui-layout layui-layout-admin">
        <div class="layui-header d-print-none">
            <!-- 头部区域 -->
            <ul class="layui-nav layui-layout-left">
                <li class="layui-nav-item layadmin-flexible" lay-unselect>
                    <a href="javascript:;" class="yd-toggle-class" data-class="layadmin-side-shrink"
                       data-target="#LAY_app" title="侧边伸缩">
                        <i class="layui-icon layui-icon-shrink-right yd-toggle-class"
                           data-class="layui-icon-spread-left" data-target="#LAY_app_flexible"
                           id="LAY_app_flexible"></i>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    今天是<?php echo date("Y年m月d日") . " 星期" . getDay(date("N"));
                    $lunar = new \Lunar();
                    $lunar_date = $lunar->convertSolarToLunar(date("Y"), date("m"), date("d"));
                    echo " 农历 " . $lunar_date[3] . "[" . $lunar_date[6] . "]年" . $lunar_date[1] . $lunar_date[2];//将阳历转换为阴历
                    ?>
                </li>
            </ul>
            <ul class="layui-nav layui-layout-right">
                <li class="layui-nav-item" lay-unselect>
                    <a href="/notice">
                        <i class="layui-icon layui-icon-notice"></i>
                        <?php if ($noticeCount) { ?><span class="layui-badge-dot"></span><?php } ?>
                    </a>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;">
                        <?= $loginUser->store_name ?>
                    </a>
                    <dl class="layui-nav-child">
                        <dd><a href="/user/profile?uuid=<?= $loginUser->uuid ?>">基本资料</a></dd>
                        <hr>
                        <dd><a href="/signout">退出</a></dd>
                    </dl>
                </li>

                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;">&nbsp;</a>
                </li>
            </ul>
        </div>

        <!-- 侧边菜单 -->
        <div class="layui-side layui-side-menu d-print-none">
            <div class="layui-side-scroll">
                <div class="layui-logo" lay-href="/">
                    <span><?= APPLICATION_NAME ?></span>
                </div>

                <?php
                if ($sidebar) {
                    echo $sidebar;
                } else {
                    echo "<ul class='layui-nav layui-nav-tree'>"
                        . "<li >请在hooks/layout.php 中注册 YD_LAYOUT_SIDEBAR_VIEW hook,输出侧边栏菜单</li></ul>";
                }
                ?>

            </div>
        </div>


        <!-- 主体内容 -->
        <div class="layui-body bg-white" id="LAY_app_body" style="top:50px !important">
            <div class="layui-tab layui-tab-brief" lay-filter="demoTitle"><?php echo $this->content_of_view() ?></div>
        </div>
        <!-- 辅助元素，一般用于移动设备下遮罩 -->
        <div class="layadmin-body-shade" layadmin-event="shade"></div>
    </div>
</div>
<?php YZE_Hook::do_hook(HOOK_JS_CRON_TASK) ?>
<script>
    //JavaScript代码区域
    layui.use('element', function () {
        var element = layui.element;
        element.render();
    });
</script>
<?php yze_module_js_bundle(); ?>
</body>
</html>