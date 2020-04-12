<?php
if (empty($Params)) {
    //if ajax load
    $Params = [$this->URI[4], $this->URI[5]];
}
echo json_encode($Params);
?>