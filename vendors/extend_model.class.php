<?php

/**
 * Class extend_model
 * Предназначен для моделей, подключения к БД
 *
 */


class extend_model extends CRUD
{
    private $curlheader;
    
    public function __construct($connectionInfo = null, $debug = false)
    {
        if ($connectionInfo == null)
        {
            $connectionInfo = $_SESSION["i4b"]["connectionInfo"];
        }
        
        parent::__construct($connectionInfo, $debug);
    }
    
    private function header_callback($ch, $header_line)
    {
        $this->curlheader .= $header_line;
        
        return strlen($header_line);
    }
    
    public function curl($url, $data, $exparam = [])
    {
        $out              = false;
        $this->curlheader = "";
        
        $encoded = "";
        if (is_array($data))
        {
            foreach ($data as $name => $value)
            {
                $encoded .= rawurlencode($name) . '=' . rawurlencode($value) . '&';
            }
            $encoded = substr($encoded, 0, strlen($encoded) - 1);
        }
        else
        {
            $encoded .= $data;
        }
        
        if ($ch = curl_init())
        {
            
            $cert = 0;
            
            curl_setopt($ch, CURLOPT_URL, $url);
            if (isset($exparam["basicauth"]))
            {
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, $exparam["basicauth"]["username"] . ":" . $exparam["basicauth"]["password"]);
            }
            
            
            curl_setopt($ch, CURLOPT_HEADER, 0);
            
            
            if ((isset($exparam["get"])) || (isset($exparam["GET"])))
            {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                curl_setopt($ch, CURLOPT_POST, 0);
            }
            elseif (isset($exparam["PUT"]))
            {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POST, 0);
            }
            elseif (isset($exparam["DELETE"]))
            {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_POST, 0);
            }
            else
            {
                curl_setopt($ch, CURLOPT_POST, 1);
            }
            
            if (isset($exparam["gzip"]))
            {
                curl_setopt($ch, CURLOPT_HTTPHEADER, [ 'Accept-Encoding: gzip,deflate' ]);
            }
            
            if (isset($exparam["headers"]))
            {
                $CURLOPT_HTTPHEADER = [];
                foreach ($exparam["headers"] as $key => $val)
                {
                    $CURLOPT_HTTPHEADER[] = $key . ": " . $val;
                }
                curl_setopt($ch, CURLOPT_HTTPHEADER, $CURLOPT_HTTPHEADER);
            }
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADERFUNCTION, [ $this, 'header_callback' ]);
            
            if ($cert)
            {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); //for solving certificate issue
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
            }
            else
            {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //for solving certificate issue
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            }
            
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encoded);
            
            $out = curl_exec($ch);
            curl_close($ch);
            
            $out = [ 'content' => $out, 'headers' => $this->curlheader ];
        }
        
        return $out;
    }
}