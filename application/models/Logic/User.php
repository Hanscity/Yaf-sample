<?php

namespace Logic;

class UserModel
{

    private static $instance = null;
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }


    public function getToken()
    {
        $tokenKey = \Yaf\Registry::get('config')->application->token->key;
        $token = md5(microtime(1).$tokenKey.rand(1,100000000));
        return $token;
    }


    public function addUser($user_name,$phone_number,$password)
    {
        if (!$user_name) {
            return \Utils\Data::jsonReturn(YAF_ERR_LOGIC_REQUIRE,'缺少参数-用户名','');
        }

        if (!$phone_number) {
            return \Utils\Data::jsonReturn(YAF_ERR_LOGIC_REQUIRE,'缺少参数-电话号码','');
        }

        if (!$password) {
            return \Utils\Data::jsonReturn(YAF_ERR_LOGIC_REQUIRE,'缺少参数-密码','');
        }

        try{
            $user = \Dao\UserModel::getInstance()->getUserInfoByPhone($phone_number);
            if ($user) {
                return \Utils\Data::jsonReturn(YAF_ERR_LOGIC_EXISTS,'手机号已存在~','');
            }

            \Dao\UserModel::getInstance()->addUser($user_name,$phone_number,$password);
            return \Utils\Data::jsonReturn();

        } catch (\PDOException $e) {

            $content = '[INFO_DB]'.__FILE__.','.__LINE__.'=>'.json_encode($e->getMessage()).PHP_EOL;
            \Utils\Log::recordLog($content);
            return \Utils\Data::jsonReturn(YAF_ERR_LOGIC_DB,'数据库开小差了~','');
        }

    }



}