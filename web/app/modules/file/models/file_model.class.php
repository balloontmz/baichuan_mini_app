<?php

namespace app\file;

use app\hr\User_Model;
use app\property\Field_Model;
use app\property\Property_Model;
use Exception;
use yangzie\YZE_DBAImpl;
use yangzie\YZE_Hook;
use yangzie\YZE_Model;
use const YZE_HOOK_GET_LOGIN_USER;
use const YZE_UPLOAD_PATH;

/**
 * 文件
 * @version $Id$
 * @package common
 *         
 */
class File_Model extends YZE_Model {

    const TABLE = "file";
    const VERSION = 'modified_on';
    const MODULE_NAME = "file";
    const KEY_NAME = "id";
    const CLASS_NAME = 'app\file\File_Model';

    /**
     *
     * @var integer
     */
    const F_ID = "id";

    /**
     *
     * @var date
     */
    const F_CREATED_ON = "created_on";

    /**
     *
     * @var date
     */
    const F_MODIFIED_ON = "modified_on";

    /**
     * 文件名
     * @var string
     */
    const F_NAME = "name";

    /**
     * 存储的url相对路径
     * @var string
     */
    const F_PATH = "path";

    /**
     * 字节大小
     * @var integer
     */
    const F_SIZE = "size";

    /**
     * 扩展名
     * @var string
     */
    const F_EXT = "ext";

    /**
     * 版本号
     * @var string
     */
    const F_VERSION = "version";

    /**
     *
     * @var integer
     */
    const F_COPY_FILE_ID = "copy_file_id";

    /**
     * 目标记录
     * @var integer
     */
    const F_TARGET_ID = "target_id";

    /**
     * 目标表
     * @var string
     */
    const F_TARGET_CLASS = "target_class";

    /**
     * 是否删除
     * @var integer
     */
    const F_IS_DELETED = "is_deleted";

    /**
     * 上传时间
     * @var date
     */
    const F_DATE = "date";

    /**
     *
     * @var integer
     */
    const F_USER_ID = "user_id";
    const F_TYPE_ID = "type_id";
    const F_PWD = "pwd";
    const F_DOWNLOAD = "download";
    const F_NUMBER = "number";
    const F_UUID = "uuid";

    public static $columns = array(
        'id' => array(
            'type' => 'integer',
            'null' => false,
            'length' => '11',
            'default' => ''
        ),
        'type_id' => array(
            'type' => 'integer',
            'null' => false,
            'length' => '11',
            'default' => ''
        ),
        'created_on' => array(
            'type' => 'date',
            'null' => false,
            'length' => '',
            'default' => 'CURRENT_TIMESTAMP'
        ),
        'modified_on' => array(
            'type' => 'date',
            'null' => false,
            'length' => '',
            'default' => 'CURRENT_TIMESTAMP'
        ),
        'name' => array(
            'type' => 'string',
            'null' => false,
            'length' => '45',
            'default' => ''
        ),
        'path' => array(
            'type' => 'string',
            'null' => false,
            'length' => '45',
            'default' => ''
        ),
        'size' => array(
            'type' => 'integer',
            'null' => false,
            'length' => '11',
            'default' => ''
        ),
        'ext' => array(
            'type' => 'string',
            'null' => true,
            'length' => '45',
            'default' => ''
        ),
        'version' => array(
            'type' => 'string',
            'null' => true,
            'length' => '45',
            'default' => ''
        ),
        'copy_file_id' => array(
            'type' => 'integer',
            'null' => false,
            'length' => '11',
            'default' => '0'
        ),
        'target_id' => array(
            'type' => 'integer',
            'null' => false,
            'length' => '11',
            'default' => ''
        ),
        'target_class' => array(
            'type' => 'string',
            'null' => false,
            'length' => '45',
            'default' => ''
        ),
        'is_deleted' => array(
            'type' => 'integer',
            'null' => false,
            'length' => '1',
            'default' => '0'
        ),
        'date' => array(
            'type' => 'date',
            'null' => false,
            'length' => '',
            'default' => ''
        ),
        'user_id' => array(
            'type' => 'integer',
            'null' => false,
            'length' => '11',
            'default' => ''
        ),
        'type_id' => array(
            'type' => 'integer',
            'null' => false,
            'length' => '11',
            'default' => ''
        ),
        'pwd' => array(
            'type' => 'string',
            'null' => false,
            'length' => '45',
            'default' => ''
        ),
        'download' => array(
            'type' => 'integer',
            'null' => false,
            'length' => '11',
            'default' => '0'
        ),
        'number' => array(
            'type' => 'string',
            'null' => false,
            'length' => '45',
            'default' => ''
        ),
        'uuid' => array(
            'type' => 'string',
            'null' => false,
            'length' => '45',
            'default' => ''
        ),
    );
    // array('attr'=>array('from'=>'id','to'=>'id','class'=>'','type'=>'one-one||one-many') )
    // $this->attr
    protected $objects = array();

