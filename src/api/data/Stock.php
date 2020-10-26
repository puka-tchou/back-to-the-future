<?php

namespace data\Stock;

use data\Database\Database;
use dealers\AlliedElec\AlliedElec;
use dealers\NetComponents\NetComponents;
use PDO;
use utilities\Reporter\Reporter;

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
        $body = 'API error: ' . $res['body'];
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
        $database = new Database();
        $reporter = new Reporter();
        $code = 4;
        $message = 'Part-number not found.';
        $body = array();
        $partNumber = strtoupper($partNumber);
        $limit = ($limit == -1) ? ';' : ('LIMIT ' . $limit . ';');
        if ($database->partNumberExists($partNumber)) {
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
}
