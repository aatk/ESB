<?php

require_once ROOT . 'vendor/autoload.php';

class TwigRender extends extend_view
{
    
    public function generate()
    {
        $html = $this->object["html"];
        $params = $this->object["params"];
    
        //echo $this->Show('Hello {{ name }} {% include "template" %}', ["name" => "TEST"]);
        echo $this->Show($html, $params);
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