<?php

require __DIR__ . '/../../vendor/autoload.php';

use data\Database\Database;
use data\Stock\Stock;
use utilities\PartList\PartList;

$stock = new Stock;
$database = new Database;
$partList = new PartList;

$parts = $partList->readFromString(file_get_contents('php://input'));
$request = isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : "/";
$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : "INVALID";

if ($method !== 'GET' && $method !== 'HEAD') {
    header('HTTP/1.1 405 Method Not Allowed');
    die();
}

header('Content-Type: application/json');

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
            $response['DB'] = $database->getStock($id);
        }
        if ($source == 'WEB' || $source == 'BOTH') {
            $response['WEB'] = $stock->get($id);
        }
        echo json_encode($response);
        break;
    case '/api/TODO':
        // TODO: Well, do this.
        foreach ($parts as $part) {
            if ($database->partNumberExists($part)) {
                $stockByPart[$part] = $database->getStock($part);
            } else {
                $stockByPart[$part]['stock'] = $stock->get($part);
            }
        }
        echo json_encode($stockByPart);
        break;
    case '/api/coffee':
        header("HTTP/1.1 418 I'm a teapot");
        $quote = json_decode(file_get_contents("https://programming-quotes-api.herokuapp.com/quotes/random"));
        echo json_encode(array(
            "☕" => $quote->en . " (" . $quote->author . ")"
        ));
        break;
    default:
        echo json_encode(
            array(
                'query' => $request,
                '/api/products' => array(
                    'description' => 'Get all the products in the database',
                    'parameters' => 'none'
                ),
                '/api/part' => array(
                    'description' => 'Get the stock of a given part number.',
                    'parameters' => array(
                        'id' => 'The part-number to check',
                        'source' => '(DB, WEB, BOTH) Get data from the database, the web or both. Defaults to BOTH'
                    )
                )
            )
        );
        break;
}
