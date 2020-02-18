<?php namespace data\Database;

require __DIR__ . '/../../../../vendor/autoload.php';

use PDO;

/**
 * Interact with the database.
 */
class Database
{
    /** Construct a new connection to the database.
     * @todo Move connection parameters to another file.
     * @return void
     */
    public function __construct()
    {
        $this->connection = new PDO('mysql:dbname=testdb;host=127.0.0.1;charset=UTF8', 'testuser', 'test');
    }

    /** Get all products stored in the database.
     * @return array The products in the database.
     */
    public function getAllProducts(): array
    {
        $database = new Database();
        $query = $database->connection->prepare('SELECT * from newtable;');
        
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Check if a given part number is present in the database.
     * @param string $partNumber The part number to test (case insensitive).
     *
     * @return bool true if the part-number exists in the database.
     */
    public function partNumberExists(string $partNumber): bool
    {
        $partNumber = strtoupper($partNumber);
        $database = new Database();
        $query = $database->connection->prepare('SELECT EXISTS(SELECT * FROM testdb.newtable WHERE `part-number` = ?)');
        
        $query->execute(array($partNumber));

        return ($query->fetch(PDO::FETCH_NUM)[0] == 1);
    }
}
