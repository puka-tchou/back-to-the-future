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
            
        return $reporter->format($code, $message, $body);
    }
}
