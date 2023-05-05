<?php
namespace php_deamon\classes\database;
require_once __DIR__ . "/../../loadenv.php";

final class DB
{
    public $conn;
    // private $db_connect = $_ENV['DB_CONNECTION'];
    // private $username = $_ENV['DB_USERNAME'];
    // private $password = $_ENV['DB_PASSWORD'];
    // private $host = $_ENV['DB_HOST'];
    // private $database = $_ENV['DB_DATABASE'];
    private $charset = "utf8";

    public function __construct()
    {
        try {
            $this->conn = new \PDO("{$_ENV['DB_CONNECTION']}:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_DATABASE']};charset={$this->charset}", $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
            // , array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)
        } catch (\PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
    }
}

?>
