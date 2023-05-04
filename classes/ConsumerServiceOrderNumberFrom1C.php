<?php

namespace php_deamon\classes;
require_once 'Consumer.php';
require_once __DIR__ . '/database/DB.php';

use PDO;
use PDOException;
use php_deamon\classes\database\DB;

class ConsumerServiceOrderNumberFrom1C extends Consumer
{
    public function __construct(string $queueName = "service_order_to_php")
    {
        parent::__construct($queueName);
    }

    protected function process()
    {
        $channel = $this->rabbitmq_conn->channel();
        $channel->queue_declare($this->queueName, false, false, false, false);
        $callback = function ($msg) {
            $db = new DB();
            $payload_arr = json_decode($msg->body, true);
            try {
                $stmt = $db->conn->prepare('UPDATE db_olliver.service_orders
                                                SET order_name_1c = :order_name
                                                WHERE uid = :uid_order;'
                );
                $stmt->bindParam(':order_name',  $payload_arr['order_name'], PDO::PARAM_STR);
                $stmt->bindParam(':uid_order', $payload_arr['order_uid'], PDO::PARAM_STR);
                $stmt->execute();
                //        $msg->ack();
                echo PHP_EOL . 'DONE: Поле order_name_1c в таблице service_orders обновлено!';
            } catch (PDOException $e) {
                echo PHP_EOL . 'ERROR: Поле order_name_1c в таблице service_orders не обновлено!' . PHP_EOL . $e;
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