<?php

namespace Dao;

class UserModel
{
    private static $instance = null;
    public static function getInstance()
    {
        if( self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }



    public function getUserInfoByPhone($phone_number)
    {
        $pdo = \Mysql\Pdo::getInstance()->getPdoInstance();
        $stmt = $pdo->prepare("SELECT id,user_name,phone_number,hash_password FROM users WHERE phone_number = ? 
                                              AND show_status = 1 limit 1");
        $stmt->execute([$phone_number]);
        $user = $stmt->fetch();
        return $user;
    }


    public function addUser($user_name,$phone_number,$password)
    {

        $hash_password = password_hash($password,PASSWORD_DEFAULT);
        $cur_date = date('Y-m-d H:i:s');

        $pdo = \Mysql\Pdo::getInstance()->getPdoInstance();
        $stmt = $pdo->prepare("INSERT INTO users 
                                    (user_name,phone_number,hash_password,created_at,updated_at,show_status) 
                                    VALUES (?,?,?,?,?,?)");

        $stmt->execute([$user_name,$phone_number,$hash_password,$cur_date,$cur_date,1]);
        $row_count = $stmt->rowCount();
        return $row_count;

    }



}