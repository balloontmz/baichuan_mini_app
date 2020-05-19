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
    return array();
}

?>
