<?php

/**
 * Class extend_controller
 *
 * Класс для работы контроллеров
 */


abstract class extend_controller implements Controller
{
    public $GET      = [];
    public $FILES    = [];
    public $POST     = [];
    public $REQUEST  = [];
    public $SERVER   = [];
    public $URI      = [];
    public $phpinput = [];
    
    public $debugclass;
    public $method;

    public function DTV($jsonarray, $inattr, $defresult = "", $dateformatfrom = "", $dateformatto = "YmdHis")
    {
        
        $finds = [];
        if (is_array($inattr))
        {
            $finds = $inattr;
        }
        else
        {
            $finds[] = $inattr;
        }
        
        $result = "";
        $rr     = $jsonarray;
        foreach ($finds as $value)
        {
            if (isset($rr[$value]))
            {
                $rr = $rr[$value];
                if (!is_array($rr))
                {
                    $result = $rr;
                }
                elseif (is_array($rr) && (count($rr) == 0))
                {
                    $result = "";
                }
                else
                {
                    $result = $rr;
                }
            }
            else
            {
                $result = "";
                break;
            }
        }
        
        if ($result == "")
        {
            $result = $defresult;
        }
        
        if ($dateformatfrom != "")
        {
            $dateDepart = DateTime::createFromFormat($dateformatfrom, $result);
            if ($dateDepart !== false)
            {
                $result = $dateDepart->format($dateformatto);
            }
            else
            {
                $result = "";
            }
        }
        
        return $result;
    }
    
    public function __construct()
    {
        $this->GET      = $_GET;
        $this->POST     = $_POST;
        $this->REQUEST  = $_REQUEST;
        $this->SERVER   = $_SERVER;
        $this->FILES    = $_FILES;
        $this->URI      = explode("/", $_SERVER["REQUEST_URI"]);
        $this->phpinput = file_get_contents("php://input");
    
        $this->debugclass = false;
        if ($this->DTV($this->REQUEST, ["debug"], false)) {
            $this->debugclass = true;
        }

        $this->method          = $_SERVER["REQUEST_METHOD"];
    }
    
    abstract public function Init($params);

}