<?php

class SystemM extends extend_controller implements InstallModule
{
    public function __construct($method = "", $debug = false)
    {
        $this->Patch = $_SERVER["DOCUMENT_ROOT"] . "/";
        parent::__construct();   //на тот случай если мы будем наследовать от класса
        $this->method = $method;
    }
    
    public function InstallModule()
    {
        $dirlist = [ "controllers", "interfaces", "models", "vendors", "tmp" ];
        foreach ($dirlist as $dir)
        {
            if (!file_exists($dir))
            {
                mkdir($dir);
            }
        }
    }
    
    public function UpdateSystem()
    {
        //TODO переделать под новый стандарт
        ob_start();
        
        echo "<h1>START INSTALL</h1>\r\n";
        echo "<h1>" . $_SERVER["SERVER_NAME"] . "</h1>\r\n";
        echo "<h1>CREATE DB</h1>\r\n";
        
        $directories = [ 'controller' => 'controllers', 'model' => 'models', 'class' => 'vendors' ];
        
        /* Устанавливаем все БД */
        foreach ($directories as $key => $dir)
        {
            echo "<h2>$dir</h2>\r\n";
            $files1 = scandir($dir);
            foreach ($files1 as $value)
            {
                if (!in_array($value, [ ".", ".." ]))
                {
                    $class = pathinfo($dir . "/" . $value);
                    $class = (str_ireplace("." . $key, "", $class["filename"]));
                    if (class_exists($class))
                    {
                        $implements = class_implements($class);
                        if (in_array('CreateDB', $implements))
                        {
                            echo "<p>Устанавливаем модуль $class</p>\r\n";
                            $newobject = loader($class);
                            $newobject->CreateDB();
                            echo "<p>Закончили с $class</p>\r\n";
                        }
                    }
                    
                }
            }
        }
        
        
        echo "<h1>Install Module</h1>\r\n";
        /* Устанавливаем все преднастройки */
        foreach ($directories as $key => $dir)
        {
            echo "<h2>$dir</h2>\r\n";
            $files1 = scandir($dir);
            foreach ($files1 as $value)
            {
                if (!in_array($value, [ ".", ".." ]))
                {
                    $class = pathinfo($dir . "/" . $value);
                    $class = (str_ireplace(".".$key, "", $class["filename"]));
                    if (class_exists($class))
                    {
                        $newobject = loader($class);
                        if ($newobject instanceof InstallModule)
                        {
                            echo "<p>Настраиваем модуль $class</p>\r\n";
                            $newobject->InstallModule();
                        }
                    }
                    
                }
            }
        }
        
        
        echo "<h1>END INSTALL</h1>\r\n";
        
        $content = ob_get_contents();
        ob_end_clean();
        
        return $content;
    }
}