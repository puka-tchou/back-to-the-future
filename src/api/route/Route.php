<?php namespace route\Route;

use data\Database\Database;
use data\Product\Product;
use data\Stock\Stock;
use tasks\UpdateStock\UpdateStock;
use utilities\Reader\Reader;

define('INPUT', 'parts_yaml');
define('FILENAME', 'tmp_name');

/**
 * Class Route
 */
class Route
{
    /** Add products to the database from a given CSV file.
     * @return string
     */
    public function add(): string
    {
        $product = new Product;
        $reader = new Reader;
        $parts = isset($_FILES[INPUT][FILENAME])
            ? $reader->readCSVFile($_FILES[INPUT][FILENAME])
            : false;

        $response = array();
        foreach ($parts as $part => $manufacturer) {
            $response[$part] = $product->add($part, 7, $manufacturer);
        }
            
        return json_encode($response);
    }

    /** Read API documentation from a YAML file.
     * @param string $url The request made to the server.
     *
     * @return string
     */
    public function documentation(string $url): string
    {
        $reader = new Reader;
        $documentation['query'] = $url;
        $documentation = $reader->readYAMLFile(__DIR__ . '/../yaml/documentation.yaml');
        
        return json_encode($documentation);
    }

    /** Get all products from the database.
     * @return void
     */
    public function products() : string
    {
        $database = new Database;
        
        return json_encode($database->getAllProducts());
    }

    /** Get stock information for a part-number.
     * @return string
     */
    public function part(): string
    {
        $stock = new Stock;
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $source = isset($_GET['source']) ? $_GET['source'] : 'BOTH';

        if ($source == 'DB' || $source == 'BOTH') {
            $response['DB'] = $stock->get($id, -1);
        }
        if ($source == 'WEB' || $source == 'BOTH') {
            $response['WEB'] = $stock->getFromDealers($id);
        }

        return json_encode($response);
    }

    /** Get stock information for a set of parts in a CSV file.
     * @return string
     */
    public function parts(): string
    {
        $database = new Database;
        $reader = new Reader;
        $stock = new Stock;
        $parts = isset($_FILES[INPUT][FILENAME])
            ? $reader->readCSVFile($_FILES[INPUT][FILENAME])
            : false;
    
        foreach ($parts as $part) {
            if ($database->partNumberExists($part)) {
                $stockByPart[$part] = $stock->get($part, -1);
            } else {
                $stockByPart[$part] = array(
                    'err' => true,
                    'response' => 'Part-number not found in the database.'
                );
            }
        }

        return json_encode($stockByPart);
    }

    /** Update stock informations for a set of parts in a CSV file.
     * @return string
     */
    public function update(): string
    {
        $database = new Database;
        $reader = new Reader;
        $task = new UpdateStock;
        $parts = isset($_FILES[INPUT][FILENAME])
            ? $reader->readCSVFile($_FILES[INPUT][FILENAME])
            : false;
        $status = array();

        foreach ($parts as $part) {
            if ($database->partNumberExists($part)) {
                $status[$part] = $task->addRecord($part);
            } else {
                $status[$part] = array(
                    'err' => true,
                    'response' => 'Part-number not found in the database.'
                );
            }
        }

        return json_encode($status);
    }
}
