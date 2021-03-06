<?php
//НИКОГДА НЕ СОХРАНЯЙТЕ В СЕССИЮ ОБЪЕКТ!!!!
//НЕ ИСПОЛЬЗУЙТЕ exit() - сломаются сессии.

ini_set('display_errors', 'Off');
header('Access-Control-Allow-Origin: *');

//Если надо обновить все настройки и таблицы, сделайте пустой php с $System->UpdateSystem();

require_once 'autoload.php'; //подключаем автозагрузку доп.классов
session_start(); //Стартуем сессию только после подключения библиотек и автолоадера
require_once 'settings.php'; //подключаем настройки

//Основной приниающий файл REST API
    $method = $_SERVER["REQUEST_METHOD"];
    $q      = $_REQUEST["q"];
    unset($_REQUEST["q"]);
    $res = explode("/", $q);
    
    $class = clean_classname($res[0]);
    
    if (class_exists("Routh"))
    {
        $wClass = loader("Routh");
        $result = $wClass->Init([$class, $res]);
    }
    elseif (class_exists($class))
    {
        $wClass = loader($class);
        $param = array_slice($res, 1);
        $result = $wClass->Init($param);
    }
    elseif (isset($_REQUEST["q"]))
    {
        $result['result'] = false;
        $result['error']  = "No such treatment";
        $result['msg']    = "$class";
    }
    else
    {
        $result['result'] = false;
        $result['error']  = "Error handling to a REST API";
    }


unset($_SESSION["db_connect"]); // удаляем из сессии класс с базой


//Выводим результат и благополучно выходим
if (is_array($result)) {
    if ((isset($result["result"]) && ($result["result"] === false)) || ($result === false))
    {
        $code    = 500;
        $message = "Internal Server Error";
        if (isset($result["errorinfo"]))
        {
            $code    = $result["errorinfo"]["code"];
            $message = $result["errorinfo"]["message"];
        }
        
        $header_text = 'HTTP/1.1 ' . $code . ' ' . $message;
        header($header_text);
    }
    else
    {
        header("Content-type: application/json");
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}

if (is_string($result))
{
    echo $result;
}
elseif (is_object($result) && ($result instanceof View))
{
    echo $result->generate();
}
elseif ($result instanceof SimpleXMLElement)
{
    header('Content-Type: text/xml; charset-utf-8');
    echo $result->asXML();
}
elseif (is_bool($result) && !$result)
{
    if (!$result)
    {
        $header_text = 'HTTP/1.1 500 False return';
    }
    header($header_text);
    echo "";
}
