<?php

namespace app\common;

use \yangzie\YZE_Resource_Controller;
use \yangzie\YZE_Request;
use \yangzie\YZE_Redirect;
use \yangzie\YZE_Session_Context;
use \yangzie\YZE_RuntimeException;
use yangzie\YZE_Simple_View;

/**
 * 视图的描述
 * @param type name optional
 *
 */
$temps = $_GET [ "params" ] ? urldecode ( $_GET [ "params" ] ) : "";
$params = array ();
if ( $temps ) {
    $temps = explode ( "&", $temps );
    if ( $temps ) {
        foreach ( $temps as $temp ) {
            if ( $temp ) {
                $_temp = explode ( "=", $temp );
                $params [ trim ( @$_temp [ 0 ] ) ] = trim ( @$_temp [ 1 ] );
            }
        }
    }
}
$data = array (
    "id" => $_GET [ "id" ],
    "save_path" => $_GET [ "save_path" ],
    "action" => $_GET [ "action" ],
    "filesize" => $_GET [ "filesize" ],
    "extensions" => $_GET [ "extensions" ],
    "back_uploade_error" => $_GET [ "back_uploade_error" ],
    "back_uploade_complete" => "back_uploade_complete",
    "back_upload_before" => "back_upload_before",
    "params" => $params
);
$view = new YZE_Simple_View ( YD_COMMON_UPLOAD_DRAG_VIEW, $data, $this->controller );
$view->output ();
?>
<script type="text/javascript">
    function back_uploade_complete () {
        $("#<?php echo $_GET["id"] . "-modal"?>").find(".common_upload_finish").prop("disabled", false);
    }

    function back_upload_before () {
        $("#<?php echo $_GET["id"] . "-modal"?>").find(".common_upload_finish").prop("disabled", true);
    }
</script>
