;
/**
 * 该全局属性定义项目使用的是那个前端库
 * @type String
 */

if (!this.YDJS) {
    this.YDJS = {
        /**
         * 使用的前端库
         */
        UI_FRAMEWORK_NAME: "layui",

        /**
         * 成功
         */
        ICON_SUCCESS: "success",
        /**
         * 失败
         */
        ICON_ERROR: "error",
        /**
         * 一般信息
         */
        ICON_INFO: "info",
        /**
         * 警告
         */
        ICON_WARN: "warn",
        /**
         * 询问
         */
        ICON_QUESTION: "question",
        /**
         * 或者不定义，就表示没有图标
         */
        ICON_NONE: "none",

        POSITION_TOP: "top",
        POSITION_LEFT: "left",
        POSITION_BOTTOM: "bottom",
        POSITION_RIGHT: "right",
        POSITION_CENTER: "center",
        POSITION_LEFT_TOP: "left&top",
        POSITION_LEFT_BOTTOM: "left&bottom",
        POSITION_RIGHT_TOP: "right&top",
        POSITION_RIGHT_BOTTOM: "right_bottom",

        SIZE_FULL: "full",
        SIZE_LARGE: "large",
        SIZE_NORMAL: "normal",
        SIZE_SMALL: "small",

        BACKDROP_NONE: "none",
        BACKDROP_NORMAL: "normal",
        BACKDROP_STATIC: "static",

        /**
         * 恢复yd-spin-btn的效果
         * 
         * @param {type} selector
         * @returns {undefined}
         */
        spin_clear: function (selector) {
            var originText = $(selector).attr("data-btn-text");
            $(selector).prop("disabled", false);
            if( ! originText)return
            $(selector).html(YDJS.urldecode(  originText ).replace(/\+/ig," "));
        },
        /**
         * 
         * @param {type} len 长度
         * @param {type} radix 进制
         * @returns {String}
         */
        uuid: function (len, radix, prefix) {
            var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.split('');
            radix = radix || chars.length;
            var uuid = [];
            if (len) {
                // Compact form
                for (i = 0; i < len; i++)
                    uuid[i] = chars[0 | Math.random() * radix];
            } else {
                // rfc4122, version 4 form
                var r;
                // rfc4122 requires these characters
                uuid[8] = uuid[13] = uuid[18] = uuid[23] = '-';
                uuid[14] = '4';
                // Fill in random data. At i==19 set the high bits of clock sequence as
                // per rfc4122, sec. 4.1.5
                for (i = 0; i < 36; i++) {
                    if (!uuid[i]) {
                        r = 0 | Math.random() * 16;
                        uuid[i] = chars[(i == 19) ? (r & 0x3) | 0x8 : r];
                    }
                }
            }

            return (prefix || "") + uuid.join('');
        },

        /**
         * 隐藏弹出的对话框,参数是toast, alert, dialog等返回的内容
         * 
         * @param {type} loadingId
         * @returns {undefined}
         */
        hide_dialog: function (loadingId) {
            console.log(YDJS.UI_FRAMEWORK_NAME + " hide_dialog 未实现");
        },
        /**
         * 
         * @param {type} msg 消息
         * @param {type} icon 图标见YDJS.ICON_XX定义 默认YDJS.ICON_INFO
         * @param {type} cb toast关闭后的回调,默认没有
         * @param {type} backdrop none 没有背景, normal 有背景 默认none
         * @param {type} delay 显示的毫秒 默认3000 -1表示不会消失
         * @param {type} position 位置 见YDJS.POSITION_XX定义 默认POSITION_CENTER
         * @returns {undefined} 对话框id
         */
        toast: function (msg, icon, cb, backdrop, delay, position) {
            console.log(YDJS.UI_FRAMEWORK_NAME + " toast 未实现");
        },
        /**
         * 
         * @param {type} content
         * @param {type} title 
         * @param {type} icon 图标见YDJS.ICON_XX定义 默认YDJS.ICON_INFO
         * @param {type} buttonAndCallbacks buttonAndCallbacks 按钮及其回调的数组 [{label:"按钮名称","cb":回调函数名或者匿名函数}] 可选,默认是确定按钮 回调参数是dialog_id
         * @param {type} dialog_id 可选
         * @returns {undefined}
         */
        alert: function (content, title, icon, buttonAndCallbacks, dialog_id) {
            console.log(YDJS.UI_FRAMEWORK_NAME + " alert 未实现");
        },
        /**
         * 
         * @param {type} msg
         * @param {type} loadingId 可选参数
         * @param {type} overlay dom元素或者selector选择器，如果指定loading则覆盖在该元素上面
         * @returns {undefined} 对话框id
         */
        loading: function (msg, loadingId, overlay) {
            console.log(YDJS.UI_FRAMEWORK_NAME + " loading 未实现");
        },
        /**
         * 
         * @param {type} url 地址,如果有地址,则忽略content,title
         * @param {type} content 对话框的内容
         * @param {type} title 对话框的标题
         * @param {type} size 见YDJS.SIZE_XXX 默认normal
         * @param {type} backdrop 见YDJS.BACKDROP
         * @param {type} buttonAndCallbacks 按钮及其回调的数组 [{label:"按钮名称","cb":回调函数名或者匿名函数}] 回调参数是dialog_id
         * @param {type} dialog_id 对话框id,可不传
         * @returns {undefined} 对话框id
         */
        dialog: function (url, content, title, size, backdrop, buttonAndCallbacks, dialog_id) {
            console.log(YDJS.UI_FRAMEWORK_NAME + " dialog 未实现");
        },
        /**
         * 
         * @param {type} content 确认对话框的内容
         * @param {type} title 确认对话框的标题
         * @param {type} yes_cb 确认按钮的回调 参数是dialog_id
         * @param {type} no_cb 取消按钮的回调 参数是dialog_id
         * @param {type} yes_label 确认按钮名称 默认"确认"
         * @param {type} no_label 取消按钮的名称 默认"取消"
         * @param {type} dialog_id 对话框id
         * @returns {undefined} 对话框id
         */
        confirm: function (content, title, yes_cb, no_cb, yes_label, no_label, dialog_id) {
            console.log(YDJS.UI_FRAMEWORK_NAME + " confirm 未实现");
        },
        /**
         * 
         * @param {type} title 标题
         * @param {type} defaultValue 默认值
         * @param {type} type input 输入框 password 密码框 textarea 文本框
         * @param {type} cb 确定后的回调,参数是dialog_id , value
         * @param {type} dialog_id
         * @returns {undefined} 对话框id
         */
        prompt: function (title, defaultValue, type, cb, dialog_id) {
            console.log(YDJS.UI_FRAMEWORK_NAME + " prompt 未实现");
        },

        /**
         * 更新对话框的内容
         *
         * @param dialog_id
         * @param content
         */
        update_loading:function (dialog_id, content){

        },

        urlencode: function (clearString)
        {
            var output = '';
            var x = 0;

            clearString = utf16to8(clearString.toString());
            var regex = /(^[a-zA-Z0-9-_.]*)/;

            while (x < clearString.length)
            {
                var match = regex.exec(clearString.substr(x));
                if (match != null && match.length > 1 && match[1] != '')
                {
                    output += match[1];
                    x += match[1].length;
                } else
                {
                    if (clearString[x] == ' ')
                        output += '+';
                    else
                    {
                        var charCode = clearString.charCodeAt(x);
                        var hexVal = charCode.toString(16);
                        output += '%' + (hexVal.length < 2 ? '0' : '') + hexVal.toUpperCase();
                    }
                    x++;
                }
            }

            function utf16to8(str)
            {
                var out, i, len, c;

                out = "";
                len = str.length;
                for (i = 0; i < len; i++)
                {
                    c = str.charCodeAt(i);
                    if ((c >= 0x0001) && (c <= 0x007F))
                    {
                        out += str.charAt(i);
                    } else if (c > 0x07FF)
                    {
                        out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));
                        out += String.fromCharCode(0x80 | ((c >> 6) & 0x3F));
                        out += String.fromCharCode(0x80 | ((c >> 0) & 0x3F));
                    } else
                    {
                        out += String.fromCharCode(0xC0 | ((c >> 6) & 0x1F));
                        out += String.fromCharCode(0x80 | ((c >> 0) & 0x3F));
                    }
                }
                return out;
            }

            return output;
        },
        urldecode: function (encodedString)
        {
            var output = encodedString;
            var binVal, thisString;
            var myregexp = /(%[^%]{2})/;
            function utf8to16(str)
            {
                var out, i, len, c;
                var char2, char3;

                out = "";
                len = str.length;
                i = 0;
                while (i < len)
                {
                    c = str.charCodeAt(i++);
                    switch (c >> 4)
                    {
                        case 0:
                        case 1:
                        case 2:
                        case 3:
                        case 4:
                        case 5:
                        case 6:
                        case 7:
                            out += str.charAt(i - 1);
                            break;
                        case 12:
                        case 13:
                            char2 = str.charCodeAt(i++);
                            out += String.fromCharCode(((c & 0x1F) << 6) | (char2 & 0x3F));
                            break;
                        case 14:
                            char2 = str.charCodeAt(i++);
                            char3 = str.charCodeAt(i++);
                            out += String.fromCharCode(((c & 0x0F) << 12) |
                                    ((char2 & 0x3F) << 6) |
                                    ((char3 & 0x3F) << 0));
                            break;
                    }
                }
                return out;
            }
            while ((match = myregexp.exec(output)) != null
                    && match.length > 1
                    && match[1] != '')
            {
                binVal = parseInt(match[1].substr(1), 16);
                thisString = String.fromCharCode(binVal);
                output = output.replace(match[1], thisString);
            }

            //output = utf8to16(output);
            output = output.replace(/\\+/g, " ");
            output = utf8to16(output);
            return output;
        },
        /**
         * 重新绑定事件
         * @returns {undefined}
         */
        rebind: function(){
            
        }
        //,
        // /**
        //  * 产生浏览器的通知
        //  */
        // notify: function(title, body, icon, onclick){
        //     var PERMISSON_GRANTED = 'granted';
        //     var PERMISSON_DENIED = 'denied';
        //     var PERMISSON_DEFAULT = 'default';
        //
        //     if (Notification.permission === PERMISSON_GRANTED) {
        //         showNotify(title);
        //     } else {
        //         Notification.requestPermission(function (res) {
        //             if (res === PERMISSON_GRANTED) {
        //                 showNotify(title);
        //             }
        //         });
        //     }
        //
        //     function showNotify(title) {
        //         var option = {body: body};
        //         if (icon) {
        //             option["icon"] = icon;
        //         }
        //         var notification = new Notification(title, option);
        //         // console.log(notification);
        //         // notification.onshow = function(event){ console.log('show : ',event); }
        //         // notification.onclose = function(event){ console.log('close : ',event); }
        //         if (onclick) {
        //             notification.onclick = function (event) {
        //                 onclick(notification, event)
        //             }
        //         }
        //     }
        // }
    };
}

(function () {
    function yd_event_bind_callback(event) {
        event.data.call(this, event);
    }
    if (typeof YDJS.yd_event_bind !== 'function') {
        /**
         * jquery on的封装, callback事件只会绑定一次[TODO 并未实现只绑定一次]
         * @param {type} event
         * @param {type} selector
         * @param {type} callback
         * @returns {undefined}
         */
        YDJS.event_bind = function (event, selector, callback) {
            $("html").off(event, selector, callback);
            //callback 作为event.data传入
            $("html").on(event, selector, callback);
        };
    }
}());