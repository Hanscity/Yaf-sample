<?php

use Logic\UserModel as UserLogic;

class UserController extends Yaf\Controller_Abstract
{
    public function gettokenAction()
    {
//        Redis\Redis::getInstance();

//        var_dump(password_hash(microtime(1).rand(1,100000000)));

        $token = UserLogic::getInstance()->getToken();

        echo "<pre>";
        var_dump($this->getRequest()); ##
        var_dump($_POST);
        var_dump($_GET);
        echo "</pre>";

    }


    public function registerAction()
    {
        $user_name = $this->getRequest()->get('user_name');
        $phone_number = $this->getRequest()->get('phone_number');
        $password = $this->getRequest()->get('password');

        $data = UserLogic::getInstance()->addUser($user_name,$phone_number,$password);
        \Utils\Data::responceReturn($data);
    }








}