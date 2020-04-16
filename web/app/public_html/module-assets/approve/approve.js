function approve_by_pwd(obj, pass_or_rejct){
    top.YDJS.prompt("请输入审批密码","","password",function (dialog_id, value) {
        var formData = $("#verify-form").serialize();
        formData += "&type=pwd&pwd="+value;
        $.post("/approve/"+pass_or_rejct+".json", formData, function (rst) {
            if(rst.success){
                top.window.location.reload();
            }else{
                top.YDJS.toast(rst.msg || "审批失败", YDJS.ICON_ERROR);
            }
        });
    });
}