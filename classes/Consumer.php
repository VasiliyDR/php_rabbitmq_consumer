<?php
namespace php_deamon\classes;
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . "/../loadenv.php";
use PhpAmqpLib\Connection\AMQPStreamConnection;

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

abstract class Consumer
{

    protected string $queueName;

    protected AMQPStreamConnection $rabbitmq_conn;

    public function __construct(string $queueName, string $logger_name, string $file_name)
    {
        $this->rabbitmq_conn = new AMQPStreamConnection($_ENV['MQ_HOST'], $_ENV['MQ_PORT'], $_ENV['MQ_USERNAME'], $_ENV['MQ_PASSWORD']);
        $this->queueName = $queueName;
        $this->logger = $this->makeLogger($logger_name, $file_name);
    }

    protected $logger; 
    
    private final function makeLogger(string $logger_name, string $file_name): Logger
    {
       $log = new Logger($logger_name);
       $log->pushHandler(new StreamHandler($file_name, Level::Warning));
       return $log;
    }


    public function run()
    {
        $this->process();
    }

    protected abstract function process();
}

?>
