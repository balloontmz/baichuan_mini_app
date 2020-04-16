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

$product_datas = $this->get_data('product_datas');
$product_cnt =$this->get_data('product_cnt');
$datas = ["data" => [],"code" => 0,"count"=>$product_cnt];
$i=0;
foreach ($product_datas as $item){
    $datas['data'][$i]['name'] = $item->name;
    $datas['data'][$i]['first_product_name']=First_Product_Model::find_by_id($item->first_product_id)->name;
    $datas['data'][$i]['id'] = $item->id;
    $i++;
}
header('Content-Type:application/json');
echo json_encode($datas);
?>


