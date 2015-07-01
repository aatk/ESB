<?php
define("PATH", $_SERVER['DOCUMENT_ROOT'].'/filebank/FILES/');

class Filebank extends ex_class {

    public function __construct($connectionInfo) {
        parent::__construct($connectionInfo);
    }   
    
    public function Start($metod, $param) {
        $result = "";
        $input = $param[q];
        $query = explode('/', $input);
        
        if ($metod == "GET") {
            if ($query[1] == "getlist") {
                //Получить список Файлов По родителю
                $param['parent'] = $query[2];
                $result = $this->getlist($metod, $param);
            } elseif ($query[1] == "file") {
                //Получить Файл
                $param['md5'] = $query[2];
                $result = $this->getfile($metod, $param);
            }            
        } elseif ($metod == "POST") {
            if ($query[1] == 'setimage') {
                $result = $this->setimage($metod, $param);
            }
        } elseif ($metod == "PATCH") {
            if ($query[1] == "markdelfile") {
                $param['md5'] = $query[2];
                $result = $this->markdelfile($metod, $param);
            }
        } elseif ($metod == "DELETE") {
            if ($query[1] == 'file') {
                $param['md5'] = $query[2];
                $result = $this->delfile($metod, $param);
            }
        }
        
        return $result;
    }
    
    


    public function getlist($metod, $param) {
        $info['parent'] = $param['parent'];
        $list = $this->_getlist($info);
        return $list;
    }
    
    public function getfile($metod, $param) {
        $info['md5'] = $param['md5'];
        $list = $this->_getfile($info);   
        return $list;
    }    

    public function setimage($metod, $param) {
        $result = '';

        if ($metod == "POST") {
            $decode = $param['decode'];
            $parent = $param['parent'];
            $uploadFile = $_FILES['file'];
            $tmp_name = $uploadFile['tmp_name'];
            
            $name = $uploadFile['name'];
            $name = mb_convert_encoding($name, 'utf-8', 'cp-1251');

            $data_filename = SID;
            $path = PATH;
            $filename = $path.$data_filename;

            if ( !is_uploaded_file($tmp_name) ) {
                $result["error"] = 'Ошибка при загрузке файла '.$name;
            } else {
                //Считываем файл в строку
                $data = file_get_contents($tmp_name);
                if ($decode == "1") { 
                    $data = base64_decode($data);
                }
                
                $md5 = md5($data);
                $filename = $path.$md5;
                //Теперь нормальный файл можно сохранить на диске
                if ( !empty($data) && ($fp = @fopen($filename, 'wb')) ) {
                    @fwrite($fp, $data);
                    @fclose($fp);
                    //rename($filename, $path.$md5);
                } else {
                    $result["error"] = 'Ошибка при записи файла '.$data_filename;
                }
                
                $info['md5']  = $md5;
                $info['name'] = $name;
                $info['parent']  = $parent;
                $info['size'] = filesize($filename);
                $this->saveinfo($info);

                $result['name'] = $info['name'];
                $result['size'] = $info['size'];
                $result['md5']  = $info['md5'];
                $result["message"] = 'Файл - ' . $result['name'] . ' успешно загружен. ';

            }
            
        } elseif ($metod == "PUT") {
            // Получаем содержимое входящего потока
            $data_filename = SID;
            $path = PATH;
            $filename = $path.$data_filename;
            $content = file_get_contents('php://input');
            // Записываем содержимое потока в файл
            $file = fopen($filename, 'w');
            fwrite($file, $content);
            fclose($file);

            $result["message"] = 'Файл - ' . $filename . ' успешно загружен. ';
        }
        
        return $result;
    }
    
    public function delfile($metod, $param) {

        $result = array();
        
        //Удалим из БД
        $info['md5']    = $param['md5'];
        $info['parent'] = $param['parent'];
        $infofiles = $this->_getlist($info);
        foreach ($infofiles as $value) {
            //Удалим любое упоминание о файле
            $res = $this->_delfile($value);
        }
        unset($info);
        
        $info['md5']    = $param['md5'];
        $list = $this->_getlist($info);
        if (count($list) == 0) {
            //Если больше не осталось объектов использующие этот файл, то удаляем его
            $filename = PATH.$info['md5'];
            unlink($filename);
        }
        
        $result['result'] = true;
        return $result;
    }

    public function markdelfile($metod, $param) {

        $result = array();
            
        //print_r($param);
        $info['md5']    = $param['md5'];
        $info['parent'] = $param['parent'];
        $infofiles = $this->_getlist($info);
        
        foreach ($infofiles as $value) {
            $value['markdel'] = 1;
            $res = $this->updatefile($value);
        }
        unset($info);
        
        $result['result'] = true;
        return $result;
    }




    
    private function saveinfo ($fileinfo) {
        
        $result = false;
        if (count($this->get_where("filebank", $fileinfo)) == 0) {
            $this->insert("filebank", $fileinfo);
            $result = true;
        }
        
        return $result;
    }

    private function _getlist ($info) {
        $result = $this->get_where("filebank", $info);
        return $result;
    }
    
    private function _getfile ($info) {
        
        $result = $this->get_where("filebank", $info);
        //return $result;
        if (count($result) > 0) {
            $path = PATH;
            $fsize = $result[0]['size'];
            $fname = $result[0]['name'];
            $fullfname = $path.$result[0]['md5'];
            
            header("Content-Length: $fsize");
            header("Content-Disposition: filename=\"$fname\"");
            header("Content-Type: application/file");
            echo file_get_contents($fullfname);
            exit;
        }
    }
    
    private function _delfile ($info) {
        $result = $this->delete("filebank", $info['id']);
        return $result;
    }
    
    private function updatefile ($info) {
        $result = $this->update("filebank", $info['id'], $info);
        return $result;
    }    
    
}

?>