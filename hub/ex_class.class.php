<?php

class ex_class extends sql_connect
{

    public function __construct($connectionInfo) {
        parent::__construct($connectionInfo);
    }


    public function jsDate($datestr) {
        $format = 'Y-m-d H:i:s';
        $mdate = date_parse_from_format($format, $datestr);
        $jsDate = $mdate['year'] . "," . ($mdate['month'] - 1) . "," . $mdate['day'] . "," . $mdate['hour'] . "," . $mdate['minute'] . "," . $mdate['second'];
        return $jsDate;
    }

    public function get_sql_array($sql) {
        $arr = array();
        
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
            while ($row = $result->fetch_assoc()) {
                $arr[] = $row;
            }
            
            mysqli_free_result($result);
        } catch (Exception $e) {
            $this->echo_log($e);
        }
        
        return $arr;
    }


    public function get_by_id($Reference="", $id = 0) {
        $result = array();
        $sn = $this->sn;
        $fn = $this->fn;
        if (($id != 0) && ($Reference != "")) {
            //$name = $this->get_name($Reference);
            $name = $Reference;
            $sql = "SELECT * FROM ".$sn.$name.$fn." WHERE ".$sn.$name.$fn.".".$sn."id".$fn." = $id";
            $array1 = $this->get_sql_array($sql);
            if (count($array1) != 0) {
                $result = $array1;
            }
        }
        return $result;
    }

    /**
     * @param string $Reference
     * @param $where
     * @return array
     */
    public function get_where($Reference="", $where, $limit = 0) {
        $result = array();
        $sn = $this->sn;
        $fn = $this->fn;
        if ($where != 0)  {
            //$Reference = $this->get_name($Reference);
            $VAL = "true";
            if (count($where)>0) {
                $W = array();
                foreach($where as $key => $value) {
                    if (is_array($value)) {
                        //Сложный запрос
                        $W[] = $sn.$Reference.$fn.".".$sn.$key.$fn." ".$value[0]."  '".$value[1]."'";
                    } else {
                        $W[] = $sn.$Reference.$fn.".".$sn.$key.$fn." =  '$value'";
                    }
                }
                $VAL = implode(" AND ",$W);
            }

            $sql = "SELECT * FROM ".$sn.$Reference.$fn." WHERE $VAL";//`pricehotels`.`id` = $id";
            $array1 = $this->get_sql_array($sql);
            if (count($array1) != 0) {
                $result = $array1;
            }
        }
        return $result;
    }


    /**
     * @param string $Reference
     * @param int $id
     * @param array $info
     * @return bool
     */
    public function update($Reference="", $id = 0, $info) {
        $result = false;
        if (($info != 0) && (is_array($info)) && ($Reference != "")) {
            $setstr = "";
            foreach ($info as $key => $value) {
                if ($setstr != "") {$setstr = $setstr.', '; };
                //$value = $this->mysqli->real_escape_string($value);
                //$value = addslashes($value);
                $value = $this->realstr($value);
                if (!is_numeric($value)) {$value = "'".$value."'";}
                else {
                    //print_r(intval($value)." -- ".(floatval($value)+0.2));
                    if (intval($value) != floatval($value)) {$value = "'".$value."'";} else
                    {$value = intval($value);}
                }
                //if (is_numeric($value) && is_float($value)) {$value = "'".$value."'";};
                $setstr = $setstr."`".$key."` = $value ";
            }

            $sql="UPDATE `$Reference` SET $setstr WHERE `id` = $id";
            $this->get_insert_id($sql);
            $result = true;
        }
        return $result;
    }


    public function delete($Reference="",$id = 0) {
        $result = false;
        if (($id != 0) && ($Reference != "")) {
            $sql="DELETE FROM `$Reference` WHERE `id` = $id";
            $this->get_insert_id($sql);
            $result = true;
        }
        return $result;
    }
    

}

?>