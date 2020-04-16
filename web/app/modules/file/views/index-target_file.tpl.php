<?php
namespace app\file;
use yangzie\YZE_Hook;
use yangzie\YZE_Simple_View;

/**
 * 某类型的文件资料列表
 * @param type name optional
 *
 */

$login_user = YZE_Hook::do_hook( YZE_HOOK_GET_LOGIN_USER );
$target_id = $this->get_data("target_id");
$target_class = $this->get_data("target_class");
$files = $this->get_data("files");
$totalCount = $this->get_data("totalCount");
$page = new YZE_Simple_View(YD_VIEW_PAGINATION, array("total" => $totalCount,"pagesize" => PAGESIZE), $this->controller);

$upload_source_datas = array (
	"id" => "upload-source-file",
	"save_path" => YZE_UPLOAD_PATH . "/target/".date("Y-m-d"),
	"action" => "save_file",
	"params" => array (
		"project_id" => 0,
		"type_id" => 0,
		"target_id" => $target_id,
		"target_class" => $target_class
	),
	"is_multi" => 1,
	"is_auto" => 1,
	"back_uploade_complete" => "back_uploade_complete_source",
	"back_uploade_add" => "back_uploade_add_source",
	"back_uploade_error" => "back_uploade_error"
	);
$upload_source = new YZE_Simple_View( YZE_APP_VENDOR . "plupload/upload-button", $upload_source_datas, $this->controller );
?>

<div id="yd-page-content">
	<h3 class="page-title">
		文件
	</h3>
    <div class="row">
        <div class="col-sm-12">
            <div class="portlet light bordered">
            <div class="portlet-title">
                <form class="form-inline" role="form">
                	<input type="hidden" name="target_class" value="<?=$_GET["target_class"]?>">
                    <input type="text" name="name" value="<?=$_GET["name"]?>" class="form-control input-small inline" placeholder="按文件名查询">
                    <button type="submit" class="btn btn-default btn-outline">
                    	<i class="fa fa-search"></i> <span>查询</span>
                    </button>
                    <?= $target_class ? "<button type='button' class='btn btn-default btn-outline' id='btn_upload'> 上传 </button>" : "";?>
                </form>
            </div>
            <div class="portlet-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th>资料名</th>
                            <th>大小</th>
                            <th>更新次数</th>
                            <th>创建日期</th>
                            <th>最后更新日期</th>
                            <th>上传用户</th>
                            <th>资料编号</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($files as $file){
                        $upload_user = $file->get_user();
                    ?>
                    <tr>
                        <td class="mini-image"><?php echo $file->showLink()?></td>
                        <td><?php echo $file->name?></td>
                        <td><?php echo $file->formate_file_size()?></td>
                        <td>
                            <a href="/file/<?php echo $file->id?>/history"><?php echo $file->version ? $file->version : 0?></a>
                        </td>
                        <td><?php echo $file->created_on?></td>
                        <td><?php echo $file->date?></td>
                        <td><?php echo $upload_user->user_name?></td>
                        <td><?php echo $file->number?></td>
                        <td>
                            <a href="/common/download?file_id=<?php echo $file->id?>" class="btn btn-sm btn-default">下载</a>
                            <?php
                            //只有上传者，才能删除
                            if( $upload_user->id == $login_user->id ){
                            ?>
                            <button type="button" class="delete_file btn btn-sm btn-danger" data-id="<?php echo $file->id?>">删除</button>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
                <div class="text-center">
                    <nav>
                    <?php
                    $page->output();
                    ?>
                    </nav>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
<div class="hide"><?=$upload_source->output();?></div>
<script type="text/javascript">
//删除文件
$(".delete_file").click(function(){
	var file_id = $(this).attr("data-id");
	if( ! confirm("确定删除该条资料吗？删除将连带删除历史版本，且不可恢复！") ){
		return;
	}
	yze_showLoading("删除中...");
	$.post("/file/" + file_id + "/delete", {}, function(data){
		yze_hideLoading();
		if(data.success){
			yze_showToastS("删除成功", 3);
			location.reload();
		}
		else{
			yze_showToastE(data.msg, 3);
		}
	}, "json");
});

$("#btn_upload").click(function(){
	$("#upload-source-file").trigger("click");
});

function back_uploade_complete_source() {
	console.log("back_uploade_complete_source");
	$("#btn_upload").prop("disabled", false);
	window.location.reload()
}
function back_uploade_add_source() {
	console.log("back_uploade_add_source");
	$("#btn_upload").prop("disabled", true);
}
function back_uploade_error(msg) {
    if (msg) yze_showToastE(msg);
}
</script>