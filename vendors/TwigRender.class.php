<?php

require_once ROOT . 'vendor/autoload.php';

class TwigRender extends extend_view
{
    
    public function generate()
    {
        $html = $this->object["html"];
        $params = $this->object["params"];
        $templates = $this->object["templates"];
        
        echo $this->Show($html, $params, $templates);
    }
    
    public function Show($text, $params = [], $templates = null)
    {
        $loader_params = [
            'index' => $text,
        ];
        
        if (!is_null($templates))
        {
            $loader_params = array_merge($loader_params, $templates);
        }
        
        $loader = new \Twig\Loader\ArrayLoader($loader_params);
        $twig   = new \Twig\Environment($loader);

        $result = $twig->render('index', $params);
        
        return $result;
    }
}