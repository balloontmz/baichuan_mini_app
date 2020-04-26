<?php

namespace app\api;

use app\attribute\First_Attribute_Model;
use app\attribute\Second_Attribute_Model;
use app\product\First_Product_Model;
use app\product\Product_Model;
use app\product_quote\Product_Price_Model;
use \yangzie\YZE_Resource_Controller;
use \yangzie\YZE_Request;
use \yangzie\YZE_Redirect;
use \yangzie\YZE_Session_Context;
use \yangzie\YZE_RuntimeException;
use \yangzie\YZE_JSON_View;

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
                    $second_name_obj_arr[$i][$j]['attr_value'] = $second_arr_name_a[$i][$j];
                    $second_name_obj_arr[$i][$j]['id'] = $second_arr_id_a[$i][$j];
                }
            }
        }
        for ($i = 0; $i < count($second_attri); $i++) {
            $datas[$i]['attr_name'] = First_Attribute_Model::find_by_id(Second_Attribute_Model::find_by_id($second_attri[$i])->first_attribute_id)->name;
            $datas[$i]['child'] = $second_name_obj_arr[$i];
        }

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