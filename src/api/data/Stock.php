<?php namespace data\Stock;

use data\Database\Database;
use dealers\AlliedElec\AlliedElec;
use PDO;
use utilities\Reporter\Reporter;

/**
 * Manipulate stock data, get data from online stores.
 */
class Stock
{
    /** Retrieve stock informations for a given part number from online stores.
     * @param string $part The part number to test.
     *
     * @return array
     */
    public function getFromDealers(string $part): array
    {
        $part = strtoupper($part);
        $alliedelec = new AlliedElec;
        $reporter = new Reporter;
        $code = 0;
        $message = 'Stock information found.';
        $body = array(
            'part_number' => $part,
            'date_checked' => date('Y-m-d'),
        );
        
        $body['stock'] = $alliedelec->getStock($part);
        $body['supplier'] = array('alliedelec');

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
        $database = new Database;
        $reporter = new Reporter;
        $code = 4;
        $message = 'Part-number not found.';
        $body = array();
        $partNumber = strtoupper($partNumber);
        $limit = ($limit == -1) ? ';' : ('LIMIT ' . $limit . ';');

        if ($database->partNumberExists($partNumber)) {
            $query = $database->connection->prepare(
                'SELECT *
                FROM stock_history
                WHERE part_number = ?
                ORDER BY id DESC '
                . $limit
            );
            
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
}
