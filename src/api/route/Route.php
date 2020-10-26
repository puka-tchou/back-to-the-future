<?php

namespace route\Route;

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
        $product = new Product();
        $reader = new Reader();
        $reporter = new Reporter();
        $code = 0;
        $shortMessage = 'The part-numbers were successfully added to the database.';
        $body = array();
        $parts = isset($_FILES[INPUT][FILENAME]) ? $reader->readCSVFile($_FILES[INPUT][FILENAME]) : null;
        if ($parts === null) {
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
        $reader = new Reader();
        $reporter = new Reporter();
        $documentation['query'] = $url;
        $documentation = $reader->readYAMLFile(__DIR__ . '/../specs.yml');
        $reporter->send(0, 'Well, here is the documentation.', $documentation);
    }

    /** This route echoes all the products stored in the database.
     * The result is limited to 100 products and is paginated. You can access more
     * pages as necessary by providing the `page` query parameter (index starts at 0)
     * eg: `/api/products?page=2` (you will get products at index `200` to `299`).
     *
     * @return void
     */
    public function products(): void
    {
        $database = new Database();
        $reporter = new Reporter();
        $result = $database->getAllProducts();
        $reporter->send($result['code'], $result['message'], $result['body']);
    }

    /** Get stock information for a part-number.
     * @return void
     */
    public function part(): void
    {
        $stock = new Stock();
        $reporter = new Reporter();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $source = isset($_GET['source']) ? $_GET['source'] : 'BOTH';
        $code = 2;
        $message = 'You must provide an id.';
        $body = array();
        if ($id !== null) {
            $code = 0;
            $message = 'Stock information found for part-number ' . $id;
            if ($source === 'DB' || $source === 'BOTH') {
                $body['DB'] = $stock->get($id, -1);
                $code += $body['DB']['code'];
            }
            if ($source === 'WEB' || $source === 'BOTH') {
                $body['WEB'] = $stock->getFromDealers($id);
            }

            if ($code !== 0) {
                $code = 1;
                $message = 'There were errors.';
            }
        }

        $reporter->send($code, $message, $body);
    }

    /** Get stock information for a set of parts in a CSV file.
     * @return void
     */
    public function parts(): void
    {
        $reader = new Reader();
        $stock = new Stock();
        $reporter = new Reporter();
        $parts = isset($_FILES[INPUT][FILENAME]) ? $reader->readCSVFile($_FILES[INPUT][FILENAME]) : null;
        $code = 2;
        $message = 'CSV file not found.';
        $body = array();
        if ($parts !== null) {
            $code = 0;
            $message = 'Found stock history for ' . count($parts) . ' part-numbers.';
            foreach ($parts as $part) {
                $res = $stock->get($part, -1);
                $body[$part] = $res;
                $code += $res['code'];
            }

            if ($code !== 0) {
                $code = 1;
                $message = 'There were errors.';
            }
        }

        $reporter->send($code, $message, $body);
    }

    /** Update stock informations for a set of parts in a CSV file.
     * @return void
     */
    public function update(): void
    {
        $reader = new Reader();
        $reporter = new Reporter();
        $task = new UpdateStock();
        $parts = isset($_FILES[INPUT][FILENAME]) ? $reader->readCSVFile($_FILES[INPUT][FILENAME]) : null;
        $code = 2;
        $message = 'The CSV file was not found.';
        $body = array();
        if ($parts !== null) {
            $code = 0;
            $message = 'The stock was successfully updated for ' . count($parts) . ' part-numbers.';
            foreach ($parts as $part) {
                $res = $task->addRecord($part);
                $body[$part] = $res;
                $code += $res['code'];
            }
            if ($code !== 0) {
                $code = 1;
                $message .= ' There were errors or warnings.';
            }
        }

        $reporter->send($code, $message, $body);
    }

    /** Update the stock information for all the $parts.
     * This an example:
     * ```
     * $task = new UpdateStock;
     * $res = $task->updateAll();
     * $reporter->send($res['code'], $res['message'], $res['body']);
     * ```
     * @todo This route should require user authentication.
     *
     * @return void
     */
    public function updateall(): void
    {
        $reporter = new Reporter();
        $answer = $reporter->format(401, 'You should be logged in', null);
        header('WWW-Authenticate: Basic realm="Back to the future"');
        header('HTTP/1.0 401 Unauthorized');
        header('Content-Type: application/json');
        echo json_encode($answer);
    }
}
