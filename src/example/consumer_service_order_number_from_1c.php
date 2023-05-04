<?php
namespace php_deamon\src;
use php_deamon\classes\ConsumerServiceOrderNumberFrom1C;

require_once __DIR__ . '/../classes/ConsumerServiceOrderNumberFrom1C.php';
$cons = new ConsumerServiceOrderNumberFrom1C();
$cons->run();
?>