<?php

namespace app\api;

use app\attribute\First_Attribute_Model;
use app\attribute\Quote_Standard_Model;
use app\attribute\Second_Attribute_Model;
use app\order\Order_Model;
use app\product\First_Product_Model;
use app\product\Product_Model;
use app\product_quote\Product_Price_Model;
use app\product_quote\Product_Quote_Module;
use app\setting\Setting_Model;
use app\user\Store_Option_Model;
use app\user\Store_User_Model;
use app\user\User_Model;
use app\vendor\helper\Product_Search;
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
 * @package api
 */
class Index_Controller extends YZE_Resource_Controller
{
    public function index()
    {
        $request = $this->request;
        //$this->layout = 'tpl name';
        $this->set_view_data('yze_page_title', 'this is controller index');
    }

    //获取一级产品
    public function post_first_product()
    {
        $request = $this->request;
        $this->layout = '';
        $type_id = $request->get_from_post('type_id');
        $first_product = First_Product_Model::get_by_type_id($type_id);
        $datas = [];
        $i = 0;
        foreach ($first_product as $item) {
            $datas[$i]['first_name'] = $item->name;
            $datas[$i]['id'] = $item->id;
            $i++;
        }
        return YZE_JSON_View::success($this, $datas);
    }

    //获取产品
    public function post_product()
    {
        $request = $this->request;
        $this->layout = '';
        $first_product_id = $request->get_from_post('first_product_id');
        $first_product = Product_Model::getProductByFid($first_product_id);
        $datas = [];
        $i = 0;
        foreach ($first_product as $item) {
            $datas[$i]['second_name'] = $item->name;
            $datas[$i]['comment'] = $item->comment;
            $datas[$i]['id'] = $item->id;
            $i++;
        }
        return YZE_JSON_View::success($this, $datas);
    }

    //获取属性
    public function post_attribute()
    {
        $request = $this->request;
        $this->layout = '';
        $product_id = $request->get_from_post('product_id');
        $product = Product_Price_Model::get_by_product_id($product_id);
        if ($product) {
            $second_attri = explode("_", $product[0]->second_attribute_ids);
            $second_arr = [];
            $second_arr_name = [];
            $datas = [];
            for ($j = 0; $j < count($product); $j++) {
                $second_attribute_ids = $product[$j]->second_attribute_ids;
                $second_arr[$j] = explode("_", $second_attribute_ids);
            }
            for ($i = 0; $i < count($second_arr); $i++) {
                for ($j = 0; $j < count($second_arr[$i]); $j++) {
                    $second_arr_name[$j]['second_name'] .= Second_Attribute_Model::find_by_id($second_arr[$i][$j])->name . ",";
                    $second_arr_name[$j]['id'] .= $second_arr[$i][$j] . ",";
                }
            }
            $second_name_obj_arr = [];
            for ($i = 0; $i < count($second_arr_name); $i++) {
                $second_arr_name_a[$i] = array_unique(explode(",", $second_arr_name[$i]['second_name'])); //转化为数组后再去重
                $second_arr_id_a[$i] = array_unique(explode(",", $second_arr_name[$i]['id']));  //去重

                for ($j = 0; $j < count($second_arr_name_a[$i]); $j++) {
                    if ($second_arr_name_a[$i][$j] != "" && $second_arr_id_a[$i][$j] != "") {//去掉空的
                        $second_name_obj_arr[$i][$j]['attr_value'] = trim($second_arr_name_a[$i][$j]);
                        $second_name_obj_arr[$i][$j]['id'] = intval($second_arr_id_a[$i][$j]);
                        $second_name_obj_arr[$i][$j]['attr_id'] = Second_Attribute_Model::find_by_id($second_arr_id_a[$i][$j])->first_attribute_id;
                    }
                }
            }
            for ($i = 0; $i < count($second_attri); $i++) {
                $datas[$i]['attr_name'] = First_Attribute_Model::find_by_id(Second_Attribute_Model::find_by_id($second_attri[$i])->first_attribute_id)->name;
                $datas[$i]['child'] = array_values($second_name_obj_arr[$i]);
            }
            return YZE_JSON_View::success($this, $datas);
        } else {
            return YZE_JSON_View::success($this);
        }
    }

