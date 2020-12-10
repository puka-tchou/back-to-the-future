<?php

namespace BackToTheFuture\data;

use BackToTheFuture\utilities\Reporter;
use Exception;
use PDO;
use RuntimeException;

/**
 * Interact with the database.
 */
class Database
{
    /**
     * @var PDO
     */
    public PDO $connection;

    /** Construct a new connection to the database.
     * @return void
     */
    public function __construct()
    {
        $db = parse_ini_file(__DIR__ . '/../db.ini');
        try {
            $this->connection = new PDO($db['type']
                . ':dbname=' . $db['name']
                . ';host=' . $db['host']
                . ';charset=UTF8', $db['user'], $db['pass']);
        } catch (Exception $exception) {
            throw new RuntimeException("Could not create a connection to the database.", 5);
        }
    }

    /** Get all products stored in the database.
     *
     * @param integer $page
     * @return array The products in the database.
     */
    public function getAllProducts(int $page = 0): array
    {
        $database = new Database();
        $reporter = new Reporter();
        $code = 0;
        $query = $database->connection->prepare('SELECT * FROM products LIMIT ' . $page * 100 . ' ,100;');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $message = 'There are ' . count($result) . ' products in the database (showing ' . ($page * 100) . ' to ' . (($page * 100) + 99) . ').';
        if ($query->errorInfo()[1] !== null) {
            $result = $query->errorInfo();
            $message = 'There was an SQL error while trying to get the products';
            $code = 5;
        }

        return $reporter->format($code, $message, $result);
    }

    /** Check if a given part number is present in the database.
     * @param string $partNumber The part number to test (case insensitive).
     *
     * @return bool `true` if the part-number exists in the database.
     */
    public function partNumberExists(string $partNumber): bool
    {
        $partNumber = strtoupper($partNumber);
        $database = new Database();
        $query = $database->connection->prepare('SELECT EXISTS(SELECT * FROM products WHERE part_number= ?)');
        $query->execute(array($partNumber));
        $res = $query->fetch(PDO::FETCH_NUM);
        return ($res[0] == 1);
    }
}
