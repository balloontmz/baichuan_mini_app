<?php

namespace app\user;

use \yangzie\YZE_Resource_Controller;
use \yangzie\YZE_Request;
use \yangzie\YZE_Redirect;
use \yangzie\YZE_Session_Context;
use \yangzie\YZE_RuntimeException;
use \yangzie\YZE_JSON_View;

/**
 *
 * @version $Id$
 * @package user
 */
class Profile_Controller extends YZE_Resource_Controller
{
    public function index()
    {
        $request = $this->request;
        //$this->layout = 'tpl name';
        $uuid = $request->get_from_get('uuid');
        $store_user = Store_User_Model::find_by_uuid($uuid);
        $this->set_view_data('store_user', $store_user);
        $this->set_view_data('yze_page_title', '基本资料');
    }

    public function post_update()
    {
        $request = $this->request;
        $this->layout = '';
        $id = $request->get_from_post('id');
        $update_arr = [
            "store_name" => $request->get_from_post('store_name'),
            "store_phone" => $request->get_from_post('store_phone'),
            "store_address" => $request->get_from_post('store_address'),
            "password" => base64_encode($request->get_from_post('password'))
        ];
        Store_User_Model::update_by_id($id, $update_arr);
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