<?php
namespace php_deamon\classes;
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . "/../loadenv.php";
use PhpAmqpLib\Connection\AMQPStreamConnection;
abstract class Consumer
{

    protected string $queueName;

    protected AMQPStreamConnection $rabbitmq_conn;

    public function __construct(string $queueName)
    {
        $this->rabbitmq_conn = new AMQPStreamConnection($_ENV['MQ_HOST'], $_ENV['MQ_PORT'], $_ENV['MQ_USERNAME'], $_ENV['MQ_PASSWORD']);
        $this->queueName = $queueName;
    }


    public function run()
    {
        $this->process();
    }

    protected abstract function process();
}

?>
