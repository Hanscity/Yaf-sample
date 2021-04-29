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

    // key 的制定规范： （模块：业务逻辑：id）
    protected static $userinfoCachePrefix = 'user:usercache:';

    public function getUserinfoCachePrefix()
    {
        return self::$userinfoCachePrefix;
    }

    public function getToken()
    {
        // md5 略微影响速度，但是不可逆
        $tokenKey = \Yaf\Registry::get('config')->application->token->key;
        // 这里加上前缀有两个原因：一个名字统一有规范，之后方便删除查找
        $token = $this->getUserinfoCachePrefix().md5(microtime(1).$tokenKey.rand(1,100000000));
        return $token;
    }


    public function getTokenByTokenPart($tokenPart)
    {
        return $this->getUserinfoCachePrefix().$tokenPart;
    }

    /**
     * @param $user
     * @return string
     *
     */
    public function getUserCacheKey($uid)
    {
        return $this->getUserinfoCachePrefix().$uid;
    }


    /**
     * @param $user
     * @return string
     */
    public function setUserinfoByToken($user) :string
    {
        $token = $this->getToken();
        unset($user['hash_password']); ## 去掉敏感的密码信息
        \Redis\Redis::getInstance()->set($token, json_encode($user), 3600*24);

        // 返回给去掉前缀的 token
        return str_replace($this->getUserinfoCachePrefix(), '', $token);
    }

    public function setUserinfoByUserIdentification($user)
    {
        $token = $this->getUserCacheKey($user['id']);
        unset($user['hash_password']); ## 去掉敏感的密码信息
        \Redis\Redis::getInstance()->set($token, json_encode($user), 3600*24);
    }



    public function getUserInfoByUserIdentification($uid)
    {
        $userInfo = \Redis\Redis::getInstance()->get($this->getUserinfoCachePrefix().$uid);
        return json_decode($userInfo, 1);
    }


    public function checkUserInfoByTokenThenReturn($token)
    {
        $userinfo = \Redis\Redis::getInstance()->get($this->getTokenByTokenPart($token));

        if ( !$userinfo ) {
            return false;
        }

        $userinfo = json_decode($userinfo, 1);
        if ( !is_array($userinfo)) {
            return false;
        }

        if ( !( isset($userinfo['id']) && isset($userinfo['user_name'])
            && isset($userinfo['phone_number']) ) ) {
            return false;
        }

        return $userinfo;
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
        // 设置用户缓存，通过用户id
        $this->setUserinfoByUserIdentification($user);
        // 设置用户缓存，通过 token
        $token = $this->setUserinfoByToken($user);
        return \Utils\Data::jsonReturn(YAF_HTTP_OK,'Success',['token'=>$token]);

    }

}