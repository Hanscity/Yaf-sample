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

        /*
         *  以下是来自 Symfony 的组件 Http/Responce 的一些定义：
         *
            const HTTP_CONTINUE = 100;
            const HTTP_SWITCHING_PROTOCOLS = 101;
            const HTTP_PROCESSING = 102;            // RFC2518
            const HTTP_EARLY_HINTS = 103;           // RFC8297
            const HTTP_OK = 200;
            const HTTP_CREATED = 201;
            const HTTP_ACCEPTED = 202;
            const HTTP_NON_AUTHORITATIVE_INFORMATION = 203;
            const HTTP_NO_CONTENT = 204;
            const HTTP_RESET_CONTENT = 205;
            const HTTP_PARTIAL_CONTENT = 206;
            const HTTP_MULTI_STATUS = 207;          // RFC4918
            const HTTP_ALREADY_REPORTED = 208;      // RFC5842
            const HTTP_IM_USED = 226;               // RFC3229
            const HTTP_MULTIPLE_CHOICES = 300;
            const HTTP_MOVED_PERMANENTLY = 301;
            const HTTP_FOUND = 302;
            const HTTP_SEE_OTHER = 303;
            const HTTP_NOT_MODIFIED = 304;
            const HTTP_USE_PROXY = 305;
            const HTTP_RESERVED = 306;
            const HTTP_TEMPORARY_REDIRECT = 307;
            const HTTP_PERMANENTLY_REDIRECT = 308;  // RFC7238
            const HTTP_BAD_REQUEST = 400;
            const HTTP_UNAUTHORIZED = 401;
            const HTTP_PAYMENT_REQUIRED = 402;
            const HTTP_FORBIDDEN = 403;
            const HTTP_NOT_FOUND = 404;
            const HTTP_METHOD_NOT_ALLOWED = 405;
            const HTTP_NOT_ACCEPTABLE = 406;
            const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
            const HTTP_REQUEST_TIMEOUT = 408;
            const HTTP_CONFLICT = 409;
            const HTTP_GONE = 410;
            const HTTP_LENGTH_REQUIRED = 411;
            const HTTP_PRECONDITION_FAILED = 412;
            const HTTP_REQUEST_ENTITY_TOO_LARGE = 413;
            const HTTP_REQUEST_URI_TOO_LONG = 414;
            const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
            const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
            const HTTP_EXPECTATION_FAILED = 417;
            const HTTP_I_AM_A_TEAPOT = 418;                                               // RFC2324
            const HTTP_MISDIRECTED_REQUEST = 421;                                         // RFC7540
            const HTTP_UNPROCESSABLE_ENTITY = 422;                                        // RFC4918
            const HTTP_LOCKED = 423;                                                      // RFC4918
            const HTTP_FAILED_DEPENDENCY = 424;                                           // RFC4918
            const HTTP_TOO_EARLY = 425;                                                   // RFC-ietf-httpbis-replay-04
            const HTTP_UPGRADE_REQUIRED = 426;                                            // RFC2817
            const HTTP_PRECONDITION_REQUIRED = 428;                                       // RFC6585
            const HTTP_TOO_MANY_REQUESTS = 429;                                           // RFC6585
            const HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;                             // RFC6585
            const HTTP_UNAVAILABLE_FOR_LEGAL_REASONS = 451;
            const HTTP_INTERNAL_SERVER_ERROR = 500;
            const HTTP_NOT_IMPLEMENTED = 501;
            const HTTP_BAD_GATEWAY = 502;
            const HTTP_SERVICE_UNAVAILABLE = 503;
            const HTTP_GATEWAY_TIMEOUT = 504;
            const HTTP_VERSION_NOT_SUPPORTED = 505;
            const HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;                        // RFC2295
            const HTTP_INSUFFICIENT_STORAGE = 507;                                        // RFC4918
            const HTTP_LOOP_DETECTED = 508;                                               // RFC5842
            const HTTP_NOT_EXTENDED = 510;                                                // RFC2774
            const HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;                             // RFC6585



            // 以下是来自 Yaf 定义的常量 (https://www.laruence.com/manual/yaf.constant.html)

            YAF_VERSION(Yaf\VERSION)	Yaf框架的三位版本信息
            YAF_ENVIRON(Yaf\ENVIRON	Yaf的环境常量, 指明了要读取的配置的节, 默认的是product
            YAF_ERR_STARTUP_FAILED(Yaf\ERR\STARTUP_FAILED)	Yaf的错误代码常量, 表示启动失败, 值为512
            YAF_ERR_ROUTE_FAILED(Yaf\ERR\ROUTE_FAILED)	Yaf的错误代码常量, 表示路由失败, 值为513
            YAF_ERR_DISPATCH_FAILED(Yaf\ERR\DISPATCH_FAILED)	Yaf的错误代码常量, 表示分发失败, 值为514
            YAF_ERR_NOTFOUND_MODULE(Yaf\ERR\NOTFOUD\MODULE)	Yaf的错误代码常量, 表示找不到指定的模块, 值为515
            YAF_ERR_NOTFOUND_CONTROLLER(Yaf\ERR\NOTFOUD\CONTROLLER)	Yaf的错误代码常量, 表示找不到指定的Controller, 值为516
            YAF_ERR_NOTFOUND_ACTION(Yaf\ERR\NOTFOUD\ACTION)	Yaf的错误代码常量, 表示找不到指定的Action, 值为517
            YAF_ERR_NOTFOUND_VIEW(Yaf\ERR\NOTFOUD\VIEW)	Yaf的错误代码常量, 表示找不到指定的视图文件, 值为518
            YAF_ERR_CALL_FAILED(Yaf\ERR\CALL_FAILED)	Yaf的错误代码常量, 表示调用失败, 值为519
            YAF_ERR_AUTOLOAD_FAILED(Yaf\ERR\AUTOLOAD_FAILED)	Yaf的错误代码常量, 表示自动加载类失败, 值为520
            YAF_ERR_TYPE_ERROR(Yaf\ERR\TYPE_ERROR)	Yaf的错误代码常量, 表示关键逻辑的参数错误, 值为521

         * */
        //定义业务 code 常量
        define('YAF_HTTP_OK', 200); ## http 请求成功
        define('YAF_HTTP_UNAUTHORIZED', 401); ## http 请求成功,但是用户没有访问权限,需要进行身份认证
        define('YAF_LOGIC_EXISTS', 530); ## 已存在，例如该注册的号码已存在
        define('YAF_LOGIC_NOT_EXISTS', 531); ## 不存在，例如用户的手机号码不存在
        define('YAF_LOGIC_REQUIRE', 532); ## 缺少参数，例如用户的手机号码没有传递
        define('YAF_LOGIC_DATA_ERROR', 532); ## 数据不正确，例如用户的密码不正确
        define('YAF_LOGIC_DB_ERROR', 540); ## MySQL 异常错误

    }

	public function _initPlugin(Yaf\Dispatcher $dispatcher) {
		//注册一个插件
		$objSystemPlugin = new SystemPlugin();
		$dispatcher->registerPlugin($objSystemPlugin);
	}

	public function _initRoute(Yaf\Dispatcher $dispatcher) {
		//在这里注册自己的路由协议，默认使用Yaf_Route_Static
	}
	
	public function _initView(Yaf\Dispatcher $dispatcher) {
		//在这里注册自己的view控制器，例如smarty,firekylin
        $dispatcher->getInstance()->disableView();
	}
}
