<?php

class MoneyV extends extend_view
{

    public function generate() {
        header("Content-type: application/json");
        echo json_encode($this->object, JSON_UNESCAPED_UNICODE);
    }
    
}