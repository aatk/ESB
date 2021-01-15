<?php

class System extends extend_controller
{
    private $SystemM;
    
    public function __construct($method = "", $debug = false)
    {
        $this->method          = $_SERVER["REQUEST_METHOD"];
        $this->SystemM = new SystemM($method);
        $this->Patch = $_SERVER["DOCUMENT_ROOT"] . "/";
        parent::__construct();   //на тот случай если мы будем наследовать от класса
    }
    
    public function Init($param)
    {
        $result           = [];
        $result["result"] = false;
        $result["error"]  = "Error function call";
        
        $func = strtolower($param[0]);
        
        if ($this->method == "GET")
        {
            if ($func == "updatesystem")
            {
                $result = $this->SystemM->UpdateSystem();
            }
        }
        
        return $result;
    }
}