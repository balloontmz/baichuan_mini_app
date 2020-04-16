<?php

/**
 * 视图的描述
 * @author 作者
 *
 */

namespace app\log;

use yangzie\YZE_Simple_View;

$notices = $this->get_data('notices');
$total = $this->get_data('total');
?>
<div class="m-3">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-content-center">
            <h3 class="page-title">消息中心</h3>
            <div>
                <button type="button" class="btn btn-primary notice_sign btn-sm"><i class="fa fa-bookmark-o"></i> 已读</button>
                &nbsp;
                <button type="button" class="btn btn-danger notice_delete btn-sm"><i class="fa fa-trash-o"></i> 删除</button>
            </div>
        </div>
        <table class="table table-bordered table-hover m-0 table-sm">
            <thead>
            <tr>
                <th><input type="checkbox" class="notice_items" value=""></th>
                <th>时间</th>
                <th>状态</th>
                <th>内容</th>
                <th>连接</th>
            </tr>
            </thead>
            <tbody id="notice-table">
            <?php
            if ($notices) {
                foreach ($notices as $notice) {
                    $link = $notice->link ? "<a href='{$notice->link}' target='_blank'>点击查看</a>" : "";
                    $read = $notice->is_readed ? "已读" : "未读";
                    echo "<tr>";
                    echo "<td><input type='checkbox' class='notice_item' value='{$notice->id}'></td>";
                    echo "<td>{$notice->created_on}</td>";
                    echo "<td>{$read}</td>";
                    echo "<td>{$notice->content}</td>";
                    echo "<td>{$link}</td>";
                    echo "</tr>";
                }
            }
            ?>
            </tbody>
        </table>
        <div class="card-footer">
            <div class="yd-pagination" data-container-id="notice-table" data-total="<?= $total ?>" data-pagesize="<?= PAGE_SIZE ?>"
                 data-currpage="<?= $_GET["page"] ?>"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        // 全选
        $(".notice_items").change(function () {
            if ($(this).is(":checked")) {
                $(".notice_item").prop("checked", true);
            } else {
                $(".notice_item").prop("checked", false);
            }
            $(".notice_item").uniform();
        });

        $(".notice_item").change(function () {
            var notices = $(".notice_item").length;
            var checked = $(".notice_item:checked").length;
            if (checked >= notices) {
                $(".notice_items").prop("checked", true);
            } else {
                $(".notice_items").prop("checked", false);
            }
            $(".notice_items").uniform();
        });

        // 标记已读
        $(".notice_sign").click(function () {
            var btn = this;
            var ids = get_notices_ids();
            if (!ids || ids.length <= 0) {
                yze_showToastI("请选择需要标记已读的消息！");
                return;
            }

            $(btn).prop("disabled", true);
            yze_showLoading("数据处理中...");
            $.ajax({
                url: "/notice/read",
                type: 'POST',
                data: {'notice_ids': ids},
                success: function (data) {
                    $(btn).prop("disabled", false);
                    yze_hideLoading();
                    if (data.success) {
                        window.location.reload();
                    } else {
                        yze_showToastI(data.msg);
                    }
                },
                error: function () {
                    $(btn).prop("disabled", false);
                    yze_hideLoading();
                    yze_showToastE("消息标记失败：系统错误！");
                }
            });
        });

        // 删除消息
        $(".notice_delete").click(function () {
            var ids = get_notices_ids();
            if (!ids || ids.length <= 0) {
                yze_showToastI("请选择需要删除的消息！");
                return;
            }

            yze_dialog_confirm("你确定要删除选择的消息吗？", function () {
                var ids = get_notices_ids();
                yze_showLoading("数据处理中...");
                $.ajax({
                    url: "/notice/delete",
                    type: 'POST',
                    data: {'notice_ids': ids},
                    success: function (data) {
                        yze_hideLoading();
                        if (data.success) {
                            window.location.reload();
                        } else {
                            yze_showToastI(data.msg);
                        }
                    },
                    error: function () {
                        yze_hideLoading();
                        yze_showToastE("消息删除失败：系统错误！");
                    }
                });
            });
        });
    });

    function get_notices_ids() {
        var notices = $(".notice_item:checked");
        var ids = [];
        if (notices.length <= 0) return ids;
        for (var i = 0; i < notices.length; i++) {
            if ($(notices[i]).val()) ids.push($(notices[i]).val());
        }
        return ids;
    }
</script>