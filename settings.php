<?php
unset($_SESSION["db_connect"]);

function adddinamicsetting(&$settings)
{
    $dir   = $_SERVER["DOCUMENT_ROOT"];
    $exdir = $dir . "/models/settings";
    if (!file_exists($dir . "/models"))
    {
        mkdir($dir . "/models");
    }
    if (!file_exists($dir . "/models" . "/settings"))
    {
        mkdir($dir . "/models" . "/settings");
    }
    $files1 = scandir($exdir);
    foreach ($files1 as $value)
    {
        if (!in_array($value, [ ".", ".." ]))
        {
            $settingsjsonfile = $exdir . "/" . $value;
            
            $content = file_get_contents($settingsjsonfile);
            $json    = json_decode($content, true);
            
            if (isset($json["Info"]))
            {
                $setsetting = [];
                foreach ($json["Info"] as $val)
                {
                    $setsetting = array_merge($setsetting, $val);
                }
                if (isset($json["Name"]))
                {
                    $settings[$json["Name"]] = $setsetting;
                }
            }
        }
    }
}

$agent             = explode(".", $_SERVER["HTTP_HOST"])[0];
$settings["agent"] = $agent;

//Динамическое подключение настроек
adddinamicsetting($settings);

$_SESSION["i4b"] = $settings;
