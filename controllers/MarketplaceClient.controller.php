<?php

class MarketplaceClient extends ex_class
{
    private $Auth;
    private $Pages;
    private $metod;
    private $connectionInfo;
    private $serverurl;
    private $username;
    private $password;
    
    public function __construct($metod = "")
    {
        parent::__construct($_SESSION["i4b"]["connectionInfo"]);   //на тот случай если мы будем наследовать от класса
        
        $this->connectionInfo = $_SESSION["i4b"]["connectionInfo"]; //Прочитаем настройки подключения к БД
        $this->metod          = $metod;
        
        $this->Auth  = new Auth();
        $this->Pages = new Pages();
        
        $this->serverurl = "https://btrip.ru/";
    }
    
    private function SetUserInfo()
    {
        
        $userinfo = $this->Auth->getusersessioninfo();
        
        $this->username = $userinfo["login"];
        if (isset($userinfo["passwordUI"]))
        {
            $this->password = $userinfo["passwordUI"];
        }
        elseif (isset($userinfo["basicpassword"]))
        {
            $this->password = $userinfo["basicpassword"];
        }
        
    }
    
    public function CreateDB()
    {
        $info["mp_Connection"] = [
            "id"                  => [ 'type' => 'int(15)', 'null' => 'NOT NULL', 'inc' => true ],
            "extkey"              => [ 'type' => 'int(15)', 'null' => 'NOT NULL' ],
            "name"                => [ 'type' => 'varchar(255)', 'null' => 'NOT NULL' ],
            "public"              => [ 'type' => 'bool', 'null' => 'NOT NULL' ],
            "version"             => [ 'type' => 'varchar(15)', 'null' => 'NOT NULL' ],
            "privateserverclass"  => [ 'type' => 'bool', 'null' => 'NOT NULL' ],
            "type"                => [ 'type' => 'bool', 'null' => 'NOT NULL' ],
            "minversion"          => [ 'type' => 'varchar(15)', 'null' => 'NOT NULL' ],
            "senddumptodeveloper" => [ 'type' => 'bool', 'null' => 'NOT NULL' ],
            "price"               => [ 'type' => 'int(15)', 'null' => 'NOT NULL' ],
            "componentprice"      => [ 'type' => 'bool', 'null' => 'NOT NULL' ],
            
            "infofile" => [ 'type' => 'longblob' ],
            "manifest" => [ 'type' => 'text' ],
            
            "install" => [ 'type' => 'bool' ],
        ];
        
        $this->create($this->connectionInfo['database_type'], $info);
    }
    
    public function Init($param)
    {
        
        $result           = [];
        $result["result"] = false;
        $result["error"]  = "Error function call";
        
        if ($this->metod == "POST")
        {
            if ($param[0] == "CreateComponentForServer")
            {
                $result = $this->CreateComponentForServer($param[1]);
                
            }
            elseif ($param[0] == "UploadComponentToServer")
            {
                $result = $this->UploadComponentToServer($param[1]);
                
            }
            elseif ($param[0] == "GetInfoComponent")
            {
                $result = $this->GetInfoComponent($param[1]);
                
            }
            elseif ($param[0] == "setsettingscomponent")
            {
                $result = $this->setsettingscomponent($param);
                
            }
        }
        elseif ($this->metod == "GET")
        {
            if ($param[0] == "InstallComponent")
            {
                $result = $this->InstallComponent($param[1]);
            }
        }
        
        return $result;
    }
    
    private function CreateFile($path, $filename, $content)
    {
        $result = [ "result" => false ];
        
        $fullpath = $_SERVER["DOCUMENT_ROOT"] . '/' . $path;
        file_put_contents($fullpath . '/' . $filename, $content);
        if (file_exists($path . '/' . $filename))
        {
            $result = [ "result" => true ];
        }
        
        return $result;
    }
    
    public function SaveClientClass($fileinfo)
    {
        $path     = "client";
        $filename = $fileinfo["name"];
        $content  = $fileinfo["content"];
        $result   = $this->CreateFile($path, $filename, $content);
        
        return $result;
    }
    
