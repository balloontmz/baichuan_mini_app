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
$name = $this->get_data("name");
$user = $this->get_data("user");
$property = $this->get_data("property");
$select_property = $this->get_data("select_property");
$files = $this->get_data("files");
$totalCount = $this->get_data("totalCount");

$tree_datas = array();
foreach ($property->get_all_childs() as $child){
    $tree_datas[] = array(
            "id" =>$child->id,
            "parent" => $child->id == $property->id ? "#": $child->parent_id,
            "text" => $child->name,
            "state" => array("opened"=>true,"selected" => $select_property->id == $child->id ? true : false)
    );
}

$page = new YZE_Simple_View(YD_VIEW_PAGINATION, array("total" => $totalCount,"pagesize" => PAGESIZE), $this->controller);
?>

<div id="yd-page-content">
	<h3 class="page-title">
		<?= $property->name?>
	</h3>
    <div class="row">
        <div class="col-sm-2"><div id="file-dir"></div></div>
        <div class="col-sm-10">
            <div class="portlet light bordered">
            <div class="portlet-title">
                <form class="form-inline" role="form">
                    <input type="text" name="name" value="<?php echo $name;?>" class="form-control input-small inline" placeholder="按文件名查询">
                    <input type="text" name="user" value="<?php echo $user;?>" class="form-control input-small inline" placeholder="按上传用户名查询">
                    <span class="input-group-btn inline">
                        <button type="submit" class="btn btn-default btn-outline">
                            <i class="fa fa-search"></i> <span>查询</span>
                        </button>
                    </span>
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

<script type="text/javascript">
//jstree 初始化
var tree_datas = <?php echo json_encode($tree_datas);?>;
$(function(){
 	$('#file-dir').on('select_node.jstree', function (e, data) {
 		var node = data.node
 		var id = node.id;
 		if(id){
 			if( id == "all" ){
 				window.location.href = "/file-<?= $property->id?>";
 				return;
 			}
 			window.location.href = "/file-<?= $property->id?>?property_id=" + id;
 		}
 		else{
 		   window.location.href = "/file-<?= $property->id?>";
 		}
 	}).jstree({ 'core' : { 'data' : tree_datas } });
});
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
</script>