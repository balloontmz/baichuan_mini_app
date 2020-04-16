<?php

namespace app\file;

use yangzie\YZE_Hook;
use yangzie\YZE_Simple_View;
use app\asset\Asset_Model;

/**
 * 我上传的文件列表
 */
$login_user = YZE_Hook::do_hook(YZE_HOOK_GET_LOGIN_USER);

$files = $this->get_data("files");
$totalCount = $this->get_data("total");
?>
<script>var files = {};</script>
<div class="card m-3" id="search-files">
    <div class="card-header p-1">

        <form class="form-inline" role="form">

            <div class="input-group m-0">
                <input type="text" name="name" value="<?= $_GET["name"]; ?>" class="form-control form-control-sm"
                       placeholder="按文件名查询">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> <span>查询</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <?php if ($_GET["ckbrowse"] || $_GET["cb"]) { ?>
        <div class="text-danger m-1">提示:双击选择文件</div>
    <?php } ?>
    <table class="table table-hover m-0 table-striped table-sm">
        <thead>
        <tr>
            <th></th>
            <th>文件名</th>
            <th>大小</th>
            <th>创建日期</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($files as $file) {
            ?>

            <script>files['<?= $file->uuid?>'] = <?= json_encode($file->get_records())?></script>
            <tr class="file-row pointer" onclick="clickRow(this, '<?= $file->uuid ?>')"
                ondblclick="dblClickRow(this, '<?= $file->uuid ?>')">
                <td class="align-middle text-center"><?php echo $file->showLink("20px;") ?></td>
                <td class="align-middle text-left text-truncate" style="max-width: 300px"><?php echo $file->name ?></td>
                <td class="align-middle text-center"><?php echo $file->formate_file_size() ?></td>
                <td class="align-middle text-center"><?php echo $file->created_on ?></td>
                <td class="align-middle text-center">
                    <a href="/common/download?file_id=<?php echo $file->uuid ?>"
                       class="layui-btn layui-btn-xs layui-btn-primary"><i class="iconfont icon-export"></i></a>
                    <button type="button" class="delete_file layui-btn layui-btn-xs layui-btn-danger"
                            data-id="<?php echo $file->uuid ?>"><i class="iconfont icon-shanchu"></i></button>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>

<div class="m-3 p-3">&nbsp;</div>

<div class="d-flex fixed-bottom bg-light pl-2 pr-2 border-top align-items-center align-content-center justify-content-between">
    <div class="yd-pagination" data-total="<?= $totalCount ?>" data-container-id="search-files"
         data-pagesize="<?= PAGE_SIZE ?>"></div>
    <?php if (!$_GET["ckbrowse"]) { ?>
        <button class="btn btn-primary btn-sm" type="button" onclick="confirm_file()">确定</button>
    <?php } ?>
</div>

<script type="text/javascript">
    var selectedFiles = {};

    function clickRow(obj, fid) {
        if ($(obj).hasClass("bg-primary")) {
            $(obj).removeClass(" bg-primary text-white");
            delete selectedFiles[fid];
        } else {
            <?php if ( !$_GET["ckbrowse"]) { ?>

            $(obj).addClass(" bg-primary text-white");
            selectedFiles[fid] = files[fid];
            <?php }else{?>
            $(obj).removeClass(" bg-primary text-white");
            selectedFiles = {};
            $(obj).addClass(" bg-primary text-white");
            selectedFiles[fid] = files[fid];
            <?php }?>
        }
    }

    function confirm_file() {
        var open = window.opener ? window.opener : window.parent;
        open["<?= $_GET["cb"]?>"](selectedFiles);
        if (window.opener) {
            window.close();
        }
    }

    function dblClickRow(obj, file_id) {
        selectedFiles = {};
        selectedFiles[file_id] = files[file_id];

        var open = window.opener ? window.opener : window.parent;
        <?php if ($_GET["ckbrowse"]) { ?>
        var callback = "<?= $_GET["CKEditorFuncNum"] ?>";
        var path = "<?= SITE_URI ?>common/download?file_id=" + file_id;
        if (path) {
            open.CKEDITOR.tools.callFunction(callback, path, "");
        }  // 输出错误提示
        else {
            open.CKEDITOR.tools.callFunction(callback, "", "")
        }
        if (window.opener) {
            window.close();
        }
        <?php }else if($_GET["cb"]){ ?>
        open["<?= $_GET["cb"]?>"](selectedFiles);
        if (window.opener) {
            window.close();
        }
        <?php }?>
    }

    $(".delete_file").click(function () {
        var file_id = $(this).attr("data-id");

        top.YDJS.confirm("确定删除该文件吗？删除不可恢复", "删除文件", function () {
            var loadingid = YDJS.loading("删除中...");
            $.post("/file/" + file_id + "/delete", {}, function (data) {
                YDJS.hide_dialog(loadingid);
                if (data.success) {
                    YDJS.toast("删除成功", YDJS.ICON_SUCCESS, function () {
                        location.reload();
                    });
                } else {
                    YDJS.toast(data.msg, YDJS.ICON_ERROR);
                }
            }, "json");
        });
    });
</script>