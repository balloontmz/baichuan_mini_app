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
    for ($i = 0; $i < count($store_user); $i++) {
        $option_arr = [];
        $symbol = [];
        $price = [];
        for ($j = 0; $j < count($first_product); $j++) {
            if ($store_user[$i]->first_product_id == $first_product[$j]->id) {
                $option_arr[$i] .= "<option value=" . $first_product[$j]->id . " selected>" . $first_product[$j]->name . "</option>";
            } else {
                $option_arr[$i] .= "<option value=" . $first_product[$j]->id . ">" . $first_product[$j]->name . "</option>";
            }
        }
        if ($store_user[$i]->symbol == 1) {
            $symbol[$i] .= "<option value=" . $store_user[$i]->symbol . " selected>加价</option>";
            $price[$i] = $store_user[$i]->store_price;
            $symbol[$i] .= "<option value=\"-1\">减价</option>";
        } else {
            $symbol[$i] .= "<option value=" . $store_user[$i]->symbol . " selected>减价</option>";
            $price[$i] = $store_user[$i]->store_price;
            $symbol[$i] .= "<option value=\"1\">加价</option>";
        }

        $tr .= "<tr>
                    <td class=\"p-0\">
                        <select name=\"first_product_id[]\" class=\"layui-select first_attribute\"
                                lay-filter=\"first_attribute\" lay-search=\"\">
                            <option value=\"\">请选择</option>" . $option_arr[$i] . "
                        </select>
                    </td>
                    <td class=\"p-0\">
                        <select name=\"symbol[]\" class=\"layui-select second_attribute\"
                                lay-filter=\"select-question\" lay-search=\"\">
                            <option value=\"\">请选择</option>" . $symbol[$i] . "
                        </select>
                    </td>
                    <td class=\"p-0\">
                       <input class=\"layui-input\" name=\"price[]\" value=" . $price[$i] . ">
                    </td>
                    <td class=\"p-1\">
                        <button type=\"button\" class=\"layui-btn layui-btn-xs layui-btn-danger removetr\">删除</button>
                    </td>
                </tr>";
    }
    $tr .= " <tr>
                    <td class=\"p-0\">
                        <select name=\"first_product_id[]\" class=\"layui-select first_attribute\"
                                lay-filter=\"first_attribute\" lay-search=\"\">
                            <option value=\"\">请选择</option>" . $option . "
                        </select>
                    </td>
                    <td class=\"p-0\">
                        <select name=\"symbol[]\" class=\"layui-select second_attribute\"
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
} else {
    $tr = " <tr>
                    <td class=\"p-0\">
                        <select name=\"first_product_id[]\" class=\"layui-select first_attribute\"
                                lay-filter=\"first_attribute\" lay-search=\"\">
                            <option value=\"\">请选择</option>" . $option . "
                        </select>
                    </td>
                    <td class=\"p-0\">
                        <select name=\"symbol[]\" class=\"layui-select second_attribute\"
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