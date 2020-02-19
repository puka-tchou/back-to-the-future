<?php namespace data\Database;

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

        return (($query->fetch(PDO::FETCH_NUM)) == 1);
    }
}