    /**
     *
     * @see YZE_Model::$unique_key
     */
    protected $unique_key = array(
        'id' => 'PRIMARY',
        'target_class' => 'target_table_id',
        'target_id' => 'target_table_id',
        'user_id' => 'fk_file_user1_idx'
    );
    private $user;

    public function get_user() {
        if (!$this->user) {
            $this->user = User_Model::find_by_id($this->get(self::F_USER_ID));
        }
        return $this->user;
    }

    /**
     *
     * @return File_Model
     */
    public function set_user(User_Model $new) {
        $this->user = $new;
        return $this;
    }

    /**
     * 返回指定的资料类型属性值
     *
     * @author leeboo
     * @param string $file_type
     * @return Property_Model
     */
    public static function get_type($file_type, $auto_create = false) {
        return Property_Model::get_by_name($file_type, self::TABLE, self::F_TYPE_ID, $auto_create, true);
    }

    /**

     * 返回自己所有的版本，按照版本号递减
     * @author leeboo
     * @return array
     */
    public function get_versions() {
        return File_Model::from()->where("copy_file_id=:id")->order_By("version", "desc")->select([":id" => $this->id]);
    }

    /**
     * 主体的一个文件,适用于查找只会有一个的情况，如果文件有多个，请用get_files()
     * @param unknown $target_class
     * @param unknown $target_id
     * @author leeboo
     * @return Field_Model
     */
    public static function get_file($target_class, $target_id) {
        return File_Model::from()->where("target_class=:tb and target_id=:tid and copy_file_id=0 and is_deleted = 0 ")->getSingle([
                ":tb" => $target_class,
                ":tid" => $target_id,
            ]);
    }

    /**
     * 主体的文件
     * 
     * leeboo@20180923 已经删除的文件不在查询出来
     * 
     * @param unknown $target_class
     * @param unknown $target_id
     * @param int $type_id null 查询所有, 具体的数字,则只查询具体的数字的type
     * @author leeboo
     * @return array(Field_Model)
     */
    public static function get_files($target_class, $target_id, $type_id = null, $copy_file_id=null) {
        $args = [":tb" => $target_class, ":tid" => $target_id];
        if (isset($type_id)) {
            $args[":type_id"] = $type_id;
        }
        return File_Model::from()->where("target_class=:tb and is_deleted=0 and target_id=:tid  "
            .( $copy_file_id ? " and copy_file_id=". intval($copy_file_id) : "" )." " . ($type_id ? " and type_id=:type_id" : ""))
                ->select($args);
    }

    /**
     * 输出文件的地址，如果是图片显示缩略图
     * 
     */
    public function showLink($imageMaxWidth = null, $showFileName = false) {
        if ($this->is_img()) {

            //生成缩略图 #11258 @modified zhangwengang 20190130
            $thumb_img = self::create_thumb_img(YZE_UPLOAD_PATH . $this->path, $imageMaxWidth, $imageMaxWidth);
            $src = $thumb_img !== false ? "data:image/jpeg;base64," . base64_encode($thumb_img) : "/common/download?file_id=" . $this->uuid;

            echo "<a href='/common/download?file_id=" . $this->uuid . "' target='_blank' class='yd-img-zoom'><img style='width:{$imageMaxWidth}' src='$src'>" . ($showFileName ? $this->name : "") . "</a>";
        } else {
            echo "<a href='/common/download?file_id=" . $this->uuid . "' target='_blank'>".strtoupper( $this->ext) . ($showFileName ? $this->name : "") . "</a>";
        }
    }

    /**
     * 重写删除文件的方法，删除文件时，历史版本也删除
     * 但是不删除磁盘上的文件本身, 这样别人用到这个文件的地址也不会影响
     * @author liulongxing
     * {@inheritDoc}
     * @see YZE_Model::remove()
     */
    public function remove() {
        try {
            //如果当前文件只是一个历史版本，那么只删除它
            if ($this->copy_file_id) {
                parent::remove();
            }
            //不是历史版本的文件，删除它的同时，删除其所有历史版本
            else {
                foreach (File_Model::from()->where("copy_file_id=:copy_file_id")->select(array(":copy_file_id" => $this->id)) as $file) {
                    $file->remove();
                }
                parent::remove();
                
            }
        } catch (Exception $e) {
            //如果有异常，删除失败，那么就标记删除
            $this->set("is_deleted", 1)->save();
        }
    }

    /**
     * 当前文件的文件大小，转为合适的单位
     * @author liulongxing
     */
    public function formate_file_size() {
        $size = $this->size;
        foreach (array("B", "KB", "MB") as $unit) {
            if ($size < 1024) {
                return round($size, 2) . $unit;
            }
            $size = $size / 1024;
        }

        return round($size, 2) . "GB";
    }

