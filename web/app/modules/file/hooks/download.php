<?php

use app\file\File_Model;
use yangzie\YZE_DBAImpl;
use yangzie\YZE_Hook;
use yangzie\YZE_Request;
use yangzie\YZE_FatalException;


/**
 * 文件下载处理
 */
YZE_Hook::add_hook(YD_COMMON_DOWNLOAD, function(){
    $request = YZE_Request::get_instance();

    $id = $request->get_from_get ( "file_id" );
    $file = File_Model::from()->where("uuid=:uuid")->getSingle( [":uuid"=> $id] );
    if(!$file)throw new YZE_FatalException("文件不存在！");
    //TODO 权限验证

    //TODO 判断是否需要密码

    //增加下载次数
    $sql = "update file as f set f.download=f.download+1 where f.id=$file->id";
    YZE_DBAImpl::getDBA()->exec($sql);

    
    // leeboo @20170628 检查文件存储在那个目录
    if(file_exists(YZE_UPLOAD_PATH . $file->path)){
        $sourceFile = YZE_UPLOAD_PATH . $file->path;
    }
    
    // 首先要判断给定的文件存在与否
    if ( ! file_exists ( $sourceFile ) ) {
            echo "404 not found";
            return;
    }
    $info = pathinfo ( $sourceFile );
    $filename = $file->name ? $file->name . "." . $info [ "extension" ] : $info [ "basename" ];
    $file_size = filesize ( $sourceFile );
    // 下载文件需要用到的头
    Header ( "Content-type: ".($file->is_img() ? "image/".$info [ "extension" ] : "application/octet-stream")."" );
    Header ( "Accept-Ranges: bytes" );
    Header ( "Accept-Length:" . $file_size );
    if(!$file->is_img())  Header ( "Content-Disposition: attachment; filename=\"" . $filename."\"" );
    echo file_get_contents ( $sourceFile );
    exit ();
});

