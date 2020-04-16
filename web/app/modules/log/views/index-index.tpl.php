<?php

namespace app\log;

use yangzie\YZE_Simple_View;

/**
 * 视图的描述
 * @param type name optional
 *       
 */
$rsts = $this->get_data('rsts');
$total = $this->get_data('total');
$view = new YZE_Simple_View(YZE_APP_VIEWS_INC . "pagination", array(
    "total" => $total,
    "pagesize" => PAGESIZE
        ), $this->controller);
?>
<style>
    tr td:first-child, tr th:first-child {
        width: 36px !important;
    }
</style>
<div id="yd-page-content">
    <h3 class="page-title">日志</h3>
    <form method="get">
        <!-- 搜索框开始 -->
        <div class="input-group input-small" style="margin: 0 0 20px;">
            <span class="input-group-addon input-circle-left">操作者 </span> 
            <input type="text" class="form-control form-control-inline input-small" name="name"
                   value="<?php echo $_GET["name"] ?>" placeholder="请输入操作者的姓名"> 
            <span class="input-group-addon">日期 </span>
            <input class="form-control form-control-inline input-small date-picker" value="<?php echo $_GET["start_date"] ?>" size="16" type="text" name="start_date" value="" placeholder="时间开始">
            <span class="input-group-addon">到</span>
            <input class="form-control form-control-inline input-small date-picker" value="<?php echo $_GET["end_date"] ?>" size="16" type="text" name="end_date" value="" placeholder="时间结束"> 
            <span class="input-group-addon">操作列 </span> 
            <input class="form-control form-control-inline input-small" size="16" type="text" name="column"  value="<?php echo $_GET["column"] ?>" placeholder="操作字段"> 
            <span class="input-group-btn ">
                <button type="submit" class="btn blue input-circle-right">
                    <i class="fa fa-search"></i> <span>搜索</span>
                </button>
            </span>
        </div>
    </form>

    <div class="portlet">
        <div class="portlet light  bordered">
            <div class="portlet-title">
            </div>
            <div class="portlet-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="yd_all_select"></th>
                            <th>操作者</th>
                            <th>操作</th>
                            <th>操作时间</th>
                            <th>操作对象</th>
                            <th>修改列</th>
                            <th>修改前</th>
                            <th>修改后</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($rsts) {
                            foreach ($rsts as $rst) {
                                $log = $rst ["log"];
                                $item = $rst ["item"];
                                $user = $log->get_user();
                                $changeObject = $log->getTargetObject(); 
                                ?>
                                <tr>
                                    <td><input type='checkbox' class='yd_sub_select'
                                               value='<?php echo $item ? $item->id : $log->id; ?>' data-type="<?php echo $item ? "item" : "log" ?>"></td>
                                    <td><?php echo $user->getEmployee()->name;//智强的逻辑 ?></td>
                                    <td><?php echo $log->getActionDesc() ?></td>
                                    <td><?php echo $log->created_on; ?></td>
                                    <td><?php echo $changeObject ? $changeObject->getTableName() : $log->Get("target_table") ?></td>
                                    <td><?php echo $changeObject && $item ? $changeObject->getColumnDesc($item->column) : $item->column ?></td>
                                    <td><?php echo $changeObject && $item ? $changeObject->getColumnValue($item->column, $item->old_value) : $item->old_value ?></td>
                                    <td><?php echo $changeObject && $item ? $changeObject->getColumnValue($item->column, $item->new_value) : $item->new_value ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <button class="btn blue delete_logs">删除</button>
                <div class="text-center"><?php $view->output(); ?></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        // 全选
        $("body").delegate("input[type='checkbox'].yd_all_select", "change", function () {
            if ($(this).is(":checked")) {
                $("input[type='checkbox'].yd_sub_select").prop("checked", true);
            } else {
                $("input[type='checkbox'].yd_sub_select").prop("checked", false);
            }
            $("input[type='checkbox'].yd_sub_select").uniform();
        });

        $("body").delegate("input[type='checkbox'].yd_sub_select", "change", function () {
            var sub_num = $("input[type='checkbox'].yd_sub_select").length;
            var checked = $("input[type='checkbox'].yd_sub_select:checked").length;
            if (checked >= sub_num) {
                $("input[type='checkbox'].yd_all_select").prop("checked", true);
            } else {
                $("input[type='checkbox'].yd_all_select").prop("checked", false);
            }
            $("input[type='checkbox'].yd_all_select").uniform();
        });

        // 删除
        $(".delete_logs").click(function () {
            var logids = new Array();
            var itemids = new Array();
            var items = $("input[type='checkbox'].yd_sub_select:checked");
            if (items.length <= 0) {
                yze_showToastE("请选择要删除的日志！");
                return;
            }
            for (var i = 0; i < items.length; i++) {
                if ($(items[i]).attr("data-type") == "log") {
                    logids.push($(items[i]).val());
                } else {
                    itemids.push($(items[i]).val());
                }

            }
            yze_dialog_confirm("您确定删除选中的日志吗？", function (datas) {
                yze_showLoading("数据处理中...");
                $.ajax({
                    url: "/log/delete",
                    type: 'POST',
                    data: {'log_ids': datas.logids, 'item_ids': itemids},
                    success: function (data) {
                        yze_hideLoading();
                        if (data.success) {
                            window.location.reload();
                        } else {
                            yze_showToastW(data.msg);
                        }
                    },
                    error: function () {
                        yze_hideLoading();
                        yze_showToastE("日志删除失败：系统错误！");
                    }
                });
            }, '', {itemids: itemids, logids: logids});
        });
    });
</script>