<?php

require_once ROOT . 'vendor/autoload.php';

class TwigRender extends extend_controller
{
    public function __construct($method)
    {
        parent::__construct($method);
    }
    
    public function Init($Params)
    {
        return $this->Show('Hello {{ name }} {% include "template" %}', ["name" => "TEST"]);
    }
    
    public function Show($text, $params = [])
    {
        $loader = new \Twig\Loader\ArrayLoader([
            'index' => $text,
            'template' => ' fffffff ',
        ]);
        $twig   = new \Twig\Environment($loader);

        $result = $twig->render('index', $params);
        
        return $result;
    }
}