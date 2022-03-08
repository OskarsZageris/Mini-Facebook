<?php
namespace App;

use Doctrine\DBAL\DriverManager;


class Database
{

      protected static array  $connectionParams = [
            'dbname' => 'minifb',
            'user' => 'root',
            'password' => 'oskars',
            'host' => '127.0.0.1',
            'driver' => 'pdo_mysql'
        ];

    public static function connect()
    {
        return DriverManager::getConnection(self::$connectionParams);
    }
}
