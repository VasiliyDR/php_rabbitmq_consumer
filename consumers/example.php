<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('192.168.10.250', 5672, 'guest', 'guest');


$channel = $connection->channel();
$channel->queue_declare('spare_part_to_crm', false, false, false, false);


$callback = function ($msg) {
    $db = new DB();
    try {
        $payload_arr = json_decode($msg->body, true);
        $sql_insert = "INSERT INTO db_olliver.service_spare_parts (guid, name, count, unit) VALUES (?,?,?,?)";
        $sql_truncate = "SET FOREIGN_KEY_CHECKS = 0;TRUNCATE TABLE db_olliver.service_spare_parts;SET FOREIGN_KEY_CHECKS = 1;";
        $stmt = $db->conn;
        $stmt->exec($sql_truncate);
        $st = $stmt->prepare($sql_insert);
        foreach ($payload_arr['date'] as $d) {
            $st->execute(array_values($d));
        }
        echo PHP_EOL . 'Таблица service_spare_parts обновлена!';
//        $msg->ack();
    } catch (PDOException $e) {
        echo PHP_EOL . 'Таблица service_spare_parts не обновлена! ' . PHP_EOL . $e;
    }

};


$channel->basic_consume('spare_part_to_crm', '', false, false, false, false, $callback);
$channel->basic_qos(null, 1, null);


while ($channel->is_open()) {
    $channel->wait();
}

class DB
{
    public $conn;
    private $username = "test_web";
    private $password = "RthfkYfer54!";
    private $host = "127.0.0.1";
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