<?php
require_once 'ConnectionParam.php';

class Database
{
    private static $objInstance;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance()
    {
        if (!self::$objInstance) {
            try {
                $dsn = ConnectionParam::EDB_DBTYPE . ':host=' . ConnectionParam::EDB_HOST . ';port=' . ConnectionParam::EDB_PORT . ';dbname=' . ConnectionParam::EDB_DBNAME . ';charset=utf8';
                self::$objInstance = new PDO($dsn, ConnectionParam::EDB_USER, ConnectionParam::EDB_PASS);
                self::$objInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //echo "Connection successful with Database!";
            } catch (PDOException $e) {
                echo "Database Error: " . $e->getMessage();
            }
        }
        return self::$objInstance;
    }

    public static function testStaticCall()
    {
        $objInstance = self::getInstance();
        return "Static call works in Database!";
    }
}
?>
