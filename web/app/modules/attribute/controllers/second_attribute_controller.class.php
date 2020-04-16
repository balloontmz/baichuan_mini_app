<?php

namespace app\attribute;

use app\vendor\helper\Second_Attribute_Search;
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
 * @package attribute
 */
class Second_Attribute_Controller extends YZE_Resource_Controller
{
    public function index()
    {
        $request = $this->request;
        //$this->layout = 'tpl name';
        $second_attribute_search = new Second_Attribute_Search();
        $name = $request->get_from_get('query');
        $second_attribute_search->page = $request->get_from_get("page", 1);
        $second_attribute_search->pagesize = $request->get_from_get("limit", 10);
        if ($name) {
            $second_attribute_search->page = 1;
            $second_attribute_search->second_attribute_name = trim($name);
        }
        $second_attribute_datas = $second_attribute_search->build_sql(new YZE_SQL(), $totalcnt);
        $this->set_View_Data('second_attribute_cnt', $totalcnt);
        $this->set_view_data('second_attribute_datas', $second_attribute_datas);
        $this->set_view_data('yze_page_title', '二级属性列表');
    }

    public function add()
    {
        $request = $this->request;
        $this->layout = 'empty';
        $second_attribute_id = $request->get_from_get("second_attribute_id");
        $get_second_attribute = Second_Attribute_Model::find_by_id($second_attribute_id);
        $this->set_view_data('get_second_attribute', $get_second_attribute);
        $this->set_view_data('yze_page_title', $second_attribute_id ? '修改分类' : '新增分类');
    }

    public function post_add()
    {
        $request = $this->request;
        $this->layout = '';
        $name = $request->get_from_post("name");
        $second_attribute_id = $request->get_from_post("second_attribute_id");
        $first_attribute_id = $request->get_from_post("first_attribute_id");
        if ($second_attribute_id) {      //修改
            Second_Attribute_Model::update_by_id(trim($second_attribute_id), ["name" => $name, "first_attribute_id" => $first_attribute_id]);
        } else {
            $second_attribute_model = new Second_Attribute_Model();
            $second_attribute_model->set(Second_Attribute_Model::F_UUID, uuid());
            $second_attribute_model->set(Second_Attribute_Model::F_NAME, $name);
            $second_attribute_model->set(Second_Attribute_Model::F_FIRST_ATTRIBUTE_ID, $first_attribute_id);
            $second_attribute_model->save();
        }
        return YZE_JSON_View::success($this);
    }

    public function post_remove()
    {
        $request = $this->request;
        $this->layout = '';
        $second_attribute_id = $request->get_from_post("id");
        $get_second_attribute = Second_Attribute_Model::find_by_id($second_attribute_id);
        $get_second_attribute->remove();
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