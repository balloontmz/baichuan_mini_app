<?php

/**
 * 视图的描述
 * @author 作者
 *        
 */

namespace app\log;

use yangzie\YZE_JSON_View;

$comments = $this->get_data ( 'comments' );
$datas = array ();
if ( $comments ) {
	foreach ( $comments as $comment ) {
		$c = $comment->get_records ();
		$u = $comment->get_user ();
		$e = $u->getEmployee ();
		$c [ "date" ] = dateTimeFormatToString($c['date']);
		$c [ "user" ] = $e->get_records ();
		$c [ "user" ] [ "avatar" ] = $c [ "user" ] [ "avatar" ] ? $c [ "user" ] [ "avatar" ] : "/img/avatar_default.png";
		$datas [] = $c;
	}
}
echo YZE_JSON_View::success ( $this->controller, $datas )->get_output ();
?>
