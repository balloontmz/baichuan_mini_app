<?php
namespace app\order;
use app\vendor\helper\Order_Search;
use yangzie\YZE_Hook;
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
* @package order
*/
class Index_Controller extends YZE_Resource_Controller {
    public function index(){
        $request = $this->request;
        //$this->layout = 'tpl name';
        $login_user = YZE_Hook::do_hook(YZE_HOOK_GET_LOGIN_USER);
        $order_search = new Order_Search();
        $status = $request->get_from_get('status');
        $order_search->page = $request->get_from_get("page",1);
        $order_search->pagesize =  $request->get_from_get("limit",10);
        $order_search->wx_appid = $login_user->wx_appid;
        if($status){
            $order_search->page = 1;
            $order_search->status=$status;
        }
        $order_datas = $order_search->build_sql(new YZE_SQL(),$totalcnt);
        $this->set_view_data('order_datas_cnt', $totalcnt);
        $this->set_view_data('order_datas', $order_datas);
        $this->set_view_data('yze_page_title', '订单列表');
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