<?php

// Singleton design pattern

namespace BadHabit\LoginManagement\Config;

class Database
{
    private static ?\PDO $pdo = null;

    public static function getConnection(string $env = "test"): \PDO
    {
        if (self::$pdo == null) {

            // If \PDO never created
            require_once __DIR__ . "/../../config/database.php";
            $config = getDatabaseConfig();
            self::$pdo = new \PDO(
                $config["database"][$env]["url"],
                $config["database"][$env]["username"],
                $config["database"][$env]["password"]
            );
        }

        // If \PDO already created
        return self::$pdo;

    }

    public static function beginTransaction(){
        self::$pdo->beginTransaction();
    }

    public static function commitTransaction(){
        self::$pdo->commit();
    }

    public static function rollbackTransaction(){
        self::$pdo->rollBack();
    }
}