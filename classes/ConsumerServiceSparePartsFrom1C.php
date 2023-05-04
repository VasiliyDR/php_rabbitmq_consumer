<?php
namespace php_deamon\classes;
require_once 'Consumer.php';
require_once __DIR__ . '/database/DB.php';

use PDOException;
use php_deamon\classes\database\DB;

class ConsumerServiceSparePartsFrom1C extends Consumer
{
    public function __construct(string $queueName = "spare_part_to_crm")
    {
        parent::__construct($queueName);
    }

    protected function process()
    {
        $channel = $this->rabbitmq_conn->channel();
        $channel->queue_declare($this->queueName, false, false, false, false);


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


        $channel->basic_consume($this->queueName, '', false, false, false, false, $callback);
        $channel->basic_qos(null, 1, null);


        while ($channel->is_open()) {
            $channel->wait();
        }
    }
}

?>
