<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use parallel\Runtime;

$connection = new AMQPStreamConnection('192.168.10.250', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare(' test_test_parallel_php', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    $data = json_decode($msg->body, true);
    echo " [x] Received message: " . $data . "\n";
    // $runtime = new Runtime();
    // $runtime->run(function () use ($data) {
        
    //     // Обработка сообщения
    //     echo " [x] Received message: " . $data . "\n";
        
    //     // sleep(1);
    // });
};

$channel->basic_consume('test_test_parallel_php', '', false, false, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();

?>