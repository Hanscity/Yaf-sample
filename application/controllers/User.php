<?php

use Logic\UserModel as UserLogic;

class UserController extends Yaf\Controller_Abstract
{

    public function registerAction()
    {
        $params = \Utils\Data::getHttpPostJson();

        $user_name = isset($params['username']) ? $params['username'] : '';
        $phone_number = isset($params['phone']) ? $params['phone'] : '';
        $password = isset($params['password']) ? $params['password'] : '';
        $codeVerify = isset($params['codeVerify']) ? $params['codeVerify'] : '';


        $data = UserLogic::getInstance()->addUser($user_name, $phone_number, $password, $codeVerify);
        \Utils\Data::responceReturn($data);
    }


    public function loginAction()
    {
        $params = \Utils\Data::getHttpPostJson();

        $phone_number = isset($params['phone']) ? $params['phone'] : '';
        $password = isset($params['password']) ? $params['password'] : '';

        $data = UserLogic::getInstance()->judgeUser($phone_number,$password);
        \Utils\Data::responceReturn($data);
    }


    /*
     * curl -X POST  -d 'account_idx=837&gid=10021&count=10' --header "Content-Type:application/json" http://yaf.test/index/user/test
     * Yaf 确实没有接收到信息，当 --header "Content-Type:application/json" 的时候
     *     获取不到并不是 Yaf 的原因，因为原生的 $_REQUEST 也是获取不到
     *
     * 可以这样获取：
     *     var_dump(file_get_contents("php://input"));
     * 结果如下：
     *     string(34) "account_idx=837&gid=10021&count=10"
     *
     *
     * */
    public function testAction()
    {

        $a = json_encode(
            [
                'code' => 200,
                'msg' => 'success'
            ]
        );
        var_dump($a);

//        var_export(file_get_contents("php://input"));
//        var_export($this->getRequest()->getPost());
//        exit;

//        \Utils\Log::recordLog(file_get_contents("php://input"));
//        \Utils\Log::recordLog(json_encode($this->getRequest()->getPost()));
//        \Utils\Log::recordLog(json_encode($this->getRequest()->getParams()));
//        $data = ['Girl A','Girl B','Girl C'];
//        \Utils\Data::responceReturn(json_encode($data));

    }

    /*
     * @commet: 为前端页面准备的
     */
    public function htmlAction()
    {
        echo "<pre>";
        var_dump($_REQUEST);
        echo "</pre>";
        exit;
    }


}