<?php
namespace app\user;

use \yangzie\YZE_Resource_Controller;
use \yangzie\YZE_Request;
use \yangzie\YZE_Redirect;
use \yangzie\YZE_Session_Context;
use \yangzie\YZE_RuntimeException;
use \yangzie\YZE_JSON_View;

/**
 *
 * @version $Id$
 * @package user
 */
$user_datas = $this->get_data('user_datas');
$user_cnt = $this->get_data('user_cnt');
$datas = ["data" => [], "code" => 0, "count" => $user_cnt];
$i = 0;
foreach ($user_datas as $item) {
    $datas['data'][$i]['name'] = $item->name;
    $datas['data'][$i]['sex'] = $item->gender == 1 ? '男' : '女';
    $datas['data'][$i]['phone'] = $item->cellphone;
    $datas['data'][$i]['status'] = $item->status == 1 ? '否' : '是';
    $datas['data'][$i]['login_date'] = $item->login_date;
    $datas['data'][$i]['out_date'] = $item->out_date;
    $datas['data'][$i]['id'] = $item->id;
    $i++;
}
header('Content-Type:application/json');
echo json_encode($datas);
?>
