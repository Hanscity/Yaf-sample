<?php

use Logic\UserModel as UserLogic;

class UserController extends Yaf\Controller_Abstract
{

    public function registerAction()
    {
        $user_name = $this->getRequest()->get('user_name');
        $phone_number = $this->getRequest()->get('phone_number');
        $password = $this->getRequest()->get('password');

        $data = UserLogic::getInstance()->addUser($user_name,$phone_number,$password);
        \Utils\Data::responceReturn($data);
    }


    public function loginAction()
    {
        $phone_number = $this->getRequest()->get('phone_number');
        $password = $this->getRequest()->get('password');

        $data = UserLogic::getInstance()->judgeUser($phone_number,$password);
        \Utils\Data::responceReturn($data);
    }












}