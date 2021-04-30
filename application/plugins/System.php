<?php
/**
 * @name SystemPlugin, 接着用 SamplePlugin 在正式开发中，似乎有点不太好。取用这个名字，顾名思义是进入系统之意，非常重要
 * @desc Yaf定义了如下的6个Hook,插件之间的执行顺序是先进先Call
 * @see http://www.php.net/manual/en/class.yaf-plugin-abstract.php
 * @author danche
 */
class SystemPlugin extends Yaf\Plugin_Abstract
{
    protected static $routeWhiteList = [
        'Index/index', ## 首页，为了备案而准备的
        'User/register',
        'User/login',
    ];

    /**
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     * @return bool|void
     * @comment 在路由之前触发	这个是7个事件中, 最早的一个. 但是一些全局自定的工作, 还是应该放在Bootstrap中去完成
     */
	public function routerStartup(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {

	}

    /**
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     * @return bool|void
     * @comment 路由结束之后触发	此时路由一定正确完成, 否则这个事件不会触发
     */
	public function routerShutdown(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {


        if ( !in_array($request->getControllerName().'/'.$request->getActionName(), self::$routeWhiteList) ) {

            $params = \Utils\Data::getHttpPostJson();
            $strToken = \Logic\CommonModel::getInstance()->getStrCheck();

            if ( !isset($params[$strToken]) ) {
                // 这一种情况其实是参数缺少，但是这种敏感的问题，不能报错的太明显
                $res = \Utils\Data::jsonReturn(YAF_HTTP_UNAUTHORIZED, '用户没有访问权限,需要进行身份认证');
                \Utils\Data::responceReturn($res);
            }

            if ( !( \Logic\UserModel::getInstance()->checkUserInfoByTokenThenReturn($params[$strToken]) )) {
                $res = \Utils\Data::jsonReturn(YAF_HTTP_UNAUTHORIZED, '用户没有访问权限,需要进行身份认证');
                \Utils\Data::responceReturn($res);
            }

        }

	}

	public function dispatchLoopStartup(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) {
	}

	public function preDispatch(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) {
	}

	public function postDispatch(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) {
	}

	public function dispatchLoopShutdown(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) {
	}
}
