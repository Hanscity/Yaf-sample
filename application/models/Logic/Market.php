<?php

namespace Logic;

use \Dao\MarketModel as MarketDao;
use Logic\CommonModel as CommonLogic;
use Logic\UserModel as UserLogic;

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
        0 => '未选择',
        1 => '稻谷',
        2 => '大米',
        3 => '稻米副产品',
        101 => '小麦',
        102 => '小麦副产品',
        201 => '玉米',
        202 => '玉米副产品',
        301 => '大豆',
        302 => '大豆副产品',
        401 => '杂粮',
        402 => '杂粮副产品'
    ];

    public static $buyCellTypeArr = [
        0 => '未选择',
        1 => '买',
        2 => '卖',
    ];

    public static $brandLengthUtf8 = 64;
    public static $tradeAreaLengthUtf8 = 108;
    public static $specificationLengthUtf8 = 256;

    public static $unitPriceTurn = 1000; // 单价的换算，前端传过来的单位是元，转化为厘存储
    public static $numTurn = 1000000;  // 数量的换算，前端传过来的单位是吨，转化为克存储


    public function addMarket()
    {
        $params = \Utils\Data::getHttpPostJson();
        $userinfo = UserLogic::getInstance()->checkUserInfoByTokenThenReturn($params[CommonLogic::getInstance()->getStrCheck()]);
        $params['user_id'] = $userinfo['id'];

        \Utils\Log::recordLog($params);

        if (! (isset($params['agri_product']) && isset($params['brand']) && isset($params['trade_area'])
            && isset($params['specification']) && isset($params['unit_price']) && isset($params['num']) && isset($params['buy_cell_type']) )) {
            return \Utils\Data::jsonReturn(YAF_LOGIC_REQUIRE,'缺少参数','');
        }

        if (! array_key_exists($params['buy_cell_type'], self::$buyCellTypeArr)) {
            return \Utils\Data::jsonReturn(YAF_LOGIC_DATA_ERROR,'操作类型参数错误','');
        }

        if (! array_key_exists($params['buy_cell_type'], self::$agriProducts)) {
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
            $content = '[INFO_DB]'.__FILE__.','.__LINE__.'=>'.$e->getMessage().PHP_EOL;
            \Utils\Log::recordLog($content);
            return \Utils\Data::jsonReturn(YAF_LOGIC_DB_ERROR,'数据库开小差了~','');
        }

    }


    public static $strMaxLength = 6;

    public function listMarket()
    {
        $params = \Utils\Data::getHttpPostJson();
        \Utils\Log::recordLog($params);

        try {
            $res = MarketDao::getInstance()->listMarket();
            // 转化数据
            foreach ($res as $key=>$value) {
                $res[$key]['agri_product'] = self::$agriProducts[$value['agri_product']];
                $res[$key]['unit_price'] = $value['unit_price']/self::$unitPriceTurn;
                $res[$key]['num'] = $value['num']/self::$numTurn;
                $res[$key]['created_at'] = CommonLogic::getInstance()->turnTimeForFront($value['created_at']);
                $res[$key]['trade_area'] = CommonLogic::getInstance()->turnStrToElipsis($value['trade_area'], self::$strMaxLength);
                $res[$key]['specification'] = CommonLogic::getInstance()->turnStrToElipsis($value['specification'], self::$strMaxLength);
                $res[$key]['buy_cell_type'] = self::$buyCellTypeArr[$value['buy_cell_type']];
                $res[$key]['user_info'] = UserLogic::getInstance()->getUserInfoByUserIdentification($value['user_id']);
            }

            return \Utils\Data::jsonReturn(YAF_HTTP_OK, 'success', $res);

        } catch (\PDOException $e) {
            $content = '[INFO_DB]'.__FILE__.','.__LINE__.'=>'.$e->getMessage().PHP_EOL;
            \Utils\Log::recordLog($content);
            return \Utils\Data::jsonReturn(YAF_LOGIC_DB_ERROR,'数据库开小差了~','');
        }

    }

}