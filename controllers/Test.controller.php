<?php


class Test extends extend_controller
{
    public function Init($params)
    {
        $getparam = $params[0];
        $result = [
            "html"   => 'Hello {{ name }} {% include "template" %}',
            "params" => [ "name" => $getparam ],
            "templates" => [
                "template" => "I'm from TEMPLATE"
            ]
        ];
        
        return new TwigRender($result);
    }
}