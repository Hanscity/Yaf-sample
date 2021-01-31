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
        define('YAF_ERR_LOGIC_EXISTS', 530); ## Yaf 框架的业务中断，已存在之类的业务，例如该注册的号码已存在~
        define('YAF_ERR_LOGIC_REQUIRE', 531); ## Yaf 框架 业务缺少参数

        define('YAF_ERR_LOGIC_DB', 540); ## Yaf 框架 MySQL 异常错误等


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
