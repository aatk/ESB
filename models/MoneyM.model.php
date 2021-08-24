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
            "price"       => [ 'type' => 'double', 'null' => 'NOT NULL' ],
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
            "stamp"              => [ 'type' => 'timestamp', 'null' => 'NOT NULL' ],
            "date"               => [ 'type' => 'date', 'null' => 'NOT NULL' ],
            "id_wallet"          => [ 'type' => 'int(11)', 'null' => 'NOT NULL' ],
            "amount"             => [ 'type' => 'double', 'null' => 'NOT NULL' ],
            "id_currency"        => [ 'type' => 'int(11)', 'null' => 'NOT NULL' ],
            "amount_in_currency" => [ 'type' => 'double', 'null' => 'NOT NULL' ],
            "amount_in_RUB"      => [ 'type' => 'double', 'null' => 'NOT NULL' ],
            "reason"             => [ 'type' => 'varchar(10)', 'null' => 'NOT NULL' ],
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
                  ADD UNIQUE KEY `stamp` (`stamp`);
                  ADD KEY `reason` (`reason`),
                  ADD KEY `datetime` (`date`,`reason`);";
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
    
        //Заполняем таблицу Money_currency_history DEMO данными
        if (!$this->has("Money_currency_history", [ "date" => "2021-01-01", "id_currency" => "840"] ))
        {
            $this->insert("Money_currency_history", [ "date" => "2021-01-01", "id_currency" => "840",  "price" => 70]);
        }
    
        //Заполняем таблицу Money_wallet DEMO данными
        if (!$this->has("Money_wallet", [ "id" => 1 ]))
        {
            $this->insert("Money_wallet", [ "id" => 1, "balance" => 0, "id_currency" => 643 ]);
        }
        if (!$this->has("Money_wallet", [ "id" => 2 ]))
        {
            $this->insert("Money_wallet", [ "id" => 2, "balance" => 0, "id_currency" => 840 ]);
        }
    
    
        //Заполняем таблицу Money_users DEMO данными
        if (!$this->has("Money_users", [ "id" => 1 ]))
        {
            $this->insert("Money_users", [ "id" => 1, "name" => "test1", "id_wallet" => 1 ]);
        }
        if (!$this->has("Money_users", [ "id" => 2 ]))
        {
            $this->insert("Money_users", [ "id" => 2, "name" => "test2", "id_wallet" => 2 ]);
        }
    
    }
    
    public function __construct($connectionInfo = null, $debug = false)
    {
        parent::__construct();
    }
    
    public function get_currency_id($currency_name)
    {
        $id_array = $this->get("Money_currency", [ "id" ], [ "name" => $currency_name ]);
        
        return $id_array["id"] ?? 0;
    }
    
    public function get_currency_history($currency_id, $time)
    {
        $date     = date('Y-m-d', $time);
        $max_date = $this->max("Money_currency_history", "date", [
            "date[<=]" => $date,
        ]);
        
        $data = $this->get("Money_currency_history", [ "price" ], [
            "id_currency" => $currency_id,
            "date"        => $max_date,
        ]);
        
        return (float)$data["price"] ?? 1;
    }
    
    public function change_balance($id_wallet, $amount_in_currency, $currency_id, $reason)
    {
        $datetime = time();
        
        $amount_in_RUB      = $amount = $amount_in_currency;
        $data_wallet        = $this->get("Money_wallet", [ "id_currency" ], [ "id" => $id_wallet ]);
        $wallet_currency_id = $data_wallet["id_currency"];
        
        if ($wallet_currency_id != $currency_id)
        {
            if ($wallet_currency_id == 643)
            {
                $price         = $this->get_currency_history($currency_id, $datetime);
                $amount_in_RUB = $amount = $amount_in_currency * $price;
            }
            else
            {
                $price  = $this->get_currency_history($data_wallet["id_currency"], $datetime);
                $amount = $amount_in_currency / $price;
            }
        }
        
        $this->TransactionData = [
            "date"               => date("Y-m-d", $datetime),
            "id_wallet"          => $id_wallet,
            "amount_in_currency" => $amount_in_currency,
            "id_currency"        => $currency_id,
            "reason"             => $reason,

            "amount"             => $amount,
            "amount_in_RUB"      => $amount_in_RUB,
        ];
        
        //Начало транзакции
        $this->action(function ($database)
        {
            $resultTransaction = false;
            
            $data = $this->TransactionData;
            $data_wallet = [
                "balance[+]" => $data["amount"],
            ];
            
            $where = [ "id" => $data["id_wallet"] ];
            if ($data["amount"] < 0) {
                $where["balance[>=]"] = abs($data["amount"]);
            }
            $PDOdata = $database->update("Money_wallet", $data_wallet, $where);
            $rowCount = $PDOdata->rowCount();
            
            if ($rowCount > 0) {
                $database->insert("Money_wallet_history", $data);
                $this->TransactionData = $result = $this->error();
                if (is_array($result) && ($result[0] === "00000"))
                {
                    $resultTransaction = true;
                }
            }
            else
            {
                $this->TransactionData = ["-9999", "not enough balance"];
            }
            
            return $resultTransaction;
        });
        //Конец транзакции
        
        return $this->TransactionData;
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
    
    public function get_last_refunds($reason)
    {
        $date = strtotime('-7 days');
        $datesql = date('Y-m-d', $date);

        $sql = "SELECT SUM(`amount_in_RUB`) FROM `Money_wallet_history` WHERE `reason` = 'refund' AND `date` >= $datesql";
        $res = $this->query($sql)->fetchAll();
        $result = $res[0][0] ?? 0;
        
        return ["amount" => $result];
    }
}