<?php

/**
 * 定义上传文件完成后的 额外逻辑
 *
 * @author guoxingcai
 */

namespace app;

use app\file\File_Model;
use yangzie\YZE_Hook;
use yangzie\YZE_Request;
use const YD_COMMON_UPLOADED;
use const YZE_HOOK_GET_LOGIN_USER;

// $data["file"]="文件的基本信息" $data["file_path"]="文件上传后的路径"
YZE_Hook::add_hook(YD_COMMON_UPLOADED, function ( $data ) {
    $result  = $data;
    $file    = $data ["extra"];
    $path    = $data ["path"];
    $request = YZE_Request::get_instance();
    $file_id = $request->get_from_get("file_id"); //传入时，就是对哪个文件进行重传
    if ($file_id) {
        $find_file = File_Model::find_by_id($file_id);
    }
    $path        = $data["path"]; //保存到的路径
    $upload_file = $data["extra"]; //文件
    //哪个目标主体的附件：
    $target_id    = $find_file ? $find_file->target_id : $request->get_from_get("target_id");
    $target_class = $find_file ? $find_file->target_class : $request->get_from_get("target_class");
    $type_id = $request->get_from_get("type_id") ? $request->get_from_get("type_id") : null;

    $file         = File_Model::save_file($upload_file["name"], $path, $upload_file["size"], $type_id, $file_id, $target_id, $target_class);
  
    $result ["id"] = $file->uuid;
    $result ["extra"]["date"] = $file->date;
    return $result;
});
?>
