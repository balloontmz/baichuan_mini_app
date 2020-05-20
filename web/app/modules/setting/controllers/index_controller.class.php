<?php

namespace app\setting;

use yangzie\YZE_Hook;
use \yangzie\YZE_Resource_Controller;
use \yangzie\YZE_Request;
use \yangzie\YZE_Redirect;
use \yangzie\YZE_Session_Context;
use \yangzie\YZE_RuntimeException;
use \yangzie\YZE_JSON_View;

/**
 *
 * @version $Id$
 * @package setting
 */
class Index_Controller extends YZE_Resource_Controller
{
    public function index()
    {
        $request = $this->request;
        //$this->layout = 'tpl name';
        $login_admin = YZE_Hook::do_hook(YZE_HOOK_GET_LOGIN_USER);
        $get_swiper_pics = Setting_Model::get_by_wx_appid($login_admin->wx_appid,'swiper');
        $get_sign_pic = Setting_Model::get_by_type($login_admin->wx_appid,'sign');
        $this->set_view_data('swiper_pics', $get_swiper_pics);
        $this->set_view_data('sign_pic', $get_sign_pic);
        $this->set_view_data('yze_page_title', '系统配置');
    }

    public function add()
    {
        $request = $this->request;
        //$this->layout = 'tpl name';
        $this->set_view_data('yze_page_title', '新增配置');
    }

    public function post_add()
    {
        $request = $this->request;
        $this->layout = '';
        $datas = $request->the_post_datas();
        $login_admin = YZE_Hook::do_hook(YZE_HOOK_GET_LOGIN_USER);
        $pics_arr = explode(",", $datas["pics"]);
        if ($datas["img_type"] == "swiper") {
            for ($i = 0; $i < count($pics_arr); $i++) {
                $setting_model = new Setting_Model();
                $setting_model->set(Setting_Model::F_UUID, uuid());
                $setting_model->set(Setting_Model::F_WX_APPID, $login_admin->wx_appid);
                $setting_model->set(Setting_Model::F_PIC_URL, $pics_arr[$i]);
                $setting_model->set(Setting_Model::F_TYPE, $datas["img_type"]);
                $setting_model->save();
            }
        } else {
            $sign_img = Setting_Model::get_by_type($login_admin->wx_appid,$datas["img_type"]);
            if ($sign_img) {
                Setting_Model::update_by_id($sign_img->id, ["pic_url" => $pics_arr[0]]);
            } else {
                $setting_model = new Setting_Model();
                $setting_model->set(Setting_Model::F_UUID, uuid());
                $setting_model->set(Setting_Model::F_WX_APPID, $login_admin->wx_appid);
                $setting_model->set(Setting_Model::F_PIC_URL, $pics_arr[0]);
                $setting_model->set(Setting_Model::F_TYPE, $datas["img_type"]);
                $setting_model->save();
            }
        }

        return YZE_JSON_View::success($this);
    }
     public function post_remove(){
         $request = $this->request;
         $this->layout = '';
         $img_id= $request->get_from_post("id");
         $get_img = Setting_Model::find_by_id($img_id);
         $get_img->remove();
         return YZE_JSON_View::success($this);
     }


    public function exception(YZE_RuntimeException $e)
    {
        $request = $this->request;
        $this->layout = 'error';
        //处理中出现了异常，如何处理，没有任何处理将显示500页面
        //如果想显示get的返回内容可调用 :
        $this->post_result_of_json = YZE_JSON_View::error($this, $e->getMessage());
        //通过request->the_method()判断是那个方法出现的异常
        //return $this->wrapResponse($this->yourmethod())
    }
}

?>