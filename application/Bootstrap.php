<?php
/**
 * @name Bootstrap
 * @author danche
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf\Bootstrap_Abstract {

    public function _initConfig() {
		//把配置保存起来
		$arrConfig = Yaf\Application::app()->getConfig();
		Yaf\Registry::set('config', $arrConfig);

		// 更愿意选择 Php 的原生报错方式
		$displayErrors = $arrConfig->get('ini')->displayErrors ?? 0;
		$errorReporting = $arrConfig->get('ini')->errorReporting ?? E_ALL;

        ini_set('display_errors',$displayErrors);
        ini_set('error_reporting',$errorReporting);

        //定义业务 code 常量
        define('YAF_HTTP_OK', 200); ## http 请求成功
        define('YAF_LOGIC_EXISTS', 530); ## 已存在，例如该注册的号码已存在
        define('YAF_LOGIC_NOT_EXISTS', 531); ## 不存在，例如用户的手机号码不存在
        define('YAF_LOGIC_REQUIRE', 532); ## 缺少参数，例如用户的手机号码没有传递
        define('YAF_LOGIC_DATA_ERROR', 532); ## 数据不正确，例如用户的密码不正确
        define('YAF_LOGIC_DB_ERROR', 540); ## MySQL 异常错误

    }

	public function _initPlugin(Yaf\Dispatcher $dispatcher) {
		//注册一个插件
		$objSamplePlugin = new SamplePlugin();
		$dispatcher->registerPlugin($objSamplePlugin);
	}

	public function _initRoute(Yaf\Dispatcher $dispatcher) {
		//在这里注册自己的路由协议，默认使用Yaf_Route_Static
	}
	
	public function _initView(Yaf\Dispatcher $dispatcher) {
		//在这里注册自己的view控制器，例如smarty,firekylin

        $dispatcher->getInstance()->disableView();
	}
}
