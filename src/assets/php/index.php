<?php
require __DIR__ . '/../../../vendor/autoload.php';

use Stock\Stock;

$stock = new Stock();
$stockByPart = $stock->get(__DIR__ . '/../xml/setlist.template.txt');
print_r($stockByPart);
