<?php

require __DIR__ . '/../../vendor/autoload.php';

use data\Database\Database;
use data\Stock\Stock;
use utilities\PartList\PartList;

$stock = new Stock;
$database = new Database;
$partList = new PartList;

$parts = $partList->readFromFile(__DIR__ . '/xml/setlist.template.txt');

// Check the current state of the history
foreach ($parts as $part) {
    if ($database->partNumberExists($part)) {
        $stockByPart[$part] = $database->getStock($part);
    } else {
        $stockByPart[$part]['stock'] = $stock->get($part);
        $database->addProduct($part, 7, 'crouzet');
    }
}

var_dump($stockByPart);