    //根据组合和产品id获取报价标准
    public function post_product_quote()
    {
        $request = $this->request;
        $this->layout = '';
        $filter = $request->get_from_post('filter');
        $product_id = $request->get_from_post('second_id');
        $wx_appid = $request->get_from_post('wx_appid');
        $wx_app_role = Store_User_Model::get_by_wx_appid(trim($wx_appid));

        $first_product_id = Product_Model::find_by_id($product_id)->first_product_id; //找出一级产品id
        $store_second = Store_Option_Model::get_by_fpsu_id($wx_app_role->id, $first_product_id);

        $result = Product_Price_Model::get_by_filter($product_id, trim($filter));
        $quote_standard_id_arr = explode(",", $result->quote_standard_ids);
        $price_arr = explode(",", $result->price);
        if ($result) {
            $datas = [];
            for ($i = 0; $i < count($quote_standard_id_arr); $i++) {
                if ($wx_app_role->tyoe == 'admin') {
                    $datas[$i]['describes'] = Quote_Standard_Model::find_by_id($quote_standard_id_arr[$i])->desc;
                    $datas[$i]['id'] = $quote_standard_id_arr[$i];
                    $datas[$i]['sta_name'] = Quote_Standard_Model::find_by_id($quote_standard_id_arr[$i])->name;
                    $datas[$i]['price'] = $price_arr[$i];
                } else {
                    $datas[$i]['describes'] = Quote_Standard_Model::find_by_id($quote_standard_id_arr[$i])->desc;
                    $datas[$i]['id'] = $quote_standard_id_arr[$i];
                    $datas[$i]['sta_name'] = Quote_Standard_Model::find_by_id($quote_standard_id_arr[$i])->name;
                    $datas[$i]['price'] = $store_second->symbol == '-1' ? intval($price_arr[$i]) - intval($store_second->store_price) : intval($price_arr[$i]) + intval($store_second->store_price);
                }

            }
            return YZE_JSON_View::success($this, $datas);
        } else {
            return YZE_JSON_View::success($this);
        }
    }

    //模糊查询机型
    public function post_query_product()
    {
        $request = $this->request;
        $this->layout = '';
        $product_search = new Product_Search();
        $name = $request->get_from_post('query');
        $product_search->page = $request->get_from_post("page", 1);
        $product_search->pagesize = $request->get_from_post("limit", 100);
        $product_search->page = 1;
        $product_search->product_name = trim($name);
        $product_datas = $product_search->build_sql(new YZE_SQL(), $totalcnt);
        $datas = [];
        $i = 0;
        foreach ($product_datas as $item) {
            $datas[$i]['id'] = $item->id;
            $datas[$i]['second_name'] = $item->name;
            $i++;
        }
        return YZE_JSON_View::success($this, $datas);
    }

    public function post_order()
    {
        $request = $this->request;
        $this->layout = '';
        $user_oppid = $request->get_from_post('openid');
        $wx_appid = $request->get_from_post('wx_appid');
        $stand_id = $request->get_from_post('stand_id');
        $price = $request->get_from_post('price');
        $product_id = $request->get_from_post('product_id');
        $filter = $request->get_from_post('filter');
        $user_id = User_Model::find_by_openid($user_oppid);
        $product_name = Product_Model::find_by_id($product_id)->name; //机型名称
        $quote_standard_name = Quote_Standard_Model::find_by_id($stand_id)->name; //标准名称
        $second_attr_arr = explode("_", $filter);
        $second_attr_name_arr = [];
        for ($i = 0; $i < count($second_attr_arr); $i++) {
            $second_attr_name_arr[$i] = Second_Attribute_Model::find_by_id($second_attr_arr[$i])->name;
        }
        $second_attr_name_str = implode("→", $second_attr_name_arr);
        $desc = $product_name . "：" . $second_attr_name_str . " " . $quote_standard_name;
        $order_model = new Order_Model();
        $order_model->set(Order_Model::F_UUID, uuid());
        $order_model->set(Order_Model::F_STATUS, "unshipped"); //未发货
        $order_model->set(Order_Model::F_DESC, $desc);
        $order_model->set(Order_Model::F_WX_APPID, $wx_appid);
        $order_model->set(Order_Model::F_USER_ID, $user_id->id);
        $order_model->set(Order_Model::F_PRICE, $price);
        $order_model->set(Order_Model::F_COUNT, "1");
        $order_model->set(Order_Model::F_ORDER_TIME, date("Y-m-d h:i:s"), time()); //未发货
        $order_model->save();
        return YZE_JSON_View::success($this, $order_model->id);
    }

