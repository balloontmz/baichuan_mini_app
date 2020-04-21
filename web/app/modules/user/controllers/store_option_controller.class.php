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
class Store_Option_Controller extends YZE_Resource_Controller
{
    public function index()
    {
        $request = $this->request;
        //$this->layout = 'tpl name';
        $store_user_id = $request->get_from_get('store_user_id');
        $store_user = Store_Option_Model::get_by_su_id($store_user_id);
        $this->set_view_data('store_user', $store_user);
        $this->set_view_data('yze_page_title', '店铺配置');
    }

    public function post_add()
    {
        $request = $this->request;
        $this->layout = '';
        $datas = $request->the_post_datas();
        for ($i = 0; $i < count($datas['first_product_id']); $i++) {
            if (!$datas['first_product_id'][$i]) continue;
            $store_option_obj = Store_Option_Model::get_by_fpsu_id($datas['store_user_id'], $datas['first_product_id'][$i]);
            if ($store_option_obj) {
                Store_Option_Model::update_by_id($store_option_obj->id, ["symbol" => $datas['symbol'][$i], "store_price" => $datas['price'][$i]]);
            } else {
                $store_option_model = new Store_Option_Model();
                $store_option_model->set(Store_Option_Model::F_UUID, uuid());
                $store_option_model->set(Store_Option_Model::F_STORE_USER_ID, $datas['store_user_id']);
                $store_option_model->set(Store_Option_Model::F_FIRST_PRODUCT_ID, $datas['first_product_id'][$i]);
                $store_option_model->set(Store_Option_Model::F_SYMBOL, $datas['symbol'][$i]);
                $store_option_model->set(Store_Option_Model::F_STORE_PRICE, $datas['price'][$i]);
                $store_option_model->save();
            }

        }
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