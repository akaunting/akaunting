<?php
class Database {
    private static $connection = null;

    public static function connect() {
        if (self::$connection === null) {
            self::$connection = new PDO(
                "mysql:host=mysql;dbname=akaunting;charset=utf8mb4",
                "akaunting",
                "akauntingpass",
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]
            );
        }
        return self::$connection;
    }
}
