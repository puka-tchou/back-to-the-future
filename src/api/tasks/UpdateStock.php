<?php namespace tasks\UpdateStock;

use data\Database\Database;
use data\Stock\Stock;

/**
 * Update stock data.
 */
class UpdateStock
{
    /** Add a stock record for the given part-number.
     * @param string $partNumber The part-number.
     *
     * @return string[]|bool[]|mixed[][]|bool[]|bool `true` if the operation succeeded, an `array` containing
     * informations about the error if the operation did not succeeded.
     */
    public function addRecord(string $partNumber)
    {
        $database = new Database;
        $stock = new Stock;
        $partNumber = strtoupper($partNumber);

        if (!$database->partNumberExists($partNumber)) {
            return array(
                'err' => true,
                'response' => 'Part-number ' . $partNumber . ' not found in the database.'
            );
        }

        $query = $database->connection->prepare('INSERT INTO stock_history (part_number, date_checked, parts_in_stock, parts_on_order, min_order, supplier, state) VALUES (?, ?, ?, ?, ?, ?, ?);');
        
        $stockValues = $stock->getFromDealers($partNumber)['stock'];
        $partsInStock = isset($stockValues['parts_in_stock']) ? $stockValues['parts_in_stock'] : -1;
        $partsOnOrder = isset($stockValues['parts_on_order']) ? $stockValues['parts_on_order'] : -1;
        $minOrder = isset($stockValues['parts_min_order']) ? $stockValues['parts_min_order'] : -1;
        $date = date('Y-m-d');
        $state = 0;
        $flag = true;

        if (isset($stockValues['err'])) {
            $res['err'] = array(
                'err' => true,
                'response' => $stockValues['response']
            );
            $state = 1;
            $flag = false;
        }

        $res = $query->execute(array($partNumber, $date, $partsInStock, $partsOnOrder, $minOrder, 'alliedelec', $state));
        
        if (!$res) {
            return array(
                'err' => true,
                'response' => array(
                    'queryString' => $query->queryString,
                    'errorCode' => $query->errorCode(),
                    'errorInfo' => $query->errorInfo()
                    )
                );
        }
            
        // If there was an error while getting the stock values, set the result to false.
        return $res && $flag;
    }
}
