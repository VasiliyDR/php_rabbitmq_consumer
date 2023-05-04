<?php
namespace php_deamon\classes;
require_once __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
abstract class Consumer
{

    protected string $queueName;

    protected AMQPStreamConnection $rabbitmq_conn;

    public function __construct(string $queueName)
    {
        $this->rabbitmq_conn = new AMQPStreamConnection('192.168.10.250', 5672, 'guest', 'guest');
        $this->queueName = $queueName;
    }


    public function run()
    {
        $this->process();
    }

    protected abstract function process();
}

?>
