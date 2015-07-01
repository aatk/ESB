<?php

class Ats24 extends ex_class {

    public function __construct($connectionInfo) {
        parent::__construct($connectionInfo);
    }   
    
    
    public function Start($metod, $param) {
        $result = "";
        $input = $param[q];
        $query = explode('/', $input);

        if ($metod == "POST") {
            $query_p["queuename"] = "ats24";
            $query_p["metod"] = $metod;
            $query_p["q"] = $query[1]."/".$query[2];
            $query_p["date"] = date("Y-m-d H:i:s");
            $query_p["bodyq"] = file_get_contents('php://input');
            $id = $this->savequeue($query_p);
            $connectionInfo = array("host" => "localhost" );
            $thread = new Threads($connectionInfo);
            $result = $thread->GET("ats24/start/".$id);
            if (isset($result["error"])) {
                $result = "Не удалось открыть поток: ".$result["error"];
            } else {
                $result = $id;
            }
        } elseif ($metod == "GET") {
            unset($query[0]); //Удалим первый элемент
            if ($query[1] == "queuelist") {
                $wparam = array();
                if (count($query) > 1) {
                    $wparam["id"] = $query[2];
                }
                $result = $this->getlist($wparam);
            } elseif ($query[1] == "queue") {
                $wparam["id"] = $query[2];
                $result = $this->getqueue($wparam);
            } elseif ($query[1] == "start") {
                $result = $this->insert_into_kih($query[2]);
            }
        }
        
        return $result;
    }
    
    
    
    public function savequeue($queue) {
        $result = 0;
        $id = $this->insert("queue", $queue);
        if ($id) {$result = $id;}
        return $result;
    }

    public function getlist($param) {
        //$result = 0;
        $limit = 0;
        $param['queuename'] = "ats24";
        if (isset($param['id'])) {
            $id = $param['id'];
            $param['id'] = array('>', $param['id']);
        } else {
            $limit = 50;
        }
        $result = $this->get_where("queue", $param, $limit);
        return $result;
    }
    
    public function getqueue($param) {
        $result = "";
        $param['queuename'] = "ats24";
        $result = $this->get_where("queue", $param);
        return $result;
    }    

    public function insert_into_kih($id) {

        $result = $id;
        $connectionInfo = array("type" => "MSSQL", "host" => "192.168.10.30", "db" => "CMS_1C_EXCHENGE_TEST", "user" => "sa", "pass" => "1CAdmin");
        $MSSQL_class = new ex_class($connectionInfo);

        $array = $this->get_by_id('queue', $id);
        $tag = $array[0];

        $input = $tag['q'];
        $query = explode('/', $input);
        $json  = $tag['bodyq'];

        $forsql['id'] = $id;
        $forsql['date'] = str_ireplace(" ","T",$tag['date']);//strtotime($tag['date']);//
        $forsql['mode'] = $query[0];
        $forsql['messageid'] = $query[1];
        $forsql['data'] = $json;

        $msaddon['dbo'] = "[CMS_1C_EXCHENGE_TEST].[dbo].";

        $result = $MSSQL_class->insert("ats24", $forsql, $msaddon);

        return $result;
    }


}

?>