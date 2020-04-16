;

/**
 * 把yd-tree渲染成树形
 * @returns {undefined}
 */
function yd_tree_render() {

    var render = function (treePanel) {
        var selectDom = $(treePanel);
        var datas = window[ selectDom.attr("data-datas") ];
        var url = selectDom.attr("data-url");
        var query_arg = selectDom.attr("data-url-query")||"query";
        var _default = selectDom.attr("data-default");
        var multiple = selectDom.attr("multiple");
        _default = _default ? _default.split(",") : []
        var callback = selectDom.attr("data-callback");
        
        var plugins = ["search"];
        if (multiple) {
            plugins.push("checkbox");
        }
        var data = function (obj, cb) {
            if (datas) {
                cb.call(this, datas);
            }
        };
        if (url){
            data = {
                'url': url,
                "datatype": "json",
                'data': function(node){
                    var args = {};
                    args[query_arg] = node.id=="#"?"":node.id;
                    return args
                }
            };
        }

        $(treePanel).jstree({
            "plugins": plugins,
            'core': {
                "multiple": multiple ? true : false,
                'data': data
            }
        });
        //$(treePanel).jstree().hide_icons();
        
        $(treePanel).on('select_node.jstree', function (e, data) {
            var i, j, r = [];
            for (i = 0, j = data.selected.length; i < j; i++) {
                r.push(data.instance.get_node(data.selected[i]));
            }
            if(callback){
                window[callback]( r );
            }
        });
        
    };

    //渲染所有的tree select
    $(".yd-tree").each(function (idx, el) {
        render(el);
       
    });
}