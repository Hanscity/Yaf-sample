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

		//2. fetch model
		$model = new SampleModel();

		$modelDao = new Dao\SampleModel();
		$info = $modelDao->selectSample();

		var_dump($info);

		//3. assign
//		$this->getView()->assign("content", $model->selectSample());
//		$this->getView()->assign("name", $name);
//        echo $this->getView()->render('index/index.phtml');
		//4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
//        return FALSE;
	}



	public function test_requestAction()
    {
//        header('Content-type:text/html;charset=utf-8');
//        header('Content-Type: application/json;charset=utf-8');

        echo "<pre>";
        var_dump($this->getRequest()); ## 获取请求的所有信息
        var_dump($this->getRequest()->getParams()); ## http://yaf.test/index/index/test_request?name=ch 可以获取到 name=ch
        var_dump($this->getRequest()->getQuery()); ## http://yaf.test/index/index/test_request?name=ch 可以获取到 name=ch
        var_dump($this->getRequest()->getPost()); ##
        var_dump($this->getRequest()->getFiles()); ##
        var_dump($this->getRequest()->get('name')); ## 可以获取到 get,post,param 中的 name 参数
        var_dump($this->getRequest()->getParam('name')); ## http://yaf.test/index/index/test_request?name=ch 可以获取到 name=ch

        var_dump($this->getRequest()->getModuleName());
        var_dump($this->getRequest()->getControllerName());
        var_dump($this->getRequest()->getActionName());


        var_dump($this->getRequest()->isCli());
        var_dump($this->getRequest()->isGet());
        var_dump($this->getRequest()->isPost());

        var_dump($this->getRequest()->getMethod()); ## 获取请求的方法，GET，POST，是大写


        echo "</pre>";
    }


    public function test_responceAction()
    {
        /*echo "<pre>";
        var_dump($this->getResponse());
        var_dump($this->getResponse()->setRedirect('https://www.baidu.com')); ## 设置跳转

        $this->getResponse()->setBody("Hello World");

        $this->getResponse()->appendBody("Hello World002");
        $this->getResponse()->appendBody();


        echo $this->getResponse()->getBody();
        echo "</pre>";*/


//        echo $config->database->get("params")->host;   // 输出 "dev.example.com"
//        echo $config->get("database")->params->dbname; // 输出 "dbname"
//

//        $this->getResponse()->setBody("Hello World--set body");

    }

    public function get_configAction()
    {
        // one
        $config = new Yaf\Config\Ini(APPLICATION_PATH.'/conf/application.ini','develop');
        //
        $envMysql = \Yaf\Registry::get('config');

        $jsonData = Utils\Data::getInstance()->jsonReturn($envMysql);
        $this->getResponse()->setBody($jsonData);
    }

    public function responceAction()
    {
        $jsonData = Utils\Data::getInstance()->jsonReturn(['hello,world']);
        Utils\Data::getInstance()->responceReturn($jsonData);
    }


    public function test_logAction()
    {

        $content = '[Info]'.__FILE__.','.__LINE__.'=>'.json_encode('aaaaaaa').PHP_EOL;
        Utils\Log::getInstance()->recordLog($content);
    }


    public function insert_oneAction()
    {
        $user_name = 'ch';
        $phone_number = '13048825663';
        $password = '123456';
        $hash_password = password_hash($password,PASSWORD_DEFAULT);
        $cur_date = date('Y-m-d H:i:s');

        $pdo = Mysql\Pdo::getInstance()->getPdoInstance();
        $stmt = $pdo->prepare("INSERT INTO users 
    (user_name,phone_number,hash_password,created_at,updated_at,show_status) 
    VALUES (?,?,?,?,?,?)");

        $stmt->execute([$user_name,$phone_number,$hash_password,$cur_date,$cur_date,1]);
        $row_count = $stmt->rowCount();
        Utils\Data::getInstance()->responceReturn($row_count);

    }


    public function get_constAction()
    {
        echo "<pre>";
        var_export(\Yaf\VERSION);
        var_export(\Yaf\ENVIRON);
        var_export(\Yaf\ERR\STARTUP_FAILED);
        var_export(\Yaf\ERR\ROUTE_FAILED);
        echo "</pre>";



    }






}