    /**
     * 保存文件
     * @author liulongxing
     * @param unknown $name				文件名
     * @param unknown $path				文件路径
     * @param unknown $size				文件大小
     * @param unknown $type_id			文件类型id，property表的一条记录id
     * @param unknown $copy_file_id		更新文件id
     * @param unknown $target_id		主体id
     * @param unknown $target_class		主体表名
     * @return File_Model
     */
    public static function save_file($name, $path, $size, $type_id, $copy_file_id, $target_id, $target_class) {

        $user = YZE_Hook::do_hook(YZE_HOOK_GET_LOGIN_USER);
        //如果$copy_file_id存在，则是重传
        if ($copy_file_id) {
            $file = File_Model::from()->where("id=:id and copy_file_id=0")->getSingle(array(":id" => $copy_file_id));
            if (!$file) {
                return null;
            }

            // 复制一条当前$file，将其copy_file_id 设为 $file的id
            $old_file = clone $file;
            $old_file->id = null;
            $old_file->uuid = self::uuid();
            $old_file->copy_file_id = $file->id;
            $old_file->save();

            $file->version = $file->version + 1;
        } else {
            $file = new File_Model();
            $file->set("uuid", self::uuid());
        }

        //通过文件名获取拓展名
        $extend = explode(".", $name);
        $extend = $extend[count($extend) - 1]; //文件拓展名

        $file->set("target_id", $target_id ? $target_id : "")
            ->set("target_class", $target_class ? $target_class : "")
            ->set("name", $name)
            ->set("path", $path)
            ->set("size", $size)
            ->set("ext", $extend)
            ->set("date", date("Y-m-d H:i:s"))
            ->set("user_id", $user->id)
            ->set("type_id", $type_id ? $type_id : 0)
            ->save();
        return $file;
    }

    /**
     * 判断当前文件是否是图片
     * @author liulongxing
     * @return bool
     */
    public function is_img() {
        $img_ext_array = array("jpg", "png", "bmp", "gif", "jpeg");
        return in_array($this->ext, $img_ext_array);
    }

    /**
     * 更新文件的target_class target_id
     *
     * @param $ids uuid
     * @param $target_class
     * @param $target_id
     * @param $copy 如果传入true，则表示ids中的文件会被复制出来和target关联
     * @author zhangwengang 20190424 leeboo 20191203
     * @throws
     */
    public static function update_file_target($ids, $target_class, $target_id, $copy=false) {
        if (is_string($ids)) {
            $ids = explode(",", $ids);
            $ids = array_filter($ids);
        }
        if (!is_array($ids))
            return;

        if ( ! $copy ){
            YZE_DBAImpl::getDBA()
                ->update(File_Model::TABLE, "target_class=:cls, target_id=:tid", "uuid in ('".join("','", $ids)."')",[":cls"=>$target_class,":tid"=>$target_id]);
            return;
        }

        foreach (find_by_uuids(File_Model::CLASS_NAME, $ids) as $file) {
            // 复制一条当前$file 关联，将其copy_file_id 设为 $file的id
            $new_file = clone $file;
            $new_file->id = null;
            $new_file->uuid = uuid();
            $new_file->target_id = $target_id;
            $new_file->target_class = $target_class;
            $new_file->copy_file_id = $file->id;
            $new_file->save();
        }

    }

    /**
     * 给出图片 绝对路径名，按给定的宽度、高度生成缩略图。
     * 若给定的宽度、高度比图片原宽度、高度小，则用原来的宽度、高度生成
     * @author zhangwengang
     * @date 20190130
     * @param $filename
     * @param $new_width
     * @param $new_height
     * @return 图片的二进制流
     */
    public function create_thumb_img($filename, $new_width, $new_height) {
        //获取文件的后缀
        $ext = strtolower(strrchr($filename, '.'));

        //判断文件格式
        switch ($ext) {
            case '.jpeg' :
            case '.jpg' :
                $type = 'jpeg';
                break;
            case '.gif':
                $type = 'gif';
                break;
            case '.png':
                $type = 'png';
                break;
            default:
                return false;
        }

        if ($new_width < 0 || $new_height < 0)
            return false;

        list( $width, $height ) = getimagesize($filename);
        $new_height = $height > $new_height ? $new_height : $height;
        $new_width = $width > $new_width ? $new_width : $width;

        //拼接打开图片的函数
        $open_fn = 'imagecreatefrom' . $type;

        // 重新取样
        $image_p = imagecreatetruecolor($new_width, $new_height);
        $color=imagecolorallocate($image_p,255,255,255);
        imagecolortransparent($image_p,$color);
        imagefill($image_p,0,0,$color);
        $image = $open_fn($filename);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        // 输出
        ob_start();
        imagejpeg($image_p, null, 40);
        $ret = ob_get_contents();
        ob_end_clean();

        return $ret;
    }

    /**
     * 删除宿主已有的文件
     *
     * @param $uuid
     * @param $target_class
     * @param $target_id
     * @throws \yangzie\YZE_FatalException
     */
    public static function removeTargetFilesNotIn($uuid, $target_class, $target_id){
        File_Model::from()->where("target_class=:cls and target_id=:tid and uuid not in('"
            . join("','", $uuid) . "')")->delete([":cls" => $target_class, ":tid" => $target_id]);
    }
}

?>