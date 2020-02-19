<?php

require __DIR__ . '/../../vendor/autoload.php';

use data\Database\Database;
use data\Stock\Stock;
use utilities\PartList\PartList;

$stock = new Stock;
$database = new Database;
$partList = new PartList;

$parts = $partList->readFromFile(__DIR__ . '/xml/setlist.template.txt');
$what = isset($_GET["what"]) ? $_GET["what"] : null;

switch ($what) {
    case 'products':
        echo json_encode($database->getAllProducts());
        break;
    case 'part':
        $id = isset($_GET["id"]) ? $_GET["id"] : null;
        if ($database->partNumberExists($id)) {
            $response = $database->getStock($id);
            $response['source'] = 'DB';
            echo json_encode($response);
        } else {
            $response = $stock->get($id);
            $response['source'] = 'WEB';
            echo json_encode($response);
        }
        break;
    case 'TODO':
        foreach ($parts as $part) {
            if ($database->partNumberExists($part)) {
                $stockByPart[$part] = $database->getStock($part);
            } else {
                $stockByPart[$part]['stock'] = $stock->get($part);
            }
        }
        echo json_encode($stockByPart);
        break;
    case 'coffee':
        header("HTTP/1.1 418 I'm a teapot");
        break;
    default:
        header("HTTP/1.1 204 API endpoint not defined");
        break;
}
