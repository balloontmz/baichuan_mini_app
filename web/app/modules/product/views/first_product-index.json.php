<?php
namespace app\product;
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
$first_product_datas = $this->get_data('first_product_datas');
$first_product_cnt =$this->get_data('first_product_cnt');
$datas = ["data" => [],"code" => 0,"count"=>$first_product_cnt];
$i=0;
foreach ($first_product_datas as $item){
    $datas['data'][$i]['name'] = $item->name;
    $datas['data'][$i]['product_type_name']=Product_Type_Model::find_by_id($item->product_type_id)->name;
    $datas['data'][$i]['id'] = $item->id;
    $i++;
}
header('Content-Type:application/json');
echo json_encode($datas);
?>