    //获取订单信息，改变订单状态
    public function post_order_info()
    {
        $request = $this->request;
        $this->layout = '';
        $order_id = $request->get_from_post('order_id');
        $order_info = Order_Model::from()->where("id=:order_id")->getSingle([":order_id" => $order_id]);
        $datas = [
            "id" => $order_info->id,
            "desc" => $order_info->desc,
            "goods_count" => $order_info->count,
            "order_time" => trim($order_info->order_time),
            "price" => $order_info->price,
        ];
        return YZE_JSON_View::success($this, $datas);
    }

    //修改订单数量
    public function post_up_order()
    {
        $request = $this->request;
        $this->layout = '';
        $order_id = $request->get_from_post('order_id');
        $counts = $request->get_from_post("counts");
        $price = $request->get_from_post("price");
        Order_Model::update_by_id($order_id, ["count" => $counts, "price" => $price]);
        return YZE_JSON_View::success($this);
    }

    //获取我的订单
    public function post_my_order()
    {
        $request = $this->request;
        $this->layout = '';
        $openid = $request->get_from_post('openid');
        $wx_appid = $request->get_from_post('wx_appid');
        $status = $request->get_from_post("status");
        if ($status) {
            $order = Order_Model::get_by_openid($openid, $wx_appid, $status);
            $datas = [];
            $i = 0;
            foreach ($order as $item) {
                $datas[$i]['id'] = $item->id;
                $datas[$i]['orderTime'] = $item->order_time;
                $datas[$i]['price'] = $item->price;
                $datas[$i]['param'] = trim($item->desc);
                $i++;
            }
        } else {
            $order = Order_Model::get_all($openid, $wx_appid);
            $datas = [];
            $i = 0;
            foreach ($order as $item) {
                $datas[$i]['id'] = $item->id;
                $datas[$i]['orderTime'] = $item->order_time;
                $datas[$i]['price'] = $item->price;
                $datas[$i]['param'] = trim($item->desc);
                $i++;
            }
        }
        return YZE_JSON_View::success($this, $datas);
    }

    //获取我的订单
    public function post_cancel_order()
    {
        $request = $this->request;
        $this->layout = '';
        $order_id = $request->get_from_post('order_id');
        $order_model = Order_Model::find_by_id($order_id);
        $order_model->remove();
        return YZE_JSON_View::success($this);
    }


    //发货
    public function up_order_status()
    {
        $request = $this->request;
        $this->layout = '';
        $order_id = $request->get_from_get('id');
        $save_data = [
            "status" => $request->get_from_get("status"),
            "consignor" => $request->get_from_get("consignor"),
            "address" => $request->get_from_get("address"),
            "express_company" => $request->get_from_get("express_company"),
            "express_num" => $request->get_from_get("express_num")
        ];
        Order_Model::update_by_id($order_id, $save_data);
        return YZE_JSON_View::success($this);
    }

    //启动页图
    public function post_sign_img()
    {
        $request = $this->request;
        $this->layout = '';
        $wx_appid = $request->get_from_post('wx_appid');
        $sign_img = Setting_Model::get_by_type(trim($wx_appid), 'sign');
        $datas = [
            "sign_img" => UPLOAD_SITE_URI . $sign_img->pic_url
        ];
        return YZE_JSON_View::success($this, $datas);
    }

    //轮播
    public function post_swiper_img()
    {
        $request = $this->request;
        $this->layout = '';
        $wx_appid = $request->get_from_post('wx_appid');
        $swiper_imgs = Setting_Model::get_by_wx_appid(trim($wx_appid), 'swiper');
        $swiper_imgs_arr = [];
        for ($i = 0; $i < count($swiper_imgs); $i++) {
            $swiper_imgs_arr[$i] = UPLOAD_SITE_URI.$swiper_imgs[$i]->pic_url;
        }
        $datas = [
            "swiper_imgs" => $swiper_imgs_arr
        ];
        return YZE_JSON_View::success($this, $datas);
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