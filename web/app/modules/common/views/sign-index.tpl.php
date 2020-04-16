<?php

namespace app\common;

use yangzie\YZE_Hook;
use yangzie\YZE_Simple_View;

/**
 * 登录界面
 * @param type name optional
 *       
 */

/**
 *
 * @var YZE_Simple_View
 */
$view = YZE_Hook::do_hook ( YD_COMMON_SIGNIN_VIEW, $this->controller );

if ( $view && is_a($view, 'yangzie\YZE_Simple_View') ) {
	$view->output ();
    $this->layout = $view->layout;
	return;
}
?>
请注册hook YD_COMMON_SIGNIN_VIEW 实现登录界面