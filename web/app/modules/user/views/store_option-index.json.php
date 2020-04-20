<?php

namespace app\user;

use app\product\First_Product_Model;
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
$first_product = First_Product_Model::find_all();
$store_user = $this->get_data('store_user');
$json = [];
$tr = "";
$option = "";
foreach ($first_product as $item) {
    $option .= "<option value=" . $item->id . ">" . $item->name . "</option>";
}
if ($store_user) {

} else {
    $tr = " <tr>
                    <td class=\"p-0\">
                        <select name=\"first_product_id[]\" class=\"layui-select first_attribute\"
                                lay-filter=\"first_attribute\" lay-search=\"\">
                            <option value=\"\">请选择</option>" . $option . "
                        </select>
                    </td>
                    <td class=\"p-0\">
                        <select name=\"second_attribute_id[]\" class=\"layui-select second_attribute\"
                                lay-filter=\"select-question\" lay-search=\"\">
                            <option value=\"\">请选择</option>
                            <option value=\"1\">加价</option>
                            <option value=\"-1\">减价</option>
                        </select>
                    </td>
                    <td class=\"p-0\">
                        <input class=\"layui-input\" name=\"price[]\">
                    </td>
                    <td class=\"p-1\">
                        <button type=\"button\" class=\"layui-btn layui-btn-xs layui-btn-danger removetr\">删除</button>
                    </td>
                </tr>";
}
$json['tr'] = $tr;
echo json_encode($json);
?>