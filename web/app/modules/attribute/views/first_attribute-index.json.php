<?php
namespace app\attribute;

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
$first_attribute_datas = $this->get_data('first_attribute_datas');
$datas_cnt =0;
$datas = ["data" => [],"code" => 0,"count"=>$datas_cnt+1];
foreach ($first_attribute_datas as $item){
    $datas['data'][$datas_cnt]['sort'] = $datas_cnt+1;
    $datas['data'][$datas_cnt]['name'] = $item->name;
    $datas['data'][$datas_cnt]['id'] = $item->id;
    $datas_cnt++;
}
header('Content-Type:application/json');
echo json_encode($datas);
?>
