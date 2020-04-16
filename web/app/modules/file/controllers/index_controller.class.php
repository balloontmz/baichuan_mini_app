<?php

namespace app\file;

use \yangzie\YZE_JSON_View;
use \yangzie\YZE_Resource_Controller;
use \yangzie\YZE_RuntimeException;
use app\file\File_Model;
use app\property\Property_Model;
use app\vendor\helper\File_Search;
use yangzie\YZE_FatalException;
use yangzie\YZE_Hook;
use app\common\Option_Model;
use yangzie\YZE_DBAImpl;

/**
 *
 * @version $Id$
 * @package file
 *         
 */
class Index_Controller extends YZE_Resource_Controller {
	
	/**
     * url:files
     * 显示我上传的所有文件
     */
	public function search () {
        
		$request = $this->request;
		$page = $request->get_from_get ( "page", 1 );
		$name = trim($request->get_from_get ( "name"));
		$type = trim($request->get_from_get ( "type"));//文件类型
		$this->set_View_Data ( "yze_page_title", "我上传的文件" );
		$this->set_view_data ( 'sidemenu', 'oa' );
        if( $page < 1 )$page=1;
        
		$login_user = YZE_Hook::do_hook( YZE_HOOK_GET_LOGIN_USER );
        $where = "user_id=:uid and is_deleted=0 and copy_file_id=0";
        $params = [":uid"=>$login_user->id];
        if( $name ){
            $where .= " AND name like :name";
            $params[":name"] = "%{$name}%";
        }
        if ($type=="image"){
            $where .= " AND lower(ext) in ('jpg','jpeg','gif','png','bmp')";
        }
        $query = File_Model::from()->where($where)->order_By("id", "DESC");
        $total = $query->count("id", $params);
		
		$this->set_view_data ( 'files', $query->limit(($page-1)*PAGE_SIZE, PAGE_SIZE)->select( $params ) );
		$this->set_view_data ( 'total', $total );
    }
    
    //资料列表
    //地址映射： 域名/file-{type_id}
    public function index() {
        $request = $this->request;
        $property = Property_Model::find_by_id($request->get_var("type_id"));

        $page = $request->get_from_get("page");
        $page = $page ? $page : 1;
        $start = (intval($page) - 1) * PAGESIZE;

        $name = $request->get_from_get("name"); //文件名
        $user = $request->get_from_get("user"); //上传者
        $select_property = $request->get_from_get("property_id"); //当前选择的资料类型
        if ($select_property)
            $select_property = Property_Model::find_by_id($select_property);

        //查询当前类型的文件信息
        $search = new File_Search();
        $search->type_id = $select_property ? $select_property->id : $property->id;
        $search->name = $name;
        $search->user = $user;
        $search->target_id ="0";//资料列表查询的是不绑定具体某个对象上的内容 leeboo@20170918
        $search->limit = array($start, PAGESIZE);
        $search->order_and_sort = array("file" => array("date" => "desc"));
        $files = $search->doSearch($totalCount);

        $this->set_View_Data("name", $name);
        $this->set_View_Data("user", $user);
        $this->set_View_Data("files", $files);

        $this->set_View_Data("property", $property);
        $this->set_View_Data("select_property", $select_property ? $select_property : $property);
        $this->set_View_Data("totalCount", $totalCount);
    }

    //资料版本查看
    //地址映射：file/{file_id}/history
    public function history() {
        $request = $this->request;
        $this->set_View_Data("yze_page_title", "资料版本查看");

        $file_id = $request->get_var("file_id");
        $page = $request->get_from_get("page");
        $page = $page ? $page : 1;
        $start = (intval($page) - 1) * PAGESIZE;

        $name = $request->get_from_get("name"); //文件名
        $user = $request->get_from_get("user"); //上传者

        $error = "";
        $find_file = File_Model::from()->where("copy_file_id=0 and is_deleted=0 and uuid=:id")->getSingle(array(":id" => $file_id));
        if (!$find_file) {
            throw new YZE_FatalException("未找到您所要查看资料");
        }

        //查找该资料的历史版本
        $search = new File_Search();
        $search->copy_file_id = $find_file->id;
        $search->name = $name;
        $search->user = $user;
        $search->limit = array($start, PAGESIZE);
        $search->order_and_sort = array("file" => array("date" => "desc"));
        $files = $search->doSearch($totalCount);

        $this->set_View_Data("error", $error);
        $this->set_View_Data("name", $name);
        $this->set_View_Data("user", $user);
        $this->set_View_Data("find_file", $find_file);
        $this->set_View_Data("files", $files);
        $this->set_View_Data("totalCount", $totalCount);
    }

    //删除文件
    //地址映射：file/{file_id}/delete
    public function post_delete() {
        $request = $this->request;
        $this->layout = "";

        $login_user = YZE_Hook::do_hook(YZE_HOOK_GET_LOGIN_USER);

        $file_id = $request->get_var("file_id");

        //验证数据
        $file = File_Model::from()->where("uuid=:uuid")->getSingle( [":uuid" => $file_id]);
        if (!$file) {
            throw new YZE_FatalException("未找到您所要删除的文件，请刷新页面后试试");
        }
        
        $file->remove();

        return \yangzie\YZE_JSON_View::success($this);
    }
    
    // file/target/(?P<target_id>\d+)
    public function target_file () {
    	$request = $this->request;
    	$target_id = $request->get_var ( "target_id" );
    	$target_cls = $request->get_from_get ( "target_class" );
    	$name = $request->get_from_get ( "name" );
    	$page = $request->get_from_get ( "page", 1 );
    	$start = ( intval ( $page ) - 1 ) * PAGESIZE;
    
    	$files = [ ];
    	$totalCount = 0;
    	if ( $target_cls ) {
    		$search = new File_Search ();
    		$search->target_id = $target_id;
    		$search->target_class = $target_cls;
    		if ( $name ) $search->name = $name;
    		$search->order_and_sort = array("file" => array("id" => "desc"));
    		$search->limit = array (
    			$start,
    			PAGESIZE
    		);
    		$files = $search->doSearch ( $totalCount );
    	}
    
    	$this->set_View_Data ( "totalCount", $totalCount );
    	$this->set_View_Data ( "target_id", $target_id );
    	$this->set_View_Data ( "target_class", $target_cls );
    	$this->set_View_Data ( "files", $files );
    	$this->set_View_Data ( "yze_page_title", "资料查看" );
    }

    public function exception(YZE_RuntimeException $e) {
        $request = $this->request;
        $this->layout = 'error';

        // 处理中出现了异常，如何处理，没有任何处理将显示500页面
        $json_method = array(
            "post_delete",
        );
        if (in_array($request->the_method(), $json_method)) {
            $this->layout = "";
            return \yangzie\YZE_JSON_View::error($this, $e->getMessage());
        }
        // 如果想显示get的返回内容可调用 :
        $this->post_result_of_json = YZE_JSON_View::error($this, $e->getMessage());
        // 通过request->the_method()判断是那个方法出现的异常
        // return $this->wrapResponse($this->yourmethod())
    }

}

?>