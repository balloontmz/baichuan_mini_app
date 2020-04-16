<?php

namespace app\product_quote;

use app\attribute\Quote_Standard_Model;
use app\attribute\Second_Attribute_Model;
use app\product\Product_Model;
use app\vendor\helper\Product_Quote_Search;
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
 * @package product_quote
 */
class Index_Controller extends YZE_Resource_Controller
{
    public function index()
    {
        $request = $this->request;
        //$this->layout = 'tpl name';
        $product_id = $request->get_from_get("query");
        $product_quote_search = new Product_Quote_Search();
        $product_quote_search->page = $request->get_from_get("page", 1);
        $product_quote_search->pagesize = $request->get_from_get("limit", 10);
        $product_price_ids = array();
        if ($product_id) {
            $product_quote_search->page = 1;
            $product_quote_search->product_id = trim($product_id);
            $product_price_obj = Product_Price_Model::get_by_product_id($product_id);
        }else{
            $product_price_obj = Product_Price_Model::find_all();
        }
        $i=0;
        foreach ($product_price_obj as $item){
            $product_price_ids[$i] = $item->id;
            $i++;
        }
        $product_quote_datas = $product_quote_search->build_sql(new YZE_SQL(), $totalcnt);

        $this->set_View_Data('product_price_ids', $product_price_ids);
        $this->set_View_Data('product_quote_cnt', $totalcnt);
        $this->set_view_data('product_quote_datas', $product_quote_datas);
        $this->set_view_data('yze_page_title', '产品报价列表');
    }

    public function add()
    {
        $request = $this->request;
        //$this->layout = 'tpl name';
        $this->set_view_data('yze_page_title', '新增报价');
    }

    public function post_add()
    {
        $request = $this->request;
        $this->layout = '';
        $datas = $request->the_post_datas();
        if (!$datas['product_id']) throw new YZE_FatalException("请选择产品！");
        if ($datas['second_attribute_id'][0] == 0) throw new YZE_FatalException("请选择二级属性！");
        if ($datas['quote_standard_id'][0] == 0) throw new YZE_FatalException("请选择报价标准！");
        if ($datas['price'][0] == 0) throw new YZE_FatalException("请输入价格！");
        $second_attribute_ids = array();
        for ($i = 0; $i < count($datas['second_attribute_id']); $i++) {
            if (!$datas['second_attribute_id'][$i]) continue;
            $second_attribute_ids[$i] = $datas['second_attribute_id'][$i];
        }
        $quote_standard_ids = array();
        $price_arr = array();
        for ($i = 0; $i < count($datas['quote_standard_id']); $i++) {
            if (!$datas['quote_standard_id'][$i] && !$datas['price'][$i]) continue;
            $quote_standard_ids[$i] = $datas['quote_standard_id'][$i];
            $price_arr[$i] = $datas['price'][$i];
        }
        $product_price_model = new Product_Price_Model();
        $product_price_model->set(Product_Price_Model::F_UUID, uuid());
        $product_price_model->set(Product_Price_Model::F_PRODUCT_ID, $datas['product_id']);
        $product_price_model->set(Product_Price_Model::F_SECOND_ATTRIBUTE_IDS, implode("_", $second_attribute_ids));
        $product_price_model->set(Product_Price_Model::F_QUOTE_STANDARD_IDS, implode(",", $quote_standard_ids));
        $product_price_model->set(Product_Price_Model::F_PRICE, implode(",", $price_arr));
        $product_price_model->save();
        return YZE_JSON_View::success($this);
    }

    //通过一级产品获取产品
    public function post_product()
    {
        $request = $this->request;
        $this->layout = '';
        $first_product_id = $request->get_from_post('first_product_id');
        $product_list = Product_Model::getProductByFid($first_product_id);
        $datas = [];
        $i = 0;
        foreach ($product_list as $item) {
            $datas[$i]['id'] = $item->id;
            $datas[$i]['name'] = $item->name;
            $i++;
        }
        return YZE_JSON_View::success($this, $datas);
    }

    //通过一级属性获取二级属性
    public function post_second_attribute()
    {
        $request = $this->request;
        $this->layout = '';
        $first_attribute_id = $request->get_from_post('first_attribute_id');
        $result_list = Second_Attribute_Model::getByFid($first_attribute_id);
        $datas = [];
        $i = 0;
        foreach ($result_list as $item) {
            $datas[$i]['id'] = $item->id;
            $datas[$i]['name'] = $item->name;
            $i++;
        }
        return YZE_JSON_View::success($this, $datas);
    }

    //获取报价标准描述
    public function post_quote_standard_desc()
    {
        $request = $this->request;
        $this->layout = '';
        $quote_standard_id = $request->get_from_post('quote_standard_id');
        $result = Quote_Standard_Model::find_by_id($quote_standard_id);
        $datas = $result->desc;
        return YZE_JSON_View::success($this, $datas);
    }

    //获取报价标准描述
    public function edit_quote()
    {
        $request = $this->request;
        $this->layout = 'empty';
        $product_price_id = $request->get_from_get('id');
        $datas = Product_Price_Model::find_by_id($product_price_id);
        $attribute_ids = explode(",", $datas->quote_standard_ids);
        $price = explode(",", $datas->price);
        $this->set_View_Data('product_price_id', $product_price_id);
        $this->set_View_Data('attribute_ids', $attribute_ids);
        $this->set_View_Data('price', $price);
        $this->set_view_data('yze_page_title', '产品改价');
    }


    //只修改报价
    public function post_price()
    {
        $request = $this->request;
        $this->layout = '';
        $datas = $request->the_post_datas();
        $quote_standard_ids = array();
        $price_arr = array();
        for ($i = 0; $i < count($datas['quote_standard_id']); $i++) {
            if (!$datas['quote_standard_id'][$i] && !$datas['price'][$i]) continue;
            $quote_standard_ids[$i] = $datas['quote_standard_id'][$i];
            $price_arr[$i] = $datas['price'][$i];
        }
        $seve_arr = [
            "quote_standard_ids" => implode(",", $quote_standard_ids),
            "price" => implode(",", $price_arr)
        ];
        Product_Price_Model::update_by_id($datas['product_price_id'], $seve_arr);
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