    public function CreateComponent($forminfo)
    {
        $result = [ "result" => false ];
        
        $filepages       = [];
        $filedb          = $forminfo["filepages"];
        $filedb          = array_keys($filedb);
        $Params["typep"] = 1;
        foreach ($filedb as $idfile)
        {
            $Params["id"] = $idfile;
            $fileinfo     = $this->Pages->GetPageInfo($Params);
            unset($fileinfo["data"]["text"]);
            $filepages[$idfile] = $fileinfo["data"];
        }
        $forminfo["filepages"] = $filepages;
        
        $fileclass             = $forminfo["fileclass"];
        $filesclass            = array_keys($fileclass);
        $forminfo["fileclass"] = $filesclass;
        
        $manifest = $forminfo;
        unset($manifest["infofile"]);
        
        $manifest["public"]              = $manifest["public"] == "on" ? true : false;
        $manifest["privateserverclass"]  = $manifest["privateserverclass"] == "on" ? true : false;
        $manifest["senddumptodeveloper"] = $manifest["senddumptodeveloper"] == "on" ? true : false;
        $manifest["componentprice"]      = $manifest["componentprice"] == "on" ? true : false;
        
        $data = [
            "extkey"              => 0,
            "name"                => $manifest["name"],
            "public"              => $manifest["public"],
            "version"             => $manifest["version"],
            "privateserverclass"  => $manifest["privateserverclass"],
            "type"                => $manifest["type"],
            "minversion"          => $manifest["minversion"],
            "senddumptodeveloper" => $manifest["senddumptodeveloper"],
            "price"               => $manifest["price"],
            "componentprice"      => $manifest["componentprice"],
        ];
        
        
        //$data = $manifest;
        $data["infofile"] = $forminfo["infofile"];
        $data["manifest"] = json_encode($manifest, JSON_UNESCAPED_UNICODE);
        
        //print_r($manifest);
        
        $id = $forminfo["id"];
        if ((int)$id > 0)
        {
            $this->update("mp_Connection", $data, [ "id" => $id ]);
        }
        else
        {
            $id = $this->insert("mp_Connection", $data);
            print_r($this->error());
            $result["result"] = true;
            $forminfo["id"]   = $id;
        }
        
        $result["forminfo"] = $forminfo;
        
        return $result;
    }
    
    public function GetComponent($id)
    {
        //
        $info = $this->get("mp_Connection", "*", [ "id" => $id ]);
        
        return $info;
    }
    
    public function GetManifestForForm($id)
    {
        $info           = $this->GetComponent($id);
        $manifest       = json_decode($info["manifest"], true);
        $manifest["id"] = $id;
        
        foreach ($manifest["filepages"] as $key => $filepage)
        {
            $manifest["filepages"][$key] = "on";
        }
        
        $newfileclass = [];
        foreach ($manifest["fileclass"] as $key => $fileclass)
        {
            $newfileclass[$fileclass] = "on";
        }
        $manifest["fileclass"] = $newfileclass;
        
        return $manifest;
    }
    
    private function GetLocalModsList()
    {
        $result = [ "result" => false ];
        
        $info = $this->select("mp_Connection", [
            "id(localid)",
            "extkey(id)",
            "name",
            "version",
            "minversion",
            "price(cost)",
        ]);
        
        $result["result"] = true;
        $result["list"]   = $info;
        
        return $info;
    }
    
    public function GetModsList()
    {
        $this->SetUserInfo();
        
        $result = [ "result" => false ];
        
        $exparam = [
            "basicauth" => [
                "username" => $this->username,
                "password" => $this->password,
            ],
        ];
        
        
        $url  = $this->serverurl . "marketplace/getfulllistcomponents/" . $this->agent . "/";
        $data = $this->http_c_post($url, "", $exparam);
        
        if (isset($data["content"]))
        {
            $contentjson = $data["content"];
            $content     = json_decode($contentjson, true);
            if ($content["result"])
            {
                $content = $content["list"];
            }
            
            $result["result"] = true;
            $result["list"]   = $content;
        }
        $glist = $result["list"];
        
        $newlist   = [];
        $locallist = $this->GetLocalModsList();
        
        foreach ($glist as $gvalue)
        {
            $newlist[$gvalue["id"]] = $gvalue;
        }
        
        
        foreach ($locallist as $lvalue)
        {
            $id = $lvalue["id"];
            if ($id == 0)
            {
                $newlist["local" . $lvalue["localid"]] = $lvalue;
            }
            else
            {
                $gvalue            = $newlist[$id];
                $gvalue["localid"] = $lvalue["localid"];
                $newlist[$id]      = $gvalue;
            }
        }
        
        $result["list"] = $newlist; //array_merge($glist, $locallist);
        
        return $result;
    }
    
