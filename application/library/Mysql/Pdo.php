<?php

namespace Mysql;

class Pdo
{

    private static $instance = null;
    public static function getInstance()
    {
        if( self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    protected static $pdo = null;

    public function __construct()
    {
        $envMysql = \Yaf\Registry::get('config')->mysql;
        $host = $envMysql['host'] ?? '127.0.0.1';
        $db   = $envMysql['db'] ?? 'test_db';
        $user = $envMysql['user'] ?? 'test';
        $pass = $envMysql['password'] ?? 'test';
        $charset = $envMysql['charset'] ?? 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION, ## Throw a PDOException if an error occurs.
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, ## 默认的数据结构
            \PDO::ATTR_EMULATE_PREPARES   => false, ## affect parameter binding when is true (the default).
        ];

        try {
            self::$pdo = new \PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {

            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getPdoInstance()
    {
        return self::$pdo;
    }


}