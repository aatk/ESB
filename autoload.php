<?php
define('ROOT',dirname(__FILE__).'/');
function class_autoload($class_name) {
  $file = ROOT.'classes/'.$class_name.'.class.php';
  if( file_exists($file) == false )
    return false;
  require_once ($file);
}
function class_hub($class_name) {
  $file = ROOT.'hub/'.$class_name.'.class.php';
  if( file_exists($file) == false )
    return false;
  require_once ($file);
}
function class_default($class_name) {
  $file = ROOT.'default/'.$class_name.'.class.php';
  if( file_exists($file) == false )
    return false;
  require_once ($file);
}
spl_autoload_register('class_autoload');
spl_autoload_register('class_hub');
spl_autoload_register('class_default');
?>