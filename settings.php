<?php
session_start();
unset($_SESSION["db_connect"]);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'default/PHPMailer/Exception.php';
require 'default/PHPMailer/PHPMailer.php';
require 'default/PHPMailer/SMTP.php';

function adddinamicsetting(&$settings)
{
    $dir = $_SERVER["DOCUMENT_ROOT"];
    $exdir = $dir . "/private/settings";
    if (!file_exists($dir . "/private")) {
        mkdir($dir . "/private");
    }
    if (!file_exists($dir . "/private" . "/settings")) {
        mkdir($dir . "/private" . "/settings");
    }
    $files1 = scandir($exdir);
    foreach ($files1 as $value) {
        if (!in_array($value, array(".", ".."))) {
            $settingsjsonfile = $exdir . "/" . $value;

            $content = file_get_contents($settingsjsonfile);
            $json = json_decode($content, true);

            if (isset($json["Info"])) {
                $setsetting = [];
                foreach ($json["Info"] as $val) {
                    $setsetting = array_merge($setsetting, $val);
                }
                if (isset($json["Name"])) {
                    $settings[$json["Name"]] = $setsetting;
                }

                if (isset($json["Name"])) {
                    $settings[$json["Name"]] = $setsetting;
                }
            }
        }
    }
}

$agent = explode(".", $_SERVER["HTTP_HOST"])[0];
$settings["agent"] = $agent;

//Динамическое подключение настроек
adddinamicsetting($settings);

if (isset($_SESSION["i4b"])) {
    $saa = $_SESSION["i4b"];
    if (isset($saa["auth"])) {
        $settings["auth"] = $saa["auth"];
    } else {
        $settings["auth"] = [];
    }
} else {
    $settings["auth"] = [];
}

$_SESSION["i4b"] = $settings;
