<?php

use yangzie\YZE_Hook;
use yangzie\YZE_Request;

YZE_Hook::add_hook(YD_LAYOUT_SIDEBAR_VIEW, function ($layout) {
    if (UI_FRAMEWORK_NAME == "layui") {
        $loginUser = YZE_Hook::do_hook(YZE_HOOK_GET_LOGIN_USER);
        $sidemenu = $layout->get_data("sidemenu") ?: YZE_Request::get_instance()->module();
        ob_start();
        ?>
        <div class="layui-nav layui-nav-tree">
            <ul class="" lay-shrink="all" id="LAY-system-side-menu" lay-filter="layadmin-system-side-menu">
                <li class="layui-nav-item  <?= $sidemenu == "product_quote" ? " layui-nav-itemed layui-this" : "" ?>">
                    <a href="javascript:;" class="yd-popper-trigger" id="menu-item-quote" data-popper-position="left"
                       data-popper-target="quote">
                        <i class="iconfont icon-gongzuotai"></i>
                        <cite>机型报价</cite>
                    </a>
                    <div class="yd-popper-content popper yd-mousetoggle-class" data-target="menu-item-quote"
                         data-class="menu-item-hover" id="quote" style="display: none">
                        <div class="text-left d-flex flex-column justify-content-start">
                            <strong class="menu-header">产品管理</strong>
                            <div class="d-flex align-items-center">
                                <a href="/product_quote/index/" class="link menu-item width100">报价列表</a>
                                <a href="/product_quote/index/add/" class="link menu-item width100">新增报价</a>
                            </div>
                        </div>
                        <div class="popper__arrow"></div>
                    </div>
                </li>
                <li class="layui-nav-item  <?= $sidemenu == "user" ? " layui-nav-itemed layui-this" : "" ?>">
                    <a href="/user/index" class="yd-popper-trigger" id="menu-item-user" data-popper-position="left"
                       data-popper-target="user">
                        <i class="iconfont icon-gongzuotai"></i>
                        <cite>用户管理</cite>
                    </a>
                </li>
                <li class="layui-nav-item <?= $sidemenu == "product" ? "layui-nav-itemed layui-this" : "" ?>">
                    <a href="javascript:;" class="yd-popper-trigger" id="menu-item-product" data-popper-position="left"
                       data-popper-target="product">
                        <i class="iconfont icon-gongzuotai"></i>
                        <cite>产品管理</cite>
                    </a>
                    <div class="yd-popper-content popper yd-mousetoggle-class" data-target="menu-item-product"
                         data-class="menu-item-hover" id="product" style="display: none">
                        <div class="text-left d-flex flex-column justify-content-start">
                            <strong class="menu-header">产品管理</strong>
                            <div class="d-flex align-items-center">
                                <a href="/product/product_type/" class="link menu-item width100">产品总分类</a>
                                <a href="/product/first_product/" class="link menu-item width100">产品一级分类</a>
                                <a href="/product/index/" class="link menu-item width100">产品列表</a>
                            </div>
                        </div>
                        <div class="popper__arrow"></div>
                    </div>
                </li>
                <li class="layui-nav-item <?= $sidemenu == "attribute" ? "layui-nav-itemed layui-this" : "" ?>">
                    <a href="javascript:;" class="yd-popper-trigger" id="menu-item-attribute"
                       data-popper-target="attribute" data-popper-position="left">
                        <i class="iconfont icon-gongchang"></i>
                        <cite>属性管理</cite>
                    </a>
                    <div class="yd-popper-content popper yd-mousetoggle-class" data-target="menu-item-attribute"
                         data-class="menu-item-hover" id="attribute" style="display: none">
                        <div class="text-left d-flex flex-column justify-content-start">
                            <strong class="menu-header">属性管理</strong>
                            <div class="d-flex align-items-center">
                                <a href="/attribute/first_attribute" class="link menu-item width100">一级属性</a>
                                <a href="/attribute/second_attribute" class="link menu-item width100">二级属性</a>
                                <a href="/attribute/quote_standard" class="link menu-item width100">报价标准</a>
                            </div>
                        </div>
                        <div class="popper__arrow"></div>
                    </div>
                </li>
                <li class="layui-nav-item <?= $sidemenu == "order" ? "layui-nav-itemed layui-this" : "" ?>">
                    <a href="/order/index" class="yd-popper-trigger" id="menu-item-news"
                       data-popper-position="left">
                        <i class="iconfont icon-gongchang"></i>
                        <cite>订单管理</cite>
                    </a>
                </li>
                <li class="layui-nav-item <?= $sidemenu == "setting" ? "layui-nav-itemed layui-this" : "" ?>">
                    <a href="/setting/index" class="yd-popper-trigger" id="menu-item-news"
                       data-popper-position="left">
                        <i class="iconfont icon-gongchang"></i>
                        <cite>轮播设置</cite>
                    </a>
                </li>
            </ul>
            <!--菜单部分}}-->
            <!--            <div class="m-3 text-muted text-xs copyright">-->
            <!--                &copy;--><?//= ORG_NAME ?><!-- 版本--><?//= VERSION ?><!-- <br/>-->
            <!--                贵州省易点互联信息技术有限公司 0851-83832371-->
            <!--            </div>-->
        </div>
        <?php
        return ob_get_clean();
    }
});
