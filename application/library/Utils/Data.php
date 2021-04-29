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
        exit();
    }

    /*
     * @comment: 当 header 是 "Content-Type:application/json" 的时候，参数转化
     * */
    public static function getHttpPostJson()
    {
        return json_decode(file_get_contents("php://input"), 1);
    }


//    public static function echoJson(int $code = 200, string $message = 'Success', $data = '')
//    {
//        echo json_encode(['code'=>$code,'message'=>$message,'data'=>$data]);
//    }
}