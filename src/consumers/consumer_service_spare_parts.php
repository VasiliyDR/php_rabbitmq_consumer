<?php
namespace php_deamon\consumers;
use php_deamon\classes\ConsumerServiceSparePartsFrom1C;
require_once __DIR__ . '/../../classes/ConsumerServiceSparePartsFrom1C.php';

$consumer_service_spare_parts_from_1C = new ConsumerServiceSparePartsFrom1C();
$consumer_service_spare_parts_from_1C->run();

?>
