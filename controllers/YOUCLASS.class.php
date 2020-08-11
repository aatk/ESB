<?php

class YOUCLASS extends ex_class
{
    private $metod;
    
    public function __construct($metod = "")
    {
        $this->metod = $metod;
        parent::__construct(null);
    }
    
    public function CreateDB()
    {
        //this code runs when installing or reinstalling the system core
        //the framework is used for working with the database made.in (documentation https://medoo.in/)
        
        /* Description of tables for the class */
        $info_table["test_table"] = [
            "id"      => [ 'type' => 'int(11)', 'null' => 'NOT NULL', 'inc' => true ],
            "varchar" => [ 'type' => 'varchar(150)', 'null' => 'NOT NULL' ],
            "int"     => [ 'type' => 'int(15)', 'null' => 'NOT NULL' ],
            "bool"    => [ 'type' => 'bool' ],
        ];
        
        $this->create('mysql', $info_table);
    }
    
    public function InstallModule()
    {
        //this code runs when installing or reinstalling the system core
    }
    
    public function Init($param)
    {
        $result          = [ "result" => false ];
        $result["error"] = "Error function call";
        
        if ($this->metod == "POST")
        {
            if ($param[0] == "demopost")
            {
                $result = $this->demoquery($param);
            }
            elseif ($param[0] == "demopost2")
            {
                //$result = $this->demopost2($param);
            }
            
        }
        elseif ($this->metod == "PATCH")
        {
            if ($param[0] == "demopatch")
            {
                $result = $this->demoquery($param);
            }
            elseif ($param[0] == "demopatch2")
            {
                //$result = $this->demoquery($param);
            }
            
        }
        elseif ($this->metod == "GET")
        {
            if ($param[0] == "demoget")
            {
                $result = $this->demoquery($param);
            }
            elseif ($param[0] == "demoget2")
            {
                //$result = $this->demoquery($param);
            }
        }
        
        return $result;
    }
    
    private function demoquery($Params)
    {
        $result = [ "result" => false ];    //if set to FALSE the request returns the status 500 and the passed data
        
        $result["Params"]   = $Params;
        $result["GET"]      = $this->GET;
        $result["POST"]     = $this->POST;
        $result["REQUEST"]  = $this->REQUEST;
        $result["SERVER"]   = $this->SERVER;
        $result["FILES"]    = $this->FILES;
        $result["URI"]      = $this->URI;
        $result["phpinput"] = $this->phpinput;
        
        $result["result"] = true;   //if set to TRUE the request returns the status 200 and the passed data
        
        return $result;
    }
}

