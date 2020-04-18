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
$i = 0;
foreach ($store_user_datas as $item) {
    $datas['data'][$i]['store_name'] = $item->store_name;
    $datas['data'][$i]['store_phone'] = $item->store_phone;
    $datas['data'][$i]['store_address'] = $item->store_address;
    $datas['data'][$i]['type'] = $item->type == 'admin'?'总店':'分店';
    $datas['data'][$i]['wx_user_count'] = User_Model::get_user_count($item->wx_aapid);
    $datas['data'][$i]['id'] = $item->id;
    $i++;
}
header('Content-Type:application/json');
echo json_encode($datas);
?>
