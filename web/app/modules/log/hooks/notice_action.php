<?php

namespace app;

use yangzie\YZE_Hook;
use yangzie\YZE_DBAImpl;
use yangzie\YZE_SQL;
use app\log\Notice_Model;

// 保存消息
YZE_Hook::add_hook(YD_NOTICE_SAVE, function ( $data ) {
    $user_ids = (array)@$data ["user_ids"];
    $content = @$data ["content"];
    $link = @$data ["link"];
    $target_class = addslashes(@$data ["target_class"]);
    $target_id = @$data ["target_id"];
    $type = @$data["type"];
    if (!$user_ids || !is_array($user_ids) || !$content)
        return $data;
    $user_ids = array_values(array_unique(array_filter($user_ids)));

    $db = YZE_DBAImpl::getDBA();
    $total = 100;
    $values = [];
    $date = date("Y-m-d H:i:s");
    $field = "INSERT INTO " . Notice_Model::TABLE . "(content, target_class, target_id, link, user_id, is_readed, created_on, type) VALUES";
    for ($i = 0; $i < count($user_ids); $i ++) {
        if (!$user_ids[$i])
            continue;
        $values [] = "('{$content}', '{$target_class}', '{$target_id}', '$link', '{$user_ids[$i]}', '0', '{$date}', '{$type}')";
        if (( $i + 1 ) % $total == 0) {
            $sql = $field . join(",", $values);
//            echo $sql;
            $db->exec($sql);
            $values = [];
        }
    }
    if ($values) {
        $sql = $field . join(",", $values);
//        echo $sql;
        $db->exec($sql);
    }
    return $data;
});

// 获取消息数量
YZE_Hook::add_hook(YD_NOTICE_GET_NUM, function ( $data ) {
    $is_read = @$data ["is_read"];
    $user_id = @$data ["user_id"];
    $target_ids = @$data ["target_ids"];
    $target_class = @$data ["target_class"];
    if (!$user_id)
        return 0;
    $sql = new YZE_SQL ();
    $sql->from(Notice_Model::CLASS_NAME, 'n');
    $sql->count('n', 'id', 'num');
    $sql->where('n', Notice_Model::F_USER_ID, YZE_SQL::EQ, $user_id);
    if ($is_read == 1) {
        $sql->where('n', Notice_Model::F_IS_READED, YZE_SQL::EQ, 1);
    } else if($is_read != - 1){
        $sql->where('n', Notice_Model::F_IS_READED, YZE_SQL::EQ, 0);
    }
    if ($target_ids && is_array($target_ids))
        $sql->where('n', Notice_Model::F_TARGET_ID, YZE_SQL::IN, $target_ids);
    if ($target_class)
        $sql->where('n', Notice_Model::F_TARGET_CLASS, YZE_SQL::EQ, $target_class);

    return YZE_DBAImpl::getDBA()->getSingle($sql)->num;
});


// 获取消息的notice_model对象数组
YZE_Hook::add_hook(YD_NOTICE_GET_NOTICES, function ( $data ) {
    $page = @$data ["page"];
    $user_id = @$data ["user_id"];
    $is_read = @$data ["is_read"];
    $target_ids = @$data ["target_ids"];
    $target_class = @$data ["target_class"];
    $pagesize = is_numeric($data ["pagesize"]) ? intval($data ["pagesize"]) : 3;
    if (!$user_id)
        return array();
    $sql = new YZE_SQL ();
    $sql->from(Notice_Model::CLASS_NAME, 'n');
    $sql->order_by('n', Notice_Model::F_ID, YZE_SQL::DESC);
    $sql->where('n', Notice_Model::F_USER_ID, YZE_SQL::EQ, $user_id);
    if ($is_read == - 1) {
        
    } else if ($is_read == 1) {
        $sql->where('n', Notice_Model::F_IS_READED, YZE_SQL::EQ, 1);
    } else {
        $sql->where('n', Notice_Model::F_IS_READED, YZE_SQL::EQ, 0);
    }
    if ($target_ids && is_array($target_ids))
        $sql->where('n', Notice_Model::F_TARGET_ID, YZE_SQL::IN, $target_ids);
    if ($target_class)
        $sql->where('n', Notice_Model::F_TARGET_CLASS, YZE_SQL::EQ, $target_class);
    if ($page > 0) {
        $sql->limit(( intval($page) - 1 ) * $pagesize, $pagesize);
    }
    $data = ["data"=>[],"total"=>0];
    $data['data'] = YZE_DBAImpl::getDBA()->select($sql);
    $sql->clean_select()->count("n","id","total");
    $rst = YZE_DBAImpl::getDBA()->getSingle($sql);
    $data['total']= $rst->total;
    return $data;
});

// 标记已读
YZE_Hook::add_hook(YD_NOTICE_SIGN_READ, function ( $data ) {
    $user_id = @$data ["user_id"];
    $notice_ids = @$data ["notice_ids"];
    if (!$user_id || !$notice_ids || !is_array($notice_ids))
        return false;
    $sql = new YZE_SQL ();
    $sql->from(Notice_Model::CLASS_NAME, 'n');
    $sql->where('n', Notice_Model::F_USER_ID, YZE_SQL::EQ, $user_id);
    $sql->where('n', Notice_Model::F_ID, YZE_SQL::IN, $notice_ids);
    $sql->update('n', array(
        'is_readed' => 1
    ));
    YZE_DBAImpl::getDBA()->execute($sql);
    return true;
});

// 删除消息
YZE_Hook::add_hook(YD_NOTICE_DELETE, function ( $data ) {
    $user_id = @$data ["user_id"];
    $notice_ids = @$data ["notice_ids"];
    if (!$user_id || !$notice_ids || !is_array($notice_ids))
        return false;
    $sql = new YZE_SQL ();
    $sql->from(Notice_Model::CLASS_NAME, 'n');
    $sql->where('n', Notice_Model::F_USER_ID, YZE_SQL::EQ, $user_id);
    $sql->where('n', Notice_Model::F_ID, YZE_SQL::IN, $notice_ids);
    $sql->delete();
    YZE_DBAImpl::getDBA()->execute($sql);
    return true;
});
?>