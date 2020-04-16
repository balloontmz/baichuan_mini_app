<?php

namespace yangzie;

$this->layout = "error";

$exception = $this->get_data("exception");
$request = YZE_Request::get_instance();
?>
<div class="alert alert-danger">
    <?php echo $exception->getMessage() ?>
</div>

