<?php

class Money extends extend_controller
{
    const MONEY_STOCK  = "stock";
    const MONEY_REFUND = "refund";
    const REASON = [
        self::MONEY_STOCK,
        self::MONEY_REFUND
    ];

    const MONEY_TYPE_DEBIT  = "debit";
    const MONEY_TYPE_CREDIT = "credit";
    const TYPES = [
        self::MONEY_TYPE_DEBIT,
        self::MONEY_TYPE_CREDIT
        ];
    
    private $Model;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->Model = new MoneyM();
    }
    
    public function Init($params)
    {
        $result           = [];
        $result["result"] = false;
        $result["error"]  = "Error function call";
    
        $func = strtolower($params[0]);
    
        if ($this->method == "GET")
        {
            if ($func == "change_balance")
            {
                $result = $this->change_balance($params);
            }
            elseif ($func == "get_balance")
            {
                $result = $this->get_balance($params);
            }
            elseif ($func == "get_last_refunds")
            {
                $result = $this->get_last_refunds($params);
            }
        }
    
        return $result;
    }
    
    public function change_balance($params)
    {
        
        $result = ["result" => false];
        
        $id_wallet = $params[1] ?? 0;
        $type      = $params[2] ?? self::MONEY_TYPE_DEBIT;
        $amount    = (double)$params[3] ?? 0;
        $currency  = $params[4] ?? "";
        $reason    = $params[5] ?? self::MONEY_STOCK;
        
        //Получаем все вводные
        if ($type == self::MONEY_TYPE_CREDIT)
        {
            $amount = -1 * $amount;
        }
    
        $currency_id = 0;
        if ($currency !== "")
        {
            $currency_id = $this->Model->get_currency_id($currency);
        }
        
        //All ok?
        if (
            ($id_wallet != 0) &&
            (in_array($type, self::TYPES)) &&
            ($amount != 0) &&
            ($currency_id != 0) &&
            (in_array($reason, self::REASON))
        )  {
            //Всё заполнено
            $result = $this->Model->change_balance($id_wallet, $amount, $currency_id, $reason);
            if (is_array($result) && ($result[0] === "00000"))
            {
                //Ошибок нет
                $result = ["result" => true];
            }
        }
        
        return new MoneyV($result);
    }
    
    public function get_balance($params)
    {
        $result = ["result" => false];
        
        $id_wallet = $this->DTV($params, [ "1" ], 0);
        if ($id_wallet <> 0)
        {
            $result = $this->Model->get_balance($id_wallet);
        }
        
        return new MoneyV($result);
    }
    
    public function get_last_refunds($params)
    {
        $result = $this->Model->get_last_refunds(self::MONEY_REFUND);
    
        return new MoneyV($result);
    }
}
