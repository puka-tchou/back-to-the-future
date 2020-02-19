<?php namespace data\Database;

use PDO;

/**
 * Interact with the database.
 */
class Database
{
    /** Construct a new connection to the database.
     * @todo Make this a singleton.
     * @return void
     */
    public function __construct()
    {
        $db = parse_ini_file(__DIR__ . '/../db.ini');
        $this->connection = new PDO(
            $db['type']
            .':dbname='.$db['name']
            .';host='.$db['host']
            .';charset=UTF8',
            $db['user'],
            $db['pass']
        );
    }

    /** Get all products stored in the database.
     * @return array The products in the database.
     */
    public function getAllProducts(): array
    {
        $database = new Database;
        $query = $database->connection->prepare('SELECT * from products;');
        
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Check if a given part number is present in the database.
     * @param string $partNumber The part number to test (case insensitive).
     *
     * @return bool `true` if the part-number exists in the database.
     */
    public function partNumberExists(string $partNumber): bool
    {
        $partNumber = strtoupper($partNumber);
        $database = new Database;
        $query = $database->connection->prepare('SELECT EXISTS(SELECT * FROM products WHERE part_number= ?)');
        
        $query->execute(array($partNumber));
        $res = $query->fetch(PDO::FETCH_NUM);

        return ($res[0] == 1);
    }

    /** Get the last recorder stock of a given part number.
     * @param string $partNumber The part-number to check.
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
    public function getStock(string $partNumber): array
    {
        $partNumber = strtoupper($partNumber);
        $database = new Database;
        $query = $database->connection->prepare('SELECT date_checked, stock, supplier FROM stock_history WHERE part_number = ? ORDER BY id DESC LIMIT 1;');

        $query->execute(array($partNumber));
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if (!$res) {
            $res = array('err' => true, 'errMessage' => 'No stock records found for "' . $partNumber . '"');
        }

        return $res;
    }

    /** Add a product to the database.
     * @param string $partNumber The product part-number.
     * @param integer $updateInterval The time, in days, between each update of the stock and the prices.
     * @param string $manufacturer The part manufacturer.
     * 
     * @return bool `true` if the creation succeded.
     */
    public function addProduct(string $partNumber, int $updateInterval, string $manufacturer): bool
    {
        $partNumber = strtoupper($partNumber);
        $tracked_since = date('Y-m-d');
        $database = new Database;
        $query = $database->connection->prepare('INSERT INTO products (part_number,tracked_since,update_interval,state,manufacturer) VALUES (?,?,?,?,?);');

        return $query->execute(array(
            $partNumber,
            $tracked_since,
            $updateInterval,
            'PENDING',
            $manufacturer
        ));
    }
}
