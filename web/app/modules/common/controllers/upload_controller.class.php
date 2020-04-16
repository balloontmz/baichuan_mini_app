<?php

namespace app\common;

use Uploader;
use \yangzie\YZE_Resource_Controller;
use \yangzie\YZE_RuntimeException;
use yangzie\YZE_Hook;
use yangzie\YZE_JSON_View;
use yangzie\yze_remove_abs_path;

/**
 *
 * @version $Id$
 * @package common
 *         
 */
class Upload_Controller extends YZE_Resource_Controller {

    protected $post_result_of_json = array();

    public function index() {
        $request = $this->request;
        $this->layout = '';
    }

    public function download() {
        $this->layout = "";
        YZE_Hook::do_hook(YD_COMMON_DOWNLOAD);
        exit();
    }

    public function post_index() {
        $request = $this->request;
        $this->layout = "";

        $upload_file_name = "file";
        if ($request->get_from_get("type") == "ckeditor") {
            $upload_file_name = "upload";
        }
        $path = urldecode($request->get_from_get("path"));
        $action = $request->get_from_get("action");
        if (!$path)
            $path = YZE_UPLOAD_PATH;
        try {
            $upload = new Uploader($path);
            $res = $upload->upload($upload_file_name);
            // 该控制器的作用是只负责上传文件处理，根据情况触发hook
            // path=savepath 表示上传保存的路径 leeboo @ 150827
            // 用Uploader类处理上传
            // action 用于hook中区分当前的文件操作是什么，自己该不该处理
            if ($res) { //
                $folder = strtr(YZE_UPLOAD_PATH, "\\", "/");
                $res = strtr($res, "\\", "/");
                //leeboo @170628 可能文件有存储在不能直接访问的路径里；这里hack一下
                if (preg_match("{" . $folder . "}", $res)) {
                    $filepath = \yangzie\yze_remove_abs_path($res, YZE_UPLOAD_PATH);
                }
                if ($filepath) {
                    $filepath = trim($filepath, "/");
                    // hook参数修改，按照yze框架公共模块设计来 liulongxing@20170505
                    $result = array(
                        "id" => "",
                        "path" => $filepath,
                        "extra" => $_FILES ["file"] ? $_FILES ["file"]:$_FILES ["upload"],
                        "action" => $action
                    );

                    $result = YZE_Hook::do_hook(YD_COMMON_UPLOADED, $result);
                    return $this->handleResponse($result);
                }
            }
            return $this->handleResponse(null, "上传失败");
        } catch (\Exception $e) {
            return $this->handleResponse(null, $e->getMessage());
        }
    }

    private function handleResponse($successData = null, $errorMsg = null) {
        $request = $this->request;
        if ($request->get_from_get("type") == "ckeditor") {
            if ($request->get_from_get("responseType") == "json") {//拖动上传
                
                $json = new \yangzie\YZE_JSON_View($this, array(
                    "uploaded" => $successData ? 1 : 0,
                    "fileName" => $successData['name'],
                    "url" => UPLOAD_SITE_URI . $successData['path'],
                    "error" => array(
                        "message" => $errorMsg
                    )
                ));
                echo $json->output();die();
            }
            // 上传成功,输出ckedit的js代码, 文件浏览器选择或者文件对话框上传
            echo '<script type="text/javascript">';
            
            $callback = $request->get_from_get("CKEditorFuncNum");
            if ($successData) {
                echo 'window.parent.CKEDITOR.tools.callFunction(' . $callback . ',"' . UPLOAD_SITE_URI . $successData['path'] . '","")';
            }  // 输出错误提示
            else {
                echo 'window.parent.CKEDITOR.tools.callFunction(' . $callback . ',"","' . $errorMsg . '")';
            }
            echo '</script>';
            die();
        } else {
            if ($successData) {
                return YZE_JSON_View::success($this, $successData);
            } else {
                return YZE_JSON_View::error($this, $errorMsg);
            }
        }
    }

    protected function check_request_token() {
        return;
    }

    public function exception(YZE_RuntimeException $e) {
        $request = $this->request;
        $this->layout = 'error';
    }

    public function get_response_guid() {
        return null;
    }

    public function cleanup() {
        parent::cleanup();
    }

}

?>