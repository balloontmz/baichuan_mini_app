<?php

namespace app\product;

use \yangzie\YZE_Resource_Controller;
use \yangzie\YZE_Request;
use \yangzie\YZE_Redirect;
use \yangzie\YZE_Session_Context;
use \yangzie\YZE_RuntimeException;
use \yangzie\YZE_JSON_View;

/**
 *
 * @version $Id$
 * @package product
 */
class Product_Type_Controller extends YZE_Resource_Controller
{
    public function index()
    {
        $request = $this->request;
        //$this->layout = 'tpl name';
        $product_type_datas = Product_Type_Model::find_all();
        $this->set_view_data('product_type_datas', $product_type_datas);
        $this->set_view_data('yze_page_title', '产品总分类列表');
    }

    public function add()
    {
        $request = $this->request;
        $this->layout = 'empty';
        $product_type_id = $request->get_from_get("product_type_id");
        $get_product_type = Product_Type_Model::find_by_id($product_type_id);
        $this->set_view_data('get_product_type', $get_product_type);
        $this->set_view_data('yze_page_title', $product_type_id ? '修改分类' : '新增分类');
    }

    public function post_add()
    {
        $request = $this->request;
        $this->layout = '';
        $name = $request->get_from_post("name");
        $product_type_id = trim($request->get_from_post("product_type_id"));
        if ($product_type_id) {
            Product_Type_Model::update_by_id(trim($product_type_id), ["name" => $name]);
        } else {
            $product_type_model = new Product_Type_Model();
            $product_type_model->set(Product_Type_Model::F_NAME, $name);
            $product_type_model->save();
        }
        return YZE_JSON_View::success($this);
    }

    public function post_remove()
    {
        $request = $this->request;
        $this->layout = '';
        $product_type_id = $request->get_from_post("id");
        $get_product_type = Product_Type_Model::find_by_id($product_type_id);
        $get_product_type->remove();
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