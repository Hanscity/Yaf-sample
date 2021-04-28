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
        // md5 略微影响速度，但是不可逆
        $tokenKey = \Yaf\Registry::get('config')->application->token->key;
        $token = md5(microtime(1).$tokenKey.rand(1,100000000));
        return $token;
    }

    /**
     * @param $user
     * @return string
     */
    public function setToken($user) :string
    {
        $token = $this->getToken();
        unset($user['hash_password']); ## 去掉敏感的密码信息
        \Redis\Redis::getInstance()->set($token,json_encode($user));

        return $token;
    }


    public function getUserInfoByToken($token) :Array
    {
        $user_info = \Redis\Redis::getInstance()->get($token);
        return json_decode($user_info,1);
    }


    public function addUser($user_name, $phone_number, $password, $codeVerify)
    {
        if (!$user_name) {
            return \Utils\Data::jsonReturn(YAF_LOGIC_REQUIRE,'缺少参数-用户名','');
        }

        if (!$phone_number) {
            return \Utils\Data::jsonReturn(YAF_LOGIC_REQUIRE,'缺少参数-电话号码','');
        }

        if (!$password) {
            return \Utils\Data::jsonReturn(YAF_LOGIC_REQUIRE,'缺少参数-密码','');
        }

        // 验证码暂且放开
//        if ($codeVerify != 9999) {
//            return \Utils\Data::jsonReturn(YAF_LOGIC_DATA_ERROR, '验证码不正确', '');
//        }

        try{
            $user = \Dao\UserModel::getInstance()->getUserInfoByPhone($phone_number);
            if ($user) {
                return \Utils\Data::jsonReturn(YAF_LOGIC_EXISTS,'手机号已存在~','');
            }

            \Dao\UserModel::getInstance()->addUser($user_name,$phone_number,$password);
            return \Utils\Data::jsonReturn();

        } catch (\PDOException $e) {

            $content = '[INFO_DB]'.__FILE__.','.__LINE__.'=>'.$e->getMessage().PHP_EOL;
            \Utils\Log::recordLog($content);
            return \Utils\Data::jsonReturn(YAF_LOGIC_DB_ERROR,'数据库开小差了~','');
        }

    }


    public function judgeUser($phone_number,$password)
    {

        if (!$phone_number) {
            return \Utils\Data::jsonReturn(YAF_LOGIC_REQUIRE,'缺少参数-电话号码','');
        }

        if (!$password) {
            return \Utils\Data::jsonReturn(YAF_LOGIC_REQUIRE,'缺少参数-密码','');
        }

        try {
            $user = \Dao\UserModel::getInstance()->getUserInfoByPhone($phone_number);
        } catch (\PDOException $e) {
            $content = '[INFO_DB]'.__FILE__.','.__LINE__.'=>'.$e->getMessage().PHP_EOL;
            \Utils\Log::recordLog($content);
            return \Utils\Data::jsonReturn(YAF_LOGIC_DB_ERROR,'数据库开小差了~','');
        }

        if (!$user) {
            return \Utils\Data::jsonReturn(YAF_LOGIC_NOT_EXISTS,'手机号或者密码不正确','');
        }


        if (! password_verify($password,$user['hash_password'])) {
            return \Utils\Data::jsonReturn(YAF_LOGIC_DATA_ERROR,'手机号或者密码不正确','');
        }

        $token = $this->setToken($user);
        return \Utils\Data::jsonReturn(YAF_HTTP_OK,'Success',['token'=>$token]);

    }

}