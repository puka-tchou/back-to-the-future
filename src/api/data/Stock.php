<?php namespace data\Stock;

use data\Database\Database;
use dealers\AlliedElec\AlliedElec;
use PDO;

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
        $stockByPart = array(
            'part_number' => $part,
            'date_checked' => date('Y-m-d'),
        );
        
        $stockByPart['stock'] = $alliedelec->getStock($part);
        $stockByPart['supplier'] = 'alliedelec';

        return $stockByPart;
    }

    /** Get the last recorded stock for a given part number.
     * @param string $partNumber The part-number to check.
     * @param float $limit The number of records to fetch. If set to `-1`, get all records.
     * @return array The last record.
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
        $partNumber = strtoupper($partNumber);
        $database = new Database;
        $limit = ($limit === -1) ? ';' : ('LIMIT ' . $limit . ';');
        $res = array(
            'err' => true,
            'response' => 'Part-number ' . $partNumber . ' not found in the product database'
        );

        if ($database->partNumberExists($partNumber)) {
            $query = $database->connection->prepare(
                'SELECT part_number, date_checked, stock, supplier
                FROM stock_history
                WHERE part_number = ?
                ORDER BY id DESC '
                . $limit
            );
            $query->execute(array($partNumber));
            $res = $query->fetch(PDO::FETCH_ASSOC);

            if (!$res) {
                $res = array('err' => true, 'response' => 'No stock records found for ' . $partNumber);
            }
        }
        return $res;
    }
}
