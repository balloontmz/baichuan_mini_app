/**
 * 
 * @param {type} directly
 * @param {type} from inbox,outbox,draft,recycle
 * @returns {undefined}
 */
function removeEmail(directly, from) {
    var datas = form.val("email-form");
    datas["directly"] = directly;
    datas["from"] = from;
    function remove() {
        var urlMap = {"inbox":"/email","outbox":"/email/sended","draft":"/email/draft","recycle":"/email/recycle"};
        $.post("/email/remove.json", datas, function (rst) {
            if (rst.success) {
                YDJS.toast("邮件已删除", YDJS.ICON_SUCCESS, function () {
                    window.location.href= urlMap[from]||"/email";
                });
            } else {
                YDJS.toast(rst.msg || "邮件删除失败", YDJS.ICON_ERROR);
            }
        });
    }
    if (directly) {
        YDJS.confirm("彻底删除后邮件将无法恢复，您确定要删除吗？", "删除确认", function () {
            remove();
        });
    } else {
        remove();
    }
}

function markRead() {
    var datas = form.val("email-form");
    $.post("/email/markread.json", datas, function (rst) {
        if (rst.success) {
            window.location.reload();
        } else {
            YDJS.toast(rst.msg || "邮件删除失败", YDJS.ICON_ERROR);
        }
    });
}

function forwardEmail() {
    var datas = form.val("email-form");

    var uuids = [];
    $.each(datas, function (idx, item) {
        uuids.push(item);
    });

    window.location.href = "/email/send?forward_email_uuid=" + uuids.join(",");
}

function restoreEmail() {
    var datas = form.val("email-form");
    $.post("/email/restore.json", datas, function (rst) {
        if (rst.success) {
            YDJS.toast("邮件已恢复", YDJS.ICON_SUCCESS, function () {
                window.location.reload();
            });
        } else {
            YDJS.toast(rst.msg || "邮件恢复失败", YDJS.ICON_ERROR);
        }
    });
}

function checkEmail(type) {
    $("#inbox ." + type).prop("checked", 1);
    form.render();
}
