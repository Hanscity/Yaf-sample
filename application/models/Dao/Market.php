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
        $stmt = $pdo->prepare("SELECT *  
                                        FROM markets WHERE show_status = 1 order by created_at Desc limit 10");
        $stmt->execute();
        $res = $stmt->fetchAll(); ## 默认是二位数组 [['argi_product'=>1,'brand'=>'wanxian'...],['argi_product'=>1,'brand'=>'huanghuazhan'...]]
                                  ## 很显然，这种数据结构转化为 json 的时候将会变成 [{},{}], 非常合适
        return $res;
    }


    public function addMarket($data)
    {
        $curDatetime = date('Y-m-d H:i:s');
        $data['created_at'] = $curDatetime;
        $data['updated_at'] = $curDatetime;
        $data['show_status'] = 1;

        $pdo = \Mysql\Pdo::getInstance();
        // 在写入数据库的时候，数据库中 buy_cell_type 需要是 int， 可是我传递的值是字符 '2',也可以成功。说明数据库有自动转化的功能
        $stmt = $pdo->prepare("INSERT INTO markets 
                                    (agri_product, buy_cell_type, brand, unit_price, num, trade_area, specification, created_at, updated_at, show_status) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([$data['agri_product'], $data['buy_cell_type'], $data['brand'], $data['unit_price'], $data['num'], $data['trade_area'],
            $data['specification'], $data['created_at'], $data['updated_at'], $data['show_status']]);
        $row_count = $stmt->rowCount();
        return $row_count;
    }



}