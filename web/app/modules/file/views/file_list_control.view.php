<?php

namespace app\file;

use yangzie\YZE_ACL;
use yangzie\YZE_Request;
use yangzie\YZE_Resource_Controller;
use yangzie\YZE_Simple_View;
use yangzie\YZE_View_Component;
use yangzie\YZE_Hook;

/**
 * 文件上传和展示组件:
 *
 * 上传提供上传新文件和选择已有文件的功能，上传新文件时post 数据的name是<strong>file_id</strong>，
 *
 * 选择已有文件时post 数据的name是<strong>exist_file_id</strong>
 *
 * 由于文件上传总是先上传并保存到file表，所以具体功能在使用时对于file_id可以通过file_model::update_file_target来让文件和宿主关联
 *
 * 而对于exist_file_id则在调用file_model::update_file_target 需要传入$copy=true这个参数，要不然会影响之前的文件和宿主的关联
 *
 *
 * 如果是回显操作，宿主已经存在的文件的post name是<strong>my_file_id</strong>, 在重新保存时需要通过 Email_Model::removeTargetFilesNotIn 把以前在，但是本次操作不在的文件删除掉重新保存
 *
 * 文件和宿主关系的保存操作可以调用File_List_Control_View::saveTargetFile，他封装了新上传文件，选择已有文件和修改宿主已经关联文件的逻辑
 *
 * 如果是宿主中保存了文件的地址等信息，而不是通过文件表的target来关联，则需要通过对应的id自行查找出path进行处理
 *
 * @author ydhlleeboo
 *
 */
class File_List_Control_View extends YZE_View_Component
{
    /**
     * 目标对象的类名
     * @var string
     */
    public $target_class;
    /**
     * 目标对象的id
     * @var int
     */
    public $target_id;

    /**
     * 组件id，没有设置时自动生成一个随机id；用于处理区别当多个该组件同时使用的情况
     * @var string
     */
    public $id;

    /**
     * 要查询的文件类型,不传入,则查询所有
     * @var type
     */
    public $type_id;

    public $allowUpload = true;
    public $allowRemove = true;


    public function __construct($data, YZE_Resource_Controller $controller)
    {
        parent::__construct($data, $controller);
        foreach ($data as $name => $value) {
            $this->$name = $value;
        }
    }

    /**
     * 封装了新上传文件，选择已有文件和修改宿主已经关联文件的逻辑
     *
     * @param $target_classs
     * @param $target_id
     * @throws \yangzie\YZE_FatalException
     */
    public static function saveTargetFile($target_classs, $target_id){
        $request = YZE_Request::get_instance();
        $new_file_uuids = $request->get_from_post("file_id"); //新附件
        $exist_file_uuids = $request->get_from_post("exist_file_id"); //选择以前的文件作为附件
        $my_file_id = $request->get_from_post("my_file_id"); //该宿主已经存在的附件
        //附件
        File_Model::removeTargetFilesNotIn((array) $my_file_id);
        if ($new_file_uuids) {
            File_Model::update_file_target($new_file_uuids, $target_classs, $target_id);
        }

        if ($exist_file_uuids) {
            File_Model::update_file_target($exist_file_uuids, $target_classs, $target_id, true);
        }
    }

