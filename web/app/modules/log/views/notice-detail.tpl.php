<?php

/**
 * 视图的描述
 * @author 作者
 *        
 */

namespace app\log;

$notice = $this->get_data ( 'notice' );
?>
<div>
	<h3 class="page-title">消息详情</h3>
	<?php if ( $notice ) { ?>
	<div class="portlet light bordered">
		<div class="portlet-title text-right">
			<button type="button"
				class="btn btn-danger btn-outline notice_delete" data-id="<?php echo $notice->id;?>">
				<i class="fa fa-trash-o"></i> 删除
			</button>
		</div>
		<div class="portlet-body">
			<div class="row">
				<div class="col-md-1">
					<strong>时间</strong>
				</div>
				<div class="col-md-11"><?php echo \YD_TOOL_TIME::TimeDistance($notice->created_on);?></div>
			</div>
			<?php if ( $notice->link ) { ?>
			<div class="row">
				<div class="col-md-1">
					<strong>连接</strong>
				</div>
				<div class="col-md-11"><?php echo "<a href='{$notice->link}' target='_blank'>点击查看</a>";?></div>
			</div>
			<?php } ?>
			<div class="row">
				<div class="col-md-1">
					<strong>内容</strong>
				</div>
				<div class="col-md-11"><?php echo $notice->content;?></div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	$(function(){
		$(".notice_delete").click(function(){
			var id = $(this).attr("data-id");
			if(!id){
				yze_showToastI("删除失败：数据错误！");
				return;
			}

			yze_dialog_confirm("你确定要删除该消息吗？", function(){
				var ids = [];
				ids[0] = $(".notice_delete").attr("data-id");
				yze_showLoading("数据处理中...");
				$.ajax({
					url : "/notice/delete",
					type : 'POST',
					data : {'notice_ids':ids},
					success : function(data) {
						yze_hideLoading();
					    if(data.success){
					    	window.location.href = "/notice";
				    	}else{
				    		yze_showToastI(data.msg);
				    	}
					},
					error : function() {
						yze_hideLoading();
						yze_showToastE("消息删除失败：系统错误！");
					}
				});
			});
		});
	})
	</script>
	<?php } else { echo "<div class='note note-danger'><h4 class='block'>消息不存在！</h4></div>"; }?>
</div>