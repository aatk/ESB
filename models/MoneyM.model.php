<?php

class MoneyM extends extend_model implements CreateDB, InstallModule
{
    public function CreateDB()
    {
        //USD, RUB
        $info["Money_currency"] = [
            "id"   => [ 'type' => 'int(11)', 'null' => 'NOT NULL' ],
            "name" => [ 'type' => 'varchar(150)', 'null' => 'NOT NULL' ],
        ];
        
        $info["Money_currency_history"] = [
            "date"        => [ 'type' => 'date', 'null' => 'NOT NULL' ],
            "id_currency" => [ 'type' => 'int(11)', 'null' => 'NOT NULL' ],
            "price"       => [ 'type' => 'int(11)', 'null' => 'NOT NULL' ],
        ];
        
        $info["Money_wallet"] = [
            "id"          => [ 'type' => 'int(11)', 'null' => 'NOT NULL', 'inc' => true ],
            "balance"     => [ 'type' => 'float', 'null' => 'NOT NULL' ],
            "id_currency" => [ 'type' => 'int(11)', 'null' => 'NOT NULL' ],
        ];
        
        $info["Money_users"] = [
            "id"        => [ 'type' => 'int(11)', 'null' => 'NOT NULL', 'inc' => true ],
            "name"      => [ 'type' => 'varchar(150)', 'null' => 'NOT NULL' ],
            "id_wallet" => [ 'type' => 'int(11)', 'null' => 'NOT NULL' ],
        ];
        
        $info["Money_wallet_history"] = [
            "stamp"    => [ 'type' => 'timestamp', 'null' => 'NOT NULL' ],
            "datetime" => [ 'type' => 'datetime', 'null' => 'NOT NULL' ],
            
            "id_wallet" => [ 'type' => 'int(11)', 'null' => 'NOT NULL' ],
            "amount"    => [ 'type' => 'float', 'null' => 'NOT NULL' ],
            
            "id_currency"        => [ 'type' => 'int(11)', 'null' => 'NOT NULL' ],
            "amount_in_currency" => [ 'type' => 'float', 'null' => 'NOT NULL' ],
            
            "reason" => [ 'type' => 'varchar(10)', 'null' => 'NOT NULL' ],
        ];
        
        $connectionInfo = $_SESSION["i4b"]["connectionInfo"];
        $this->create($connectionInfo['database_type'], $info);
    }
    
    public function InstallModule()
    {
        //Заполняем валюты
        if (!$this->has("Money_currency", [ "name" => "USD" ]))
        {
            $this->insert("Money_currency", [ "id" => 840, "name" => "USD" ]);
        }
        if (!$this->has("Money_currency", [ "name" => "RUB" ]))
        {
            $this->insert("Money_currency", [ "id" => 643, "name" => "RUB" ]);
        }
    }
    
    public function __construct($connectionInfo = null, $debug = false)
    {
        parent::__construct();
    }

    
    public function get_currency_id($currency_name)
    {
        $id_array = $this->get("Money_currency", ["id"], ["name" => $currency_name]);
        return $id_array["id"];
    }
    
    public function change_balance($id_wallet, $amount, $currency, $reason)
    {
    
    }
    
    public function get_balance($id_wallet)
    {
        $res = $this->get("Money_wallet",
            [
                "[>]Money_currency(currency)" => [ "id_currency" => "id" ],
            ], [
                "id",
                "balance",
                "currency.name",
            ],
            [ "id" => $id_wallet ]
        );
        
        return $res;
    }
}