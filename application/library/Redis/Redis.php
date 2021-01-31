<?php


namespace Redis;

class Redis
{
    private static $instance = null;
    public static function getInstance()
    {
        if( self::$instance === null) {
            //连接本地的 Redis 服务
            $redis = new \Redis();
            $host = \Yaf\Registry::get('config')->redis->host;
            $port = \Yaf\Registry::get('config')->redis->port;
            $password = \Yaf\Registry::get('config')->redis->password;
            $redis->connect($host,$port);
            $redis->auth($password);
            self::$instance = $redis;
        }
        return self::$instance;
    }

}