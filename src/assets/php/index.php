<?php
require __DIR__ . '/../../../vendor/autoload.php';

use data\Database\Database;
use Stock\Stock;
use utilities\PartList\PartList;

$stock = new Stock;
$database = new Database;
$partList = new PartList;

$parts = $partList->readFromFile(__DIR__ . '/../xml/setlist.template.txt');

foreach ($parts as $part) {
    $partIsAlreadyInDB[$part] = $database->partNumberExists($part);
    $stockByPart[$part] = $stock->get($part);
}

var_dump($stockByPart);