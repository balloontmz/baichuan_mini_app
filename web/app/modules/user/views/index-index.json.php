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
$user_datas[] = $this->get_data('user_datas');
//var_dump($user_datas);
$user_cnt =0;
$datas = ["data" => [],"code" => 0,"count"=>$user_cnt+1];
foreach ($user_datas as $item){
    $datas['data'][$user_cnt]['name'] = $item[$user_cnt]->name;
    $datas['data'][$user_cnt]['sex'] = $item[$user_cnt]->sex;
    $datas['data'][$user_cnt]['phone'] = $item[$user_cnt]->phone;
    $datas['data'][$user_cnt]['status'] = $item[$user_cnt]->status==1?'禁用':'正常';
    $datas['data'][$user_cnt]['login_date'] = $item[$user_cnt]->login_date;
    $datas['data'][$user_cnt]['out_date'] = $item[$user_cnt]->out_date;
    $user_cnt++;
}
header('Content-Type:application/json');
echo json_encode($datas);
?>
