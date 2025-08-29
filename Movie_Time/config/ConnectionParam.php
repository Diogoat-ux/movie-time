<?php
class ConnectionParam
{
    const EDB_DBTYPE = "mysql";
    const EDB_DBNAME = "MOVIE_TIME";      // À compléter
    const EDB_HOST   = "localhost";      // À compléter
    const EDB_PORT   = "3306";
    const EDB_USER   = "root";      // À compléter
    const EDB_PASS   = "";      // À compléter

    public static function testConnection()
    {
        try {
            $dsn = self::EDB_DBTYPE . ':host=' . self::EDB_HOST . ';port=' . self::EDB_PORT . ';dbname=' . self::EDB_DBNAME . ';charset=utf8';
            $pdo = new PDO($dsn, self::EDB_USER, self::EDB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connection successful with ConnectionParam!";
        } catch (PDOException $e) {
            echo "Connection error in ConnectionParam: " . $e->getMessage();
        }
    }
}

// Configuration de l'API TMDB
define('TMDB_API_KEY', 'd6d5e4adb8dc1d508abc06b579be1df9');
define('TMDB_API_BASE_URL', 'https://api.themoviedb.org/3');
?>
