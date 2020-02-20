<?php namespace data\Product;

use data\Database\Database;

/**
 * Manipulate products in the database.
 */
class Product
{
    /** Add a product to the database.
     * @param string $partNumber The product part-number.
     * @param integer $updateInterval The time, in days, between each update of the stock and the prices.
     * @param string $manufacturer The part manufacturer.
     *
     * @return bool `true` if the creation succeded.
     */
    public function add(string $partNumber, int $updateInterval, string $manufacturer): bool
    {
        $partNumber = strtoupper($partNumber);
        $tracked_since = date('Y-m-d');
        $database = new Database;
        $query = $database->connection->prepare('INSERT INTO products (part_number, tracked_since, update_interval, state, manufacturer) VALUES (?, ?, ?, ?, ?);');

        return $query->execute(array(
            $partNumber,
            $tracked_since,
            $updateInterval,
            'PENDING',
            $manufacturer
        ));
    }
}
