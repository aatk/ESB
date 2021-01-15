<?php

define('ROOT', dirname(__FILE__) . '/');

function loader($class, $metod = "")
{
    return new $class();
}

function mb_ucfirst($str, $encoding = 'UTF-8')
{
    $str = mb_ereg_replace('^[\ ]+', '', $str);
    $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) .
        mb_substr($str, 1, mb_strlen($str), $encoding);
    
    return $str;
}

function clean_classname($classname)
{
    /*НАДО БЕЗОПАСНО ОБРАБОТАТЬ ПЕРЕД EVAL*/
    $notAllow  = [ '/', '\\', '"', ':', '*', '?', '<', '>', '|', '%' ];
    $classname = str_replace($notAllow, '', $classname);
    $classname = mb_substr($classname, 0, 50, 'utf-8');
    $classname = mb_ucfirst($classname);
    //$classname = mysql_escape_string($classname);   //PHP 7 - не работает
    /*ЗАКОНЧИЛИ ОБРЕЗАНИЕ*/
    
    return $classname;
}

/**
 * @param string $class_name
 *
 * @return bool
 */
function class_autoload($class_name)
{
    $require_file = "";
    if ($require_file == "")
    {
        $file = ROOT . 'controllers/' . $class_name . '.controller.php';
        if (file_exists($file) !== false)
        {
            $require_file = $file;
        }
    }
    
    if ($require_file == "")
    {
        $file = ROOT . 'models/' . $class_name . '.model.php';
        if (file_exists($file) !== false)
        {
            $require_file = $file;
        }
    }
    
    if ($require_file == "")
    {
        $file = ROOT . 'vendors/' . $class_name . '.class.php';
        if (file_exists($file) !== false)
        {
            $require_file = $file;
        }
    }
    
    if ($require_file == "")
    {
        $file = ROOT . 'interfaces/' . $class_name . '.interface.php';
        if (file_exists($file) !== false)
        {
            $require_file = $file;
        }
    }
    
    if ($require_file != "")
    {
        require_once($require_file);
        $result = true;
    }
    else
    {
        $result = false;
    }
    
    return $result;
}

spl_autoload_register('class_autoload');