    public function GetInfoComponent($key)
    {
        $this->SetUserInfo();
        
        $result = [ "result" => false ];
        
        $exparam = [
            "basicauth" => [
                "username" => $this->username,
                "password" => $this->password,
            ],
        ];
        
        $url  = $this->serverurl . "marketplace/getinfo/" . $key;
        $data = $this->http_c_post($url, "", $exparam);
        if (isset($data["content"]))
        {
            $res    = $data["content"];
            $result = [ "result" => true, "content" => $res ];
        }
        
        return $result;
    }




//    private function ZipStatusString( $status )
//    {
//        switch( (int) $status )
//        {
//            case ZipArchive::ER_OK           : return 'N No error';
//            case ZipArchive::ER_MULTIDISK    : return 'N Multi-disk zip archives not supported';
//            case ZipArchive::ER_RENAME       : return 'S Renaming temporary file failed';
//            case ZipArchive::ER_CLOSE        : return 'S Closing zip archive failed';
//            case ZipArchive::ER_SEEK         : return 'S Seek error';
//            case ZipArchive::ER_READ         : return 'S Read error';
//            case ZipArchive::ER_WRITE        : return 'S Write error';
//            case ZipArchive::ER_CRC          : return 'N CRC error';
//            case ZipArchive::ER_ZIPCLOSED    : return 'N Containing zip archive was closed';
//            case ZipArchive::ER_NOENT        : return 'N No such file';
//            case ZipArchive::ER_EXISTS       : return 'N File already exists';
//            case ZipArchive::ER_OPEN         : return 'S Can\'t open file';
//            case ZipArchive::ER_TMPOPEN      : return 'S Failure to create temporary file';
//            case ZipArchive::ER_ZLIB         : return 'Z Zlib error';
//            case ZipArchive::ER_MEMORY       : return 'N Malloc failure';
//            case ZipArchive::ER_CHANGED      : return 'N Entry has been changed';
//            case ZipArchive::ER_COMPNOTSUPP  : return 'N Compression method not supported';
//            case ZipArchive::ER_EOF          : return 'N Premature EOF';
//            case ZipArchive::ER_INVAL        : return 'N Invalid argument';
//            case ZipArchive::ER_NOZIP        : return 'N Not a zip archive';
//            case ZipArchive::ER_INTERNAL     : return 'N Internal error';
//            case ZipArchive::ER_INCONS       : return 'N Zip archive inconsistent';
//            case ZipArchive::ER_REMOVE       : return 'S Can\'t remove file';
//            case ZipArchive::ER_DELETED      : return 'N Entry has been deleted';
//
//            default: return sprintf('Unknown status %s', $status );
//        }
//    }
    
    
    //Получить компоненту с сервера
    public function InstallComponent($key)
    {
        $result = [ "result" => false ];
        
        if (class_exists("Auth"))
        {
            $auth = new Auth();
            if ($auth->userauth())
            {
                
                $this->SetUserInfo();
                
                $exparam = [
                    "GET"       => "GET",
                    "basicauth" => [
                        "username" => $this->username,
                        "password" => $this->password,
                    ],
                ];
                
                
                $url  = $this->serverurl . "marketplace/downloadfullcomponent/" . $key;
                $data = $this->http_c_post($url, "", $exparam);
                
                if (isset($data["content"]))
                {
                    if ($data["content"] != "")
                    {
                        $zipname = "tmp/tmp" . $key . ".zip";
                        file_put_contents($zipname, $data["content"]);
                        
                        $fullzipname = $_SERVER["DOCUMENT_ROOT"] . "/" . $zipname;
                        
                        $zip = new ZipArchive;
                        $res = $zip->open($fullzipname);
                        if ($res === true)
                        {
                            $manifestcotent = $zip->getFromName('manifest.json');
                            for ($i = 0; $i <= 31; ++$i)
                            {
                                $manifestcotent = str_replace(chr($i), "", $manifestcotent);
                            }
                            $checkLogin = str_replace(chr(127), "", $manifestcotent);
                            if (0 === strpos(bin2hex($manifestcotent), 'efbbbf'))
                            {
                                $manifestcotent = substr($manifestcotent, 3);
                            }
                            
                            $manifest = json_decode($manifestcotent, true);
                            
                            if ((string)$manifest["type"] == "7")
                            {
                                $this->InstallComponent_t7($zip, $manifest);
                            }
                            else
                            {
                                $this->installcomponent_told($fullzipname, $zip, $manifest);
                            }
                            
                            $zip->close();
                            unlink($fullzipname);
                            
                            $result = [ "result" => true ];
                        }
                        else
                        {
                            $result = [ "errors" => [ "Error zip format" ] ];
                        }
                        
                        
                    }
                }
                
                
            }
            else
            {
                $result = [ "errors" => [ "Error authorization" ] ];
            }
        }
        else
        {
            $result = [ "errors" => [ "class Auth not exist" ] ];
        }
        
        return $result;
    }
    