    protected function output_component()
    {
        $files = File_Model::get_files($this->target_class, $this->target_id, $this->type_id);
//        $login_user = YZE_Hook::do_hook(YZE_HOOK_GET_LOGIN_USER);
        if (!$this->id) {
            $this->id = "flc" . uniqid() . rand(1, 100);
        }
        ?>
        <table id="<?= $this->id ?>" class="layui-table m-0">
            <?php foreach ($files as $file) {
                ?>
                <tr id="<?= $file->uuid ?>">
                    <td><a href="/common/download?file_id=<?= $file->uuid ?>" target="_blank"><?= $file->name ?></a>
                        <input type="hidden" class="file-id" name="my_file_id[]" value="<?= $file->uuid ?>">
                    </td>
                    <td><?= $file->formate_file_size() ?></td>
                    <?php if ($this->allowRemove) { ?>
                        <td>
                            <button class="layui-btn layui-btn-danger layui-btn-xs yd-remove-self"
                                    data-remove-from="#<?= $file->uuid ?>" type="button">删除
                            </button>
                        </td>
                    <?php } ?>
                </tr>
                <?php
            }
            if ($this->allowUpload) {
                ?>

                <tfoot>
                <tr>
                    <th colspan="<?= $this->allowRemove ? 3 : 2 ?>">
                        <button type="button" class="layui-btn layui-btn-primary layui-btn-sm yd-upload"
                                data-upload-mime="*/*"
                                data-url="/common/upload"
                                data-multi-selection="1" ;
                                data-fileadd-callback="file_add_<?= $this->id ?>"
                                data-error-callback="file_error_<?= $this->id ?>"
                                data-progress-callback="file_progress_<?= $this->id ?>"
                                data-uploaded-callback="file_uploaded_<?= $this->id ?>"
                        ><i class="iconfont icon-shangchuan"></i>上传新文件 </button>
                        <button type="button" class="layui-btn layui-btn-primary layui-btn-sm yd-dialog" data-in-top="1" data-backdrop="normal" data-dialog-id="fd_<?= $this->id ?>"
                                data-url="/files.dialog?cb=selected_file_<?= $this->id ?>">
                            <i class="iconfont icon-fujian"></i> 选择已上传文件
                        </button>
                    </th>
                </tr>
                </tfoot>
                <?php
            } ?>
        </table>

        <script type="text/html" id="<?= $this->id ?>-tpl">
            <tr id="{{file_id}}">
                <td>{{filename}}
                    <input type="hidden" class="file-id" name="{{field_name}}[]" value="{{file_id}}">
                    <div class="progress" style="height: 1px;">
                        <div class="progress-bar" role="progressbar" style="width: {{percent}}%;"></div>
                    </div>
                    <div class="file-error text-danger"></div>
                </td>
                <td>{{file_size}}</td>
                <td>
                    <button class="layui-btn layui-btn-danger layui-btn-xs yd-remove-self"
                            data-remove-from="#{{file_id}}" type="button">删除
                    </button>
                </td>
            </tr>
        </script>
        <script>
            function selected_file_<?= $this->id ?>(files) {
                var ids = Object.keys(files);
                for(var i=0; i < ids.length; i++) {
                    var file_id = ids[i];
                    var file_name = files[file_id].name;
                    var file_size = plupload.formatSize( files[file_id].size );

                    $("#<?= $this->id ?>").append($("#<?= $this->id ?>-tpl").html()
                        .replace(/{{field_name}}/ig, "exist_file_id")
                        .replace(/{{file_size}}/ig, file_size)
                        .replace(/{{file_id}}/ig, file_id)
                    );
                    $("#<?= $this->id ?> .progress").remove();

                }
                top.YDJS.hide_dialog("fd_<?= $this->id ?>");
            }

            function pareseTpl_<?= $this->id ?>(file, percent) {
                return $("#<?= $this->id ?>-tpl").html()
                    .replace(/{{field_name}}/ig, "file_id")
                    .replace(/{{file_size}}/ig, plupload.formatSize(file.size))
                    .replace(/{{file_id}}/ig, file.id)
                    .replace(/{{percent}}/ig, percent);
            }

            function file_add_<?= $this->id ?>(up, files) {
                for (var i = 0; i < files.length; i++) {
                    $("#<?= $this->id ?>").append(pareseTpl_<?= $this->id ?>(files[i], 0));
                }
            }

            function file_error_<?= $this->id ?>(up, err) {
                YDJS.alert(err, "文件上传出错", YDJS.ICON_ERROR);
            }

            function file_progress_<?= $this->id ?>(up, file) {
                $("#" + file.id + " .progress-bar").css('width', file.percent + "%");
            }

            function file_uploaded_<?= $this->id ?>(up, file, rst) {
                if (rst.success) {
                    $("#" + file.id + " .file-id").val(rst.data.id);
                } else {
                    $("#" + file.id + " .file-error").text(file.name + " (" + plupload.formatSize(file.size) + ") 上传失败:" + rst.msg);

                }
            }
        </script>
        <?php
    }
}

?>