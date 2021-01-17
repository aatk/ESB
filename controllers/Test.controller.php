<?php


class Test extends extend_controller
{
    public function Init($params)
    {
        $result = [
            "html"   => 'Hello {{ name }} {% include "template" %}',
            "params" => [ "name" => "TEST" ],
        ];
        
        //echo $this->Show('Hello {{ name }} {% include "template" %}', ["name" => "TEST"]);
        return new TwigRender($result);
    }
}