<?php

class System extends ex_class
{
    private $SystemM;
    
    public function __construct($method = "", $debug = false)
    {
        $this->SystemM = new SystemM($method);
        $this->Patch = $_SERVER["DOCUMENT_ROOT"] . "/";
        parent::__construct(null);   //на тот случай если мы будем наследовать от класса
        $this->method = $method;
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