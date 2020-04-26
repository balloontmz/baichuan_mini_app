<?php
/**
 * author      :  LiZhen
 * createTime  :  2019/12/9 13:56
 * description :
 */

namespace app\api;


use app\user\User_Model;
use yangzie\YZE_FatalException;
use yangzie\YZE_JSON_View;
use yangzie\YZE_Resource_Controller;
use yangzie\YZE_RuntimeException;

define("OK", 0);
define("IllegalAesKey", -41001);
define("IllegalIv", -41002);
define("IllegalBuffer", -41003);
define("DecodeBase64Error", -41004);


class Phone_Controller extends YZE_Resource_Controller
{
    public function index()
    {
        $request = $this->request;
        $this->layout = '';
        // return YZE_JSON_View::success($this);
    }

    public function post_index()
    {
        $request = $this->request;
        $this->layout = '';
        $wx_openid = $request->get_from_post("openid");
        $sessionKey = $request->get_from_post("session_key");
        $encryptedData = $request->get_from_post("encryptedData");
        $iv = $request->get_from_post("iv");
        $datas = $this->decryptData($sessionKey, $encryptedData, $iv);
        $datas = json_decode($datas);
        $user = User_Model::find_by_openid($wx_openid);
        if (!$user) throw new YZE_FatalException("没有这个用户");
        User_Model::update_by_id($user->id, ["cellphone" => $datas->phoneNumber]);
        return YZE_JSON_View::success($this, ["phoneNumber" => $datas->phoneNumber]);
    }

    private function decryptData($sessionKey, $encryptedData, $iv)
    {
        if (strlen($sessionKey) != 24) {
            throw new YZE_FatalException("encodingAesKey 非法", IllegalAesKey);
        }
        $aesKey = base64_decode($sessionKey);

        if (strlen($iv) != 24) {
            throw new YZE_FatalException("iv非法", IllegalIv);
        }
        $aesIV = base64_decode($iv);

        $aesCipher = base64_decode($encryptedData);

        $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

        $dataObj = json_decode($result);
        if ($dataObj == NULL) {
            throw new YZE_FatalException("aes 解密失败", IllegalBuffer);
        }
//        if ($dataObj->watermark->appid != YD_WECHAT_APPLET_APPID) {
//            throw new YZE_FatalException("aes 解密失败", IllegalBuffer);
//        }
        $data = $result;
        //return OK;
        return $data;
    }

    protected function check_request_token()
    {
        return;
    }

    public function exception(YZE_RuntimeException $e)
    {
        $this->layout = '';
        return YZE_JSON_View::error($this, $e->getMessage(), $e->getCode());
    }

}