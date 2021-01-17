<?php

interface Db
{
    public function __construct($connectionInfo, $debug = false);
    
    public function create($database_type, $info);
    
    /*SQL*/
    
    public function select($table, $join, $columns = null, $where = null);
    
    public function insert($table, $datas);
    
    public function update($table, $data, $where = null);
    
    public function delete($table, $where);
    
    public function replace($table, $columns, $search = null, $replace = null, $where = null);
    
    public function get($table, $join = null, $columns = null, $where = null);
    
    public function has($table, $join, $where = null);
    
    public function count($table, $join = null, $column = null, $where = null);
    
    public function max($table, $join, $column = null, $where = null);
    
    public function min($table, $join, $column = null, $where = null);
    
    public function avg($table, $join, $column = null, $where = null);
    
    public function sum($table, $join, $column = null, $where = null);
    
    public function action($actions);
    
    public function error();
    
    public function last_query();
    
    public function log();
    
    public function debug();
    
    public function query($sql);
}