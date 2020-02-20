<?php

require __DIR__ . '/../../vendor/autoload.php';

use data\Database\Database;
use data\Product\Product;
use data\Stock\Stock;
use tasks\UpdateStock\UpdateStock;
use utilities\PartList\PartList;

$file_input = isset($_FILES['parts_yaml']['tmp_name']) ? $_FILES['parts_yaml']['tmp_name'] : null;
$request = isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : '/';
$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'INVALID';

if ($method !== 'GET'
&& $method !== 'HEAD'
&& $method !== 'POST'
) {
    header('HTTP/1.1 405 Method Not Allowed');
    die();
}

header('Content-Type: application/json');

$stock = new Stock;
$database = new Database;
$partList = new PartList;
$update = new UpdateStock;
$product = new Product;

switch ($request) {
    case '/api/products':
        echo json_encode($database->getAllProducts());
        break;
    case '/api/part':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $source = isset($_GET['source']) ? $_GET['source'] : 'BOTH';
        $response['source'] = $source;

        if (($source == 'DB' || $source == 'BOTH')
            && $database->partNumberExists($id)
        ) {
            $response['DB'] = $stock->getLast($id);
        }
        if ($source == 'WEB' || $source == 'BOTH') {
            $response['WEB'] = $stock->getFromDealers($id);
        }
        echo json_encode($response);
        break;
    case '/api/parts':
        $parts = $partList->readFromFile($file_input);

        foreach ($parts as $part) {
            if ($database->partNumberExists($part)) {
                $stockByPart[$part] = $stock->getLast($part);
            } else {
                $stockByPart[$part] = array(
                    'err' => true,
                    'response' => 'Part-number not found in the database.'
                );
            }
        }
        echo json_encode($stockByPart);
        break;
    case '/api/update':
        $parts = $partList->readFromFile($file_input);
        $status;
        foreach ($parts as $part) {
            if ($database->partNumberExists($part)) {
                $status[$part] = $update->addRecord($part);
            } else {
                $status[$part] = array(
                    'err' => true,
                    'response' => 'Part-number not found in the database.'
                );
            }
        }
        echo json_encode($status);
        break;
    case '/api/add':
        $parts = $partList->readFromFile($file_input);
        $status = array();
        foreach ($parts as $part => $manufacturer) {
            $status[$part] = $product->add($part, 7, $manufacturer);
        }
        echo json_encode($status);
        break;
    case '/api/coffee':
        header("HTTP/1.1 418 I'm a teapot");
        $quote = json_decode(file_get_contents('https://programming-quotes-api.herokuapp.com/quotes/random'));
        echo json_encode(array(
            '☕' => $quote->en . ' (' . $quote->author . ')'
        ));
        break;
    default:
        $documentation = $partList->readFromFile(__DIR__ . '/yaml/documentation.yaml');
        $documentation['your_query'] = $request;
        echo json_encode($documentation);
        break;
}