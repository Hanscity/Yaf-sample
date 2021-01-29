<?php
/**
 * @name IndexController
 * @author danche
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends Yaf\Controller_Abstract {
    /**
     * 默认初始化方法，如果不需要，可以删除掉这个方法
     * 如果这个方法被定义，那么在Controller被构造以后，Yaf会调用这个方法
     */
    public function init() {
		$this->getView()->assign("header", "Yaf Example");
	}

	/** 
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/yaf_skeleton/index/index/index/name/danche 的时候, 你就会发现不同
     */
	public function indexAction($name = "Stranger indexModule") {

	    // 全局库的加载
//	    echo \Host::getHost();
	    // 全局库的加载不支持命名空间，子目录等
	    //echo \Test\Test::getTest();

        //项目库的加载
//        echo Tool\Http::getHost();
//        echo Test\Http::getHost();

        // get the file
//        \yaf\Loader::import('/home/danche/www/Tool/Http.php');

        /*$iniConfig = \yaf\application::app()->getConfig();
        echo "<pre>";
        var_export($iniConfig);
        echo "<pre>";*/

		//1. fetch query
		$get = $this->getRequest()->getQuery("get", "default value");
		/*echo "<pre>";
		var_dump($_REQUEST);
		var_dump($_SERVER);
		var_dump($_GET);
		echo "</pre>";*/

		//2. fetch model
		$model = new SampleModel();

		//3. assign
		$this->getView()->assign("content", $model->selectSample());
		$this->getView()->assign("name", $name);

		//4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
        return TRUE;
	}
}
