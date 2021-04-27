<?php


use \Logic\MarketModel as MarketLogic;

class MarketController extends Yaf\Controller_Abstract
{

    /**
     * @coment: 市场信息列表
     */
    public function listAction()
    {
        $data = MarketLogic::getInstance()->listMarket();
        \Utils\Data::responceReturn($data);
    }


    /**
     * @coment: 新增市场信息列表
     */
    public function addAction()
    {
        $data = MarketLogic::getInstance()->addMarket();
        \Utils\Data::responceReturn($data);

    }


    public function testAction()
    {
        \Utils\Data::responceReturn(1111);
    }
}