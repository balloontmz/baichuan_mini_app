function forbidenUser(uuid) {
    $.post("/hr/user/enabled.json", {uuid:uuid}, function (rst) {
        if (rst.success) {
            YDJS.toast(rst.msg || "用户已成功禁用, 用户将无法登陆系统", YDJS.ICON_SUCCESS, function (rst) {
                window.location.reload();
            });
        } else {
            YDJS.toast(rst.msg || "禁用失败", YDJS.ICON_ERROR);
        }
    });
}

function removeUser(uuid) {
    YDJS.confirm("确认删除用户吗, 删除不会影响历史数据", "删除用户", function () {
        $.post("/hr/user/remove.json", {uuid:uuid}, function (rst) {
            if (rst.success) {
                YDJS.toast(rst.msg || "删除成功", YDJS.ICON_SUCCESS, function (rst) {
                    window.location.reload();
                });
            } else {
                YDJS.toast(rst.msg || "删除失败", YDJS.ICON_ERROR);
            }
        });
    });
}