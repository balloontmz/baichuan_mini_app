<?php

namespace app\log;

use app\log\Comment_Model;
use yangzie\YZE_Hook;
use yangzie\YZE_Resource_Controller;
use yangzie\YZE_View_Component;

/**
 * target_class
 * target_id
 * module: 所在的模块名
 * notify_uids array 新留言产生时要通知谁，注意是user id，为空不通知
 * notify_link 产生的通知中点击进入的地址
 *
 * @author ydhlleeboo
 *        
 */
class Discuss_List_Control_View extends YZE_View_Component {
    public $target_class;
    public $target_id;
    public $module;
    public $notify_uids;
    public $notify_link;
    
    public function __construct($data, YZE_Resource_Controller $controller){
        parent::__construct($data, $controller);
        foreach ($data as $name => $value){
            $this->$name = $value;
        }
    }
    protected function output_component() {
        $comments     = Comment_Model::getComments ( $this->target_class, $this->target_id );
        $firstComment = Comment_Model::getFirstComment( $this->target_class, $this->target_id );
        $user         = YZE_Hook::do_hook ( YZE_HOOK_GET_LOGIN_USER );
        $reverse_comments = array_reverse($comments);
        $curr_first_comment = reset($reverse_comments);
?>
<script>var first_comment_id = "<?php echo $firstComment->id?>",loaded_first_comment_id=""</script>
<div class="portlet portlet-fit light">
    <div class="portlet-title">
        <div class="caption font-black-sharp">
            <span class="caption-subject bold uppercase"> 回复与讨论</span>
        </div>
    </div>

    <div class="portlet-body" >
        
        <ul class="chats" id="chats">
            <?php if($comments){
            ?>
            <?php if($curr_first_comment->id > $firstComment->id){?>
            <li class="text-center" id="load-more-comment"><a href="javascript:void(0)" onclick="loadMoreComment()">加载更多(<?php echo $curr_first_comment->id ,",", $firstComment->id?>)...</a></li>
        <?php }?>
            <?php 
                foreach ($reverse_comments as $comment){
            ?>
             <li class="<?php echo $user->id == $comment->get_user()->id ? "out" :"in"?>"><img class="avatar" alt="<?php $comment->get_user()->getEmployee()->name?>"
                src="<?php echo $comment->get_user()->getEmployee()->getAvatar()?>">
                <div class="message">
                    <span class="arrow"> </span> <a
                        href="javascript:;" class="name"><?php echo $comment->get_user()->getEmployee()->name?></a> 
                        <span class="datetime"> <?php echo dateTimeFormatToString($comment->date)?></span> <span class="body"> <?php echo $comment->comment?> </span>
                </div></li>
            <?php }
            echo '<script>loaded_first_comment_id = "'.$curr_first_comment->id.'";</script>';
            }else{?>
             <li class="comment-empty-msg"><p class="text-center text-muted">这里空空如也</p></li>
            <?php }?>
           
        </ul>
        <div class="chat-form">
            <div class="input-cont">
                <input class="form-control" onkeydown="returnSubmitComment(event)" id="comment-content" type="text" placeholder="说点什么吧...输入并回车即可">
            </div>
            <div class="btn-cont">
                <span class="arrow"> </span> <button type="button" onclick="submitComment(this)" id="comment-submit-btn" class="btn blue icn-only  fa fa-check"> </button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function returnSubmitComment(event){
    if(event.keyCode==13){
        $("#comment-submit-btn").click();
    }
}

function loadMoreComment(){
    $.get("/log/comment.json",{
        "last_id":      loaded_first_comment_id,
        "target_id":    "<?php echo $this->target_id?>",
        "target_class": "<?php echo addslashes($this->target_class)?>"
    },function(rst){
        $("#load-more-comment").remove();
        if( ! rst.data.length)return;
        var curr_first = rst.data[ rst.data.length-1 ];
        for(var i=0; i<rst.data.length; i++){
            $("#chats").prepend('<li class="out"><img class="avatar" alt="'+rst.data[i].user.name+'"'+
                    'src="<?= UPLOAD_SITE_URI?>'+rst.data[i].user.avatar+'">'+
                    '<div class="message">'+
                    '    <span class="arrow"> </span> <a'+
                    '        href="javascript:;" class="name">'+rst.data[i].user.name+'</a> '+
                    '        <span class="datetime"> '+rst.data[i].date+'</span> <span class="body"> '+rst.data[i].comment+'</span>'+
                    '</div></li>');
        }
        loaded_first_comment_id = curr_first['id'];
        if(curr_first['id'] > first_comment_id){
            $("#chats").prepend('<li class="text-center" id="load-more-comment"><a href="javascript:void(0)" onclick="loadMoreComment()">加载更多...</a></li>');
        }
    });
}

function submitComment(obj){
    var comment = $("#comment-content").val().trim();
    if( ! comment)return;
    $(obj).prop("disabled", true);
    $("#comment-content").prop("disabled", true);
    $.post("/log/comment",{
        "comment": comment,
        "notify_uids":  "<?php echo join(",", $this->notify_uids)?>",
        "notify_link":  "<?php echo $this->notify_link?>",
        "target_id":    "<?php echo $this->target_id?>",
        "target_class": "<?php echo addslashes($this->target_class)?>"
    },function(rst){
        $(obj).prop("disabled", false);
        $("#comment-content").prop("disabled", false);
        if(rst.success){
            $("#comment-content").val("");
            $(".comment-empty-msg").remove();
            $("#chats").append('<li class="out"><img class="avatar" alt="'+rst.data.user.name+'"'+
                    'src="<?= UPLOAD_SITE_URI?>'+rst.data.user.avatar+'">'+
                    '<div class="message">'+
                    '    <span class="arrow"> </span> <a'+
                    '        href="javascript:;" class="name">'+rst.data.user.name+'</a> '+
                    '        <span class="datetime"> 刚刚</span> <span class="body"> '+rst.data.comment+'</span>'+
                    '</div></li>');
        }else{
            yze_showToastE(rst.msg || "留言失败");
        }
    },"json");
}
</script>
<?php
    }
}
?>