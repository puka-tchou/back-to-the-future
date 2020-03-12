<?php namespace tasks\UpdateStock;

use data\Database\Database;
use data\Stock\Stock;
use utilities\Reporter\Reporter;

/**
 * Update stock data.
 */
class UpdateStock
{
    /** Add a stock record for the given part-number.
     * @param string $partNumber The part-number.
     *
     * @return mixed[] `true` if the operation succeeded, an `array` containing
     * informations about the error if the operation did not succeeded.
     */
    public function addRecord(string $partNumber): array
    {
        $database = new Database;
        $stock = new Stock;
        $reporter = new Reporter;
        $partNumber = strtoupper($partNumber);
        $code = 4;
        $message = 'Part-number not found.';
        $body = array();

        if ($database->partNumberExists($partNumber)) {
            $query = $database->connection->prepare('INSERT INTO stock_history (part_number, date_checked, parts_in_stock, parts_on_order, min_order, supplier, state) VALUES (?, ?, ?, ?, ?, ?, ?);');
        
            $stockValues = $stock->getFromDealers($partNumber)['body']['stock'];
            $partsInStock = isset($stockValues['parts_in_stock']) ? $stockValues['parts_in_stock'] : -1;
            $partsOnOrder = isset($stockValues['parts_on_order']) ? $stockValues['parts_on_order'] : -1;
            $minOrder = isset($stockValues['parts_min_order']) ? $stockValues['parts_min_order'] : -1;
            $date = date('Y-m-d');
            $code = 0;
            $message = 'Updated.';

            if (isset($stockValues['err'])) {
                $code = 1;
                $message = 'Stock information not found.';
            }

            $res = $query->execute(array($partNumber, $date, $partsInStock, $partsOnOrder, $minOrder, 'alliedelec', $code));
        
            if (!$res) {
                $code = 5;
                $message = 'SQL error.';
                $body = $query->errorInfo();
            }
        }
            
        return $reporter->format($code, $message, $body);
    }
}
