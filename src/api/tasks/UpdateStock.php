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
     * @return bool|array `true` if the operation succeeded, an `array` containing
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

        $query = $database->connection->prepare('INSERT INTO stock_history (part_number, date_checked, stock, supplier, state) VALUES (?, ?, ?, ?, ?);');
        
        $stockValues = $stock->getFromDealers($partNumber)['stock'];
        $date = date('Y-m-d');
        $state = 'OK';
        $flag = true;

        if (isset($stockValues['err'])) {
            $res['err'] = array(
                'err' => true,
                'response' => $stockValues['response']
            );
            $state = 'ERROR';
            $flag = false;
        }

        $stockValues = json_encode($stockValues);
        $res = $query->execute(array($partNumber, $date, $stockValues, 'alliedelec', $state));
        
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
