<?php

namespace yangzie;
//LAYUI 前端框架布局,整体框架布局

?>
<!DOCTYPE html>
<html class="bg-white">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title><?php echo $this->get_data("yze_page_title") ?></title>
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
    <body>
        <?php echo $this->content_of_view() ?>
        <?php yze_module_js_bundle(); ?>
    </body>
</html>