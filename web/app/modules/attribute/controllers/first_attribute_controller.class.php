<?php
namespace app\attribute;
use \yangzie\YZE_Resource_Controller;
use \yangzie\YZE_Request;
use \yangzie\YZE_Redirect;
use \yangzie\YZE_Session_Context;
use \yangzie\YZE_RuntimeException;
use \yangzie\YZE_JSON_View;

/**
*
* @version $Id$
* @package attribute
*/
class First_Attribute_Controller extends YZE_Resource_Controller {
    public function index(){
        $request = $this->request;
        //$this->layout = 'tpl name';
        $first_attribute_datas = First_Attribute_Model::find_all();
        $this->set_view_data('first_attribute_datas', $first_attribute_datas);
        $this->set_view_data('yze_page_title', '一级属性列表');
    }

    public function add()
    {
        $request = $this->request;
        $this->layout = 'empty';
        $first_attribute_id = $request->get_from_get("first_attribute_id");
        $get_first_attribute = First_Attribute_Model::find_by_id($first_attribute_id);
        $this->set_view_data('get_first_attribute', $get_first_attribute);
        $this->set_view_data('yze_page_title', $first_attribute_id ? '修改分类' : '新增分类');
    }

    public function post_add()
    {
        $request = $this->request;
        $this->layout = '';
        $name = $request->get_from_post("name");
        $first_attribute_id = $request->get_from_post("first_attribute_id");
        if ($first_attribute_id) {      //修改
            First_Attribute_Model::update_by_id(trim($first_attribute_id), ["name" => $name]);
        } else {
            $first_attribute_model = new First_Attribute_Model();
            $first_attribute_model->set(First_Attribute_Model::F_UUID, uuid());
            $first_attribute_model->set(First_Attribute_Model::F_NAME, $name);
            $first_attribute_model->save();
        }
        return YZE_JSON_View::success($this);
    }
    public function post_remove(){
        $request = $this->request;
        $this->layout = '';
        $first_attribute_id = $request->get_from_post("id");
        $get_first_attribute = First_Attribute_Model::find_by_id($first_attribute_id);
        $get_first_attribute->remove();
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