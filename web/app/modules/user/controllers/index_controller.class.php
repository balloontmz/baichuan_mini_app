<?php

namespace app\user;

use app\vendor\helper\User_Search;
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
 * @package user
 */
class Index_Controller extends YZE_Resource_Controller
{
    public function index()
    {
        $request = $this->request;
        //$this->layout = 'tpl name';
        $status = $request->get_from_get('status');
        $user_name = $request->get_from_get('user_name');
        $login_user = YZE_Hook::do_hook(YZE_HOOK_GET_LOGIN_USER);
        $user_search = new User_Search();
        $user_search->page = $request->get_from_get("page", 1);
        $user_search->pagesize = $request->get_from_get("limit", 10);
        $user_search->wx_appid = $login_user->wx_appid;
        if ($status) {
            $user_search->status = $status;
        }
        if ($user_name) {
            $user_search->user_name = trim($user_name);
        }
        $user_datas = $user_search->build_sql(new YZE_SQL(), $totalcnt);
        $this->set_View_Data('user_cnt', $totalcnt);
        $this->set_View_Data('user_datas', $user_datas);
        $this->set_view_data('yze_page_title', '用户管理');
    }

    //禁用或解除禁用用户
    public function post_set_user()
    {
        $request = $this->request;
        $this->layout = '';
        $user_id = $request->get_from_post('user_id');
        $status = $request->get_from_post('status');
        User_Model::update_by_id($user_id, ["status" => $status == 1 ? -1 : 1]);
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