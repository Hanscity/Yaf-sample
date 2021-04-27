<?php

namespace Dao;

class MarketModel
{
    private static $instance = null;
    public static function getInstance()
    {
        if( self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }



    public function listMarket()
    {
        $pdo = \Mysql\Pdo::getInstance();
        $stmt = $pdo->prepare("SELECT agri_product, brand, unit_price, num, trade_area, specification, created_at, updated_at 
                                        FROM markets WHERE show_status = 1 order by created_at Desc limit 10");
        $stmt->execute();
        $res = $stmt->fetch();
        return $res;
    }


    public function addMarket($data)
    {
        $curDatetime = date('Y-m-d H:i:s');
        $data['created_at'] = $curDatetime;
        $data['updated_at'] = $curDatetime;
        $data['show_status'] = 1;

        $pdo = \Mysql\Pdo::getInstance();
        $stmt = $pdo->prepare("INSERT INTO markets 
                                    (agri_product, brand, unit_price, num, trade_area, specification, created_at, updated_at, show_status) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([$data['agri_product'], $data['brand'], $data['unit_price'], $data['num'], $data['trade_area'],
            $data['specification'], $data['created_at'], $data['updated_at'], $data['show_status']]);
        $row_count = $stmt->rowCount();
        return $row_count;
    }



}