    private function InstallComponent_t7($zip, $manifest)
    {
        
        $filepages = [];
        $fileclass = [];
        
        $filepages = $manifest["filepages"] == null ? [] : $manifest["filepages"];
        $fileclass = $manifest["fileclass"] == null ? [] : $manifest["fileclass"];
        
        
        $infofilecontent = $zip->getFromName('info.html');
    
        $manifestcotent = "";
        $key = $manifest["key"];
        
        $mp_Connection = [
            "extkey" => $key,
            "name"   => $manifest["name"],
            
            "public"              => $manifest["public"],
            "version"             => $manifest["version"],
            "privateserverclass"  => $manifest["privateserverclass"],
            "type"                => $manifest["type"],
            "minversion"          => $manifest["minversion"],
            "senddumptodeveloper" => $manifest["senddumptodeveloper"],
            "price"               => $manifest["price"],
            "componentprice"      => $manifest["componentprice"],
            
            "infofile" => $infofilecontent,
            "manifest" => $manifestcotent,
            
            "install" => true,
        ];
        
        $mp_id = $this->get("mp_Connection", [ "id" ], [ "extkey" => $key ]);
        if (isset($mp_id["id"]))
        {
            $this->update("mp_Connection", $mp_Connection, [ "id" => $mp_id["id"] ]);
        }
        else
        {
            $this->insert("mp_Connection", $mp_Connection);
        }
        
        foreach ($filepages as $newpage)
        {
            $text            = $zip->getFromName("pages/" . $newpage["path"]);
            $newpage["text"] = $text;
            if (!isset($newpage["friendlyname"]))
            {
                $newpage["friendlyname"] = $newpage["name"];
            }
            $this->Pages->SavePageToDB($newpage);
        }
        
        $deldir = (bool)$manifest["privateserverclass"] ? "client" : "private";
        $dir    = (bool)$manifest["privateserverclass"] ? "private" : "client";
        foreach ($fileclass as $newclass)
        {
            unlink($deldir . "/" . $newclass);
            
            $content = $zip->getFromName("client/" . $newclass);
            file_put_contents($dir . "/" . $newclass, $content);
        }
        
    }
    
    private function removeBomUtf8($s)
    {
        if (substr($s, 0, 3) == chr(hexdec('EF')) . chr(hexdec('BB')) . chr(hexdec('BF')))
        {
            return substr($s, 3);
        }
        else
        {
            return $s;
        }
    }
    
    private function installcomponent_told($zippath, $zip, $manifest)
    {
        $clientdir = trim($_SERVER["DOCUMENT_ROOT"] . "/client"); //если скрипт приватный надо копировать в папку private!!!
        
        if (isset($manifest["privateserverclass"]))
        {
            if ((int)$manifest["privateserverclass"] == 1)
            {
                $clientdir = trim($_SERVER["DOCUMENT_ROOT"] . "/private");
            }
        }
        
        for ($i = 0; $i < $zip->numFiles; $i++)
        {
            $filename = $zip->getNameIndex($i);
            $fileinfo = pathinfo($filename);
            if ($fileinfo["extension"] == "php")
            {
                copy("zip://" . $zippath . "#" . $filename, $clientdir . "/" . $fileinfo['basename']);
                
                $content = file_get_contents($clientdir . "/" . $fileinfo['basename']);
                $content = $this->removeBomUtf8($content);
                file_put_contents($clientdir . "/" . $fileinfo['basename'], $content);
            }
        }
        
        
        $result = [ "result" => true ];
        
        return $result;
    }
    
    
    
    
    /*
     *          UPLOAD COMPONENT
     */
    
