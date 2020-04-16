<?php

namespace app\product;

use app\vendor\helper\First_Product_Search;
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
 * @package product
 */
class First_Product_Controller extends YZE_Resource_Controller
{
    public function index()
    {
        $request = $this->request;
        //$this->layout = 'tpl name';
        $first_product_search = new First_Product_Search();
        $name = $request->get_from_get('query');
        $first_product_search->page = $request->get_from_get("page",1);
        $first_product_search->pagesize =  $request->get_from_get("limit",10);
        if($name){
            $first_product_search->page = 1;
            $first_product_search->first_product_name=trim($name);
        }
        $first_product_datas = $first_product_search->build_sql(new YZE_SQL(),$totalcnt);
        $this->set_View_Data('first_product_cnt',$totalcnt);
        $this->set_view_data('first_product_datas', $first_product_datas);
        $this->set_view_data('yze_page_title', '产品一级分类列表');
    }

    public function add()
    {
        $request = $this->request;
        $this->layout = 'empty';
        $first_product_id = $request->get_from_get("first_product_id");
        $get_first_product = First_Product_Model::find_by_id($first_product_id);
        $this->set_view_data('get_first_product', $get_first_product);
        $this->set_view_data('yze_page_title', $first_product_id ? '修改分类' : '新增分类');
    }

    public function post_add()
    {
        $request = $this->request;
        $this->layout = '';
        $name = $request->get_from_post("name");
        $first_product_id = $request->get_from_post("first_product_id");
        $product_type_id = trim($request->get_from_post("product_type_id"));
        if ($first_product_id) {      //修改
            First_Product_Model::update_by_id(trim($first_product_id), ["name" => $name, "product_type_id" => $product_type_id]);
        } else {
            $first_product_model = new First_Product_Model();
            $first_product_model->set(First_Product_Model::F_UUID, uuid());
            $first_product_model->set(First_Product_Model::F_NAME, $name);
            $first_product_model->set(First_Product_Model::F_PRODUCT_TYPE_ID, $product_type_id);
            $first_product_model->save();
        }
        return YZE_JSON_View::success($this);
    }

    public function post_remove()
    {
        $request = $this->request;
        $this->layout = '';
        $first_product_id = $request->get_from_post("id");
        $first_product = First_Product_Model::find_by_id($first_product_id);
        $first_product->remove();
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