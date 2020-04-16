<?php
namespace app\product;
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
* @package product
*/
class Index_Controller extends YZE_Resource_Controller {
    public function index(){
        $request = $this->request;
        //$this->layout = 'tpl name';
        $product_search = new Product_Search();
        $name = $request->get_from_get('query');
        $product_search->page = $request->get_from_get("page",1);
        $product_search->pagesize =  $request->get_from_get("limit",10);
        if($name){
            $product_search->page = 1;
            $product_search->product_name=trim($name);
        }
        $product_datas = $product_search->build_sql(new YZE_SQL(),$totalcnt);
        $this->set_View_Data('product_cnt',$totalcnt);
        $this->set_view_data('product_datas', $product_datas);
        $this->set_view_data('yze_page_title', '产品列表');
    }

    public function add()
    {
        $request = $this->request;
        $this->layout = 'empty';
        $product_id = $request->get_from_get("product_id");
        $get_product = Product_Model::find_by_id($product_id);
        $this->set_view_data('get_product', $get_product);
        $this->set_view_data('yze_page_title', $product_id ? '修改分类' : '新增分类');
    }

    public function post_add()
    {
        $request = $this->request;
        $this->layout = '';
        $name = $request->get_from_post("name");
        $first_product_id = $request->get_from_post("first_product_id");
        $product_id = $request->get_from_post("product_id");
        if ($product_id) {      //修改
            Product_Model::update_by_id(trim($product_id), ["name" => $name, "first_product_id" => $first_product_id]);
        } else {
            $product_model = new Product_Model();
            $product_model->set(Product_Model::F_UUID, uuid());
            $product_model->set(Product_Model::F_NAME, $name);
            $product_model->set(Product_Model::F_FIRST_PRODUCT_ID, $first_product_id);
            $product_model->save();
        }
        return YZE_JSON_View::success($this);
    }

    public function exception(YZE_RuntimeException $e){
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