    //Загрузить компоненту на сервер
    public function CreateComponentForServer($id)
    {
        $Component = $this->GetComponent($id);
        
        $outPath    = "tmp/component_" . $id . ".zip";
        $outZipPath = $_SERVER["DOCUMENT_ROOT"] . "/" . $outPath;
        
        unlink($outZipPath);
        
        $zip = new ZipArchive();
        $res = $zip->open($outZipPath, ZIPARCHIVE::CREATE);
        if ($res === true)
        {
            $zip->addFromString('manifest.json', $Component["manifest"]);
            $zip->addFromString('info.html', $Component["infofile"]);
        }
        
        $manifest = json_decode($Component["manifest"], true);
        if (count($manifest["filepages"]) > 0)
        {
            $zip->addEmptyDir("pages");
        }
        foreach ($manifest["filepages"] as $filepage)
        {
            $id   = $filepage["id"];
            $name = $filepage["name"];
            $path = $filepage["path"];
            
            $resPage = $this->Pages->GetPageInfo([ "typep" => 1, "id" => $id ]);
            if ($resPage["result"])
            {
                //Есть контент файла
                $patharray = explode("/", $path);
                if (count($patharray) > 1)
                {
                    unset($patharray[count($patharray) - 1]); //удалим имя файла
                    foreach ($patharray as $value)
                    {
                        $zip->addEmptyDir("pages/" . $value);
                    }
                }
                $zip->addFromString("pages/" . $path, $resPage["data"]["text"]);
            }
        }
        
        
        if (count($manifest["fileclass"]) > 0)
        {
            $dir = "client";
            if ($manifest["privateserverclass"])
            {
                $dir = "private";
            }
            $zip->addEmptyDir($dir);
            foreach ($manifest["fileclass"] as $fileclass)
            {
                $zip->addFile("client/" . $fileclass, $dir . "/" . $fileclass);
            }
        }
        $zip->close();
        
        return [ "result" => true, "zipPath" => $outPath, "extkey" => $Component["extkey"] ];
    }
    
    private function SetKeyComponent($id, $key)
    {
        $this->update("mp_Connection", [ "extkey" => $key ], [ "id" => $id ]);
        
        return [ "result" => true ];
    }
    
    public function UploadComponentToServer($id)
    {
        $this->SetUserInfo();
        
        $result = [ "result" => false ];
        
        $exparam = [
            "basicauth" => [
                "username" => $this->username,
                "password" => $this->password,
            ],
        ];
        
        $Component = $this->CreateComponentForServer($id);
        
        $extkey = $Component["extkey"];
        $url    = $this->serverurl . "marketplace/createcomponent/" . $extkey . "/base64/v4";
        $data   = base64_encode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/" . $Component["zipPath"]));
        $res    = $this->http_c_post($url, $data, $exparam);
        
        $contentstring = $res["content"];
        $content       = json_decode($contentstring, true);
        
        
        if (isset($content["id"]))
        {
            $this->SetKeyComponent($id, $content["id"]);
            $result = [ "result" => true ];
        }
        else
        {
            $result = $content;
        }
        
        return $result;
    }
    
    public function setsettingscomponent($inparams)
    {
        $result = [ "result" => true ];
        
        $dir   = $_SERVER["DOCUMENT_ROOT"];
        $fname = $inparams[1] . ".json";
        $exdir = $dir . "/private/settings";
        
        if (!file_exists($dir . "/private"))
        {
            mkdir($dir . "/private");
        }
        if (!file_exists($dir . "/private" . "/settings"))
        {
            mkdir($dir . "/private" . "/settings");
        }
        
        $json = $this->phpinput;
        file_put_contents($exdir . "/" . $fname, $json);
        
        return $result;
    }
}