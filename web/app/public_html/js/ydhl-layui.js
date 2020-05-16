;
/**
 * layui的前端效果和行为实现
 * 该文件会对dom元素总按照易点互联的规范进行各种事件和效果的绑定, 对应动态load的html 内容,
 * 也需要加载该文件,否则页面加载后再次加载的html不会有效果
 * 该文件需要在ydhl.js之后加载
 * 
 * @author leeboo weiqianlai 
 */

/**
 * layui API实现
 */
(function () {
    if (YDJS.UI_FRAMEWORK_NAME != "layui")
        return;

    var icon_map = {"success": 1, "error": 2, "question": 3, "info": 6, "warn": 0, "none": -1, "": -1};
    function invoke_cb(cb, args) {
        if (typeof cb === "function") {
            cb.apply(this, args);
        } else if (window[cb]) {
            window[cb].apply(this, args);
        } else {
            YDJS.hide_dialog(args[0]);
        }
    }

    YDJS.hide_dialog = function (dialogId) {
        var index = $("#" + dialogId).parents(".layui-layer").attr("times"); //通过id 找到layui的index
        layer.close(index);
    };
    YDJS.update_loading =function (dialog_id, content){

    };
    YDJS.loading = function (msg, loadingId, overlay) {
        loadingId = loadingId || YDJS.uuid(16, 16, "loading-");

        if (overlay && $(overlay).length) {
//            console.log($(overlay).offset());
//            console.log($(overlay).width()+"-"+$(overlay).height());
            var offset = [$(overlay).height() / 2 + $(overlay).offset().top, $(overlay).width() / 2 + $(overlay).offset().left];
//            console.log(offset);
            layer.open({
                type: 3,
                title: false,
                closeBtn: false,
                icon: -1,
                content: msg,
                id: loadingId,
                area: [$(overlay).width(), $(overlay).height()],
                offset: offset,
                time: 0,
                fixed: false,
                shade: 0,
                shadeClose: false
            });
        } else {
            layer.msg(msg, {
                icon: 16,
                id: loadingId,
                time: 0,
                shade: 0.3,
                shadeClose: false
            });
        }
        return loadingId;
    }
    YDJS.toast = function (msg, icon, cb, backdrop, delay, position) {
        icon = icon || "info";
        backdrop = backdrop != "normal" ? "none" : backdrop;
        delay = delay || 3000;
        position = position || "center";

        if (backdrop === 'normal') {
            var backdrop_value = 0.3;
        } else {
            backdrop_value = 0.01;
        }

        var position_value;
        var map = {
            'top': 't',
            'left': 'l',
            'bottom': 'b',
            'right': 'r',
            'center': 'auto',
            'left&top': 'lt',
            'left&bottom': 'lb',
            'right&top': 'rt',
            'right&bottom': 'rb'
        };
        position_value = map[position];
        var dialogid = YDJS.uuid(16, 16, "toast-");
        layer.msg(msg, {
            icon: icon_map[icon],
            time: delay,
            id: dialogid,
            shade: backdrop_value,
            shadeClose: true,
            btn: 0,
            zIndex: 19891014,
            end: function () {
                cb ? invoke_cb(cb, [dialogid]) : null
            },
            offset: position_value
        });
    };
    YDJS.dialog = function (url, content, title, size, backdrop, buttonAndCallbacks, dialog_id) {
        dialog_id = dialog_id || YDJS.uuid(16, 16, "dialog-"); //加载框的id
        size = size || "normal"; //定义对话框的大小:full 全屏 larget 大 normal 普通 small 小


        var flag = false;
        var backdrop_value = 0;
        if (backdrop === "static") {
            backdrop_value = 0.3;
        } else if (backdrop === "normal") {
            backdrop_value = 0.3;
            flag = true;
        } else {
            flag = true;
        }

        var area = {
            "full": ['100%', '100%'],
            "large": ['80%', '80%'],
            "normal": ['50%', '80%'],
            "small": ['30%', '50%']
        };
        var args = {
            type: url ? 2 : 1, //有地址就用iframe,没有地址就用页面层
            content: url || content,
            title: title,
            id: dialog_id,
            btnAlign: 'c',
            shade: backdrop_value,
            shadeClose: flag,
            maxmin: true, //开启最大化最小化按钮
            area: area[size],
            success: function (layero, index) {
                if (!url)
                    return;
                var title = layer.getChildFrame('title', index);
                layer.title(title.text(), index);
            }
        };

        if (!buttonAndCallbacks) {
            buttonAndCallbacks = [
                {
                    label: "确定",
                    cb: function () {
                        YDJS.hide_dialog(dialog_id);
                    }
                }
            ];
        }
        if (buttonAndCallbacks.length > 0) {
            args.btn = [];
            var okBtn = buttonAndCallbacks[0];

            args.btn.push(okBtn.label);
            args.yes = function (index, layer) {
                invoke_cb(okBtn.cb, [dialog_id]);
            };

            for (var i = 1; i < buttonAndCallbacks.length; i++) {
                var btn = buttonAndCallbacks[i];
                args.btn.push(buttonAndCallbacks[i].label);
                args.btn2 = function (index, layer) {
                    invoke_cb(btn.cb, [dialog_id]);
                };
            }
        }
        layer.open(args);
        return dialog_id;
    };
    YDJS.alert = function (content, title, icon, buttonAndCallbacks, dialog_id) {
        icon = icon || "info";
        title = title || "提示";
        dialog_id = dialog_id || YDJS.uuid(16, 16, "alert-"); //加载框的id
        var args = {
            type: 0,
            icon: icon_map[icon],
            content: content||" ",
            title: title,
            id: dialog_id,
            btnAlign: 'r',
            btn: [],
            shade: 0.3,
            shadeClose: false
        };

        if (!buttonAndCallbacks) {
            buttonAndCallbacks = [
                {
                    label: "确定",
                    cb: function () {
                        YDJS.hide_dialog(dialog_id);
                    }
                }
            ];
        }

        if (buttonAndCallbacks.length > 0) {
            var okBtn = buttonAndCallbacks[0];

            args.btn.push(okBtn.label);
            args.yes = function (index, layer) {
                invoke_cb(okBtn.cb, [dialog_id]);
            };

            for (var i = 1; i < buttonAndCallbacks.length; i++) {
                var btn = buttonAndCallbacks[i];
                args.btn.push(buttonAndCallbacks[i].label);
                args.btn2 = function (index, layer) {
                    invoke_cb(btn.cb, [dialog_id]);
                };
            }
        }
        layer.open(args);
        return dialog_id;
    };
    YDJS.confirm = function (content, title, yes_cb, no_cb, yes_label, no_label, dialog_id) {
        var icon = "question";
        title = title || "提示";
        dialog_id = dialog_id || YDJS.uuid(16, 16, "confirm-"); //加载框的id
        var args = {
            type: 0,
            icon: icon_map[icon],
            content: content,
            title: title,
            id: dialog_id,
            btnAlign: 'r',
            btn: [yes_label || "确定", no_label || "取消"],
            shade: 0.3,
            shadeClose: false
        };

        args.yes = function (index, layer) {
            if($("#"+dialog_id+"yes").attr("clicking"))return;//放置重复点击
            invoke_cb(yes_cb, [dialog_id]);

            $("#"+dialog_id+"yes").removeAttr("clicking");
        };
        args.btn2 = args.cancel = function (index, layer) {
            invoke_cb(no_cb, [dialog_id]);
        };
        layer.open(args);
        return dialog_id;
    };
    YDJS.prompt = function (title, defaultValue, type, cb, dialog_id) {
        var type_map = {input: 0, password: 1, textarea: 2};
        if (!type_map[type]) {
            type = "input";
        }

        dialog_id = dialog_id || YDJS.uuid(16, 16, "prompt-"); //加载框的id
        layer.prompt({
            id: dialog_id,
            formType: type_map[type],
            value: defaultValue||"",
            success: function(layero, index){
                setTimeout(function () {
                    $(layero).find("input[type=password]").val("");
                },1000);
            },
            title: title
        }, function (value, index, elem) {
            invoke_cb(cb, [dialog_id, value]);
        });
        return dialog_id;
    };

    YDJS.rebind = function () {
        var laydate = layui.laydate;
        var laypage = layui.laypage;

        $(".yd-date-picker").each(function (idx, el) {

            if ($(el).attr("lay-key"))
                return;// 避免重复绑定
            var range = $(el).attr("data-range");
            var format = $(el).attr("data-format");//yyyy-MM-dd hh:mm
            var type = $(el).attr("data-type");
            var min = $(el).attr("data-picker-min");
            var max = $(el).attr("data-picker-max");
            var args = {
                elem: el
                , trigger: "click"
                ,range: range||false //或 range: '~' 来自定义分割字符
                , done: function (value, date, endDate) {
//                    console.log(value); //得到日期生成的值，如：2017-08-18
//                    console.log(date); //得到日期时间对象：{year: 2017, month: 8, date: 18, hours: 0, minutes: 0, seconds: 0}
//                    console.log(endDate); //得结束的日期时间对象，开启范围选择（range: true）才会返回。对象成员同上。
                    $(el).change();//触发input的change时间
                }
            };
            if (format){
                args["format"] = format;
            }
            if (type){
                args["type"] = type;
            }
            if (min){
                args["min"] = min;
            }
            if (max){
                args["max"] = max;
            }
            laydate.render(args);

            $(el).attr(":data-date-picker", "date-picker");
        });

        /**
         * 不在使用，用yd-date-picker
         */
        $(".yd-time-picker").each(function (idx, el) {
            if ($(el).attr("lay-key"))
                return;// 避免重复绑定
            laydate.render({
                elem: el
                , type: 'time'
                , trigger: "click"
                , format: "HH:mm"
                , done: function (value, date, endDate) {
//                    console.log(value); //得到日期生成的值，如：2017-08-18
//                    console.log(date); //得到日期时间对象：{year: 2017, month: 8, date: 18, hours: 0, minutes: 0, seconds: 0}
//                    console.log(endDate); //得结束的日期时间对象，开启范围选择（range: true）才会返回。对象成员同上。
                    $(el).change();//触发input的change时间
                }
            });

            $(el).attr(":data-time-picker", "time-picker");
        });


        $(".yd-pagination").each(function (idx, el) {
            var container = $(el).attr("data-container-id");
            laypage.render({
                elem: el,
                count: $(el).attr("data-total"),
                limit: $(el).attr("data-pagesize"),
                curr: $(el).attr("data-currpage"),
                jump: function (obj, first) {
                    //obj包含了当前分页的所有参数，比如：
//                    console.log(obj.curr); //得到当前页，以便向服务端请求对应页的数据。
//                    console.log(obj.limit); //得到每页显示的条数
//debugger;
                    //首次不执行
                    if (!first) {
                        //do something
                        var url = ydhlib_AddParamsInUrl($(el).attr("data-url") || window.location.href, {page: obj.curr});
                        if ($("#" + container).length > 0) {
                            $.get(url, function (html) {
                                $("#" + container).replaceWith(html);
                            });
                        } else {
                            window.location.href = url;
                        }
                    }
                }
            });
        });


        $(".yd-tooltip").hover(function () {
            var content = $(this).attr("data-tooltip") || $(this).text();
            var background = $(this).attr("data-background") || "#000000";
            window["subtips"] = layer.tips(content, this, {tips: [1, background], time: 30000});
        }, function () {
            layer.close(window["subtips"]);
        });

        //富文本编辑器
        $(".yd-editor").each(function (idx, el) {
            if (!window["ydeditor"])
                window["ydeditor"] = {};

            var editorid = $(el).attr("id") || YDJS.uuid(16, 16, "editor-");


            if (window["ydeditor"][editorid])
                return;//已经实例化过了


            $(el).prop("id", editorid);

            window.onbeforeunload = function () {
                if (CKEDITOR.instances[editorid] && CKEDITOR.instances[editorid].checkDirty()) {
                    return "页面上包含尚未保存的表单内容。如果离开此页，未保存的内容将被丢弃。";/*You have made changes on this page that you have not yet confirmed. If you navigate away from this page you will lose your unsaved changes*/
                }
            };

            var browseUrl = $(el).attr("data-browser-url");
            var uploadUrl = $(el).attr("data-upload-url");
            var config = $(el).attr("data-config-url");
            var height = $(el).attr("data-height");
            var isInline = $(el).attr("data-isinline") == 1;


            var setting = {
                customConfig: config,
                height: height || 300
            };

            if (browseUrl) {
                setting[ "filebrowserBrowseUrl" ] = browseUrl;
            }

            if (uploadUrl) {
                setting[ "filebrowserUploadUrl" ] = uploadUrl;
            }

            if (isInline) {
                window["ydeditor"][editorid] = CKEDITOR.inline(editorid, setting);
            } else {
                window["ydeditor"][editorid] = CKEDITOR.replace(editorid, setting);
            }

        });

        //拖动排序
        $(".yd-sortable").each(function (idx, el) {
            var stopCb = $(el).attr("data-sortable-stop");
            var helperCb = $(el).attr("data-sortable-helper");
            var placeholder = $(el).attr("data-sortable-placeholder");
            var handle = $(el).attr("data-sortable-handler");

            $(el).sortable({
                helper: window[helperCb] || "original",
                stop: window[stopCb] || null,
                handle: handle || false,
                placeholder: placeholder || ""
            });
        });

        //自动弹出的提示
        $(".yd-auto-tip").each(function(index, el){
            var content = $(el).attr("data-tip-content");
            layer.tips(content, el,{tips:1,time: 5000});
        });


        yd_tree_select_render();
        yd_tree_render();
        yd_dynamic_select_render();
        yd_upload_render();


    };
}());


