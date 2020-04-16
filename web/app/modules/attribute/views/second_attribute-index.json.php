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
$second_attribute_datas = $this->get_data('second_attribute_datas');
$second_attribute_cnt = $this->get_data('second_attribute_cnt');
$i =0;
$datas = ["data" => [],"code" => 0,"count"=>$second_attribute_cnt];
foreach ($second_attribute_datas as $item){
    $datas['data'][$i]['name'] = $item->name;
    $datas['data'][$i]['first_attribute_name'] = First_Attribute_Model::find_by_id($item->first_attribute_id)->name;
    $datas['data'][$i]['id'] = $item->id;
    $i++;
}
header('Content-Type:application/json');
echo json_encode($datas);
?>
