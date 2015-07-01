<?php

class sql_connect
{
    public $sql_interface = null;

    private $type = '';
    private $dbHost = '';// чаще всего это так, но иногда требуется прописать ip адрес базы данных
    private $dbName = '';// название вашей базы
    private $dbUser = '';// пользователь базы данных
    private $dbPass = '';// пароль пользователя

    public $sn = '`';
    public $fn = '`';

    public function __construct($connectionInfo)
    {
        if (isset($connectionInfo["type"]) &&
            isset($connectionInfo["host"]) &&
            isset($connectionInfo["user"]) &&
            isset($connectionInfo["pass"]) &&
            isset($connectionInfo["db"])
        ) {

            $this->type = $connectionInfo["type"];
            $this->dbHost = $connectionInfo["host"];
            $this->dbUser = $connectionInfo["user"];
            $this->dbPass = $connectionInfo["pass"];
            $this->dbName = $connectionInfo["db"];

            $this->sql_interface = $this->connect($this->type);
        } else {
            $this->sql_interface = false;
        }

    }


    public function mastdie($code, $error = "") {
        $this->echo_log("ОШИБКА: ".$error);
        throw new MyException($code.":".$error);
    }

    public function echo_log($e) {
        ob_start();
        if ((is_array($e)) || (is_object($e))) {
            print_r($e);
        } else {
            echo $e."\r\n";
        }
        $String = ob_get_contents();
        ob_end_clean();

        echo $String;
        file_put_contents('dumplog.txt', $String, FILE_APPEND);
    }





    private function connect($type)
    {
        //if ($_SERVER["SERVER_NAME"] == "localhost") {
        //  $this->dbHost = "talin.beget.ru";
        //}

        $dbHost = $this->dbHost;
        $dbUser = $this->dbUser;
        $dbPass = $this->dbPass;
        $dbName = $this->dbName;

        if ($type == "MSSQL") {
            $this->sn = '[';
            $this->fn = ']';

            $serverName = $dbHost;//"192.168.10.30";
            $connectionInfo = array("Database" => $dbName, "UID" => $dbUser, "PWD" => $dbPass);
            $sql = sqlsrv_connect($serverName, $connectionInfo);
            if ($sql === false) {
                printf("Error information:\r\n");
                foreach (sqlsrv_errors() as $error) {
                    printf("SQLSTATE: " . $error['SQLSTATE'] . "\r\n");
                    printf("Code: " . $error['code'] . "\r\n");
                    printf("Message: " . $error['message'] . "\r\n");
                    exit();
                }
            }

        } else {
            $sql = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
            if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
            }
            if (!mysqli_set_charset($sql, "utf8")) {
                printf("Ошибка при загрузке набора символов utf8: %s\n", mysqli_error($sql));
            };
            mysqli_query($sql, "set names utf8");
        }

        return $sql;
    }

    public function reconnect()
    {
        if ($this->type == "MSSQL") {
            $result = $this->reconnect_mssql();
        } else {
            $result = $this->reconnect_mysql();
        }
        return $result;
    }


    /**
     * @param string $Reference
     * @param $info
     * @return bool|int|string
     */
    public function insert($Reference="", $info, $msaddon = null) {
        $result = false;
        $sn = $this->sn;
        $fn = $this->fn;

        if (($info != 0) && (is_array($info)) && ($Reference != "")) {
            $keystr = "";
            $paramstr = "";
            $key_ar = array();
            $param_ar = array();

            foreach ($info as $key => $value) {
                if ($keystr != "") {
                    $keystr = $keystr . ', ';
                };
                if ($paramstr != "") {
                    $paramstr = $paramstr . ', ';
                };
                $keystr = $keystr . $sn . $key . $fn;
                $value = $this->realstr($value);
                $paramstr = $paramstr . "'" . $value . "'";

                $key_ar[] = $key;
                $param_ar[] = $value;
            }

            $dbo = '';
            if (isset($msaddon['dbo'])) {$dbo = $msaddon['dbo'];}
            $sql="INSERT INTO ".$dbo.$sn.$Reference.$fn." ($keystr) VALUES ($paramstr)";
            $id = $this->get_insert_id($sql, $param_ar);
            $result = $id;
        }
        return $result;
    }

    public function get_insert_id($sql, $params=null) {
        if ($this->type == "MSSQL") {
            $result = $this->get_insert_id_mssql($sql, $params);
        } else {
            $result = $this->get_insert_id_mysql($sql);
        }
        return $result;
    }

    public function realstr($str) {
        if ($this->type == "MSSQL") {
            $result = $this->realstr_mssql($str);
        } else {
            $result = $this->realstr_mysql($str);
        }
        return $result;
    }

    //////////////////////////////////////////////////////////////////////////////
    //
    //                                MySQL
    //
    //////////////////////////////////////////////////////////////////////////////

    public function get_insert_id_mysql($sql) {
        $arr = 0;

        //Это должно помочь восстанавливать соединения если они разорвались
        $this->reconnect();

        $result = mysqli_query($this->sql_interface, $sql);
        //echo $sql;
        if (!$result) {
            $error_no = mysqli_errno($this->sql_interface);
            $error_text = mysqli_error($this->sql_interface);
            $sqlstate_text = mysqli_sqlstate($this->sql_interface);
            $this->mastdie(0, "сдох при чтении :( - ".$sqlstate_text."  Error: $error_no:".$error_text."  SQL:".$sql);
        }

        try{
            $arr = mysqli_insert_id($this->sql_interface);
            mysqli_free_result($result);
        } catch (Exception $e) {
            $this->echo_log($e);
        }

        return $arr;
    }

    public function realstr_mysql($str) {
        $result = $this->sql_interface->real_escape_string($str);
        return $result;
    }

    public function reconnect_mysql()
    {
        if (!$this->sql_interface->ping()) {
            $this->sql_interface = $this->connect($this->type);
        }
        $result = $this->sql_interface;
        return $result;
    }

    //////////////////////////////////////////////////////////////////////////////
    //
    //                                MSSQL
    //
    //////////////////////////////////////////////////////////////////////////////

    public function get_insert_id_mssql($sql, $params = null) {
        $arr = 0;

        //Это должно помочь восстанавливать соединения если они разорвались
        $this->reconnect();
        $sql = $sql."; SELECT SCOPE_IDENTITY() as insrtId";

        $result = sqlsrv_query($this->sql_interface, $sql, $params);
        //print_r($params);
        //echo $sql;
        if (!$result) {
            foreach (sqlsrv_errors() as $error) {
                $sqlstate_text  = $error['SQLSTATE'];
                $error_no       = $error['code'];
                $error_text     = mb_convert_encoding($error['message'], 'utf-8', 'ibm-866');
            }
            $this->mastdie(0, "сдох при чтении :( - ".$sqlstate_text."  Error: $error_no:".$error_text."  SQL:".$sql);
        }

        try{
            $res = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC);
            if (isset($res[0]['insrtId'])) {
                $arr = $res[0]['insrtId'];
            } else {$arr = -1;};
        } catch (Exception $e) {
            $this->echo_log($e);
        }

        return $arr;
    }

    private function sql_valid($string) {
        return str_replace("'","''", $string);
    }

    public function realstr_mssql($str) {
        $result = $this->sql_valid($str);
        return $result;
    }

    public function reconnect_mssql()
    {
        $result = $this->sql_interface;
        return $result;
    }

}

?>