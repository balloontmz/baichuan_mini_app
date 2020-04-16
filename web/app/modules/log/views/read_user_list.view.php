<?php

namespace app\log;

use app\log\At_User_Model;
use yangzie\YZE_View_Component;

/**
 * 某个主体的阅读者列表
 * target_class target_id, module
 * 
 * @author ydhlleeboo
 *        
 */
class Read_User_List_View extends YZE_View_Component {
    protected function output_component() {
        
        $at_users = At_User_Model::from()->where("target_class=:table and target_id=:bid")
        ->select(array(":table"=>$this->get_data("target_class"),":bid"=>$this->get_data("target_id")));
        ?>
<div class="portlet-title" style="margin-top: 20px;">
    <div class="caption font-black-sharp">
        <span class="caption-subject bold uppercase"> 阅读者</span>
    </div>
</div>

<div class="portlet-body">
    <div class="row">
					<?php
        
foreach ( $at_users as $at_user ) {
                $read_user = $at_user->get_user ();
                $employee = $read_user->getEmployee ();
            ?>
						<img class="yd-img-touxiang" alt="<?php echo $employee->name?>" title="<?php echo $employee->name?>"
            src="<?php echo $employee->getAvatar()?>"> 
					<?php }?>
					</div>
</div>
<?php
    }
}
?>