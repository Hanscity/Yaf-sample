<?php

namespace Utils;

class Data
{
    private static $instance = null;
    public static function getInstance()
    {
        if( self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public static function jsonReturn(int $code = 200, string $message = 'Success', $data = '')
    {
        return json_encode(['code'=>$code,'message'=>$message,'data'=>$data]);
    }


    // 有很多种用法，echo, $this->getResponse()->setBody($jsonData); Synfony 的用法，等等；
    // 这里先锋装，到时候再看~
    public static function responceReturn(string $data)
    {
        echo $data;
    }
}