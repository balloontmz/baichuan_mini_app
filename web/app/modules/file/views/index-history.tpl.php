<?php
namespace app\file;
use \yangzie\YZE_Resource_Controller;
use \yangzie\YZE_Request;
use \yangzie\YZE_Redirect;
use \yangzie\YZE_Session_Context;
use \yangzie\YZE_RuntimeException;
use yangzie\YZE_Simple_View;
use yangzie\YZE_Hook;
use function yangzie\yze_controller_error;

/**
 * 某类型的文件资料列表
 * @param type name optional
 *
 */
 
$find_file= $this->get_data("find_file");//当前要查看哪个文件的历史版本
$files = $this->get_data("files");//该文件的所有历史版本，包括自己
$totalCount = $this->get_data("totalCount");

$login_user = YZE_Hook::do_hook( YZE_HOOK_GET_LOGIN_USER );//当前登陆用户，判断是否显示删除
$name = $this->get_data("name");
$user = $this->get_data("user");

$page = new YZE_Simple_View(YD_VIEW_PAGINATION, array("total" => $totalCount,"pagesize" => PAGESIZE), $this->controller);
?>

<div id="yd-page-content">
	<h3 class="page-title">
		资料历史版本列表 
	</h3>
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
                        <th>资料名</th>
						<th>大小</th>
						<th>类型</th>
						<th>版本号</th>
						<th>创建日期</th>
						<th>更新日期</th>
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
             		<td><?php echo $file->name?></td>
					<td><?php echo $file->formate_file_size()?></td>
					<td><?php echo $file->ext?></td>
					<td><?php echo $file->version ? $file->version : 0?></td>
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
						<button type="button" class="delete_file btn btn-sm btn-danger" data-copy-id="<?php echo $file->copy_file_id?>" data-id="<?php echo $file->id?>">删除</button>
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

<script type="text/javascript">
//删除文件
$(".delete_file").click(function(){
	var file_id = $(this).attr("data-id");
	var copy_id = $(this).attr("data-copy-id");
	var confirm_string = "确定删除该条资料吗？删除将连带删除历史版本，且不可恢复！";
	if( copy_id > 0 ){
		confirm_string = "确定删除该版本文件吗？删除将不可恢复！";
	}
	if( ! confirm(confirm_string) ){
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