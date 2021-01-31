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
        $file = APPLICATION_PATH.'/storages/logs/log'.'_'.date('Y-m-d');
        error_log($content,3,$file);
    }


    /*public static function assembleContent($content)
    {

    }*/
}