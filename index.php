<?php
session_start();
$autoload = 'autoload.php';
require_once ($autoload);

function mb_ucfirst($str, $encoding='UTF-8') {
    $str = mb_ereg_replace('^[\ ]+', '', $str);
    $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
    mb_substr($str, 1, mb_strlen($str), $encoding);
    return $str;
}

////
//
//  СОЗДАЕМ АВТОМАТИЧЕСКИ ОЧЕРЕДЬ ДЛЯ НОВОГО КЛАССА
//
////
function create_queue($classname, $connection) {
    $prepare = new ex_class($connection);
    $sql = "SHOW TABLES LIKE 'queue_$classname'";
    $res = $prepare->get_sql_array($sql);
    //echo $sql;
    if (count($res) == 0) {
        $sql = "CREATE TABLE IF NOT EXISTS `queue_$classname` ( "
            ."      `id` int(11) NOT NULL,"
            ."      `date` datetime NOT NULL,"
            ."      `queuename` varchar(50) NOT NULL,"
            ."      `metod` varchar(50) NOT NULL,"
            ."      `q` varchar(500) NOT NULL,"
            ."      `bodyq` longtext NOT NULL"
            ."    ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;\r\n";
        $res = $prepare->get_sql_array($sql);
        $sql = "    ALTER TABLE `queue_$classname`"
            ."      ADD PRIMARY KEY (`id`),"
            ."      ADD FULLTEXT KEY `queuename` (`queuename`);\r\n";
        $res = $prepare->get_sql_array($sql);

        $sql = "    ALTER TABLE `queue_$classname`"
            ."      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
        $res = $prepare->get_sql_array($sql);
    }
}

$connectionInfo = array("type" => "mysql", "host" => "localhost", "db" => "aatk_filebank", "user" => "aatk_filebank", "pass" => "");

$res = array();
$METHOD = $_SERVER['REQUEST_METHOD'];
$param = $_REQUEST;
$input = $param["q"];
$query = explode('/', $input);

$classname = $query[0];
/*НАДО БЕЗОПАСНО ОБРАБОТАТЬ ПЕРЕД EVAL*/
$notAllow = Array('/', '\\', '"', ':', '*', '?', '<', '>', '|', '%');
$classname = str_replace($notAllow, '', $classname);
$classname = mb_substr($classname,0,50,'utf-8');
$classname = mb_ucfirst($classname);
/*ЗАКОНЧИЛИ ОБРЕЗАНИЕ*/
try {
    $comand = "\$wClass = new ".$classname."(\$connectionInfo);";
    eval($comand);
    create_queue($classname, $connectionInfo);
    $res = $wClass->Start($METHOD, $param);   //У ВСЕХ КЛАССОВ ДОЛЖНА БЫТЬ ФУНКЦИЯ START
} catch (Exception $e) {

}


if (is_string($res)) {
    echo $res;
} else {
    echo json_encode( $res );
}
?>