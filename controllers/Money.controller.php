<?php

class Money extends extend_controller
{
    const MONEY_STOCK  = "stock";
    const MONEY_REFUND = "refund";
    
    const MONEY_TYPE_DEBIT  = "debit";
    const MONEY_TYPE_CREDIT = "credit";

    private $Model;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->Model = new MoneyM();
    }
    
    public function change_balance($params)
    {
        $result = [];
        
        $id_wallet = $params[1] ?? 0;
        $type      = $params[2] ?? self::MONEY_TYPE_DEBIT;
        $amount    = $params[3] ?? 0;
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
        
        if ($id_wallet != 0) {
            $result = $this->Model->change_balance($id_wallet, $amount, $currency_id, $reason);
        }
        
        return new MoneyV($result);
    }
    
    public function get_balance($params)
    {
        $result = [];
        
        $id_wallet = $this->DTV($params, [ "1" ], 0);
        if ($id_wallet <> 0)
        {
            $result = $this->Model->get_balance($id_wallet);
        }
        
        return new MoneyV($result);
    }
    
    public function get_last_refunds($params)
    {
        $result = [];
        $id_wallet = $params[1] ?? 0;
    
        if ($id_wallet != 0) {
            $result = $this->Model->get_last_refunds($id_wallet);
        }
    
        return new MoneyV($result);
    }
}
