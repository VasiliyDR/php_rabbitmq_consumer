<?php
namespace php_deamon\src;
use php_deamon\classes\ConsumerServiceSparePartsFrom1C;

require_once __DIR__ . '/../classes/ConsumerServiceSparePartsFrom1C.php';
$cons = new ConsumerServiceSparePartsFrom1C();
$cons->run();
?>