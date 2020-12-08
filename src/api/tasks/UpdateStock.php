<?php

namespace BackToTheFuture\tasks;

use BackToTheFuture\data\Database;
use BackToTheFuture\data\Stock;
use BackToTheFuture\utilities\Reporter;
use PDO;

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
        $database = new Database();
        $stock = new Stock();
        $reporter = new Reporter();
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
        $database = new Database();
        $reporter = new Reporter();
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