// 动态绑定
$(function () {
    function invoke_cb(cb, args) {
        if (typeof cb === "function") {
            cb.apply(this, args);
        } else if (window[cb]) {
            window[cb].apply(this, args);
        }
    }

    YDJS.event_bind("click", "[yd-link-ignore]", function (e) {
        if (e && e.stopPropagation)
            e.stopPropagation();
        else
            window.event.cancelBubble = true;

    });

    //yd-auto-tip
    YDJS.event_bind("click", ".yd-remove-self", function () {
        var data_remove_from = $(this).attr("data-remove-from");
        if (data_remove_from) {
            $(this).parents(data_remove_from).remove();
        } else {
            $(this).remove();
        }
    });


    //popper 弹出框处理 leeboo 20190917
    YDJS.event_bind("mouseover", ".yd-popper-trigger", function () {
        if (window["ydpoper"]) {
            $(window["ydpoper"].popper).hide();
            window["ydpoper"].destroy();
        }
        if (!$(this).attr("data-popper-target"))
            return;
        var reference = this;
        var popper = document.querySelector('#' + $(this).attr("data-popper-target"));
        $(popper).show();
        var popperInstance = new Popper(reference, popper, {
            placement: $(this).attr("data-popper-position"),
            positionFixed: true,
            modifiers: {
                offset: {offset: "0px,-5px"},
                preventOverflow: {enabled: false}
            }
        });
        window["ydpoper"] = popperInstance;
        $(window["ydpoper"].popper).on("mouseout", function (event) {
            if ($(event.toElement).parents(".yd-popper-content").length > 0)
                return;
            $(window["ydpoper"].popper).hide();
            window["ydpoper"].destroy();
        });
    });

    //点击切换目标元素的指定样式
    YDJS.event_bind("click", ".yd-toggle-class", function () {
        var target = $(this).attr("data-target");
        var cb = $(this).attr("data-toggle-callback");
        if (!target)
            return;
        $(target).toggleClass($(this).attr("data-class"));
        invoke_cb(cb, [$(this), $(target).hasClass($(this).attr("data-class"))]);
    });

    //鼠标悬浮时切换目标元素的指定样式
    YDJS.event_bind("mouseover", ".yd-mousetoggle-class", function () {
        var target = $(this).attr("data-target");
        if (!target)
            return;
        $(target).addClass($(this).attr("data-class"));
    });

    YDJS.event_bind("mouseout", ".yd-mousetoggle-class", function () {
        var target = $(this).attr("data-target");
        if (!target)
            return;
        $(target).removeClass($(this).attr("data-class"));
    });

    //yd-spin-button提交按钮
    YDJS.event_bind("click", ".yd-spin-btn", function () {
        var text = YDJS.urlencode( $(this).html() );
        var icon = '<span class="layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop"></span>';
        $(this).prop("disabled", true);
        $(this).attr("data-btn-text", text);
        $(this).html(icon);
        return true;
    });

    //yd-date-picker 分页
    layui.use(['laydate', 'laypage', "table"], function () {
        YDJS.rebind();
    });

    YDJS.event_bind("click", ".yd-image-preview,.yd-image-preview img", function () {
        var url = $(this).attr("src");
        if ( ! url)return;
        var intop = $(this).attr("data-in-top");
        var _layer = intop ? top.layer : layer;
        _layer.open({
            type: 2,
            title:false,
            area: ['80%', '80%'],
            content: url
        });
    });

    YDJS.event_bind("click", ".yd-link", function () {
        var url = $(this).attr("data-url");
        var intop = $(this).attr("data-in-top");
        var _window = intop ? top.window : window;
        if ($(this).attr("data-target") == "_blank") {
            _window.open(url);
        } else {
            _window.location.href = url;
        }
    });



    //点击后显示提示框
    YDJS.event_bind("click", ".yd-toast", function () {
        var msg = $(this).attr("data-msg");
        var icon = $(this).attr("data-icon");
        var backdrop = $(this).attr("data-backdrop");
        var delay = parseInt($(this).attr("data-delay")) || 0;
        var position = $(this).attr("data-position");
        YDJS.toast(msg, icon, backdrop, delay, position);
    });

    //yd-loading加载框
    YDJS.event_bind("click", ".yd-loading", function () {
        var msg = $(this).attr("data-msg");
        var loadingId = $(this).attr("data-loading-id");

        YDJS.loading(msg, loadingId);
    });

    //打开对话框
    YDJS.event_bind("click", ".yd-dialog", function () {
        var url = $(this).attr("data-url");   //要打开的网址
        var content = $(this).attr("data-content");     //内容
        var content_ref = $(this).attr("data-content-ref");     //内容dom的选择器
        var title = $(this).attr("data-title");     //标题
        var primary_button_label = $(this).attr("data-primary-button-label");   //主要按钮
        var secondary_button_label = $(this).attr("data-secondary-button-label");    //次要按钮
        var primary_button_click = $(this).attr("data-primary-button-click");//主要按钮的文字点击的事件名，只传入dialogId
        var secondary_button_click = $(this).attr("data-secondary-button-click");//次要按钮的文字点击的时间名，只传入dialogId
        var dialog_id = $(this).attr("data-dialog-id")
        var size = $(this).attr("data-size");
        var backdrop = $(this).attr("data-backdrop");
        var intop = $(this).attr("data-in-top");
        var buttonAndCallbacks = [];

        if (primary_button_label) {
            buttonAndCallbacks.push({"label": primary_button_label, "cb": primary_button_click});
        }
        if (secondary_button_label) {
            buttonAndCallbacks.push({"label": secondary_button_label, "cb": secondary_button_click});
        }

        if(content_ref){
            content = $(content_ref).html();
        }

        var _ydjs = intop ? top.YDJS : YDJS;
        _ydjs.dialog(url, content, title, size, backdrop, buttonAndCallbacks, dialog_id);

    });


    //确认对话框
    YDJS.event_bind("click", ".yd-confirm", function () {
        var content = $(this).attr("data-content");     //内容   
        var title = $(this).attr("data-title") || "提示";     //标题
        var primary_button_label = $(this).attr("data-primary-button-label");   //主要按钮
        var secondary_button_label = $(this).attr("data-secondary-button-label");    //次要按钮
        var primary_button_click = $(this).attr("data-primary-button-click");//主要按钮的文字点击的事件名，只传入dialogId
        var secondary_button_click = $(this).attr("data-secondary-button-click");//次要按钮的文字点击的时间名，只传入dialogId
        var dialog_id = $(this).attr("data-dialog-id");

        YDJS.confirm(content, title, primary_button_click, secondary_button_click, primary_button_label, secondary_button_label, dialog_id);

    });

    //确认对话框, 确认后ajax请求某网址，
    YDJS.event_bind("click", ".yd-confirm-post", function () {
        var btn = this;
        var content = $(this).attr("data-content");     //内容
        var title = $(this).attr("data-title") || "提示";     //标题
        var post_url = $(this).attr("data-url");   //请求api
        var redirect = $(this).attr("data-redirect");   //接口成功后重定向地址,可以是一个具体的网址，也可以reload，表示重载页面, 也可以是一个村子的函数
        var intop = $(this).attr("data-in-top");   //接口成功后重定向地址
        var args = $(this).attr("data-args");   //请求参数
        var primary_button_label = $(this).attr("data-primary-button-label");   //主要按钮
        var secondary_button_label = $(this).attr("data-secondary-button-label");    //次要按钮
        var dialog_id = $(this).attr("data-dialog-id") || YDJS.uuid(16, 16, "confirm-post-");
        args = args ? JSON.parse(args) : {};

        var _ydjs = intop ? top.YDJS : YDJS;
        _ydjs.confirm(content, title, function(){
            _ydjs.spin_clear($(btn));
            $.post(post_url,  args , function (rst) {
                if(rst.success){
                    if(redirect){
                        var _window = intop ? top.window : window;
                        if(redirect=="reload"){
                            _window.location.reload();
                        }else if(_window[redirect]){
                            invoke_cb(redirect, [dialog_id]);
                        }else{
                            _window.location.href = redirect;
                        }
                    }else{
                        top.YDJS.toast("处理成功", YDJS.ICON_SUCCESS);
                    }
                }else {
                    top.YDJS.toast(rst.msg || "接口请求识别", YDJS.ICON_ERROR);
                }
            })
        },null, primary_button_label,secondary_button_label, dialog_id);

    });

    //输入提示对话框
    YDJS.event_bind("click", ".yd-prompt", function () {
        var title = $(this).attr("data-title") || "提示";     //标题
        var defaultValue = $(this).attr("data-default-value");   //默认内容
        var type = $(this).attr("data-type");    //input password textarea
        var cb = $(this).attr("data-prompt-cb");//确认回调
        var dialog_id = $(this).attr("data-dialog-id");

        YDJS.prompt(title, defaultValue, type, cb, dialog_id);
    });

    //yd-remove-self
    YDJS.event_bind("click", ".yd-remove-self", function () {
        var data_remove_from = $(this).attr("data-remove-from");
        if (data_remove_from) {
            $(this).parents(data_remove_from).remove();
        } else {
            $(this).remove();
        }
    });


    //表单提交
    YDJS.event_bind("click", ".yd-form-submit", function () {
        var url = $(this).attr("data-url");
        var redirect = $(this).attr("data-redirect");
        var cb = $(this).attr("data-submit-cb");
        var before = $(this).attr("data-before-submit");
        var msg = $(this).attr("data-confirm-msg");
        var intop = $(this).attr("data-in-top");   //接口成功后重定向地址
        var _ydjs = intop ? top.YDJS : YDJS;
        var _window = intop ? top.window : window;
        var self = this;

        if(before){
            invoke_cb(before,[]);
        }

        var post = function(){
            $.post(url, $(self).parents("form").serialize(), function (rst) {
                _ydjs.spin_clear(self);

                if (rst.success) {
                    _ydjs.toast("操作成功", YDJS.ICON_SUCCESS, function () {
                        if (redirect) {
                            if(redirect=="reload"){
                                _window.location.reload();
                            }else{
                                _window.location.href = redirect;
                            }
                        }else if(cb){
                            invoke_cb(cb, [rst]);
                        }
                    });
                } else {
                    _ydjs.toast(rst.msg || "保存失败", YDJS.ICON_ERROR);
                }
            }, "json");
        };

        if (msg){
            top.YDJS.confirm(msg, $(self).text(),function () {
                post();
            },function () {
                _ydjs.spin_clear(self);
            });
        }else{
            post();
        }

    });

});
