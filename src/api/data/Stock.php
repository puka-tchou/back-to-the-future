<?php

namespace BackToTheFuture\data;

use BackToTheFuture\dealers\AlliedElec;
use BackToTheFuture\dealers\NetComponents;
use BackToTheFuture\utilities\Reporter;
use Exception;
use PDO;

/**
 * Manipulate stock data, get data from online stores.
 */
class Stock
{
    /** Retrieve stock informations for a given part number from online stores.
     * @param string $part The part number.
     *
     * @return array
     */
    public function getFromDealers(string $part): array
    {
        $part = strtoupper($part);
        $alliedelec = new AlliedElec();
        $reporter = new Reporter();
        $res = $alliedelec->getStock($part);
        $code = $res['code'];
        $message = $res['message'];
        $body = array(
            'part_number' => $part,
            'date_checked' => date('Y-m-d'),
            'alliedelec' => $res['body']
        );
        return $reporter->format($code, $message, $body);
    }

    /** Retrieve stock informations for a given part number from the netComponents API.
     * @param string $part The part-number. It must be one of Crouzet's part-number.
     *
     * @return array A `Reporter` formatted array with the stock information in the body.
     * ```json
     * {
     *  'code': 0,
     *    'message': 'Found stock for 10 dealers.',
     *    'body': {
     *      'Mouser Electronics Inc.': 36,
     *      'Allied Electronics': 50,
     *      'Digi-Key Electronics': 97,
     *      'Distrelec Group AG': 73,
     *      'Electro Sonic Group, Inc.': 8,
     *      'Galco Industrial Electronics': 23,
     *      'Master Electronics': 6,
     *      'Newark, An Avnet Company': 16,
     *      'Sentronic AG': 9,
     *      'OEM Automatic UK': 17
     *     }
     * }
     * ```
     */
    public function getFromDilp(string $part): array
    {
        $part = strtoupper($part);
        $netcomponents = new NetComponents();
        $reporter = new Reporter();
        $res = $netcomponents->getStock($part);
        $code = $res['code'];
        $message = $part . ': ' . $res['message'];
        $body = is_string($res['body']) ? 'API error: ' . $res['body'] : json_encode($res['body']);
        if ($code === 0) {
            $body = array(
                'part_number' => $part,
                'date_checked' => date('Y-m-d'),
                'stock' => $res['body']
            );
        }

        return $reporter->format($code, $message, $body);
    }

    /** Get the stock history for part-number.
     * @param string $partNumber The part-number to check.
     * @param float $limit The number of records to fetch. If set to `-1`, get all the records.
     * @return array The stock history.
     * ```php
     * // The array structure
     * array(
     *  'date_checked' => string '2020-02-20'
     *  'stock' => string 'test2'
     *  'supplier' => string 'alliedelec'
     * );
     * ````
     */
    public function get(string $partNumber, float $limit): array
    {
        $code = 4;
        $message = 'Part-number not found.';
        $body = array();
        $reporter = new Reporter();
        $partNumber = strtoupper($partNumber);
        $limit = ($limit == -1) ? ';' : ('LIMIT ' . $limit . ';');

        try {
            $database = new Database();
        } catch (Exception $exception) {
            $code = $exception->getCode();
            $message = $exception->getMessage();
            $body = '';
        }
        if ($code === 4 && $database->partNumberExists($partNumber)) {
            $query = $database->connection->prepare('SELECT *
                FROM stock_history
                WHERE part_number = ?
                ORDER BY id ASC '
                . $limit);
            $res = $query->execute(array($partNumber));
            $code = 0;
            $body = $query->fetchAll(PDO::FETCH_ASSOC);
            $message = count($body) . ' stock records found.';
            if (!$res) {
                $code = 5;
                $message = 'SQL error';
                $body = $query->errorInfo();
            }
        }

        return $reporter->format($code, $message, $body);
    }

    /** Get only the last stock records for a given part-number.
     * @param string $part The part-number to check.
     *
     * @return array An array containing the stock objects under the form:
     * ```php
     * [
     *  {
     *      "id": [int],
     *      "part_number": [string],
     *      "date_checked": [string], //formatted as YYYY-mm-dd
     *      "state": [float] //`-1` if there is no data, `0` if it's OK, `1` if there is a problem
     *      "parts_in_stock": [int],
     *      "parts_on_order": [int],
     *      "min_order": [int],
     *      "supplier": [string]
     *  }
     * ]
     * ```
     */
    private function getLastRecord(string $part)
    {
        $database = new Database;

        $productQuery = $database->connection->prepare('SELECT last_check FROM products WHERE part_number = ?');
        $productQueryRes = $productQuery->execute(array($part));

        if ($productQueryRes) {
            $lastCheckDate = $productQuery->fetch(PDO::FETCH_ASSOC)['last_check'];

            if ($lastCheckDate != null) {
                $stockQuery = $database->connection->prepare('SELECT * FROM stock_history WHERE part_number = ? AND date_checked = ?');
                $stockQueryRes = $stockQuery->execute(array($part, $lastCheckDate));
                
                if ($stockQueryRes) {
                    $records = $stockQuery->fetchAll(PDO::FETCH_ASSOC);
                }
            }
        }

        return $records;
    }

    /** Add a stock record for the given part-number.
     * @param string $part The part-number.
     *
     * @return mixed[] `true` if the operation succeeded, an `array` containing
     * informations about the error if the operation did not succeeded.
     */
    public function addRecord(string $part): array
    {
        $database = new Database;
        $stock = new Stock;
        $reporter = new Reporter;
        $part = strtoupper($part);
        $code = 4;
        $message = 'Part-number ' . $part . ' not found in the database.';
        $body = array();

        if ($database->partNumberExists($part)) {
            $query = $database->connection->prepare('INSERT INTO stock_history (part_number, date_checked, parts_in_stock, parts_on_order, min_order, supplier, state) VALUES (?, ?, ?, ?, ?, ?, ?);');
            
            $res = $stock->getFromDilp($part);
            $date = date('Y-m-d');
            $code = $res['code'];
            $message = $res['message'];
            $body = $res['body'];

            if ($code === 0) {
                foreach ($body['stock'] as $dealer => $stock) {
                    $SQLres = $query->execute(array($part, $date, $stock, -1, -1, $dealer, $code));
        
                    if (!$SQLres) {
                        $code = 5;
                        $message = 'SQL error.';
                        $body = $query->errorInfo();
                    }
                }
            }
        }
            
        return $reporter->format($code, $message, $body);
    }

    /** Update all the stock records at once.
     *
     * @return array
     */
    public function updateAll(): array
    {
        $database = new Database;
        $reporter = new Reporter;
        $code = 0;
        $message = 'Stock successfully updated!';

        $query = $database->connection->prepare('SELECT part_number from products');
        $res = $query->execute();
        
        if (!$res) {
            $code = 5;
            $message = 'SQL error';
            $body = $query->errorInfo();
        } else {
            $parts = $query->fetchAll(PDO::FETCH_COLUMN);

            $addCode = 0;

            foreach ($parts as $part) {
                $addResult = $this->addRecord($part);
                $addCode += $addResult['code'];
                $body[$part] = $addResult['body'];
            }

            if ($addCode !== 0) {
                $code = 5;
                $message = 'There was an error while trying to update the stock.';
            }
        }

        return $reporter->format($code, $message, $body);
    }
}
