<?php
namespace app\user;

use \yangzie\YZE_Resource_Controller;
use \yangzie\YZE_Request;
use \yangzie\YZE_Redirect;
use \yangzie\YZE_Session_Context;
use \yangzie\YZE_RuntimeException;

/**
 * 视图的描述
 * @param type name optional
 *
 */
$store_user_cnt = $this->get_data('store_user_cnt');
$store_user_datas = $this->get_data('store_user_datas');
$datas = ["data" => [], "code" => 0, "count" => $store_user_cnt];
for ($i = 0; $i < count($store_user_datas); $i++) {
    $datas['data'][$i]['store_name'] = $store_user_datas[$i]->store_name;
    $datas['data'][$i]['store_phone'] = $store_user_datas[$i]->store_phone;
    $datas['data'][$i]['store_address'] = $store_user_datas[$i]->store_address;
    $datas['data'][$i]['type'] = $store_user_datas[$i]->type == 'admin' ? '总店' : '分店';
    $datas['data'][$i]['wx_user_count'] = User_Model::get_user_count(trim($store_user_datas[$i]->wx_appid));
    $datas['data'][$i]['id'] = $store_user_datas[$i]->id;
}
header('Content-Type:application/json');
echo json_encode($datas);
?>
