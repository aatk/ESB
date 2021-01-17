<?php

class Api extends extend_controller
{
    public function Init($params)
    {
        $result = [
            "result" => false,
            "inputparams" => $params
        ];
        
        if ($this->method = "GET") {
            //GET
        }
        elseif ($this->method = "POST") {
            //POST
        }
        elseif ($this->method = "PUT") {
            //PUT
        }
        elseif ($this->method = "DELETE") {
            //DELETE
        }

        return $result;
    }
}