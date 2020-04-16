<?php

namespace app;

use yangzie\YZE_DBAImpl;
use yangzie\YZE_Session_Context;

/**
 * 用户分组的形式是：
 * /------组根
 * 大组名/
 * 小组名/
 * 用户id
 *
 * /module/controller/action
 */
function yze_get_aco_desc($aconame)
{
    foreach (( array )yze_get_acos_aros() as $aco => $desc) {
        if (preg_match("{^" . $aco . "}", $aconame)) {
            return @$desc ['desc'];
        }
    }
    return '';
}

function yze_get_ignore_acos()
{
    return array();
}

function yze_get_acos_aros()
{
    $array = array(
        "/attendance/index/index" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "考勤与值班",
            "name" => "考勤看板",
            "desc" => "查看每日考勤统计情况"
        ),
        "/attendance/report/index" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "考勤与值班",
            "name" => "考勤日统计",
            "desc" => "查看考勤日统计"
        ),
        "/attendance/report/month" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "考勤与值班",
            "name" => "考勤月统计",
            "desc" => "查看考勤月度统计"
        ),
        "/attendance/record" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "考勤与值班",
            "name" => "考勤原始记录",
            "desc" => "查看考勤原始记录"
        ),
        "/attendance/setting" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "考勤与值班",
            "name" => "考勤设置",
            "desc" => "配置考勤的班组、班次等"
        ),
        "/attendance/apply/add" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "考勤与值班",
            "name" => "出勤登记",
            "desc" => "登记某人的考勤记录，更新打卡时间等"
        ),
        "/attendance/onduty/add" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "考勤与值班",
            "name" => "值班安排",
            "desc" => "排班表设置"
        ),
        "/meeting/add/index" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "会议管理",
            "name" => "安排会议",
            "desc" => "发起新会议"
        ),
        "/approve/stamp/add" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "流程与审批",
            "name" => "电子章管理",
            "desc" => "新增、删除电子章"
        ),
        "/approve/setting/director" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "流程与审批",
            "name" => "主管部门设置",
            "desc" => "设置个流程审批的主要管理部门"
        ),
        "/approve/form/add" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "流程与审批",
            "name" => "审批表单设计",
            "desc" => "定义各申请事项的表单"
        ),
        "/officialdoc/setting" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "公文管理",
            "name" => "发文配置",
            "desc" => "配置发文的文号及其对应的主管部门、使用的印章等"
        ),
        "/hr/org/post_index" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "人力资源",
            "name" => "部门管理",
            "desc" => "部门的新增、修改等维护"
        ),
        "/hr/index/(add|edit)" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "人力资源",
            "name" => "新增干警",
            "desc" => "新增、修改干警信息"
        ),
        "/hr/user/(post_remove|post_enabled)" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "人力资源",
            "name" => "删除禁用干警",
            "desc" => "删除禁用干警账号"
        ),
        "/hr/worker/(add|edit)" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "人力资源",
            "name" => "新增工人",
            "desc" => "新增、修改工人信息"
        ),
        "/hr/import" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "人力资源",
            "name" => "数据导入",
            "desc" => "导入人员信息"
        ),
        "/hr/group/add" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "人力资源",
            "name" => "分组管理",
            "desc" => "对人员进行分组管理，在系统中可以按分组指定人"
        ),
        "/hr/org/post_position" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "人力资源",
            "name" => "职务管理",
            "desc" => "职务维护"
        ),

        "/acl" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "人力资源",
            "name" => "权限控制",
            "desc" => "设置功能的使用权限"
        ),
        "/purchase/goods/add" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "采购管理",
            "name" => "物资登记",
            "desc" => "登记物资信息、数量"
        ),
        "/purchase/goods/grant" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "采购管理",
            "name" => "物品领用登记",
            "desc" => "登记物资发放记录，那个物品领取了什么"
        ),
        "/file/dir/post_config" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "资料管理",
            "name" => "目录管理",
            "desc" => "管理共享目录机构"
        ),
        "/cms/notify/add" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "通知与信息",
            "name" => "发布通知",
            "desc" => "发布站内通知"
        ),
        "/cms/news/add" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "通知与信息",
            "name" => "发布信息",
            "desc" => "发布站内信息"
        ),
        "/cms/news/post_column" => array(
            "deny" => "*",
            "allow" => array(),
            "module" => "通知与信息",
            "name" => "信息栏目管理",
            "desc" => "维护信息栏目"
        ),
        "/dashboard/links"=> array(
            "deny" => "*",
            "allow" => array(),
            "module" => "系统管理",
            "name" => "自定义业务菜单",
            "desc" => "自定义业务菜单中的链接"
        ),

        "/car/add"=> array(
            "deny" => "*",
            "allow" => array(),
            "module" => "公务用车",
            "name" => "登记车辆",
            "desc" => "登记修改车辆"
        ),

        "/outsource/green"=> array(
            "deny" => "*",
            "allow" => array(),
            "module" => "外协人员入监",
            "name" => "绿色通道",
            "desc" => "绿色通道，无需审批和门岗审核"
        ),
    );

    return $array;
}

?>
