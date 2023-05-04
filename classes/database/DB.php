<?php
namespace php_deamon\classes\database;

final class DB
{
    public $conn;
    private $username = "ealdo";
    private $password = "SupeP@ssWord123";
    private $host = "192.168.37.188";
    private $database = "db_olliver";
    private $charset = "utf8";

    public function __construct()
    {
        try {
            $this->conn = new \PDO("mysql:host={$this->host};dbname={$this->database};charset={$this->charset}", $this->username, $this->password);
            // , array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)
        } catch (\PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
    }
}

?>
