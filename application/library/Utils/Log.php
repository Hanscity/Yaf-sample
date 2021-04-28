<?php


namespace Utils;

class Log
{

    private static $instance = null;
    public static function getInstance()
    {
        if( self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public static function recordLog($content)
    {
        $file = APPLICATION_PATH.'/storages/logs/log'.'_'.date('Y-m-d').'.log';
        // 小心 error_log 函数，它的第一个参数需要是 string, 哪怕是 boolean 也不行
        // 所以我在这里自动转化以下，调用的时候注意
        error_log(json_encode($content).PHP_EOL, 3, $file);
    }


    /*public static function assembleContent($content)
    {

    }*/
}