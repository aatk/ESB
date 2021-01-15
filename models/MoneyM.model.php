<?php

class MoneyM extends extend_model implements CreateDB, InstallModule
{
    private $TransactionData;
    
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
            "balance"     => [ 'type' => 'double', 'null' => 'NOT NULL' ],
            "id_currency" => [ 'type' => 'int(11)', 'null' => 'NOT NULL' ],
        ];
        
        $info["Money_users"] = [
            "id"        => [ 'type' => 'int(11)', 'null' => 'NOT NULL', 'inc' => true ],
            "name"      => [ 'type' => 'varchar(150)', 'null' => 'NOT NULL' ],
            "id_wallet" => [ 'type' => 'int(11)', 'null' => 'NOT NULL' ],
        ];
        
        $info["Money_wallet_history"] = [
            "stamp"    => [ 'type' => 'timestamp', 'null' => 'NOT NULL' ],
            "datetime" => [ 'type' => 'date', 'null' => 'NOT NULL' ],
            
            "id_wallet" => [ 'type' => 'int(11)', 'null' => 'NOT NULL' ],
            "amount"    => [ 'type' => 'double', 'null' => 'NOT NULL' ],
            
            "id_currency"        => [ 'type' => 'int(11)', 'null' => 'NOT NULL' ],
            "amount_in_currency" => [ 'type' => 'float', 'null' => 'NOT NULL' ],
            
            "reason" => [ 'type' => 'varchar(10)', 'null' => 'NOT NULL' ],
        ];
        
        $connectionInfo = $_SESSION["i4b"]["connectionInfo"];
        $this->create($connectionInfo['database_type'], $info);
    }
    
    public function InstallModule()
    {

        $sql = "ALTER TABLE `Money_currency`
                  ADD PRIMARY KEY (`id`),
                  ADD KEY `name` (`name`);
                
                ALTER TABLE `Money_currency_history`
                  ADD KEY `date` (`date`),
                  ADD KEY `id_currency` (`id_currency`),
                  ADD KEY `date_2` (`date`,`id_currency`);
    
                ALTER TABLE `Money_wallet_history`
                  ADD UNIQUE KEY `stamp` (`stamp`);";
        $this->query($sql);
    
    
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
        $id_array = $this->get("Money_currency", [ "id" ], [ "name" => $currency_name ]);
        
        return $id_array["id"];
    }
    
    public function get_currency_history($currency_id, $time)
    {
        $date = date('Y-m-d', $time);
        $max_date = $this->max("Money_currency_history", "date", [
            "date[<=]" => $date
        ]);
    
        $data = $this->get("Money_currency_history" , ["price"], ["id_currency"=> $currency_id, "date" => $max_date]);
        return $data["price"] ?? 1;
    }
    
    
    
    public function change_balance($id_wallet, $amount_in_currency, $currency_id, $reason)
    {
        $datetime = time();
        
        $amount = $amount_in_currency;
        $data_wallet = $this->get("Money_wallet", ["id_currency"], ["id" => $id_wallet]);
        if ($data_wallet["id_currency"] !== $currency_id)
        {
            $price = $this->get_currency_history($currency_id, $datetime);
            if ($data_wallet["id_currency"] == 643) {
                $amount = $amount_in_currency*$price;
            } else {
                $amount = $amount_in_currency/$price;
            }
        }
        
        $this->TransactionData = [
            "datetime"           => $datetime,
            "id_wallet"          => $id_wallet,
            "amount"             => $amount,
            "id_currency"        => $currency_id,
            "amount_in_currency" => $amount_in_currency,
            "reason"             => $reason,
        ];
        
        //Начало транзакции
        $this->action(function ($database)
        {
            $data = $this->TransactionData;
            $database->insert("Money_wallet_history", $data);
            
            $data_wallet = [
                "balance[+]" => $data["amount"],
            ];
            $database->update("Money_wallet", $data_wallet, [ "id" => $data["id_wallet"] ]);
        });
        //Конец транзакции
        
        $result = $this->error();
        
        return $result;
    }
    
    public function get_balance($id_wallet)
    {
        $res = $this->get("Money_wallet(wallet)",
            [
                "[>]Money_currency(currency)" => [ "wallet.id_currency" => "id" ],
            ], [
                "wallet.id",
                "wallet.balance",
                "wallet.id_currency",
                "currency.name(name_currency)",
            ],
            [ "wallet.id" => $id_wallet ]
        );
        
        return $res;
    }
    
    public function get_last_refunds($id_wallet)
    {
        $sql = "";
        $res = $this->query($sql);
        
        return $res;
    }
}