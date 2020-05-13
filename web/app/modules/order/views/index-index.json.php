<?php
namespace app\order;
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

$rder_datas = $this->get_data('order_datas');
$order_datas_cnt =$this->get_data('order_datas_cnt');
$datas = ["data" => [],"code" => 0,"count"=>$order_datas_cnt];
$i=0;
foreach ($rder_datas as $item){
    $datas['data'][$i]['order_time'] = $item->order_time;
    $datas['data'][$i]['desc'] = trim($item->desc);
    $datas['data'][$i]['count'] = $item->count;
    $datas['data'][$i]['price'] = $item->price;
    $datas['data'][$i]['consignor'] = $item->consignor;
    $datas['data'][$i]['address'] = $item->address;
    $datas['data'][$i]['express_company'] = $item->express_company;
    $datas['data'][$i]['express_num'] = $item->express_num;
    $datas['data'][$i]['status'] = Order_Model::get_status_text($item->status);
    $datas['data'][$i]['id'] = $item->id;
    $i++;
}
header('Content-Type:application/json');
echo json_encode($datas);
?>
