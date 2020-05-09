<?php

namespace app\product_quote;

use app\attribute\Quote_Standard_Model;
use app\attribute\Second_Attribute_Model;
use app\product\Product_Model;
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


$product_quote_datas = $this->get_data('product_quote_datas');
$product_quote_cnt = $this->get_data('product_quote_cnt');
$datas = ["data" => [], "code" => 0, "count" => $product_quote_cnt];
$i = 0;
foreach ($product_quote_datas as $item) {
    $attribute_arr = explode("_", $item['pq']->second_attribute_ids);
    $second_attribute_names_arr = array();
    for ($j = 0; $j < count($attribute_arr); $j++) {
        $second_attribute_names_arr[$j] = Second_Attribute_Model::find_by_id($attribute_arr[$j])->name;
    }
    $quote_arr = explode(",", $item['pq']->quote_standard_ids);
    $price_arr = explode(",",$item['pq']->price);
    $quote_standard_arr = array();
    for ($m = 0; $m < count($quote_arr); $m++) {
        $quote_standard_arr[$m] = Quote_Standard_Model::find_by_id($quote_arr[$m])->name . "：" . $price_arr[$m];
    }
    $datas['data'][$i]['name'] = Product_Model::find_by_id($item['pq']->product_id)->name;
    $datas['data'][$i]['second_attribute_names'] = implode("→", $second_attribute_names_arr);
    $datas['data'][$i]['quote_standard'] = implode("，", $quote_standard_arr);
    $datas['data'][$i]['id'] = $item['pq']->id;
    $i++;
}
header('Content-Type:application/json');
echo json_encode($datas);


?>