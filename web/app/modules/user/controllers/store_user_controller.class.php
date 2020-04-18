<?php

namespace app\user;

use app\vendor\helper\Product_Quote_Search;
use app\vendor\helper\Store_User_Search;
use yangzie\YZE_FatalException;
use \yangzie\YZE_Resource_Controller;
use \yangzie\YZE_Request;
use \yangzie\YZE_Redirect;
use \yangzie\YZE_Session_Context;
use \yangzie\YZE_RuntimeException;
use \yangzie\YZE_JSON_View;
use yangzie\YZE_SQL;

/**
 *
 * @version $Id$
 * @package user
 */
class Store_User_Controller extends YZE_Resource_Controller
{
    public function index()
    {
        $request = $this->request;
        //$this->layout = 'tpl name';
        $store_name = $request->get_from_get("query");
        $store_user_search = new Store_User_Search();
        $store_user_search->page = $request->get_from_get("page", 1);
        $store_user_search->pagesize = $request->get_from_get("limit", 10);
        if ($store_name) {
            $store_user_search->page = 1;
            $store_user_search->store_name = trim($store_name);
        }
        $store_user_datas = $store_user_search->build_sql(new YZE_SQL(), $totalcnt);
        $this->set_view_data('store_user_cnt', $totalcnt);
        $this->set_view_data('store_user_datas', $store_user_datas);
        $this->set_view_data('yze_page_title', '店铺用户列表');
    }

    public function add()
    {
        $request = $this->request;
        $this->layout = 'empty';
        $store_user_id = $request->get_from_get('store_user_id');
        $store_user_obj = Store_User_Model::find_by_id($store_user_id);
        $this->set_view_data('store_user_obj', $store_user_obj);
        $this->set_view_data('yze_page_title', '新增分店');
    }

    public function post_add()
    {
        $request = $this->request;
        $this->layout = '';
        $store_user_id = $request->get_from_post('store_user_id');
        $store_name = $request->get_from_post('store_name');
        $store_phone = $request->get_from_post('store_phone');
        $store_address = $request->get_from_post('store_address');
        $wx_appid = $request->get_from_post('wx_appid');
        if (!$store_name) throw new YZE_FatalException("请输入店铺名称！");
        if (!$store_phone) throw new YZE_FatalException("请输入店铺联系方式！");
        if (!$store_address) throw new YZE_FatalException("请输入店铺地址！");
        if (!$wx_appid) throw new YZE_FatalException("请输入店铺AppId！");
        if ($store_user_id) {
            $update_datas = [
                "store_name" => $store_name,
                "store_phone" => $store_phone,
                "store_address" => $store_address,
                "wx_appid" => $wx_appid,
            ];
            Store_User_Model::update_by_id(trim($store_user_id), $update_datas);
        } else {
            $store_user_model = new Store_User_Model();
            $store_user_model->set(Store_User_Model::F_UUID, uuid());
            $store_user_model->set(Store_User_Model::F_STORE_NAME, $store_name);
            $store_user_model->set(Store_User_Model::F_STORE_PHONE, $store_phone);
            $store_user_model->set(Store_User_Model::F_STORE_ADDRESS, $store_address);
            $store_user_model->set(Store_User_Model::F_WX_APPID, $wx_appid);
            $store_user_model->set(Store_User_Model::F_TYPE, 'normal');
            $store_user_model->set(Store_User_Model::F_PASSWORD, base64_encode("123456"));
            $store_user_model->save();
        }
        return YZE_JSON_View::success($this);
    }

    public function post_remove()
    {
        $request = $this->request;
        $this->layout = '';
        $store_user_id = $request->get_from_post('id');
        $store_user_obj = Store_User_Model::find_by_id($store_user_id);
        if ($store_user_obj->type == 'admin') {
            throw new YZE_FatalException("无法删除总店！");
        } else {
            $store_user_obj->remove();
        }
        return YZE_JSON_View::success($this);
    }


    public function exception(YZE_RuntimeException $e)
    {
        $request = $this->request;
        $this->layout = 'error';
        //处理中出现了异常，如何处理，没有任何处理将显示500页面
        if ($request->is_post()) {
            $this->layout = '';
            return YZE_JSON_View::error($this, $e->getMessage(), $e->getCode());
        }
        //如果想显示get的返回内容可调用 :
        $this->post_result_of_json = YZE_JSON_View::error($this, $e->getMessage());
        //通过request->the_method()判断是那个方法出现的异常
        //return $this->wrapResponse($this->yourmethod())
    }
}

?>