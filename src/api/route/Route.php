<?php namespace route\Route;

use data\Database\Database;
use data\Product\Product;
use data\Stock\Stock;
use tasks\UpdateStock\UpdateStock;
use utilities\Reader\Reader;
use utilities\Reporter\Reporter;

define('INPUT', 'parts');
define('FILENAME', 'tmp_name');

/**
 * This class represents the different API endpoints.
 */
class Route
{
    /** This route adds the part-numbers to the products database.
     * @return void
     */
    public function add(): void
    {
        $product = new Product;
        $reader = new Reader;
        $reporter = new Reporter;
        $code = 0;
        $shortMessage = 'The part-numbers were successfully added to the database.';
        $body = array();
        $parts = isset($_FILES[INPUT][FILENAME]) ? $reader->readCSVFile($_FILES[INPUT][FILENAME]) : false;
        
        if (!$parts) {
            $code = 2;
            $shortMessage = 'The CSV file was not found.';
            $body[] =  'The CSV file containing the part-numbers was not found in your request. Please, make sure that you are sending a "multipart/form-data" request with a "parts" field containing the CSV file. The "parts" field should be of type "file".';
        } else {
            foreach ($parts as $part => $manufacturer) {
                $status = $product->add($part, 7, $manufacturer);
                if ($status['code'] !== 0) {
                    $code = 1;
                    $shortMessage = 'Some part-numbers could not be added to the database.';
                }
                $body[$part] = $reporter->format($status['code'], $status['message'], $status['body']);
            }
        }

        $reporter->send($code, $shortMessage, $body);
    }

    /** Read API documentation from a YAML file.
     * @param string $url The request made to the server.
     *
     * @return void
     */
    public function documentation(string $url): void
    {
        $reader = new Reader;
        $reporter = new Reporter;
        $documentation['query'] = $url;
        $documentation = $reader->readYAMLFile(__DIR__ . '/../yaml/documentation.yaml');
        
        $reporter->send($documentation, 'Well, there is the documentation.');
    }

    /** Get all products from the database.
     * @return void
     */
    public function products() : void
    {
        $database = new Database;
        $reporter = new Reporter;
        $body = $database->getAllProducts();

        $reporter->send($body);
    }

    /** Get stock information for a part-number.
     * @return void
     */
    public function part(): void
    {
        $stock = new Stock;
        $reporter = new Reporter;
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $source = isset($_GET['source']) ? $_GET['source'] : 'BOTH';

        if ($source == 'DB' || $source == 'BOTH') {
            $response['DB'] = $stock->get($id, -1);
        }
        if ($source == 'WEB' || $source == 'BOTH') {
            $response['WEB'] = $stock->getFromDealers($id);
        }

        $reporter->send($response);
    }

    /** Get stock information for a set of parts in a CSV file.
     * @return string
     */
    public function parts(): void
    {
        $database = new Database;
        $reader = new Reader;
        $stock = new Stock;
        $reporter = new Reporter;
        $code = 0;
        $message = 'Everything went fine.';
        $parts = isset($_FILES[INPUT][FILENAME]) ? $reader->readCSVFile($_FILES[INPUT][FILENAME]) : false;
    
        foreach ($parts as $part) {
            if ($database->partNumberExists($part)) {
                $stockByPart[$part] = $stock->get($part, -1);
            } else {
                $code = 1;
                $message = 'Some part-numbers were not found in the database.';
                $stockByPart[$part] = $reporter->format(
                    '',
                    'Part-number not found in the database.',
                    4
                );
            }
        }

        $reporter->send($stockByPart, $message, $code);
    }

    /** Update stock informations for a set of parts in a CSV file.
     * @return void
     */
    public function update(): void
    {
        $database = new Database;
        $reader = new Reader;
        $reporter = new Reporter;
        $task = new UpdateStock;
        $code = 0;
        $message = 'Stock information successfully updated.';
        $parts = isset($_FILES[INPUT][FILENAME]) ? $reader->readCSVFile($_FILES[INPUT][FILENAME]) : false;
        $status = array();

        foreach ($parts as $part) {
            if ($database->partNumberExists($part)) {
                $status[$part] = $task->addRecord($part);
            } else {
                $code = 1;
                $message = 'Some part-numbers were not found in the database.';
                $status[$part] = $reporter->format(
                    '',
                    'Part-number not found in the database.',
                    4
                );
            }
        }

        $reporter->send($status, $message, $code);
    }
}
