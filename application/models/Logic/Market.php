<?php

namespace Logic;

use \Dao\MarketModel as MarketDao;

class MarketModel
{

    private static $instance = null;
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public static $agriProducts = [
        1 => '稻米',
        2 => '小麦',
        3 => '玉米'
    ];

    public static $brandLengthUtf8 = 64;
    public static $tradeAreaLengthUtf8 = 108;
    public static $specificationLengthUtf8 = 256;

    public static $unitPriceTurn = 1000; // 单价的换算，前端传过来的单位是元，转化为厘存储
    public static $numTurn = 1000000;  // 数量的换算，前端传过来的单位是吨，转化为克存储


    public function addMarket()
    {
        $params = \Utils\Data::getHttpPostJson();
        \Utils\Log::recordLog(json_encode($params));
        if (! (isset($params['agri_product']) && $params['brand'] && $params['trade_area']
            && $params['specification'] && $params['unit_price'] && $params['num'] )) {
            return \Utils\Data::jsonReturn(YAF_LOGIC_REQUIRE,'缺少参数','');
        }

        if (! array_key_exists($params['agri_product'], self::$agriProducts)) {
            return \Utils\Data::jsonReturn(YAF_LOGIC_DATA_ERROR,'农贸品类参数错误','');
        }

        if ( mb_strlen($params['brand'], 'utf8') >= self::$brandLengthUtf8 ) {
            return \Utils\Data::jsonReturn(YAF_LOGIC_DATA_ERROR,'品牌字符超出','');
        }

        if ( mb_strlen($params['trade_area'], 'utf8') >= self::$brandLengthUtf8 ) {
            return \Utils\Data::jsonReturn(YAF_LOGIC_DATA_ERROR,'交货地字符超出','');
        }

        if ( mb_strlen($params['specification'], 'utf8') >= self::$brandLengthUtf8 ) {
            return \Utils\Data::jsonReturn(YAF_LOGIC_DATA_ERROR,'规格字符超出','');
        }

        if ( !is_numeric($params['unit_price']) ) {
            return \Utils\Data::jsonReturn(YAF_LOGIC_DATA_ERROR,'单价需要是数字','');
        }

        if ( !is_numeric($params['num']) ) {
            return \Utils\Data::jsonReturn(YAF_LOGIC_DATA_ERROR,'数量需要是数字','');
        }

        $params['unit_price'] = $params['unit_price'] * self::$unitPriceTurn;
        $params['num'] = $params['num'] * self::$numTurn;

        try {
            MarketDao::getInstance()->addMarket($params);
            return \Utils\Data::jsonReturn();

        } catch (\PDOException $e) {
            $content = '[INFO_DB]'.__FILE__.','.__LINE__.'=>'.json_encode($e->getMessage()).PHP_EOL;
            \Utils\Log::recordLog($content);
            return \Utils\Data::jsonReturn(YAF_LOGIC_DB_ERROR,'数据库开小差了~','');
        }

    }



    public function listMarket()
    {
        try {
            $res = MarketDao::getInstance()->listMarket();
            return \Utils\Data::jsonReturn(YAF_HTTP_OK, 'success', $res);

        } catch (\PDOException $e) {
            $content = '[INFO_DB]'.__FILE__.','.__LINE__.'=>'.json_encode($e->getMessage()).PHP_EOL;
            \Utils\Log::recordLog($content);
            return \Utils\Data::jsonReturn(YAF_LOGIC_DB_ERROR,'数据库开小差了~','');
        }

    }

}