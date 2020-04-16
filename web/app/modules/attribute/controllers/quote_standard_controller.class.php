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
class Quote_Standard_Controller extends YZE_Resource_Controller {
    public function index(){
        $request = $this->request;
        //$this->layout = 'tpl name';
        $get_quote_standard = Quote_Standard_Model::find_all();
        $this->set_view_data('get_quote_standard', $get_quote_standard);
        $this->set_view_data('yze_page_title', '报价标准列表');
    }

    public function add(){
        $request = $this->request;
        $this->layout = 'empty';
        $quote_standard_id = $request->get_from_get('quote_standard_id');
        $get_quote_standard = Quote_Standard_Model::find_by_id($quote_standard_id);
        $this->set_view_data('get_quote_standard', $get_quote_standard);
        $this->set_view_data('yze_page_title', $quote_standard_id?'修改标准':'新增标准');
    }

    public function post_add()
    {
        $request = $this->request;
        $this->layout = '';
        $name = $request->get_from_post("name");
        $desc = $request->get_from_post("desc");
        $quote_standard_id = $request->get_from_post("quote_standard_id");
        if ($quote_standard_id) {      //修改
            Quote_Standard_Model::update_by_id(trim($quote_standard_id), ["name" => $name,"desc"=>$desc]);
        } else {
            $quote_standard_model = new Quote_Standard_Model();
            $quote_standard_model->set(Quote_Standard_Model::F_UUID, uuid());
            $quote_standard_model->set(Quote_Standard_Model::F_NAME, $name);
            $quote_standard_model->set(Quote_Standard_Model::F_DESC, $desc);
            $quote_standard_model->save();
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